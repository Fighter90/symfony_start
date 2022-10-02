<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\Repository;

use App\Currencies\Domain\Entity\Currency;
use App\Currencies\Domain\Repository\CurrencyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyRepository extends ServiceEntityRepository implements CurrencyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function add(Currency $currency): void
    {
        $this->_em->persist($currency);
        $this->_em->flush();
    }

    public function findByVchCodeAndCreatedDate(string $vchCode, \DateTime $createdDate): ?Currency
    {
        return $this->findOneBy(['vchCode' => $vchCode, 'createdDate' => $createdDate]);
    }

    public function findById(int $id): ?Currency
    {
        return $this->find($id);
    }
}
