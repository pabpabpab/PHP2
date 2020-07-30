<?php

namespace App\controllers;

use App\entities\Good;
use App\repositories\GoodRepository;
use App\services\Paginator;
use App\services\GoodService;
use App\services\FilesUploader;


class GoodController extends Controller
{

    public function allAction()
    {
        $entityName = $this->request->getControllerName();

        $paginator = $this->app->paginator;
        $paginator->setItems($entityName, $this->getQuantityPerPage(), $this->getPage());

        return $this->render(
            'goods',
            [
                'paginator' => $paginator,
                'imgPath' => '..'
            ]
        );
    }

    public function oneAction()
    {
        $id = $this->getId();
        return $this->render(
            'good',
            [
                'good' => $this->goodRepository()->getOneWithImages($id),
                'imgPath' => '..',
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
                'good' => $this->goodRepository()->getOne($id),
            ]
        );
    }

    public function saveAction()
    {
        $id = $this->getId();
        $action = ($id > 0) ? 'update' : 'insert';

        $result = $this->app->goodService->save($id, $this->post(''));

        if (empty($result)) {
            $this->saySaveFail($action, $id);
        }

        if ($action == 'insert') {
            $id = $result;
        }


        $userImgName = $this->app->getConfig('components')['filesUploader']['config']['htmlFormImgFieldName'];
        $uploaded = $this->files($userImgName);

        if (!empty($uploaded['name'][0])) {
            $successImageCount = $this->saveImages($uploaded, $id);
            if ($successImageCount === 0) {
                $this->setMSG('Ни одного фото не удалось сохранить.');
            }
        }

        $this->redirect('/good/one?id=' . $id);
        return $result; // int
    }


    protected function saveImages($uploaded, $good_id)
    {
        $prefix = $good_id;

        list($correctImages, $uploadErrors) = $this->app->filesUploader->multipleUpload($uploaded, $prefix);

        if (!empty($uploadErrors)) {
            $this->setMSG(implode("<br>", $uploadErrors));
        }

        if (empty($correctImages)) {
            return 0;
        }

        $imgFolder = $this->app->getConfig('components')['filesUploader']['config']['imgFolder'];

        list($successImageCount, $errors) = $this->app->goodService->saveImages($good_id, $correctImages, $imgFolder);

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
        if (!($this->goodRepository()->isExists($id))) {
            $this->setMSG('Товар не существует.');
            $this->redirect('');
            return false;
        }
        $result = $this->goodRepository()->delete($id);
        if ($result !== 1) {
            $this->setMSG('Не удалось удалить товар.');
            $this->redirect('');
            return false;
        }
        $this->setMSG('Товар номер ' . $id . ' удален.');
        $this->redirect('/good/all');
        return true;
    }

    protected function goodRepository()
    {
        return $this->app->goodRepository;
    }
}
