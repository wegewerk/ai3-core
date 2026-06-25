.. include:: /Includes.rst.txt

.. _installation:

============
Installation
============

.. _installation-composer:

Installation via Composer
=========================

Ai3 Core is installed as a dependency of other Ai3 Suite packages.
It is not intended to be required directly in most projects. If you do need
it explicitly, run:

.. code-block:: bash
    :caption: Install via Composer

    composer require wegewerk/ai3_core

.. _installation-env-vars:

Environment variables
=====================

The Zak_ai API client reads its credentials exclusively from environment
variables. API_KEY and SECRET are obtained in the Backend Module.

.. code-block:: bash
    :caption: .env or server environment

    ZAKAI_API_KEY=<your-api-key>
    ZAKAI_SECRET=<your-secret>

The complete registration flow — including requesting and confirming the API
key — is available through the :ref:`backend module <usage-backend-module>`.
