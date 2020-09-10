<?php

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $pathToSave;

    public function __construct(string $pathToSave)
    {

        $this->pathToSave = $pathToSave;
    }

    public function upload(UploadedFile $uploadedFile, string $physicalName, string $dirName): void
    {
        $this->createDir($dirName);
        $uploadedFile->move($this->pathToSave. '/' . $dirName,$physicalName);
    }

    public function createDir(string $dirName)
    {
        if(is_dir($this->pathToSave . $dirName)){
            dd('yeap');
        }
        dd('no');
    }
}