<?php

namespace App\Controller;

use App\Builder\TableBuilder;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Http\Table\Request\CreateTablesRequest;
use App\Http\Table\Request\UpdateTableRequest;
use App\Http\Table\Response\CreateTablesResponse;
use App\Http\Table\Response\Tables;
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
     *     tags={"Table"},
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
     * @Route("/api/v1/restaurants/{slug}/tables", name="api_restaurant_create_one_table", methods={"POST"})
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
     *     summary="Get tables",
     *     tags={"Table"},
     *     description="Получение столов ресторана",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=Tables::class))
     *     )
     * )
     * @Route("/api/v1/restaurants/{slug}/tables", name="api_restaurant_get_all_tables", methods={"GET"})
     */
    public function getTables(Restaurant $restaurant): Response
    {
        $tables = [];
        foreach ($restaurant->getTables() as $table){
            $tables[] = $table;
        }

        $response = new Tables();
        $response->setTables($tables);

        return $this->responseMaker->makeItemResponse($response, ['groups' => 'tables:read'], Response::HTTP_OK);
    }

    /**
     * @SWG\Get(
     *     summary="Get single table by id",
     *     tags={"Table"},
     *     description="Получение стола по id",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     * @Route("/api/v1/tables/{id}", name="api_table_get_one", methods={"GET"})
     */
    public function getSingleTable(Table $table): Response
    {
        return $this->responseMaker->makeItemResponse($table, ['groups' => 'table:read'], Response::HTTP_OK);
    }
}