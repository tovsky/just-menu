<?php

namespace App\Http\Table\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTablesRequest implements ArgumentValueInterface
{
    /**
     * @var CreateTableRequest[]
     * @Assert\Valid(groups={"App\Http\Table\Request\CreateTablesRequest"})
     * @Assert\NotBlank(groups={"App\Http\Table\Request\CreateTablesRequest"})
     */
    private array $tables;

    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @param CreateTableRequest[] $tables
     */
    public function setTables(array $tables): void
    {
        $this->tables = $tables;
    }
}