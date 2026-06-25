<?php

namespace Wegewerk\Ai3Core\Api;

/**
 * Implemets the API Endpoints related to a ZakAi Account
 *
 * - api Key Request
 * - api Key confirmation
 * - query Credits
 * - query Account Information
 */
class ZakAiAccount
{
    public function __construct(private ZakAiClient $client) {}

    public function requestApikey(mixed $email, string $secret)
    {
        $body = $this->client->postJson(
            'accounts',
            [
                'email'  => $email,
                'secret' => $secret,
            ],
            [],
            false
        );
        return true;
    }

    public function confirmApikey()
    {
        $body = $this->client->putJson('accounts');
        return true;
    }

    public function queryCredits(): mixed
    {
        $body = $this->client->getJson('credits');
        return $body;
    }
    public function queryAccount(): mixed
    {
        $body = $this->client->getJson('accounts');
        return $body;
    }

    public function clientHasApikey(): bool
    {
        return $this->client->hasApikey();
    }
    public function clientHasSecret(): bool
    {
        return $this->client->hasSecret();
    }
}
