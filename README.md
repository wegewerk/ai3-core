# Ai3 Core — TYPO3 Extension

**Ai3 Core** (`ai3_core`) is the shared foundation of the *Ai3 Suite* —
an AI-assisted content-generation toolkit for TYPO3. It provides the
ZAK-AI API client, the capability registry, the generation task queue,
a backend module, and CLI commands used by all other Ai3 packages.

## Requirements

| Dependency | Version |
|---|---|
| TYPO3 CMS | `^13.4.0 \| ^14.0` |
| PHP extension | `ext-intl` |

## Installation

```bash
composer require wegewerk/ai3_core
```

## Quick start

1. If you already have a Zak_ai account set the required environment variables:

   ```bash
   export ZAKAI_API_KEY=<your-api-key>
   export ZAKAI_SECRET=<your-secret>
   ```

2. If you dont have a Zak_ai account, Open **Web > Ai3** in the TYPO3 backend and follow the ZAK-AI
   registration wizard to obtain your API key.

3. Run pending generation tasks via CLI:

   ```bash
   vendor/bin/typo3 ai3_core:process_tasks
   ```

## CLI commands

| Command | Description |
|---|---|
| `ai3_core:credits` | Query the ZAK-AI credits balance. |
| `ai3_core:process_tasks` | Process up to 100 pending generation tasks. |

## Extending with a custom capability

Implement `ZakAiEndpointInterface` and tag your service in
`Configuration/Services.yaml`:

```yaml
MyVendor\MyExt\Endpoint\MyEndpoint:
    tags:
        - name: ai3.capability
    arguments:
        $key: 'my_key'
        $title: 'My capability'
        $endpoint: '@MyVendor\MyExt\Endpoint\MyEndpoint'
```

Full developer documentation: `Documentation/`

## Changelog

See [CHANGELOG.md](CHANGELOG.md).
