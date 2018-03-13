<?php

/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * The abstract kernel holds everything that is common between
 * AdminKernel and WebsiteKernel.
 */
abstract class AbstractKernel extends SuluKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            // symfony standard
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Sulu\Bundle\CoreBundle\SuluCoreBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),

            // doctrine extensions
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            // rest
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),

            // massive
            new Massive\Bundle\SearchBundle\MassiveSearchBundle(),

            // sulu
            new Sulu\Bundle\SearchBundle\SuluSearchBundle(),
            new Sulu\Bundle\PersistenceBundle\SuluPersistenceBundle(),
            new Sulu\Bundle\ContactBundle\SuluContactBundle(),
            new Sulu\Bundle\MediaBundle\SuluMediaBundle(),
            new Sulu\Bundle\SecurityBundle\SuluSecurityBundle(),
            new Sulu\Bundle\CategoryBundle\SuluCategoryBundle(),
            new Sulu\Bundle\SnippetBundle\SuluSnippetBundle(),
            new Sulu\Bundle\ContentBundle\SuluContentBundle(),
            new Sulu\Bundle\TagBundle\SuluTagBundle(),
            new Sulu\Bundle\WebsiteBundle\SuluWebsiteBundle(),
            new Sulu\Bundle\LocationBundle\SuluLocationBundle(),
            new Sulu\Bundle\HttpCacheBundle\SuluHttpCacheBundle(),
            new Sulu\Bundle\WebsocketBundle\SuluWebsocketBundle(),
            new Sulu\Bundle\TranslateBundle\SuluTranslateBundle(),
            new Sulu\Bundle\DocumentManagerBundle\SuluDocumentManagerBundle(),
            new Sulu\Bundle\HashBundle\SuluHashBundle(),
            new Sulu\Bundle\CustomUrlBundle\SuluCustomUrlBundle(),
            new Sulu\Bundle\RouteBundle\SuluRouteBundle(),
            new Sulu\Bundle\MarkupBundle\SuluMarkupBundle(),
            new DTL\Bundle\PhpcrMigrations\PhpcrMigrationsBundle(),
            new Dubture\FFmpegBundle\DubtureFFmpegBundle(),

            // tools
            new Massive\Bundle\BuildBundle\MassiveBuildBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            // symfony standard
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            if (class_exists('Symfony\Bundle\WebServerBundle\WebServerBundle')) {
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }

            // debug enhancement
            $bundles[] = new Sulu\Bundle\TestBundle\SuluTestBundle();
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(
            $this->rootDir . DIRECTORY_SEPARATOR
            . 'config' . DIRECTORY_SEPARATOR
            . $this->getContext() . DIRECTORY_SEPARATOR
            . 'config_' . $this->getEnvironment() . '.yml'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return dirname($this->rootDir) . DIRECTORY_SEPARATOR
        . 'var' . DIRECTORY_SEPARATOR
        . 'cache' . DIRECTORY_SEPARATOR
        . $this->getContext() . DIRECTORY_SEPARATOR
        . $this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return dirname($this->rootDir) . DIRECTORY_SEPARATOR
        . 'var' . DIRECTORY_SEPARATOR
        . 'logs' . DIRECTORY_SEPARATOR
        . $this->getContext();
    }

    protected function getKernelParameters()
    {
        return array_merge(
            parent::getKernelParameters(),
            [
                'kernel.var_dir' => dirname($this->rootDir) . DIRECTORY_SEPARATOR . 'var'
            ]
        );
    }
}
