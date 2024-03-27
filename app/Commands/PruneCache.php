<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\Cache;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cache:prune',
    description: 'Removes expired cache',
    hidden: false
)]
final class PruneCache extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info("Pruning cache..");

		if (Cache::prune()) {
			$io->success("Cache prune successful");
		} else {
			$io->error('There was an error pruning the cache');
		}

        return Command::SUCCESS;
    }
}
