<?php

namespace App\Http\Table\Request;

use App\Entity\User;
use App\Resolver\ArgumentValueInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTableRequest implements ArgumentValueInterface
{
    /**
     * @Assert\NotBlank(groups={"App\Http\Table\Request\UpdateTablesRequest"})
     * @Assert\Positive(groups={"App\Http\Table\Request\UpdateTablesRequest"})
     */
    private int $number;

    /**
     * @SWG\Property(property="employee", type="integer")
     */
    private User $employee;

    /**
     * @Assert\NotBlank(groups={"App\Http\Table\Request\UpdateTablesRequest"})
     * @Assert\Choice({"busy", "free", "served"})
     */
    private string $status;

    public function getEmployee(): User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): void
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