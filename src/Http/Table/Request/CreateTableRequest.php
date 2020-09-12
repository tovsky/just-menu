<?php

namespace App\Http\Table\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTableRequest
{
    /**
     * @Assert\NotBlank(groups={"App\Http\Table\Request\CreateTablesRequest"})
     * @Assert\Positive(groups={"App\Http\Table\Request\CreateTablesRequest"})
     */
    private int $number;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}