<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Sulu\Rector\Set\SuluLevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    // basic rules
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        LevelSetList::UP_TO_PHP_81,
    ]);

    // symfony rules
    $rectorConfig->symfonyContainerPhp(__DIR__ . '/var/cache/website/dev/App_KernelDevDebugContainer.xml');

    $rectorConfig->sets([
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonyLevelSetList::UP_TO_SYMFONY_60,
    ]);

    // doctrine rules
    $rectorConfig->sets([
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ]);

    // phpunit rules
    $rectorConfig->sets([
        PHPUnitLevelSetList::UP_TO_PHPUNIT_90,
        PHPUnitSetList::PHPUNIT_91,
    ]);

    // sulu rules
    $rectorConfig->sets([
        SuluLevelSetList::UP_TO_SULU_25,
    ]);
};
