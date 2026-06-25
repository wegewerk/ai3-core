.. include:: /Includes.rst.txt

.. _start:

========
Ai3 Core
========

:Extension key:
    ai3_core

:Package name:
    |composer_name|

:Version:
    |release|

:Language:
    en

:Author:
    Wegewerk GmbH

:License:
    This document is published under the
    `Creative Commons BY 4.0 <https://creativecommons.org/licenses/by/4.0/>`__
    license.

:Rendered:
    |today|

----

**Ai3 Core** is the shared foundation of the *Ai3 Suite* — an AI-assisted
content-generation toolkit for TYPO3. It provides:

- A PSR HTTP client wrapper for the **Zak_ai REST API**
- A **capability registry** that other Ai3 packages extend via a DI tag
- A persistent **generation task queue** (Extbase model + repository)
- A **backend module** with dashboard, Zak_ai registration wizard, and
  task overview
- Two **CLI commands** for querying Zak_ai credits and processing pending tasks

This package will support TYPO3 13 and 14.

.. toctree::
    :maxdepth: 2
    :titlesonly:
    :hidden:

    Introduction/Index
    Installation/Index
    Configuration/Index
    Usage/Index
    Developer/Index
    Changelog/Index
