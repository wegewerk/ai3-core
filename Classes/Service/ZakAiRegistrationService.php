<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Service;

use TYPO3\CMS\Core\Registry;
use Wegewerk\Ai3Core\Enums\RegistrationStatus;

/**
 * Bildet den Registration Status und die Zugangsdaten für ZAK AI in der TYPO3 registry ab
 *
 * Registrierungsdaten werden über einen 'context' adressiert.
 * Der context ist ein beliebiger String, kann auch leer sein.
 */
final class ZakAiRegistrationService
{
    private const REG_NAMESPACE = 'ai3';
    private const CONTEXT = 'zakai/registration';

    public function __construct(
        private readonly Registry $registry,
    ) {}

    public function setSecret(mixed $secret)
    {
        $this->initialize();
        $current = $this->_get();
        $current['secret'] = $secret;
        $this->_set($current);
    }

    public function setEmail(mixed $email)
    {
        $this->initialize();
        $current = $this->_get();
        $current['email'] = $email;
        $this->_set($current);
    }

    public function getSecret()
    {
        $this->initialize();
        $current = $this->_get();
        return $current['secret'] ?? '';
    }

    public function getEmail()
    {
        $this->initialize();
        $current = $this->_get();
        return $current['email'] ?? '';
    }

    private function initialize(): void
    {
        if ($this->_get() === []) {
            $this->_set([
                'status' => RegistrationStatus::initial->value,
                'requestedAt' => '',
            ]);
        }
    }

    public function getStatus(): RegistrationStatus
    {
        $this->initialize();
        $current = $this->_get();
        return RegistrationStatus::from($current['status']);
    }
    public function setStatus(RegistrationStatus $status): void
    {
        $this->initialize();
        $current = $this->_get();
        $current['status'] = $status->value;
        $this->_set($current);
    }
    private function _get(): array
    {
        return (array)$this->registry->get(self::REG_NAMESPACE, self::CONTEXT, []);
    }

    private function _set(array $data): void
    {
        $data['updatedAt'] = time();
        $this->registry->set(self::REG_NAMESPACE, self::CONTEXT, $data);
    }
}
