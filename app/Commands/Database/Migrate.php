<?php

declare(strict_types=1);

namespace App\Commands\Database;

use App\Services\Cache;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name: 'database:migrate',
	description: 'Run database migrations',
	aliases: ['migrate'],
	hidden: false
)]
final class Migrate extends Command
{
	protected function configure(): void
	{
		$this->addOption('seed', 's', InputOption::VALUE_NONE, 'Seed database after migration');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		$io->writeln('Running database migrations');

		// Run pending migrations
		$appliedMigrations = $this->getCachedMigrations();
		$migrations = $this->getPendingMigrations($appliedMigrations);

		if (empty($migrations)) {
			$io->writeln('No pending migrations');
			return Command::SUCCESS;
		}

		// Sort migrations by timestamp
		usort($migrations, function ($a, $b) {
			return $this->getTimestampFromFilename($a) - $this->getTimestampFromFilename($b);
		});

		foreach ($migrations as $migrationName) {

			$migration = require_once $migrationName;

			if (!$migration->exists()) {
				$migration->up();

				$this->recordMigration($migrationName);
				$io->writeln('success: '.$migrationName);
			} else {
				$io->writeln('skipped: '.$migrationName);
			}
		}

		$io->success('Database migration completed successfully');

		return Command::SUCCESS;
	}

	// Helper functions
	function getCachedMigrations(): array
	{
		return Cache::get('app_applied_migrations') ?? [];
	}

	function getPendingMigrations(array $appliedMigrations): array
	{
		// Get all migration files from a specific directory
		$migrationFiles = glob(BASE_PATH . '/database/migrations/*.php');

		$pendingMigrations = array_diff($migrationFiles, $appliedMigrations);

		// Sort pending migrations by timestamp
		usort($pendingMigrations, function ($a, $b) {
			return $this->getTimestampFromFilename($a) - $this->getTimestampFromFilename($b);
		});

		return $pendingMigrations;
	}

	function recordMigration(string $migrationName): void
	{
		// Record the migration as applied in the cache
		$appliedMigrations = $this->getCachedMigrations();
		$appliedMigrations[] = $migrationName;

		Cache::store('app_applied_migrations', $appliedMigrations);
	}

	function getTimestampFromFilename(string $filename): int|string
	{
		// Extract the timestamp from the migration filename
		preg_match('/(\d{14})/', $filename, $matches);
		return $matches[1] ?? 0;
	}
}