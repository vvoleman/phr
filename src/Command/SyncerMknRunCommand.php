<?php

namespace App\Command;

use App\Service\SUKL\Exception\SuklException;
use App\Service\UZIS\LoadUZISFile;
use App\Service\UZIS\UZISCsvSyncer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'syncer:mkn:run',
    description: 'Download and import all MKN data from UZIS',
)]
class SyncerMknRunCommand extends Command
{
	public function __construct(private readonly LoadUZISFile $zipFile, private readonly UZISCsvSyncer $csvSyncer) { parent::__construct(); }

	protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
		try {
//			$this->zipFile->load();
			$this->csvSyncer->sync();

			$io->success("Data loaded successfully (duration: " . (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) . "s).");
		} catch (SuklException $e) {
			$io->error($e->getMessage());
			return Command::FAILURE;
		}
        return Command::SUCCESS;
    }
}
