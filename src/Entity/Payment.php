<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 * @ORM\Table(options={"comment":"Сведения об оплате подписки"})
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"comment":"Сумма оплаты, в копейках"})
     */
    private $sum;

    /**
     * @ORM\Column(type="datetime", options={"comment":"Дата совершения оплаты"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", options={"comment":"Статус оплаты"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Subscription::class, inversedBy="payments")
     */
    private $subscription;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSum(): ?int
    {
        return $this->sum;
    }

    public function setSum(int $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }
}
