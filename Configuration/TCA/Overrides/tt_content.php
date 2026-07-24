<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();


$GLOBALS['TCA']['tt_content']['columns']['tx_ai3_type'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:ai3_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_ai3_type',
    'config' => [
        'type' => 'input',
        'size' => 30,
        'eval' => 'trim',
        'default' => '',
    ],
];

$GLOBALS['TCA']['tt_content']['columns']['tx_ai3_source'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:ai3_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_ai3_source',
    'config' => [
        'type' => 'text',
        'cols' => 40,
        'rows' => 15,
        'eval' => 'trim',
        'default' => '',
    ],
];

$GLOBALS['TCA']['tt_content']['columns']['tx_ai3_raw'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:ai3_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_ai3_raw',
    'config' => [
        'type' => 'text',
        'cols' => 40,
        'rows' => 15,
        'eval' => 'trim',
        'default' => '',
    ],
];

