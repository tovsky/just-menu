<?php

namespace App\Service\File;

use App\Exception\CreateDirException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $pathToSave;

    private string $pathToArchive;

    public function __construct(string $pathToSave, string $pathToArchive)
    {
        $this->pathToSave = $pathToSave;

        $this->pathToArchive = $pathToArchive;
    }

    public function upload(UploadedFile $uploadedFile, string $physicalName, string $dirName): void
    {
        $isCreated = $this->createDir($dirName);

        if (false === $isCreated) {
            throw new CreateDirException();
        }

        $uploadedFile->move($this->pathToSave . '/' . $dirName, $physicalName);
    }

    public function createDir(string $dirName): bool
    {
        $concurrentDirectory = $this->pathToSave . '/' . $dirName;

        if (is_dir($concurrentDirectory)) {
            return true;
        }

        return mkdir($concurrentDirectory);
    }

    public function move(string $dirName, string $physicalName): bool
    {
       return rename($this->pathToSave . '/' . $dirName . '/' . $physicalName, $this->pathToArchive . '/' . $physicalName);
    }
}