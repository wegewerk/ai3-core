<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\FormEngine;

use Wegewerk\Ai3Core\Enums\Status;

/**
 * Hole alle verfügbaren Funktionen für ein Formularfeld
 */
class StatusItemsProcFunc
{
    /**
     * @param array $params
     */
    public function itemsProcFunc(&$params): void
    {
        $statuses = Status::cases();

        foreach ($statuses as $status) {
            $params['items'][] = ['label' => $status->value, 'value' => $status->value];
        }
    }
}
