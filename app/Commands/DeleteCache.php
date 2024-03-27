<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\Cache;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cache:delete',
    description: 'Delete cache by key',
    hidden: false
)]
final class DeleteCache extends Command
{
	public function configure()
	{
		$this->addArgument('key', InputArgument::REQUIRED, 'Cache key');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

		$key = $input->getArgument('key');

		if (Cache::delete($key)) {
			$io->success("Cache key deleted successful");
		} else {
			$io->error('There was an error deleting the cache');
		}

        return Command::SUCCESS;
    }
}
