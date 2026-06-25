<?php

use Wegewerk\Ai3Core\Controller\Ai3Controller;

return [
    'web_ai3' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/page/ai3',
        'iconIdentifier' => 'ai3-extension',
        'labels' => 'LLL:EXT:ai3_core/Resources/Private/Language/locallang_mod.xlf',
        'routes' => [
            '_default' => [
                'target' => Ai3Controller::class . '::handleRequest',
            ],
        ],
    ],
];
