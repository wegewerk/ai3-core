.. include:: /Includes.rst.txt

.. _configuration-js-modules:

==================
JavaScript modules
==================

Ai3 Core registers ES modules under the import-map namespace
``@wegewerk/ai3core/``. The mapping is defined in
:file:`Configuration/JavaScriptModules.php`:

.. code-block:: php
    :caption: Configuration/JavaScriptModules.php

    return [
        'dependencies' => ['core', 'backend'],
        'tags' => [
            'backend.form',
            'backend.contextmenu',
        ],
        'imports' => [
            '@wegewerk/ai3core/' =>
                'EXT:ai3_core/Resources/Public/JavaScript/',
        ],
    ];

The following modules are available:

.. list-table::
    :header-rows: 1
    :widths: 40 60

    * - Import path
      - Purpose
    * - ``@wegewerk/ai3core/ai3api.js``
      - Low-level API call helpers
    * - ``@wegewerk/ai3core/ajax.js``
      - Common Error Handling for AJAX Calls
    * - ``@wegewerk/ai3core/credits.js``
      - Credit display widget
    * - ``@wegewerk/ai3core/response-handling.js``
      - Shared response-processing logic
    * - ``@wegewerk/ai3core/Typo3Icon.js``
      - Helper for TYPO3 SVG icons

Other Ai3 extensions import these modules using standard ES
``import`` syntax:

.. code-block:: javascript
    :caption: Resources/Public/JavaScript/MyModule.js

    import { fetchCredits } from
        '@wegewerk/ai3core/credits.js';
