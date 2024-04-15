<?php

namespace App\Command;

use App\Service\NRPZs\LoadNRPZSFile;
use App\Service\NRPZs\NRPZSCsvSyncer;
use League\Csv\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'syncer:nrpzs:run',
    description: 'Download and import all NRPZS data',
)]
class SyncerNrpzsRunCommand extends Command
{

	public function __construct(private readonly LoadNRPZSFile $file, private readonly NRPZSCsvSyncer $csvSyncer){
		parent::__construct();
	}

	protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
		$io = new SymfonyStyle($input, $output);
		try {
			$this->file->load();
			$this->csvSyncer->sync();

			$io->success("Data loaded successfully (duration: " . (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) . "s).");
		} catch (Exception $e) {
			$io->error($e->getMessage());
			return Command::FAILURE;
		}
		return Command::SUCCESS;
    }
}
