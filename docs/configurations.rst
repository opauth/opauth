Opauth configurations
=====================

Instatiation of Opauth class expects a configuration array as input.

::

    <?php
    require 'vendor/autoload.php';
    $config = array(
        'path' => '/auth/',
        'http_transport' => "Opauth\\Opauth\\Transport\\Curl",
        'callback' => 'callback'
    )
    $Opauth = new Opauth\Opauth\Opauth($config);
    $response = $Opauth->run();

- ``path``
    - Default: ``/``
    - Path where Opauth is accessed.
    - Begins and ends with ``/``
    - Examples:
        - if Opauth is reached at ``http://example.org/auth/``, path should be set to ``/auth/``
        - if Opauth is reached at ``http://auth.example.org/``, path should be set to ``/``

- ``http_transport``
    - Default: ``Opauth\\Opauth\\Transport\\Curl`` for cURL (requires ``php_curl``)
    - Client to be used by Opauth for making HTTP calls to authentication providers.
    - Opauth also ships with File transport, for making HTTP calls via ``file_get_contents()``. To use File, set ``http_transport`` to ``Opauth\\Opauth\\Transport\\File``

- ``callback``
    - Default: ``callback``
    - Strategy method that returns a Response object.
