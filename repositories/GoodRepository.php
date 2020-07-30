<?php

namespace App\repositories;

use App\entities\Good;

class GoodRepository extends Repository
{
    public function getTableName(): string
    {
        return 'products';
    }

    public function getImagesTableName(): string
    {
        return 'products_images';
    }

    public function getEntityName(): string
    {
        return Good::class;
    }

    public function getOneWithImages($goodId)
    {
        $good = $this->getOne($goodId);

        $result = $this->getImages($goodId);
        $images = [];
        foreach ($result as $item) {
            $images[] = $item['img_name_info'];
        }

        $good->images = $images;
        return $good;
    }


    protected function getImages($goodId)
    {
        $sql = 'SELECT * FROM ' . $this->getImagesTableName() . ' WHERE product_id = :id';
        return $this->getDB()->findAll($sql, [':id' => $goodId]);
    }

    public function insertImage($goodId, $imgName)
    {
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->getImagesTableName(),
            'product_id, img_name_info',
            ':product_id, :img_name_info'
        );
        $params = [':product_id' => $goodId, ':img_name_info' => $imgName];
        $this->getDB()->execute($sql, $params);
        return $this->getDB()->getInsertId(); // last id
    }

    public function updateGoodByImagesInfo($goodId, $imgFolder, $mainImage, $imageCount)
    {
        $params[':id'] = $goodId;

        $columns = [
            'img_folder = :img_folder',
            'number_of_images = :number_of_images',
            'main_img_name = :main_img_name'
        ];

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->getTableName(),
            implode(', ', $columns)
        );

        $params = [
            ':img_folder' => $imgFolder,
            ':number_of_images' => $imageCount,
            ':main_img_name' => $mainImage,
            ':id' => $goodId
        ];

        $pdoStatement = $this->getDB()->execute($sql, $params);
        return $pdoStatement->rowCount(); // number of affected rows
    }
}
