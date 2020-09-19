<?php

namespace App\Controller\API;

use App\Builder\FileBuilder;
use App\Entity\File;
use App\Entity\Restaurant;
use App\Exception\FileNotDeleteException;
use App\Exception\ValidationException;
use App\Http\Request\File\UploadFileRequest;
use App\Repository\FileRepository;
use App\Service\File\FileUploader;
use App\Service\Http\JsonResponseMaker;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;


/**
 * @Route("/api/v1")
 */
class FileController extends AbstractController
{
    private JsonResponseMaker$jsonResponseMaker;

    private FileRepository $fileRepository;

    public function __construct(JsonResponseMaker $jsonResponseMaker, FileRepository $fileRepository)
    {
        $this->jsonResponseMaker = $jsonResponseMaker;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @SWG\Post(
     *     summary="Upload File",
     *     tags={"Restaurant", "File"},
     *     description="Загрузка файла для ресторана",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              ref=@Model(type=UploadFileRequest::class)
     *          )
     *      ),
     *      @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *      )
     * )
     * @Route("/restaurant/{slug}/file", name="api_file_save_one", methods={"POST"})
     */
    public function saveItem(
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        Restaurant $restaurant,
        FileBuilder $fileBuilder,
        EntityManagerInterface $em,
        FileUploader $fileUploader
    ): JsonResponse
    {
        $uploadedFileRequest = new UploadFileRequest();

        $uploadedFileRequest->setFile($request->files->get('file'));
        $uploadedFileRequest->setRestaurant($restaurant);
        $uploadedFileRequest->setUser($this->getUser());

        $serializer->denormalize($request->request->all(), UploadFileRequest::class, 'array', [AbstractNormalizer::OBJECT_TO_POPULATE => $uploadedFileRequest]);

        $violations = $validator->validate($uploadedFileRequest);
        if ($violations->count()) {
            throw new ValidationException($violations);
        }

        $file = $fileBuilder->build($uploadedFileRequest);
        $fileUploader->upload($uploadedFileRequest->getFile(), $file->getPhysicalFileName(), $file->getRestaurant()->getId());

        $em->persist($file);
        $em->flush();

        return $this->jsonResponseMaker->makeItemResponse($file, ['groups' => 'file:read'], Response::HTTP_CREATED);
    }

    /**
     * @SWG\Get(
     *     summary="Get file",
     *     tags={"File"},
     *     description="Получение файла по id",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=File::class))
     *     )
     * )
     * @Route("/files/{id}", name="api_file_get_one", methods={"GET"})
     */
    public function getItem(File $file = null): JsonResponse
    {
        return $this->jsonResponseMaker->makeItemResponse($file, ['groups' => ['file:read']]);
    }

    /**
     * @Route("/file/{id}", name="api_file_delete", methods={"DELETE"})
     * @Entity("file", expr="repository.findActiveFile(id)")
     */
    public function deleteItem(File $file, EntityManagerInterface $em, FileUploader $fileUploader): JsonResponse
    {
        $file->setActive(false);
        $isDelete = $fileUploader->move($file->getRestaurant()->getId(), $file->getPhysicalFileName());

        if (false === $isDelete) {
            throw new FileNotDeleteException();
        }

        $em->persist($file);
        $em->flush();

        return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_OK);
    }

    /**
     * @SWG\Get(
     *     summary="Get files by restaurant",
     *     tags={"File"},
     *     description="Получение файлов ресторана",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     * @Route("/restaurant/{slug}/files", name="api_files_get", methods={"GET"})
     */
    public function getAllFilesRestaurant(Restaurant $restaurant)
    {
        return $this->jsonResponseMaker->makeItemResponse($restaurant->getActiveFiles(), ['groups' => 'file:read'], Response::HTTP_OK);
    }
}
