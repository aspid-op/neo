<?php

declare(strict_types=1);

namespace App\Command\Neo;

use App\Tools\Import\Import;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

#[AsCommand(
    name: 'neo:import',
    description: 'Imports the Near-Earth Objects'
)]
class ImportCommand extends Command
{
    public function __construct(
        private readonly Import $importService,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'start-date',
                's',
                InputArgument::OPTIONAL,
                'Date to start import iteration',
                date('Y-m-d'),
            )
            ->addOption(
                'days',
                'd',
                InputArgument::OPTIONAL,
                'Days for import',
                7,

            )
            ->addOption(
                'iterations',
                'i',
                InputArgument::OPTIONAL,
                'Count of iterations for import',
                1,
            )
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this
                ->importService
                ->setApiParameters($input->getOption('start-date'), $input->getOption('period'))
                ->import($input->getOption('iterations'));
        } catch (Throwable $exception) {
            throw new BadRequestException($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return Command::SUCCESS;
    }
}
