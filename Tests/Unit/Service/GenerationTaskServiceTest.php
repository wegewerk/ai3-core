<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Tests\Unit\Service;

use PHPUnit\Framework\Attributes\Test;
use Psr\Log\NullLogger;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Wegewerk\Ai3Core\Domain\Model\Dto\AddGenerationTask;
use Wegewerk\Ai3Core\Domain\Model\GenerationTask;
use Wegewerk\Ai3Core\Domain\Repository\GenerationTaskRepository;
use Wegewerk\Ai3Core\Enums\Status;
use Wegewerk\Ai3Core\Service\CapabilityRegistry;
use Wegewerk\Ai3Core\Service\GenerationTaskService;

final class GenerationTaskServiceTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    #[Test]
    public function addTaskPersistsTaskViaRepositoryAndPersistenceManager(): void
    {
        $repository = $this->createMock(GenerationTaskRepository::class);
        $persistenceManager = $this->createMock(PersistenceManager::class);
        $capabilityRegistry = $this->createStub(CapabilityRegistry::class);

        $repository->expects($this->once())
            ->method('add')
            ->willReturnCallback(static function (GenerationTask $task): GenerationTask {
                return $task;
            });
        $persistenceManager->expects($this->once())->method('persistAll');

        $service = new GenerationTaskService(
            $repository,
            $persistenceManager,
            new NullLogger(),
            $capabilityRegistry,
        );

        $dto = new AddGenerationTask(
            status: 'Pending',
            prompt: 'Translate this text to German',
            image: 'fileadmin/some-image.jpg',
            capability: 'translation',
            record_table: 'tt_content',
            record_field: 'bodytext',
            record_uid: 42,
            generateLanguage: 'en',
            parameters: '{}',
            result: '',
            result_meta: '',
            error_message: '',
        );

        $task = $service->addTask($dto);

        $this->assertNull($task->getUid());
        $this->assertSame(0, $task->getPid());
        $this->assertSame(Status::pending, $task->getStatus());
        $this->assertSame('Translate this text to German', $task->getPrompt());
        $this->assertSame('fileadmin/some-image.jpg', $task->getImage());
        $this->assertSame('translation', $task->getCapability());
        $this->assertSame('tt_content', $task->getRecordTable());
        $this->assertSame('bodytext', $task->getRecordField());
        $this->assertSame(42, $task->getRecordUid());
        $this->assertSame('en', $task->getGenerateLanguage());
        $this->assertSame('{}', $task->getParameters());
    }
}
