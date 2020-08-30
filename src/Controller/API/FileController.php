<?php

namespace App\Controller\API;

use App\Entity\File;
use App\Repository\FileRepository;
use App\Service\Http\JsonResponseMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/files")
 */
class FileController extends AbstractController
{
    /**
     * @var JsonResponseMaker
     */
    private $jsonResponseMaker;
    /**
     * @var FileRepository
     */
    private $fileRepository;

    public function __construct(JsonResponseMaker $jsonResponseMaker, FileRepository $fileRepository)
    {
        $this->jsonResponseMaker = $jsonResponseMaker;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @Route("/", name="api_file_get_all", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $files = $this->fileRepository->findAll();

        return $this->jsonResponseMaker->makeItemsResponse($files, ['groups' => ['file:read']]);
    }

    /**
     * @Route("/}", name="api_file_save_one", methods={"POST"})
     */
    public function saveItem(): JsonResponse
    {

    }

    /**
     * @Route("/{id}", name="api_file_get_one", methods={"GET"})
     */
    public function getItem(File $file): JsonResponse
    {
        return $this->jsonResponseMaker->makeItemResponse($file, ['groups' => ['file:read']]);
    }


    /**
     * @Route("/{id}}", name="api_file_update", methods={"PUT"})
     */
    public function updateItem(File $file): JsonResponse
    {

    }

    /**
     * @Route("/{id}", name="api_file_delete", methods={"DELETE"})
     */
    public function deleteItem(File $file): JsonResponse
    {

    }
}
