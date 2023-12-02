<?php

declare(strict_types=1);

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'server:start',
    description: 'Start the development server',
    hidden: false
)]
final class StartServer extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption(
                'port',
                'p',
                InputOption::VALUE_OPTIONAL,
                'What port should the server run on?',
                8000
            )
            ->addOption(
                'host',
                'H',
                InputOption::VALUE_OPTIONAL,
                'What host should the server run on?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $port = $input->getOption('port');
        $io = new SymfonyStyle($input, $output);

        $address = "localhost:$port";
        if ($input->hasOption('host')) {
            $address = "0.0.0.0:8888";
        }

        // show starting server info style message
        $io->info("Starting server on http://$address");

        // start the server
        shell_exec("php -S $address -t public");

        $io->success("Server started on http://$address");

        return Command::SUCCESS;
    }
}
