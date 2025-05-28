<?php

declare(strict_types=1);

use App\Kernel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\ResolveTargetEntityListener;
use Symfony\Component\DependencyInjection\ContainerInterface;

require \dirname(__DIR__) . '/bootstrap.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']); // @phpstan-ignore-line argument.type
$kernel->boot();

/** @var ContainerInterface $container */
$container = $kernel->getContainer();

/** @var EntityManager $objectManager */
$objectManager = $container->get('doctrine')->getManager();

// remove ResolveTargetEntityListener from returned EntityManager to not resolve SuluPersistenceBundle classes
// this is a workaround for the following phpstan issue: https://github.com/phpstan/phpstan-doctrine/issues/98
$resolveTargetEntityListener = \current(\array_filter(
    $objectManager->getEventManager()->getListeners('loadClassMetadata'),
    static fn ($listener) => $listener instanceof ResolveTargetEntityListener,
));

if (false !== $resolveTargetEntityListener) {
    $objectManager->getEventManager()->removeEventListener([Events::loadClassMetadata], $resolveTargetEntityListener);
}

return $objectManager;
