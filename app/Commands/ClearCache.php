<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\Cache;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cache:clear',
    description: 'Clears the cache',
    hidden: false
)]
final class ClearCache extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info("Clearing cache..");

		Cache::deleteAll();

        $io->success("Cache cleared successfully");

        return Command::SUCCESS;
    }
}
