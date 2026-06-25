<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Wegewerk\Ai3Core\DependencyInjection\TaggedCapabilityPass;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->addCompilerPass(new TaggedCapabilityPass('ai3.capability'));
};
