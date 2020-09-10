<?php

namespace App\Controller\API;

use App\Builder\FileBuilder;
use App\Entity\File;
use App\Entity\Restaurant;
use App\Exception\ValidationException;
use App\Http\Request\File\UploadFileRequest;
use App\Repository\FileRepository;
use App\Repository\UserRepository;
use App\Service\File\FileUploader;
use App\Service\Http\JsonResponseMaker;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     * @Route("/", name="api_file_get_all", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $files = $this->fileRepository->findAll();

        return $this->jsonResponseMaker->makeItemsResponse($files, ['groups' => ['file:read']]);
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
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=File::class))
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
        UserRepository $userRepository,
    FileUploader $fileUploader
    ): JsonResponse
    {
        $uploadedFileRequest = new UploadFileRequest();

        $uploadedFileRequest->setFile($request->files->get('file'));
        $uploadedFileRequest->setRestaurant($restaurant);
        $uploadedFileRequest->setUser($userRepository->findOneBy(['id' => 1]));

        $serializer->denormalize($request->request->all(), UploadFileRequest::class, 'array', [AbstractNormalizer::OBJECT_TO_POPULATE => $uploadedFileRequest]);

        $violations = $validator->validate($uploadedFileRequest);
        if ($violations->count()) {
            throw new ValidationException($violations);
        }

        $file = $fileBuilder->build($uploadedFileRequest);
        $fileUploader->upload($uploadedFileRequest->getFile(), $file->getPhysicalFileName(), $file->getRestaurants());
        $em->persist($file);
        $em->flush();

        return $this->jsonResponseMaker->makeItemResponse($file, ['groups' => 'file:read'], Response::HTTP_CREATED);
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
