<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Ai3 Core',
    'description' => 'The shared foundation of the Ai3 Suite - an AI-assisted content-generation toolkit for TYPO3',
    'category' => 'be',
    'author' => 'wegewerk developers',
    'author_email' => 'typo3@wegewerk.com',
    'author_company' => 'wegewerk GmbH',
    'state' => 'stable',
    'version' => '0.9.13',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.3.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
