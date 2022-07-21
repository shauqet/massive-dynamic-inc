<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FilesHelper
{

    public static function uploadFile($file) {
        $destinationPath = '/documents/';
        $filename = Str::random(4) . '-' . str_replace(' ', '-', $file->getClientOriginalName());
        $file->move(public_path() . $destinationPath, $filename);
        return $destinationPath . $filename;
    }

    public static function deleteFile($file) {
        $path = public_path() . $file;
        if(file_exists($path)) {
            unlink($path);
        }
    }

}
