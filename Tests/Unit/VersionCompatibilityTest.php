<?php

declare(strict_types=1);


use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversNothing]
final class VersionCompatibilityTest extends UnitTestCase
{
    #[Test]
    public function currentVersionIsSupported(): void
    {
        $supportedVersions = [13,14];
        $currentVersion = (new Typo3Version())->getMajorVersion();
        echo "current typo3 version: $currentVersion";
        self::assertContains(
            $currentVersion,
            $supportedVersions,
        );
    }
}
