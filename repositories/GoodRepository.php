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

    public function insertImage($good_id, $imgName)
    {
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->getImagesTableName(),
            'product_id, img_name_info',
            ':product_id, :img_name_info'
        );
        $params = [':product_id' => $good_id, ':img_name_info' => $imgName];
        static::getDB()->execute($sql, $params);
        return static::getDB()->getInsertId(); // last id
    }

    public function updateGoodByImagesInfo($good_id, $imgFolder, $mainImage, $imageCount)
    {
        $params[':id'] = $good_id;

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
            ':id' => $good_id
        ];

        $pdoStatement = static::getDB()->execute($sql, $params);
        return $pdoStatement->rowCount(); // number of affected rows
    }
}
