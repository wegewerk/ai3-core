<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class GenerationTaskRepository extends Repository
{
    protected $defaultOrderings = [
        'uid' => QueryInterface::ORDER_DESCENDING,
    ];

    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $querySettings->setRespectSysLanguage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}
