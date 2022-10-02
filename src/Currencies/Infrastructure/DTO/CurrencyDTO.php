<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\DTO;

class CurrencyDTO
{
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
    public function getVNom(): int
    {
        return $this->vNom;
    }

    /**
     * @param int $vNom
     */
    public function setVNom(int $vNom): void
    {
        $this->vNom = $vNom;
    }

    /**
     * @return float
     */
    public function getVCurs(): float
    {
        return $this->vCurs;
    }

    /**
     * @param float $vCurs
     */
    public function setVCurs(float $vCurs): void
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

    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
}
