.. include:: /Includes.rst.txt

.. _developer-capability-api:

==============
Capability API
==============

A *capability* is a named AI endpoint that a third-party Ai3 extension
provides. Ai3 Core exposes a **tagged DI compiler pass** so that
capabilities register without code changes in ``ai3_core``.

.. _developer-capability-api-interface:

ZakAiEndpointInterface
======================

Every capability must implement
:php:`Wegewerk\Ai3Core\Api\ZakAiEndpointInterface`:

.. code-block:: php
    :caption: Classes/Api/ZakAiEndpointInterface.php

    interface ZakAiEndpointInterface
    {
        public function generate(array $options = []): string;
    }

The ``generate()`` method receives an associative array of options (such
as ``text``, ``image``, ``language``, etc.) and returns the generated
string.

.. _developer-capability-api-abstract-endpoint:

AbstractZakAiEndpoint
=====================

For most capabilities, extending
:php:`Wegewerk\Ai3Core\Api\AbstractZakAiEndpoint` is the recommended way
to implement endpoints. It handles required parameter validation,
building the API request payload, sending the POST request via
``ZakAiClient``, and extracting the response key.

Subclasses declare their configuration via protected properties:

.. list-table::
    :header-rows: 1
    :widths: 30 70

    * - Property
      - Description
    * - ``string $endpoint``
      - The API path for the POST request (e.g. ``bulletpoints``, ``faq``, ``alttexts``).
    * - ``string $responseKey``
      - The JSON key to extract from the API response.
    * - ``array $requiredOptions``
      - List of required option keys (e.g. ``['text']`` or ``['image']``).
    * - ``array $optionalOptions``
      - List of optional option keys passed through to the payload if present.

Payload construction and hooks:

- **Validation:** ``validateRequiredOptions(array $options)`` verifies that
  all keys in ``$requiredOptions`` are present and non-empty.
- **Payload building:** ``buildPayload(array $options)`` populates the
  payload directly with all required and present optional option keys.
- **Preparation hook:** ``preparePayload(array $payload, array $options): array``
  allows custom manipulation before sending (e.g. image resizing and base64 encoding).
- **Result extraction:** ``extractResult(array $response): string`` extracts
  the ``$responseKey`` value (converting arrays to JSON if needed).

.. _developer-capability-api-register:

Registering a capability
========================

Tag your endpoint service with ``ai3.capability`` in your extension's
:file:`Configuration/Services.yaml`. The compiler pass picks up the
``$key``, ``$title``, and ``$endpoint`` constructor arguments
automatically:

.. code-block:: yaml
    :caption: Configuration/Services.yaml (your extension)

    MyVendor\MyAiExt\Endpoint\SummaryEndpoint:
        tags:
            - name: ai3.capability
        arguments:
            $key: 'my_ext_summary'
            $title: 'Automatic summary'
            $endpoint: '@MyVendor\MyAiExt\Endpoint\SummaryEndpoint'

.. code-block:: php
    :caption: Classes/Endpoint/SummaryEndpoint.php

    use Wegewerk\Ai3Core\Api\AbstractZakAiEndpoint;

    final class SummaryEndpoint extends AbstractZakAiEndpoint
    {
        protected string $endpoint = 'bulletpoints';
        protected string $responseKey = 'bulletpoints';
        protected array $requiredOptions = ['text'];
        protected array $optionalOptions = ['language'];
    }

.. _developer-capability-registry:

CapabilityRegistry
==================

At runtime, all registered capabilities are available via
:php:`Wegewerk\Ai3Core\Service\CapabilityRegistry`. It is a
``SingletonInterface`` and can be injected via constructor:

.. code-block:: php
    :caption: Injecting the registry

    use Wegewerk\Ai3Core\Service\CapabilityRegistry;

    final class MyService
    {
        public function __construct(
            private readonly CapabilityRegistry $capabilityRegistry,
        ) {}

        public function run(string $capabilityKey): string
        {
            $capability = $this->capabilityRegistry
                ->getCapability($capabilityKey);
            if ($capability === null) {
                throw new \RuntimeException(
                    'Unknown capability: ' . $capabilityKey,
                );
            }
            return $capability->endpoint->generate([
                'text' => 'Hello',
                'language' => 'en',
            ]);
        }
    }

.. _developer-capability-formengine:

TCA integration
===============

The CapabilityRegistry is used in the ``itemsProcFunc`` of ``capability`` in tx_ai3_domain_model_generation_task
:

.. code-block:: php
    :caption: TCA column configuration

    'capability' => [
        'label' => 'AI capability',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' =>
                \Wegewerk\Ai3Core\FormEngine\CapabilityItemsProcFunc::class
                . '->itemsProcFunc',
        ],
    ],

