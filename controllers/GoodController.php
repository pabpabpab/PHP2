<?php

namespace App\controllers;

use App\entities\Good;
use App\repositories\GoodRepository;
use App\services\Paginator;
use App\services\GoodService;
use App\services\FilesUploader;


class GoodController extends Controller
{

    protected $uploadSettings = [
        'htmlFormImgFieldName' => 'userfile',
        'imgFolder' => 1,
        'imgFolderPath' => './',
        'maxImgWeightInMb' => 10
    ];

    public function allAction()
    {
        $entityName = $this->request->getControllerName();
        $paginator = new Paginator($entityName, $this->getQuantityPerPage());
        $paginator->setItems($this->getPage());

        return $this->render(
            'goods',
            [
                'paginator' => $paginator
            ]
        );
    }

    public function oneAction()
    {
        $id = $this->getId();
        return $this->render(
            'good',
            [
                'good' => (new GoodRepository())->getOne($id),
                'goodCartCount' => (int) $this->session('goods')[$id]['count']
            ]
        );
    }

    public function addAction()
    {
        return $this->render(
            'addGood',
            ['good' => new Good() ]
        );
    }

    public function editAction()
    {
        $id = $this->getId();
        return $this->render(
            'addGood',
            [
                'good' => (new GoodRepository())->getOne($id),
            ]
        );
    }

    public function saveAction()
    {
        $id = $this->getId();
        $action = ($id > 0) ? 'update' : 'insert';

        $result = (new GoodService())->save($id, $this->post(''));

        if (empty($result)) {
            $this->saySaveFail($action, $id);
        }

        if ($action == 'insert') {
            $id = $result;
        }

        $userImgName = $this->uploadSettings['htmlFormImgFieldName'];
        $uploaded = $this->files($userImgName);
        if (is_array($uploaded)) {
            $result = $this->saveImages($uploaded, $id);
            if ($result === 0) {
                $this->setMSG('Ни одного фото не удалось сохранить.');
            }
        }

        $this->redirect('/good/one?id=' . $id);
        return $result; // int
    }


    protected function saveImages($uploaded, $good_id)
    {
        $prefix = $good_id;

        $uploader = new FilesUploader($this->uploadSettings);

        list($correctImages, $uploadErrors) = $uploader->multipleUpload($uploaded, $prefix);

        if (!empty($uploadErrors)) {
            $this->setMSG(implode("<br>", $uploadErrors));
        }

        if (empty($correctImages)) {
            return 0;
        }

        $imgFolder = $this->uploadSettings['imgFolder'];
        list($successImageCount, $errors) = (new GoodService())->saveImages($good_id, $correctImages, $imgFolder);

        if (!empty($errors)) {
            $this->setMSG(implode("<br>", $errors));
        }

        return $successImageCount;
    }

    protected function saySaveFail($action, $id)
    {
        if ($action === 'insert') {
            $this->setMSG('Не удалось сохранить товар.');
            $this->redirect('/good/add');
        } else {
            $this->setMSG('Не удалось сохранить изменения.');
            $this->redirect('/good/edit?id=' . $id);
        }
        exit();
    }

    public function deleteAction()
    {
        $id = $this->getId();
        if (!(new GoodRepository())->isExists($id)) {
            $this->setMSG('Товар не существует.');
            $this->redirect('');
            return false;
        }
        $result = (new GoodRepository())->delete($id);
        if ($result !== 1) {
            $this->setMSG('Не удалось удалить товар.');
            $this->redirect('');
            return false;
        }
        $this->setMSG('Товар номер ' . $id . ' удален.');
        $this->redirect('/good/all');
        return true;
    }
}
