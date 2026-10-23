<?php

use Wegewerk\Ai3Core\Controller\Ajax\AccountController;
use Wegewerk\Ai3Core\Controller\Ajax\TaskController;

return [
    'ai3_credits' => [
        'path' => '/ai3/zakai/credits',
        'target' => AccountController::class . '::getCredits',
    ],
    'ai3_account' => [
        'path' => '/ai3/zakai/account',
        'target' => AccountController::class . '::getAccount',
    ],
    'ai3_core_process_tasks' => [
        'path' => '/ai3/process-tasks',
        'target' => TaskController::class . '::processTasks',
    ],
];
