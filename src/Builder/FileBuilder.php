<?php

namespace App\Builder;

use App\Entity\File;
use App\Http\Request\File\UploadFileRequest;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class FileBuilder
{
    public function build(UploadFileRequest $uploadFileRequest): File
    {
        $file = new File();

        return $file->setName($uploadFileRequest->getName())
            ->setUser($uploadFileRequest->getUser())
            ->setPhysicalFileName($this->createPhysicalName($uploadFileRequest))
            ->setType($uploadFileRequest->getType())
            ->setSize($uploadFileRequest->getFile()->getSize())
            ->setMimeType($uploadFileRequest->getFile()->getMimeType());
    }

    private function createPhysicalName(UploadFileRequest $file)
    {
        switch (true) {
            case strripos($file->getFile()->getMimeType(), 'png'):
                $format = 'png';
                break;
            case strripos($file->getFile()->getMimeType(), 'pdf'):
                $format = 'pdf';
                break;
            case strripos($file->getFile()->getMimeType(), 'jpeg'):
                $format = 'jpg';
                break;
            default:
                throw new NotEncodableValueException('Формат неизвестен');
        }

        return $file->getRestaurant()->getId() . '.' . $format;
    }
}