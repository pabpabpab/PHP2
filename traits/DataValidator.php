<?php


namespace App\traits;


trait DataValidator
{
    protected function getNumeric($input) {
        if (is_numeric($input)) {
            return $input + 0;
        }
        return 0;
    }
}