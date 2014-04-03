Opauth configurations
=====================

Instantiation of Opauth class expects a configuration array as input.

::

    require 'vendor/autoload.php';
    $config = array(
        'path' => '/auth/',
        'http_client' => "Opauth\\Opauth\\HttpClient\\Curl",
        'callback' => 'callback',
        'Strategy' => array(
            //strategy configurations should go here
            //See Strategy configuration section
        )
    )
    $Opauth = new Opauth\Opauth\Opauth($config);
    $response = $Opauth->run();

- ``path``
    - Default: ``/``
    - Path where Opauth is accessed.
    - Begins and ends with ``/``
    - For example, if Opauth is reached at ``http://example.org/auth/``, ``path``
      should be set to ``/auth/``; if Opauth is reached at ``http://auth.example.org/``,
      ``path`` should be set to ``/``

- ``http_client``
    - Default: ``Opauth\\Opauth\\HttpClient\\Curl`` for cURL (requires ``php_curl``)
    - Client to be used by Opauth for making HTTP calls to authentication providers.
    - Opauth also ships with other HttpClients, see `Http clients`_ section

- ``callback``
    - Default: ``callback``
    - This forms the final section of the callback URL from authentication provider,
      ie. ``http://example.org/auth/strategy/callback``

Http clients
------------

- ``Curl``, for making HTTP calls with cURL (requires ``php_curl``)

  This is the default client, you don't need to configure anything

- ``File``, for making HTTP calls via ``file_get_contents()``.

  To use ``File``, set ``http_client`` to ``Opauth\\Opauth\\HttpClient\\File``

- ``Guzzle``, for making HTTP calls via Guzzle version 3

  To use Guzzle, set ``http_client`` to ``Opauth\\Opauth\\HttpClient\\Guzzle``

- ``GuzzleHttp``, for making HTTP calls via Guzzle version 4

  To use ``GuzzleHttp``, set ``http_client`` to ``Opauth\\Opauth\\HttpClient\\GuzzleHttp``

The recommended client is ``GuzzleHttp``, but due to minimum PHP(>=5.4.2) requirements
we don't default to this.

Strategy configuration
----------------------

Each strategy has its own configuration keys. Check the strategy README file for more information.
The strategies should be configured in the ``'Strategy'`` key in the config array, each under its own key that matches
the classname of the strategy.

More info...
