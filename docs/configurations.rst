Opauth configurations
=====================

Instantiation of Opauth class accepts a configuration array as input.

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
    - Opauth also ships with other `HTTP clients`_.

- ``callback``
    - Default: ``callback``
    - This forms the final section of the callback URL from authentication provider,
      ie. ``http://example.org/auth/strategy/callback``

HTTP clients
------------

cURL
  - Uses cURL for making of HTTP calls.
  - Requires ``php_curl``
  - Default client. Zero configuration needed.

File
    - Uses ``file_get_contents()`` for making of HTTP calls.
    - Requires `allow_url_fopen <http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen>`_ to be enabled.
    - To use, set ``http_client`` to ``Opauth\\Opauth\\HttpClient\\File``

Guzzle version 4
    - Uses latest stable version of `Guzzle <https://github.com/guzzle/guzzle>`_ for making HTTP calls.
    - Recommended HTTP client for Opauth
    - Not set as default for Opauth due to minimum PHP requirement being >= 5.4.2.
    - To use:
      1. Composer require ``guzzlehttp/guzzle:~4.0``
      1. set ``http_client`` to ``Opauth\\Opauth\\HttpClient\\Guzzle``

Guzzle version 3
    - Uses `Guzzle version 3 <https://github.com/guzzle/guzzle3>`_ for making HTTP calls.
    - To use:
      1. Composer require ``guzzle/guzzle:~3.7``
      1. set ``http_client`` to ``Opauth\\Opauth\\HttpClient\\Guzzle3``


Opauth HTTP client is extensible. You can author your own desired clients if you wish.

Strategy configuration
----------------------

Each strategy has its own configuration keys. Check the strategy README file for more information.
The strategies should be configured in the ``'Strategy'`` key in the config array, each under its own key that matches
the classname of the strategy.

More info...
