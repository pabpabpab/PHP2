<?php


namespace App\traits;


trait FolderChecker
{
    protected function checkFolder($folderName) {
        if (!is_dir($folderName)) {
            if (!mkdir($folderName, 0777)) {
               return "Не могу создать папку " . $folderName;
            }
        }
        return '';
    }

}