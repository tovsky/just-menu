<?php

namespace App\Builder;

use App\Entity\Restaurant;
use App\Entity\Table;
use App\Enum\TableStatuses;
use App\Http\Table\Request\CreateTableRequest;
use App\Service\Generator\GeneratorQrTable;

class TableBuilder
{
    private GeneratorQrTable $qrGenerate;

    public function __construct(GeneratorQrTable $qrGenerate)
    {
        $this->qrGenerate = $qrGenerate;
    }

    public function build(CreateTableRequest $createTableRequest, Restaurant $restaurant): Table
    {
        $table = new Table();
        $table->setNumber($createTableRequest->getNumber())
            ->setQr(
                $this->qrGenerate->generate(
                    [
                        'id' => $table->getId()->__toString(),
                        'slug' => $restaurant->getSlug()
                    ],
                    $table->getId()->__toString()
                )
            )
            ->setStatus(TableStatuses::SERVED)
            ->setRestaurant($restaurant);

        return $table;
    }
}