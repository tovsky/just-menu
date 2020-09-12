<?php

namespace App\Http\Table\Response;

use App\Entity\Table;
use Symfony\Component\Serializer\Annotation\Groups;

class CreateTablesResponse
{
    //@TODO переписать на нормальный респонсе, а не Сущность отдавать
    /**
     * @var Table[]
     * @Groups({"tables:read"})
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