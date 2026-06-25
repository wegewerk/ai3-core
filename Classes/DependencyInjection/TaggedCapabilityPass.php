<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wegewerk\Ai3Core\Service\CapabilityRegistry;

final readonly class TaggedCapabilityPass implements CompilerPassInterface
{
    public function __construct(private string $tagName) {}

    public function process(ContainerBuilder $container): void
    {
        $capabilityRegistryDefinition = $container->findDefinition(CapabilityRegistry::class);
        foreach ($container->findTaggedServiceIds($this->tagName) as $serviceName => $tags) {
            $definition = $container->findDefinition($serviceName);
            $definition->setPublic(true);
            foreach ($tags as $attributes) {
                $capabilityRegistryDefinition->addMethodCall(
                    'addCapability',
                    [
                        $definition->getArgument('$key'),
                        $definition->getArgument('$title'),
                        $definition->getArgument('$endpoint'),
                    ]
                );
            }
        }
    }
}
