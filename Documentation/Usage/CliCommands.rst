.. include:: /Includes.rst.txt

.. _usage-cli:

============
CLI commands
============

Ai3 Core ships two Symfony console commands registered with
TYPO3's CLI dispatcher.

.. _usage-cli-credits:

ai3_core:credits
================

Queries the Zak_ai credits endpoint and dumps the raw response.

.. code-block:: bash
    :caption: Query available credits

    vendor/bin/typo3 ai3_core:credits

**Output:** A ``var_dump()`` of the decoded JSON response from
``GET /credits``. Useful for verifying that ``ZAKAI_API_KEY`` is set
correctly and the API is reachable.

.. _usage-cli-process-tasks:

ai3_core:process_tasks
======================

Fetches up to 100 pending ``GenerationTask`` records (ordered by UID
ascending) and processes each one by calling the registered capability
endpoint.

.. code-block:: bash
    :caption: Process all pending generation tasks

    vendor/bin/typo3 ai3_core:process_tasks

**Processing logic per task:**

1. Look up the capability identified by ``task.capability`` in the
   :ref:`CapabilityRegistry <developer-capability-registry>`.
2. Call ``capability->endpoint->generate(image, prompt, language)``.
3. If a result is returned, set ``task.result``, mark the task as
   *Done*, and store ``task.generated_timestamp``.
4. Persist all changes.

.. tip::
    Run this command periodically via a TYPO3 scheduler task or a
    system cron job to keep the task queue drained.
