Opauth configurations
=====================

Instatiation of Opauth class expects a configuration array as input.

::

    <?php
    require 'vendor/autoload.php';
    $config = array(
        'path' => '/auth/',
        'http_client' => "Opauth\\Opauth\\HttpClient\\Curl",
        'callback' => 'callback'
    )
    $Opauth = new Opauth\Opauth\Opauth($config);
    $response = $Opauth->run();

- ``path``
    - Default: ``/``
    - Path where Opauth is accessed.
    - Begins and ends with ``/``
    - For example, if Opauth is reached at ``http://example.org/auth/``, ``path`` should be set to ``/auth/``; if Opauth is reached at ``http://auth.example.org/``, ``path`` should be set to ``/``

- ``http_client``
    - Default: ``Opauth\\Opauth\\HttpClient\\Curl`` for cURL (requires ``php_curl``)
    - Client to be used by Opauth for making HTTP calls to authentication providers.
    - Opauth also ships with File HttpClient, for making HTTP calls via ``file_get_contents()``. To use File, set ``http_client`` to ``Opauth\\Opauth\\HttpClient\\File``

- ``callback``
    - Default: ``callback``
    - This forms the final section of the callback URL from authentication provider, ie. ``http://example.org/auth/strategy/callback``
