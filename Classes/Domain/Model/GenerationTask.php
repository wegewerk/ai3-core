<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use Wegewerk\Ai3Core\Domain\Model\Dto\AddGenerationTask;
use Wegewerk\Ai3Core\Enums\Status;

class GenerationTask extends AbstractEntity
{
    protected Status $status = Status::pending;
    protected string $prompt = '';
    protected string $image = '';
    protected string $capability = '';
    protected string $recordTable = '';
    protected string $recordField = '';
    protected int $recordUid;
    protected string $generateLanguage = '';
    protected string $parameters = '';
    protected string $result = '';
    protected string $resultMeta = '';
    protected string $errorMessage = '';
    protected bool $reviewed = false;
    protected int $generatedTimestamp = 0;

    public static function create(AddGenerationTask $addGenerationTask): self
    {
        /** @var $logger \TYPO3\CMS\Core\Log\Logger */
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)
            ->getLogger(__CLASS__);

        try {
            $generationTask = new self();
            $generationTask->setPid(0);
            $logger->debug('GenerationTask created', [ 'generationTask' => $generationTask ]);

            $generationTask->setStatus(Status::from($addGenerationTask->status));
            $generationTask->setPrompt($addGenerationTask->prompt);
            $generationTask->setImage($addGenerationTask->image);
            $generationTask->setCapability($addGenerationTask->capability);
            $generationTask->setRecordTable($addGenerationTask->record_table);
            $generationTask->setRecordField($addGenerationTask->record_field);
            $generationTask->setRecordUid($addGenerationTask->record_uid);
            $generationTask->setGenerateLanguage($addGenerationTask->generateLanguage);
            $generationTask->setParameters($addGenerationTask->parameters);
            $generationTask->setResult($addGenerationTask->result);
            $generationTask->setResultMeta($addGenerationTask->result_meta);
            $generationTask->setErrorMessage($addGenerationTask->error_message);

            $logger->debug('GenerationTask created', [ 'generationTask' => $generationTask ]);

        } catch (\Throwable $e) {
            $logger->debug('Fehler beim Erstellen des GenerationTask: ', [ 'error' => $e->getMessage() ]);
        }

        return $generationTask;
    }

    public function markPending(): void
    {
        $this->status = Status::pending;
    }

    public function markProcessing(): void
    {
        $this->status = Status::processing;
    }

    public function markDone(): void
    {
        $this->status = Status::done;
    }

    public function fail(string $error): void
    {
        $this->status = Status::failed;
        $this->errorMessage = $error;
    }

    public function cancel(): void
    {
        $this->status = Status::canceled;
    }

    public function markReviewed(): void
    {
        $this->reviewed = true;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getCapability(): string
    {
        return $this->capability;
    }

    public function setCapability(string $capability): void
    {
        $this->capability = $capability;
    }

    public function getRecordTable(): string
    {
        return $this->recordTable;
    }

    public function setRecordTable(string $record_table): void
    {
        $this->recordTable = $record_table;
    }

    public function getRecordField(): string
    {
        return $this->recordField;
    }

    public function setRecordField(string $record_field): void
    {
        $this->recordField = $record_field;
    }

    public function getRecordUid(): int
    {
        return $this->recordUid;
    }

    public function setRecordUid(int $recordUid): void
    {
        $this->recordUid = $recordUid;
    }

    public function getGenerateLanguage(): string
    {
        return $this->generateLanguage;
    }

    public function setGenerateLanguage(string $generateLanguage): void
    {
        $this->generateLanguage = $generateLanguage;
    }

    public function getParameters(): string
    {
        return $this->parameters;
    }

    public function setParameters(string $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result): void
    {
        $this->result = $result;
    }

    public function getResultMeta(): string
    {
        return $this->resultMeta;
    }

    public function setResultMeta(string $resultMeta): void
    {
        $this->resultMeta = $resultMeta;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    public function isReviewed(): bool
    {
        return $this->reviewed;
    }

    public function setReviewed(bool $reviewed): void
    {
        $this->reviewed = $reviewed;
    }

    public function getGeneratedTimestamp(): int
    {
        return $this->generatedTimestamp;
    }

    public function setGeneratedTimestamp(int $generatedTimestamp): void
    {
        $this->generatedTimestamp = $generatedTimestamp;
    }

    public function hasResult(): bool
    {
        return $this->getResult() !== '';
    }

    public function isDone()
    {
        return $this->status === Status::done;
    }
}
