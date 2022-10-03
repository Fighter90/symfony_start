<?php

declare(strict_types=1);

namespace App\Currencies\Application\Query\GetCalcCurrency;

use App\Currencies\Domain\Repository\CurrencyRepositoryInterface;
use App\Currencies\Infrastructure\DTO\CursDTO;
use App\Shared\Application\Query\QueryHandlerInterface;

class GetCalcCurrencyHandler implements QueryHandlerInterface
{
    private const DEFAULT_VCH_CODE = 'RUB';

    private ?int $vNom;

    private ?float $vCurs;

    public function __construct(private readonly CurrencyRepositoryInterface $currencyRepository)
    {
    }

    public function __invoke(GetCalcCurrencyQuery $query): CursDTO
    {
        $this->calcCursByParams($query->vchCode, $query->baseVchCode, $query->createdDate);

        return new CursDTO(
            $query->vchCode,
            $query->baseVchCode,
            $this->vNom,
            $this->vCurs,
            $query->createdDate
        );
    }

    private function calcCursByParams(string $vchCode, string $baseVchCode, \DateTime $createdDate): void
    {
        $this->vNom = null;
        $this->vCurs = null;

        if (self::DEFAULT_VCH_CODE === $baseVchCode) {
            $currency = $this->currencyRepository->findByVchCodeAndCreatedDate(
                $vchCode,
                $createdDate
            );

            $this->vNom = $currency ? $currency->getVNom() : null;
            $this->vCurs = $currency ? $currency->getVCurs() : null;
            return;
        }

        if (self::DEFAULT_VCH_CODE === $vchCode) {
            $currency = $this->currencyRepository->findByVchCodeAndCreatedDate(
                $baseVchCode,
                $createdDate
            );

            $this->vNom = $currency ? 1 : null;
            $this->vCurs = $currency ? round($currency->getVNom() / $currency->getVCurs(), 4) : null;
            return;
        }

        $currency1 = $this->currencyRepository->findByVchCodeAndCreatedDate(
            $vchCode,
            $createdDate
        );

        $currency2 = $this->currencyRepository->findByVchCodeAndCreatedDate(
            $baseVchCode,
            $createdDate
        );

        $this->vNom = $currency1 && $currency2 ? 1 : null;
        $this->vCurs = $currency1 && $currency2 ?
            round(($currency2->getVNom() / $currency2->getVCurs()) * $currency1->getVCurs(), 4)
            : null;
    }
}
