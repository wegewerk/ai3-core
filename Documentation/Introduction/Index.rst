.. include:: /Includes.rst.txt

.. _introduction:

============
Introduction
============

.. _what-it-does:

What it does
============

Ai3 Core is the shared core package of the **Ai3 Suite**. It is not a
standalone user-facing extension; instead, it provides infrastructure that all
other Ai3 packages depend on.

**Core responsibilities:**

- Authenticate against the **Zak_ai REST API** using credentials supplied
  via environment variables.
- Manage a **Zak_ai registration lifecycle** (API key request and confirm).
  the registration progress is persisted in the TYPO3 system registry.
- Maintain a **generation task queue** stored in tx_ai3_domain_model_generation_task.
  AI-generation requests can be processed asynchronously.
- Expose a **capability registry** that other Ai3 extensions use to
  register their own AI endpoints.
- Provide a TYPO3 **backend module** (*Ai3*) with a dashboard, the Zak_ai
  setup wizard, and view of the generation task queue.

.. _features:

Features
========

- PSR-compliant HTTP client wrapper for the Zak_ai API
- Symfony DI compiler pass for tagged capability registration
- Extbase ``GenerationTask`` entity and repository
- Backend module
- AJAX endpoints for live Zak_ai credit and account info
- CLI: ``ai3_core:process_tasks``
- ES module namespace ``@wegewerk/ai3core/``

.. _requirements:

Requirements
============

.. list-table::
    :header-rows: 1
    :widths: 30 70

    * - Dependency
      - Requirement
    * - TYPO3 CMS
      - ``^13.4.0 || ^14.0``
    * - PHP
      - >=8.4
    * - PHP extension
      - ``ext-intl``
