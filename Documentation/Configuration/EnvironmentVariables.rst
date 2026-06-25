.. include:: /Includes.rst.txt

.. _configuration-env-vars:

=====================
Environment variables
=====================

The Zak_ai HTTP client is configured exclusively through environment
variables.

.. confval:: ZAKAI_API_KEY
    :name: ZAKAI_API_KEY
    :type: string
    :default: *(empty)*

    The API key issued by Zak_ai after a successful registration. When
    empty, all authenticated API calls throw a ``\RuntimeException``.

    Obtain the key by completing the registration wizard in the
    :ref:`backend module <usage-zakaimgmt>`.

.. confval:: ZAKAI_SECRET
    :name: ZAKAI_SECRET
    :type: string
    :default: *(empty)*

    The secret associated with your Zak_ai account. Together with
    ``ZAKAI_API_KEY`` it forms the HTTP Basic Auth header sent with every
    authenticated request.

.. _configuration-env-vars-api-base-url:

API base URL
============

The Zak_ai API base URL is defined in :file:`Configuration/Services.yaml`.
To override it in a specific environment, rebind the service argument in your project's
:file:`Services.yaml`:

.. code-block:: yaml
    :caption: config/services.yaml (project level)

    Wegewerk\Ai3Core\Api\ZakAiClient:
        arguments:
            $baseUrl: 'https://staging.zak-ai.com/api/v1/'

Or define to use another env-var:

.. code-block:: yaml
    :caption: config/services.yaml (project level)

    Wegewerk\Ai3Core\Api\ZakAiClient:
        arguments:
            $baseUrl: '%env(ZAK_AI_BASE_URL)%'


.. _configuration-env-vars-model:

Default model
=============

The default inference model is ``mistral-small3.2:latest``, defined as the
``DEFAULT_MODEL`` constant in
:php:`Wegewerk\Ai3Core\Api\ZakAiClient`. It is appended automatically to
every ``POST`` payload.

It is not detemined yet if this should be user configurable in the future.
