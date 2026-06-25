<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wegewerk\Ai3Core\Domain\Repository\GenerationTaskRepository;
use Wegewerk\Ai3Core\Enums\Status;
use Wegewerk\Ai3Core\Service\GenerationTaskService;

/**
 * vendor/bin/typo3 ai3_generate:process_tasks
 */
#[AsCommand(
    name: 'ai3_core:process_tasks',
    description: 'Process tasks for ai generation',
)]
class GenerateTask extends Command
{
    public function __construct(
        private readonly GenerationTaskService $generationTaskService,
        private readonly GenerationTaskRepository $generationTaskRepository,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        parent::configure();
        $this->addOption(
            'test',
            null,
            InputOption::VALUE_NONE,
            'Test',
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tasks = $this->generationTaskRepository->findBy(['status' => Status::pending], ['uid' => 'ASC'], 100);
        foreach ($tasks as $task) {
            $this->generationTaskService->processTask($task);
        }

        return 0;
    }
}
