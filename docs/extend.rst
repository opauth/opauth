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
    - Opauth uses `Guzzle <http://guzzlephp.org/>`_ for HTTP client.
    - Refer to ``TransportInterface.php`` for more details if you wish to use some other HTTP clients.

- Strategy
    - Strategies are specific instructions for Opauth on how to handle 3rd-party provider's authentication steps, which can be hugely different from one to other.
    - See :doc:`strategies` for known list of Opauth-managed and community-contributed strategies.
    - Or see the Strategy contribution guide below if you would like to author your own.


Strategy contribution guide
---------------------------

Before writing your own strategy, you might want to check out the :doc:`strategies` list to see if it is already created, or if the existing one fits your requirements.

To start, you might want to refer to either of the following strategies as guide:

- `Twitter <https://github.com/opauth/twitter>`_ for oAuth 1-based strategies
- `Google <https://github.com/opauth/google>`_ for oAuth 2-based strategies
