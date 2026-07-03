<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Service;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Wegewerk\Ai3Core\Domain\Model\Dto\AddGenerationTask;
use Wegewerk\Ai3Core\Domain\Model\GenerationTask;
use Wegewerk\Ai3Core\Domain\Repository\GenerationTaskRepository;
use Wegewerk\Ai3Core\Enums\Status;

final class GenerationTaskService implements SingletonInterface
{
    public function __construct(
        protected GenerationTaskRepository $generationTaskRepository,
        protected PersistenceManager $persistenceManager,
        protected LoggerInterface $logger,
        protected CapabilityRegistry $capabilityRegistry,
    ) {}

    public function addTask(AddGenerationTask $addGenerationTask): GenerationTask
    {
        $this->logger->debug('Add generation task', [ 'task' => $addGenerationTask ]);
        $generationTask = GenerationTask::create($addGenerationTask);
        $this->logger->debug('Task', [ 'task' => $generationTask ]);

        $generationTask->setPid(0);

        if ($generationTask->getUid() === null) {
            $this->generationTaskRepository->add($generationTask);
            $this->persistenceManager->persistAll();

            return $generationTask;
        }
        throw new \Exception('Task could not be created');

    }

    public function getTask(int $uid): ?GenerationTask
    {
        /** @var GenerationTask $task */
        $task = $this->generationTaskRepository->findOneBy(
            [ 'record_uid' => $uid ],
            [ 'tstamp' => QueryInterface::ORDER_DESCENDING ]
        );
        return $task ?? null;
    }

    public function isTaskRunning(int $uid): bool
    {

        /** @var GenerationTask $task */
        $task = $this->generationTaskRepository->findOneBy([ 'record_uid' => $uid ]);

        if ($task) {
            return $task->getStatus() === Status::pending || $task->getStatus() === Status::processing;
        }

        return false;
    }

    public function isGenerated(int $uid, string $currentText): bool
    {
        /** @var GenerationTask $task */
        $task = $this->generationTaskRepository->findOneBy([ 'record_uid' => $uid, 'status' => Status::done->value ]);
        $result = $task?->getResult();

        return $result === $currentText;
    }

    // check if latest Task is done
    public function hasGenerationDone(int $uid): bool
    {
        $task = $this->generationTaskRepository->findOneBy([ 'record_uid' => $uid ]);
        return $task?->getStatus() === Status::done;
    }

    // if latest Task is newer than timestamp
    public function lastGenerationNewerThan(int $uid, int $timestamp): bool
    {
        $task = $this->generationTaskRepository->findOneBy(
            [ 'record_uid' => $uid ],
            [ 'generated_timestamp' => QueryInterface::ORDER_DESCENDING ]
        );
        return $task?->getGeneratedtimestamp() > $timestamp;
    }

    public function getLatestResult(int $uid): string
    {
        $task = $this->generationTaskRepository->findOneBy([ 'record_uid' => $uid, 'status' => Status::done->value ]);
        if ($task) {
            return $task->getResult();
        }
        return '';
    }

    /**
     * @param int $uid
     * @return bool
     *
     * TODO möglicherweise können hier mehrere Einträge vorliegen, muss noch gehärtet werden
     */
    public function isReviewed(int $uid): bool
    {
        /** @var GenerationTask $task */
        $task = $this->generationTaskRepository->findOneBy([ 'record_uid' => $uid ]);
        if ($task === null) {
            return false;
        }

        return $task->isReviewed();
    }

    public function setReviewed(GenerationTask $task): void
    {
        $task->setReviewed(true);
        $this->generationTaskRepository->update($task);
        $this->persistenceManager->persistAll();
    }

    public function processTask(GenerationTask $task): void
    {
        $task->markProcessing();
        $this->generationTaskRepository->update($task);
        $this->persistenceManager->persistAll();

        $this->logger->debug(
            'Process task',
            [
                'uid' => $task->getUid(),
                'capability' => $task->getCapability(),
                'prompt' => $task->getPrompt(),
                'image' => $task->getImage(),
            ]
        );

        $result = null;

        try {
            $capability = $this->capabilityRegistry->getCapability($task->getCapability());
            if ($capability !== null) {
                $result = $capability->endpoint->generate($task->getImage(), $task->getPrompt(), $task->getGenerateLanguage());
            }
            if ($result !== null) {
                $task->setResult($result);
                $task->markDone();
                $task->setGeneratedtimestamp(time());
            }

        } catch (\JsonException $e) {
            $task->markPending();
            $this->logger->error('JSON Dekodierungsfehler: ' . $e->getMessage());
            throw $e;
        } catch (ClientExceptionInterface $e) {
            $task->fail($e->getMessage());
            $this->logger->error('Client Exception: ' . $e->getMessage());
            throw $e;
        } finally {
            $this->generationTaskRepository->update($task);
            $this->persistenceManager->persistAll();
        }

    }

}
