<?php

namespace App\Controller;

use App\Builder\TableBuilder;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Http\Table\Request\CreateTablesRequest;
use App\Http\Table\Response\CreateTablesResponse;
use App\Service\Http\ResponseMakerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableController
{
    private ResponseMakerInterface $responseMaker;

    public function __construct(ResponseMakerInterface $responseMaker)
    {
        $this->responseMaker = $responseMaker;
    }

    /**
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

    /**
     * @Route("/api/v1/restaurant/{slug}/table/{id}", name="api_table_get", methods={"GET"})
     */
    public function getTable(Table $table)
    {
        return $this->responseMaker->makeItemResponse($table);
    }
}