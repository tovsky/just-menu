<?php

namespace App\Http\Table\Request;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTableRequest
{
    /**
     * @Assert\NotBlank(groups={"App\Http\Table\Request\UpdateTablesRequest"})
     * @Assert\Positive(groups={"App\Http\Table\Request\UpdateTablesRequest"})
     */
    private int $number;

    private UserInterface $employee;

    /**
     * @Assert\NotBlank(groups={"App\Http\Table\Request\UpdateTablesRequest"})
     * @Assert\Choice({"busy", "free", "served"})
     */
    private string $status;

    public function getEmployee(): UserInterface
    {
        return $this->employee;
    }

    public function setEmployee(UserInterface $employee): void
    {
        $this->employee = $employee;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}