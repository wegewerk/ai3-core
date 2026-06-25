.. include:: /Includes.rst.txt

.. _developer-api-client:

==========
API client
==========

The Zak_ai API client layer consists of two classes:

- :php:`Wegewerk\Ai3Core\Api\ZakAiClient` — low-level PSR HTTP wrapper
- :php:`Wegewerk\Ai3Core\Api\ZakAiAccount` — account-level operations

Both classes are registered as services and can be injected via
constructor DI.

.. _developer-api-client-zak-ai-client:

ZakAiClient
===========

``ZakAiClient`` handles authentication and the raw HTTP layer. It reads
credentials from the environment at construction time (see
:ref:`configuration-env-vars`). The base URL defaults to
``https://www.zak-ai.com/api/v1/``.

**Public methods:**

.. list-table::
    :header-rows: 1
    :widths: 45 55

    * - Method
      - Description
    * - ``postJson(string $path, array $payload, ...)``
      - Sends a JSON POST and returns the decoded response.
        Appends ``model`` to the payload automatically.
    * - ``putJson(string $path, array $payload, ...)``
      - Sends a JSON PUT.
    * - ``getJson(string $path, ...)``
      - Sends a GET request (no body).
    * - ``hasApikey(): bool``
      - Returns ``true`` if ``ZAKAI_API_KEY`` is non-empty.
    * - ``hasSecret(): bool``
      - Returns ``true`` if ``ZAKAI_SECRET`` is non-empty.

All request methods accept optional ``$extraHeaders`` and a
``$requireAuth`` flag. When ``$requireAuth`` is ``true`` (default) and
no API key is set, a ``\RuntimeException`` is thrown before the request
is sent.

.. _developer-api-client-zak-ai-account:

ZakAiAccount
============

``ZakAiAccount`` wraps the account-related endpoints:

.. list-table::
    :header-rows: 1
    :widths: 45 55

    * - Method
      - Description
    * - ``requestApikey(mixed $email, string $secret)``
      - ``POST /accounts`` — request a new API key.
    * - ``confirmApikey()``
      - ``PUT /accounts`` — confirm the pending API key.
    * - ``queryCredits(): mixed``
      - ``GET /credits`` — return the current credit balance.
    * - ``clientHasApikey(): bool``
      - Proxy for ``ZakAiClient::hasApikey()``.
    * - ``clientHasSecret(): bool``
      - Proxy for ``ZakAiClient::hasSecret()``.

.. note::
    ``requestApikey()`` and ``confirmApikey()`` are called internally
    by the :ref:`Zak_ai management view <usage-zakaimgmt>` in the
    backend module. Direct calls by other Code is not intended.
