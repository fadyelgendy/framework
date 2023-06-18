<?php

namespace Lighter\Framework;

class File
{
    public const UPLOADS_DIR = DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

    /**
     * Uploads File and return its path
     * Throws Exception otherwise
     *
     * @param $file
     * @param string $path
     * @return string
     */
    function upload($file, string $path): string
    {
        // check if path exists
        if (!is_dir(self::UPLOADS_DIR . $path)) {
            mkdir(self::UPLOADS_DIR . $path, recursive: true);
        }

        $uploadedFile = self::UPLOADS_DIR . $path . DIRECTORY_SEPARATOR . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $uploadedFile)) {
            abort('error', 'ERROR: can not upload file!', 500);
        }

        return $uploadedFile;
    }
}
