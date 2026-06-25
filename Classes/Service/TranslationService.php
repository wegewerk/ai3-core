<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Service;

use TYPO3\CMS\Core\Localization\LanguageService;

final class TranslationService
{
    public function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    public function translate(string $xlfKey, array $arguments = []): string
    {
        $xlfPrefix = '';
        if (!str_starts_with($xlfKey, 'LLL:')) {
            $xlfPrefix = 'LLL:EXT:ai3_core/Resources/Private/Language/locallang.xlf:';
        }

        return sprintf($this->getLanguageService()->sL($xlfPrefix . $xlfKey), ...$arguments);
    }
}
