<?php

namespace App\Http\Table\Response;

use App\Entity\Table;
use Symfony\Component\Serializer\Annotation\Groups;
use Swagger\Annotations as SWG;

class CreateTablesResponse
{
    //@TODO переписать на нормальный респонсе, а не Сущность отдавать
    /**
     * @var Table[]
     * @Groups({"tables:read"})
     * @SWG\Property(property="tables", type="array", @SWG\Items(type="object"))
     */
    private array $tables;

    /**
     * @return Table[]
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @param Table[] $tables
     */
    public function setTables(array $tables): void
    {
        $this->tables = $tables;
    }
}