<?php


namespace App\Helpers;


use Illuminate\Http\UploadedFile;

class FileHelper
{
    /**
     * @param UploadedFile $file
     * @param string $dir
     * @return bool|string
     */
    public static function FileUpload(UploadedFile $file, string $dir)
    {
        if ($fileName = $file->store('/public' . $dir)) {
            $array = explode('/', $fileName);
            return $fileName = array_pop($array);
        }
        else {
            return false;
        }
    }
}
