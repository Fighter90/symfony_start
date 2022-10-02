<?php

namespace App\Currencies\Infrastructure\Console;

use App\Currencies\Domain\Factory\CurrencyFactory;
use App\Currencies\Infrastructure\Repository\CurrencyRepository;
use App\Currencies\Infrastructure\Service\Interfaces\CurrencyParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:currencies:parse',
    description: 'parse currencies to db',
)]
final class ParseCurrencies extends Command
{
    public function __construct(
        private readonly CurrencyRepository $currencyRepository,
        private readonly CurrencyParserInterface $currencyParser,
        private readonly CurrencyFactory $currencyFactory,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('date', InputArgument::OPTIONAL, 'The date for parsing');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = $input->getArgument('date');

        $this->logger->debug('Start parsing data. Date: '.($date ?? 'current'));

        try {
            $currencyList = $this->currencyParser->parse($date);

            foreach ($currencyList as $currencyItem) {
                $currency = $this->currencyRepository->findByVchCodeAndCreatedDate($currencyItem->getVchCode(), $currencyItem->getCreatedDate());

                if (!$currency) {
                    $currency = $this->currencyFactory->create(
                        $currencyItem->getVchCode(),
                        $currencyItem->getVNom(),
                        $currencyItem->getVCurs(),
                        $currencyItem->getVCode(),
                        $currencyItem->getCreatedDate()
                    );
                } else {
                    $currency->setVCurs($currencyItem->getVCurs());
                    $currency->setVNom($currencyItem->getVNom());
                }

                $this->currencyRepository->save($currency);
            }

            $this->logger->debug('End parsing data. Count: '.count($currencyList));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['date' => $date]);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
