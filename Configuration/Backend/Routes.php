<?php

use Wegewerk\Ai3Core\Controller\Ai3Controller;
use Wegewerk\Ai3Core\Controller\GenerateTaskController;
use Wegewerk\Ai3Core\Controller\ZakAiController;

return [
    'ai3_dashboard'            => [
        'path'   => '/ai3/dashboard',
        'target' => Ai3Controller::class . '::handleRequest',
    ],
    /**
     * Zak_ai routes
     */
    'ai3_zakai'                => [
        'path'   => '/ai3/zakai',
        'target' => ZakAiController::class . '::handleRequest',
    ],
    'ai3_zakai_request_apikey' => [
        'path'   => '/ai3/zakai/request',
        'target' => ZakAiController::class . '::requestApikey',
    ],
    'ai3_zakai_confirm_apikey' => [
        'path'   => '/ai3/zakai/confirm',
        'target' => ZakAiController::class . '::confirmApikey',
    ],
    'ai3_zakai_final_check'    => [
        'path'   => '/ai3/zakai/check',
        'target' => ZakAiController::class . '::checkApikey',
    ],

    'ai3_generatetask' => [
        'path'   => '/ai3/generatetask',
        'target' => GenerateTaskController::class . '::handleRequest',
    ],

];
