<?php

namespace App\Command;

use App\Service\CompanyImporterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:product-importer',
    description: 'Imports companies from a CSV file.',
)]
class ProductsImporterCommand extends Command
{
    public function __construct(CompanyImporterService $companyImporter)
    {

        $this->companyImporter = $companyImporter;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csvFilePath = '/var/www/html/public/assets/FileSap.csv';
        if (!file_exists($csvFilePath)) {
            $output->writeln("<error>CSV file does not exist at path: $csvFilePath</error>");
            return Command::FAILURE;
        }
        try {
            $this->companyImporter->importProducts($csvFilePath);
            $output->writeln('Products imported successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to import products: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
