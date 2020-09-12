<?php

namespace App\Controller;

use App\Builder\TableBuilder;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Http\Table\Request\CreateTablesRequest;
use App\Http\Table\Response\CreateTablesResponse;
use App\Service\Http\ResponseMakerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class TableController
{
    private ResponseMakerInterface $responseMaker;

    public function __construct(ResponseMakerInterface $responseMaker)
    {
        $this->responseMaker = $responseMaker;
    }

    /**
     * @SWG\Post(
     *     summary="Create tables",
     *     tags={"Restaurant"},
     *     description="Создание сталов для ресторана",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              ref=@Model(type=CreateTablesRequest::class)
     *          )
     *      ),
     *      @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=CreateTablesResponse::class))
     *      )
     * )
     * @Route("/api/v1/restaurant/{slug}/tables", name="api_tables_create", methods={"POST"})
     */
    public function createTables(
        CreateTablesRequest $createTablesRequest,
        Restaurant $restaurant,
        TableBuilder $tableBuilder,
        EntityManagerInterface $em
    ): Response {
        $newTables = [];

        foreach ($createTablesRequest->getTables() as $createTable) {
            $newTable = $tableBuilder->build($createTable, $restaurant);
            $newTables[] = $newTable;

            $em->persist($newTable);
        }
        $em->flush();

        $response = new CreateTablesResponse();
        $response->setTables($newTables);

        return $this->responseMaker->makeItemResponse($response, ['groups' => 'tables:read'], Response::HTTP_CREATED);
    }


    //@TODO починить ответ
    /**
     * @SWG\Get(
     *     summary="Get restaurant by slug",
     *     tags={"Restaurant"},
     *     description="Получение ресторана по слагу",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     * @Route("/api/v1/restaurant/{slug}/tables/{id}", name="api_table_get", methods={"GET"})
     */
    public function getTable(Table $table)
    {
        return $this->responseMaker->makeItemResponse($table, ['groups' => 'tables:read'], Response::HTTP_OK);
    }
}