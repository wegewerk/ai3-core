<?php

use Wegewerk\Ai3Core\Controller\Ajax\AccountController;

return [
    'ai3_credits' => [
        'path' => '/ai3/zakai/credits',
        'target' => AccountController::class . '::getCredits',
    ],
    'ai3_account' => [
        'path' => '/ai3/zakai/account',
        'target' => AccountController::class . '::getAccount',
    ],
];
