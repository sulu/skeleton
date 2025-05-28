<?php

declare(strict_types=1);

use TwigCsFixer\Config\Config;
use TwigCsFixer\File\Finder;
use TwigCsFixer\Ruleset\Ruleset;
use TwigCsFixer\Standard\TwigCsFixer;

$ruleset = new Ruleset();
$ruleset->addStandard(new TwigCsFixer());

$finder = new Finder();
$finder->in('templates/');

$config = new Config();
$config->allowNonFixableRules();
$config->setRuleset($ruleset);
$config->setFinder($finder);

// $config->addTwigExtension(new Sulu\Twig\Extensions\PortalExtension());
// $config->addTokenParser(new Sulu\Twig\Extensions\TokenParser\PortalTokenParser());

return $config;
