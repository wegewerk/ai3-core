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
        public function generate(
            string $imagePath,
            string $description,
            string $language,
        ): string;
    }

The ``generate()`` method receives the image path (may be empty), a
text prompt, and a language code. It must return the generated text.

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

    use Wegewerk\Ai3Core\Api\ZakAiEndpointInterface;

    final class SummaryEndpoint implements ZakAiEndpointInterface
    {
        public function generate(
            string $imagePath,
            string $description,
            string $language,
        ): string {
            // Call Zak_ai provider here.
            return 'Generated summary …';
        }
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
            return $capability->endpoint->generate('', 'Hello', 'en');
        }
    }

.. _developer-capability-formengine:

TCA integration
===============

To expose capability selection in a FormEngine field, use the provided
``itemsProcFunc``:

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

The items list is populated dynamically from the ``CapabilityRegistry``
at render time.
