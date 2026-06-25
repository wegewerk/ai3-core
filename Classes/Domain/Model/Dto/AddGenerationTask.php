<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Domain\Model\Dto;

final class AddGenerationTask
{
    public function __construct(
        public string $status,
        public string $prompt,
        public string $image,
        public string $capability,
        public string $record_table,
        public string $record_field,
        public int $record_uid,
        public string $generateLanguage,
        public string $parameters,
        public string $result,
        public string $result_meta,
        public string $error_message,
    ) {}
}
