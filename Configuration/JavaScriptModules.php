<?php

return [
    'dependencies' => ['core', 'backend'],
    'tags' => [
        'backend.form',
        'backend.contextmenu',
    ],
    'imports' => [
        '@wegewerk/ai3core/' => 'EXT:ai3_core/Resources/Public/JavaScript/',
    ],
];
