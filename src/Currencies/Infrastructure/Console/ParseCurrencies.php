<?php

namespace App\Currencies\Infrastructure\Console;

use App\Currencies\Application\Command\CreateCurrency\CreateCurrencyCommand;
use App\Currencies\Application\Command\UpdateCurrency\UpdateCurrencyCommand;
use App\Currencies\Application\DTO\CurrencyDTO;
use App\Currencies\Application\Query\FindCurrency\FindCurrencyByVchCodeAndCreatedDateQuery;
use App\Currencies\Infrastructure\Service\Interfaces\CurrencyParserInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Cache\CacheInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
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
        private readonly CurrencyParserInterface $currencyParser,
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly MemcachedAdapter $cachePool,
        private readonly CacheInterface $cacheUtil
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
            // Clear all cache before update currencies
            $this->cacheUtil->deleteAll($this->cachePool);
            $currencyList = $this->currencyParser->parse($date);

            foreach ($currencyList as $currencyItem) {
                $query = new FindCurrencyByVchCodeAndCreatedDateQuery(
                    $currencyItem->getVchCode(),
                    $currencyItem->getCreatedDate()
                );

                /**
                 * @var CurrencyDTO $currencyDTO
                 */
                $currencyDTO = $this->queryBus->execute($query);

                if (!$currencyDTO->id) {
                    $command = new CreateCurrencyCommand(
                        $currencyItem->getVchCode(),
                        $currencyItem->getVNom(),
                        $currencyItem->getVCurs(),
                        $currencyItem->getVCode(),
                        $currencyItem->getCreatedDate()
                    );
                    $this->commandBus->execute($command);
                } else {
                    $command = new UpdateCurrencyCommand(
                        $currencyDTO->id,
                        $currencyItem->getVNom(),
                        $currencyItem->getVCurs(),
                    );
                    $this->commandBus->execute($command);
                }
            }

            $this->logger->debug('End parsing data. Count: '.count($currencyList));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['date' => $date]);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
