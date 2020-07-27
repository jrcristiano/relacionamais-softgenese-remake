<?php

namespace App\Uploads\Facades;

use App\Uploads\Upload as UploadFile;

class Upload
{
    public static function file($request, $field, $extensions, $destiny = 'public')
    {
        return (new UploadFile)->file($request, $field, $extensions, $destiny)
            ->send();
    }
}
