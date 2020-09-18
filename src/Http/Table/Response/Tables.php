<?php

namespace App\Http\Table\Response;

use App\Entity\Table;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

class Tables
{
    /**
     * @var Table[]
     * @Groups({"tables:read"})
     * @SWG\Property(property="tables", type="array", @SWG\Items(type="object"))
     */
    private array $tables;

    public function getTables(): array
    {
        return $this->tables;
    }

    public function setTables(array $tables): void
    {
        $this->tables = $tables;
    }
}