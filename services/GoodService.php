<?php

namespace App\services;

use App\entities\Good;
use App\repositories\GoodRepository;
use App\traits\MsgMaker;
use App\traits\Redirect;
use App\traits\DataValidator;

class GoodService extends Service
{
    use MsgMaker;
    use Redirect;
    use DataValidator;

    public function save($id, $data)
    {
        if (!empty($id)) {
            if (!$this->container->goodRepository->isExists($id)) {
                $this->setMSG('Товар не существует.');
                $this->redirect('');
                exit();
            }
        }

        $error = $this->checkData($data);
        if (!empty($error)) {
            $this->setMSG($error);
            $this->redirect('');
            exit();
        }

        $good = new Good();

        $good->id = $id;
        $good->name = $data['name'];
        $good->info = $data['info'];
        $good->price = $data['price'];
        return $this->container->goodRepository->save($good);
    }

    protected function checkData($data)
    {
        $error = '';
        if (empty($data['name'])) {
            $error .= 'Не указано наименование товара.<br>';
        }
        if (empty($this->getNumeric($data['price']))) {
            $error .= 'Не указана цена товара.<br>';
        }
        if (empty($data['info'])) {
            $error .= 'Не указано описание товара.<br>';
        }
        return $error;
    }

    public function saveImages($good_id, $images, $imgFolder)
    {
        $errors = [];
        $insertErrorCount = 0;
        $successImages = [];
        foreach ($images as $imgName) {
            $result = $this->container->goodRepository->insertImage($good_id, $imgName);
            if (empty($result)) {
                $insertErrorCount++;
                continue;
            }
            $successImages[] = $imgName;
        }

        if ($insertErrorCount > 0) {
            $errors[] = 'Не удалось сохранить в базе ' . $insertErrorCount . ' фото.';
        }

        if (empty($successImages)) {
            return $errors;
        }

        $successImageCount = count($successImages);
        $mainImage = $successImages[0];
        $result = $this->container->goodRepository->updateGoodByImagesInfo($good_id, $imgFolder, $mainImage, $successImageCount);
        if ($result !== 1) {
            $errors[] = 'Не удалось сохранить в таблице товара данные о фото.';
            $successImageCount = 0;
        }

        return [$successImageCount, $errors];
    }
}