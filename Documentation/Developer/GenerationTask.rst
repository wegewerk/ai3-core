.. include:: /Includes.rst.txt

.. _developer-generation-task:

===============
Generation task
===============

A ``GenerationTask`` represents one AI-generation request. Tasks are
stored in the database table ``tx_ai3_domain_model_generation_task``
and managed through the Extbase ``GenerationTaskRepository``.

.. _developer-generation-task-status:

Task lifecycle
==============

Every task moves through a set of states defined by
:php:`Wegewerk\Ai3Core\Enums\Status`:

.. list-table::
    :header-rows: 1
    :widths: 20 80

    * - Value
      - Meaning
    * - ``Pending``
      - Task created, waiting to be processed.
    * - ``Processing``
      - Reserved for future use (processing flag).
    * - ``Done``
      - AI generation completed; ``result`` is populated.
    * - ``Failed``
      - Generation failed; ``error_message`` is populated.
    * - ``Canceled``
      - Task was canceled before processing.

.. _developer-generation-task-fields:

Field reference
===============

.. confval:: status
    :name: generation-task-status
    :type: string (enum)
    :default: ``Pending``

    Current lifecycle state. One of ``Pending``, ``Processing``,
    ``Done``, ``Failed``, ``Canceled``.

.. confval:: prompt
    :name: generation-task-prompt
    :type: string
    :default: *(empty)*

    The text prompt sent to the AI endpoint.

.. confval:: image
    :name: generation-task-image
    :type: string
    :default: *(empty)*

    Optional path to an image passed to the capability endpoint.

.. confval:: capability
    :name: generation-task-capability
    :type: string
    :default: *(empty)*

    Key of the registered capability (e.g. ``my_ext_summary``).
    See :ref:`developer-capability-api`.

.. confval:: record_table
    :name: generation-task-record-table
    :type: string
    :default: *(empty)*

    Name of the TYPO3 database table that owns the record being
    processed (e.g. ``tt_content``).

.. confval:: record_field
    :name: generation-task-record-field
    :type: string
    :default: *(empty)*

    Name of the field within ``record_table`` that will receive the
    generated text.

.. confval:: record_uid
    :name: generation-task-record-uid
    :type: int

    UID of the owning record.

.. confval:: generate_language
    :name: generation-task-generate-language
    :type: string
    :default: *(empty)*

    ISO language code passed to the capability endpoint.

.. confval:: result
    :name: generation-task-result
    :type: string
    :default: *(empty)*

    The text returned by the capability endpoint after a successful
    generation.

.. confval:: result_meta
    :name: generation-task-result-meta
    :type: string
    :default: *(empty)*

    Optional JSON metadata returned alongside the result.

.. confval:: error_message
    :name: generation-task-error-message
    :type: string
    :default: *(empty)*

    Human-readable error description when ``status`` is ``Failed``.

.. confval:: reviewed
    :name: generation-task-reviewed
    :type: bool
    :default: ``false``

    Set to ``true`` by an editor after reviewing the generated result.

.. confval:: generated_timestamp
    :name: generation-task-generated-timestamp
    :type: int (Unix timestamp)
    :default: ``0``

    Unix timestamp set when generation completes successfully.

.. _developer-generation-task-service:

GenerationTaskService
=====================

:php:`Wegewerk\Ai3Core\Service\GenerationTaskService` is the primary
API for working with the task queue. Inject it via constructor:

.. code-block:: php
    :caption: Adding a generation task

    use Wegewerk\Ai3Core\Domain\Model\Dto\AddGenerationTask;
    use Wegewerk\Ai3Core\Service\GenerationTaskService;

    $dto = new AddGenerationTask(
        status: 'Pending',
        prompt: 'Write a short product description.',
        image: '',
        capability: 'my_ext_summary',
        record_table: 'tt_content',
        record_field: 'bodytext',
        record_uid: 42,
        generateLanguage: 'en',
        parameters: '',
        result: '',
        result_meta: '',
        error_message: '',
    );

    $task = $this->generationTaskService->addTask($dto);

Useful query methods:

.. list-table::
    :header-rows: 1
    :widths: 45 55

    * - Method
      - Description
    * - ``getTask(int $uid)``
      - Latest task for ``record_uid``.
    * - ``isTaskRunning(int $uid)``
      - ``true`` if status is Pending or Processing.
    * - ``hasGenerationDone(int $uid)``
      - ``true`` if the latest task is Done.
    * - ``getLatestResult(int $uid)``
      - Result string of the most recent Done task.
    * - ``isReviewed(int $uid)``
      - ``true`` if the latest task has been reviewed.
    * - ``setReviewed(GenerationTask $task)``
      - Marks the task as reviewed and persists.
