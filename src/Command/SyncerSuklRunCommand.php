<?php

namespace App\Command;

use App\Service\CsvSyncer;
use App\Service\SUKL\SUKLCsvSyncer;
use App\Service\SUKL\LoadSUKLFile;
use App\Service\SUKL\Exception\SuklException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'syncer:sukl:run',
    description: 'Download and import SUKL medicine data',
)]
class SyncerSuklRunCommand extends Command
{

	public function __construct(private readonly LoadSUKLFile $zipFile, private readonly SUKLCsvSyncer $csvSyncer) { parent::__construct(); }

	protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

		try {
			$start = microtime(true);
			$this->zipFile->load();
			CsvSyncer::clear();
			$this->csvSyncer->sync();

			$topMemory = CsvSyncer::getTopMemory();
			$time = microtime(true) - $start;
			$io->success(sprintf("Data loaded successfully (duration: %fs, peak memory: %fMB).", $time, $topMemory));
		} catch (SuklException $e) {
			$io->error($e->getMessage());
			return Command::FAILURE;
		}

        return Command::SUCCESS;
    }
}
