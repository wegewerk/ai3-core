<?php

namespace Wegewerk\Ai3Core\Service;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\SingletonInterface;
use Wegewerk\Ai3Core\Domain\Capabilities\Capability;

#[Autoconfigure(public: true)]
class CapabilityRegistry implements SingletonInterface
{
    /**
     * @var array
     */
    private iterable $capabilities = [];

    public function addCapability($key, $title, $endpointClass): void
    {
        $this->capabilities[$key] = new Capability($key, $title, $endpointClass);
    }

    public function getCapability(string $key): ?Capability
    {
        return $this->capabilities[$key] ?? null;
    }

    public function getEntries()
    {
        return $this->capabilities;
    }

}
