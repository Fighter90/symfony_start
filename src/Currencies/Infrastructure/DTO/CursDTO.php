<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\DTO;

class CursDTO
{
    private string $vchCode1;

    private string $vchCode2;

    private ?int $vNom;

    private ?float $vCurs;

    private \DateTime $createdDate;

    public function __construct(string $vchCode1, string $vchCode2, ?int $vNom, ?float $vCurs, \DateTime $createdDate)
    {
        $this->vchCode1 = $vchCode1;
        $this->vchCode2 = $vchCode2;
        $this->vNom = $vNom;
        $this->vCurs = $vCurs;
        $this->createdDate = $createdDate;
    }

    /**
     * @return string
     */
    public function getVchCode1(): string
    {
        return $this->vchCode1;
    }

    /**
     * @param string $vchCode1
     */
    public function setVchCode1(string $vchCode1): void
    {
        $this->vchCode1 = $vchCode1;
    }

    /**
     * @return string
     */
    public function getVchCode2(): string
    {
        return $this->vchCode2;
    }

    /**
     * @param string $vchCode2
     */
    public function setVchCode2(string $vchCode2): void
    {
        $this->vchCode2 = $vchCode2;
    }

    /**
     * @return int|null
     */
    public function getVNom(): ?int
    {
        return $this->vNom;
    }

    /**
     * @param int|null $vNom
     */
    public function setVNom(?int $vNom): void
    {
        $this->vNom = $vNom;
    }

    /**
     * @return float|null
     */
    public function getVCurs(): ?float
    {
        return $this->vCurs;
    }

    /**
     * @param float|null $vCurs
     */
    public function setVCurs(?float $vCurs): void
    {
        $this->vCurs = $vCurs;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate(\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
}
