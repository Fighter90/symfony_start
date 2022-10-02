<?php

declare(strict_types=1);

namespace App\Currencies\Domain\Entity;

class Currency
{
    private int $id;

    private string $vchCode;

    private int $vNom;

    private float $vCurs;

    private int $vCode;

    private \DateTime $createdDate;

    public function __construct(string $vchCode, int $vNom, float $vCurs, int $vCode, \DateTime $createdDate)
    {
        $this->vchCode = $vchCode;
        $this->vNom = $vNom;
        $this->vCurs = $vCurs;
        $this->vCode = $vCode;
        $this->createdDate = $createdDate;
    }

    public function getVchCode(): string
    {
        return $this->vchCode;
    }

    public function setVchCode(string $vchCode): void
    {
        $this->vchCode = $vchCode;
    }

    /**
     * @return int
     */
    public function getVNom(): float|int
    {
        return $this->vNom;
    }

    /**
     * @param int $vNom
     */
    public function setVNom(float|int $vNom): void
    {
        $this->vNom = $vNom;
    }

    /**
     * @return float
     */
    public function getVCurs(): float|string
    {
        return $this->vCurs;
    }

    /**
     * @param float $vCurs
     */
    public function setVCurs(float|string $vCurs): void
    {
        $this->vCurs = $vCurs;
    }

    public function getVCode(): int
    {
        return $this->vCode;
    }

    public function setVCode(int $vCode): void
    {
        $this->vCode = $vCode;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
}
