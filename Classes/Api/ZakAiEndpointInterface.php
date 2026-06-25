<?php

namespace Wegewerk\Ai3Core\Api;

interface ZakAiEndpointInterface
{
    public function generate(string $imagePath, string $description, string $language): string;

}
