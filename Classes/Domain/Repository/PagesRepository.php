<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

class PagesRepository
{
    // get bodytext from these CTypes only when determining Page Content
    const summaryCtypes = [ 'header', 'text', 'textpic', 'textmedia' ];

    public function __construct(
        protected readonly ConnectionPool $connectionPool,
    ) {
    }

    public function getPageContent(int $pageId): string {
        // Language der übergebenen Seite holen
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('pages');
        $queryBuilder->select('sys_language_uid', 'l10n_parent')
            ->from('pages')
            ->where($queryBuilder->expr()
                ->eq('uid', $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)),);

        $pageRow = $queryBuilder->executeQuery()
            ->fetchAssociative();
        // wenn nicht standardsprache
        // muss l10n_parent für die pid in tt_content verwendet werden
        if ($pageRow['sys_language_uid'] > 0) {
            $pageId = $pageRow['l10n_parent'];
            $languageId = $pageRow['sys_language_uid'];
        } else {
            $languageId = 0;
        }


        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');

        $queryBuilder->select('bodytext')
            ->from('tt_content')
            ->where($queryBuilder->expr()
                ->eq('pid', $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)),
                $queryBuilder->expr()
                    ->in('CType',
                        $queryBuilder->createNamedParameter(self::summaryCtypes, Connection::PARAM_STR_ARRAY)),
                $queryBuilder->expr()
                    ->neq('bodytext', $queryBuilder->createNamedParameter('')),
                $queryBuilder->expr()
                    ->isNotNull('bodytext'),
                $queryBuilder->expr()
                    ->eq('sys_language_uid', $queryBuilder->createNamedParameter($languageId, Connection::PARAM_INT)));

        $results = $queryBuilder->executeQuery()
            ->fetchAllAssociative();
        $content = [];
        foreach ($results as $row) {
            $content[] = strip_tags($row['bodytext']);
        }

        return implode("\n\n", $content);
    }
}
