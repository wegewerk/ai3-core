<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Controller\Ajax;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Core\Http\Response;
use Wegewerk\Ai3Core\Domain\Repository\GenerationTaskRepository;
use Wegewerk\Ai3Core\Enums\Status;
use Wegewerk\Ai3Core\Service\GenerationTaskService;

#[\AllowDynamicProperties]
#[AsController]
class TaskController extends AbstractAjaxController
{
    public function __construct(
        LoggerInterface $logger,
        private readonly GenerationTaskService $generationTaskService,
        private readonly GenerationTaskRepository $generationTaskRepository,
    ) {
        parent::__construct($logger);
    }

    public function processTasks(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $tasks = $this->generationTaskRepository->findBy(['status' => Status::pending], ['uid' => 'ASC'], 100);
            $processed = 0;
            foreach ($tasks as $task) {
                $this->generationTaskService->processTask($task);
                $processed++;
            }

            return $this->createJsonSuccessResponse(
                new Response(),
                ['processed' => $processed]
            );
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            return $this->createJsonErrorResponse(
                new Response(),
                ['message' => $e->getMessage()]
            );
        }
    }
}
