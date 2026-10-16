<?php

namespace Wegewerk\Ai3Core\Api;

interface ZakAiEndpointInterface
{
    public function generate(array $options = []): string;

}
