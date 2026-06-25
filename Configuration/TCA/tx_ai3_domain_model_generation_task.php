<?php

use Wegewerk\Ai3Core\FormEngine\CapabilityItemsProcFunc;
use Wegewerk\Ai3Core\FormEngine\StatusItemsProcFunc;

return [
    'ctrl' => [
        'title' => 'Generation Task',
        'label' => 'record_field',
        'label_alt' => 'record_table, uid',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'rootLevel' => 1,
        'versioningWS' => false,
        'iconfile' => 'EXT:ai3_core/Resources/Public/Icons/Extension.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                status, prompt, image, capability, record_table, record_field, record_uid, generate_language, parameters, result, result_meta, error_message, reviewed
            ',
        ],
    ],
    'select' => [
        'exclude'     => false,
        'label'       => 'LLL:EXT:ai3/Resources/Private/Language/locallang_db.xlf:tx_ai3_domain_model_generation_task.select',
        'description' => 'LLL:EXT:ai3/Resources/Private/Language/locallang_db.xlf:tx_ai3_domain_model_generation_task.select.description',
        'config'      => [
            'type'       => 'select',
            'renderType' => 'selectSingle',
            'items'      => [
                [
                    'label' => 'LLL:EXT:ai3/Resources/Private/Language/locallang_db.xlf:tx_ai3_domain_model_generation_task.select.items.divider',
                    'value' => '--div--',
                ],
                [
                    'label' => 'LLL:EXT:ai3/Resources/Private/Language/locallang_db.xlf:tx_ai3_domain_model_generation_task.select.items.second',
                    'value' => 'value',
                ],
            ],
        ],
    ],
    'columns' => [
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'status' => [
            'label' => 'status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => StatusItemsProcFunc::class . '->itemsProcFunc',
                'default' => 'pending',
                'size' => 1,
                'readOnly' => false,
            ],
        ],
        'prompt' => [
            'label' => 'prompt',
            'config' => [
                'type' => 'text',
                'readOnly' => true,
            ],
        ],
        'image' => [
            'label' => 'image',
            'config' => [
                'type' => 'text',
                'readOnly' => true,
            ],
        ],
        'capability' => [
            'label' => 'capability',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => CapabilityItemsProcFunc::class . '->itemsProcFunc',
                'size' => 1,
                'readOnly' => false,
            ],
        ],
        'record_table' => [
            'label' => 'table',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'readOnly' => true,
            ],
        ],
        'record_field' => [
            'label' => 'field',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'readOnly' => true,
            ],
        ],
        'record_uid' => [
            'label' => 'uid',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'readOnly' => true,
            ],
        ],
        'generate_language' => [
            'label' => 'language',
            'config' => [
                'type' => 'text',
                'readOnly' => true,
            ],
        ],
        'parameters' => [
            'label' => 'parameters',
            'config' => [
                'type' => 'text',
                'readOnly' => true,
            ],
        ],
        'result' => [
            'label' => 'result',
            'config' => [
                'type' => 'text',
                'readOnly' => true,
            ],
        ],
        'result_meta' => [
            'label' => 'result_meta',
            'config' => [
                'type' => 'text',
                'readOnly' => true,
            ],
        ],
        'error_message' => [
            'label' => 'error_message',
            'config' => [
                'type' => 'text',
                'readOnly' => true,
            ],
        ],
        'generated_timestamp' => [
            'label' => 'generated timestamp',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'readOnly' => true,
            ],
        ],
        'reviewed' => [
            'label' => 'reviewed',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => '', 'value' => 1],
                ],
                'default' => 0,
                'readOnly' => true,
            ],
        ],
    ],
];
