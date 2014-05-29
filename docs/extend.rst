Extend Opauth
=============

Extend Opauth core
------------------
The following components of Opauth v1 is fully extensible:

- Request parser
    - Opauth makes decision on which strategy, method, action to call based on URL.
    - You can override this if you wish to make a different decision.
    - Refer to ``Request/Parser.php`` and ``ParserInterface.php`` for more details.

- HTTP Client
    - Opauth uses by default cURL for making http requests and has some other built-in clients.
    - Your own http client can be created if none of the built-in ones fits your needs.
    - Refer to ``HttpClientInterface.php`` for more details if you wish to create your own HTTP clients.

- Strategy
    - Strategies are specific instructions for Opauth on how to handle 3rd-party provider's authentication steps, which
      can be hugely different from one to other.
    - See :doc:`strategies` for known list of Opauth-managed and community-contributed strategies.
    - Or see the Strategy contribution guide below if you would like to author your own.

- Omit Opauth class
    - You can even choose not to use the ``Opauth`` class and just use the Strategies directly if needed, although this
      will require additional code in your application. This possibility has been made possible based on users requesting
      for this in the 0.4 cycle. In most cases you can probably still use ``Opauth`` class now and use a custom request
      parser class to bend it to your needs.


Strategy contribution guide
---------------------------

Before :ref:`writing your own strategy<create>`, you might want to check out the :doc:`strategies` list to see if it is already created,
or if the existing one fits your requirements.

To start, you might want to refer to either of the following strategies as guide:

- `Twitter <https://github.com/opauth/twitter>`_ for oAuth 1-based strategies
- `Google <https://github.com/opauth/google>`_ for oAuth 2-based strategies
