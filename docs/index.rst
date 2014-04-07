.. Opauth documentation master file, created by
   sphinx-quickstart on Mon Mar 24 15:26:14 2014.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Opauth Documentations
=====================

Opauth is a multi-provider authentication framework for PHP, inspired by
`OmniAuth for Ruby <https://github.com/intridea/omniauth>`_

Opauth enables PHP applications to do *user authentication* with ease, by providing a standardized method for PHP
applications to interface with authentication providers.

For many authentication providers we have :doc:`strategies <strategies>`. A strategy is the adapter specific to a certain
authentication provider. If Opauth does not have a strategy for your favorite provider, it's easy to
:ref:`create <create>` one.

Contents:

.. toctree::
   :maxdepth: 2

   getting-started
   configurations
   response
   strategies
   extend
   v1-improvements
   migration-guide
   contribute
   support
   changelog

Indices and tables
==================

* :ref:`genindex`
* :ref:`search`
