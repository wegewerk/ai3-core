<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Domain\Capabilities;

use Wegewerk\Ai3Core\Api\ZakAiEndpointInterface;

class Capability
{
    public function __construct(
        public string $key,
        public string $title,
        public ZakAiEndpointInterface $endpoint
    ) {}
}
