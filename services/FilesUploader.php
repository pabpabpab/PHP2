<?php


namespace App\services;

use App\traits\FolderChecker;

class FilesUploader
{
    use FolderChecker;

    protected $uploadSettings = [
        'htmlFormImgFieldName' => 'userfile',
        'imgFolder' => 1,
        'imgFolderPath' => './',
        'maxImgWeightInMb' => 10
    ];
    protected $uploadErrors = [
        0 => 'Ошибок не возникло, файл был успешно загружен на сервер. ',
        1 => 'Размер принятого файла больше разрешенного php.ini.',
        2 => 'Размер загружаемого файла больше разрешенного MAX_FILE_SIZE в HTML-форме.',
        3 => 'Загружаемый файл был получен только частично.',
        4 => 'Файл не был загружен.',
        6 => 'Отсутствует временная папка.',
        7 => 'Не удалось записать файл на диск',
        8 => 'Внутренняя ошибка php при upload файла. '
    ];
    protected $mimeTypes = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/webp' => 'webp'
    ];

    public function __construct($uploadSettings)
    {
        $this->uploadSettings = $uploadSettings;
    }

    public function multipleUpload($uploaded, $prefix)
    {
        $settings = $this->uploadSettings;
        $folderError = $this->checkFolder($settings['imgFolderPath'] . $settings['imgFolder']);
        if (!empty($folderError)) {
            return [[], [$folderError]]; // list($correctImages, $uploadErrors)
        }

        $imgNumber = count($uploaded['name']);
        $errors = [];
        $correctImages = [];


        for ($i = 0; $i < $imgNumber; $i++) {
            $error = $this->checkUploaded($uploaded, $i);

            if (!empty($error)) {
                $errors[] = $error;
                continue;
            }

            $fileExtension = $this->getExtension($uploaded['type'][$i]);

            $imgName = uniqid($prefix . '_') . $i . "." . $fileExtension;
            $destination = $settings['imgFolderPath'] . $settings['imgFolder'] . "/" . $imgName;

            if (!move_uploaded_file($uploaded['tmp_name'][$i], $destination)) {
                $errors[] = "Возможная атака с помощью файловой загрузки!";
            } else {
                $correctImages[] = $imgName;
            }
        }

        return [$correctImages, $errors];
    }

    protected function checkUploaded($uploaded, $index)
    {
        $error = '';
        $number = $index + 1;
        $settings = $this->uploadSettings;

        if ($uploaded['error'][$index] > 0) {
            $error .= "файл " . $number . " - " . $this->getUploadError($uploaded['error'][$index]) . "<br>";
        }

        if ($uploaded['size'][$index] > $settings['maxImgWeightInMb'] * 1024 * 1024) {
            $error .= "файл " . $number . " - " . "более {$settings['maxImgWeightInMb']} мегабайт." . "<br>";
        }

        $fileExtension = $this->getExtension($uploaded['type'][$index]);
        if (empty($fileExtension)) {
            $error .= "файл " . $number . " - " . "недопустимый формат, разрешены: " . implode(", ", $this->mimeTypes) . "." . "<br>";
        }

        return $error;
    }

    protected function getUploadError($key)
    {
        if (empty($this->uploadErrors[$key])) {
            return 'Неизвестная ошибка при upload файла.';
        }
        return $this->uploadErrors[$key];
    }

    protected function getExtension($key)
    {
        return $this->mimeTypes[$key];
    }
}