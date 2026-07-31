<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Tests\Functional\Service;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Wegewerk\Ai3Core\Domain\Model\Dto\AddGenerationTask;
use Wegewerk\Ai3Core\Service\GenerationTaskService;

final class GenerationTaskServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'ai3_core',
    ];

    #[Test]
    public function addTaskPersistsGenerationTaskToDatabase(): void
    {
        $service = $this->get(GenerationTaskService::class);

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

        self::assertNotNull($task->getUid());

        $queryBuilder = $this->get(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_ai3_domain_model_generation_task');
        $queryBuilder->getRestrictions()->removeAll();

        $row = $queryBuilder
            ->select('*')
            ->from('tx_ai3_domain_model_generation_task')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($task->getUid())))
            ->executeQuery()
            ->fetchAssociative();

        self::assertIsArray($row);
        self::assertSame('Translate this text to German', $row['prompt']);
        self::assertSame('tt_content', $row['record_table']);
        self::assertSame('bodytext', $row['record_field']);
        self::assertSame(42, (int)$row['record_uid']);
        self::assertSame('en', $row['generate_language']);
        self::assertSame('Pending', $row['status']);
        self::assertSame(0, (int)$row['deleted']);
    }
}
