<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name: 'syncer:all:run',
	description: 'Add a short description for your command',
)]
class SyncerAllRunCommand extends Command
{
	protected function configure(): void
	{
		$this->setDescription('Runs multiple syncer commands');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$commands = [
			'NRPZS syncer' => 'syncer:nrpzs:run',
			'SUKL syncer' => 'syncer:sukl:run',
			'MKN syncer' => 'syncer:mkn:run',
		];

		$io = new SymfonyStyle($input, $output);
		$io->title('Running syncers');

		foreach ($commands as $name => $command) {
			$io->section($name);
			$this->runCommand($command, $output);
		}

		return Command::SUCCESS;
	}

	private function runCommand($command, OutputInterface $output): void
	{
		$application = $this->getApplication();

		$commandInstance = $application->find($command);

		$input = new ArrayInput([]);
		$commandInstance->run($input, $output);
	}
}
