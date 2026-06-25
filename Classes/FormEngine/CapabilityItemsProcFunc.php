<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\FormEngine;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Wegewerk\Ai3Core\Service\CapabilityRegistry;

/**
 * Provides the list of capabilities for a FormEngine select field.
 *
 * The constructor argument is optional so the class can be instantiated via
 * `GeneralUtility::makeInstance()` (fallback for older TYPO3 installations or
 * when the DI container does not resolve the class for the itemsProcFunc).
 */
class CapabilityItemsProcFunc
{
    /** @var CapabilityRegistry */
    private CapabilityRegistry $capabilityRegistry;

    /**
     * @param CapabilityRegistry|null $capabilityRegistry Optional injected service.
     */
    public function __construct()
    {
        $this->capabilityRegistry = GeneralUtility::makeInstance(CapabilityRegistry::class);
    }

    /**
     * itemsProcFunc – called by TYPO3 FormEngine.
     *
     * @param array $params The params array provided by FormEngine; we fill
     *                      $params['items'] with ['label' => …, 'value' => …] entries.
     */
    public function itemsProcFunc(array &$params): void
    {
        foreach ($this->capabilityRegistry->getEntries() as $capability) {
            $params['items'][] = [
                'label' => $capability->title,
                'value' => $capability->key,
            ];
        }
    }
}
