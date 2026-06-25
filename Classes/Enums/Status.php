<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Enums;

/**
 * Definiert alle Status für GenerationTasks
 */
enum Status: string
{
    case pending = 'Pending';
    case processing = 'Processing';
    case done = 'Done';
    case failed = 'Failed';
    case canceled = 'Canceled';
}
