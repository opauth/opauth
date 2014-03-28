Getting started
===============

The easiest way to start with Opauth is using composer. Choosing to do a manual installation of Opauth will require
additional code for autoloading in your application.

You can add Opauth and stratetgies to your applications composer.json::

    {
        "require": {
            "opauth/opauth": "dev-wip/1.0",
            "opauth/facebook": "dev-wip/1.0"
        }
    }

Next you need to run::

    $ composer install

Alternatively you can use the command line::

   $ composer require opauth/opauth:dev-wip/1.0
   $ composer require opauth/facebook:dev-wip/1.0

This will add Opauth and Facebook strategy to your applications ``composer.json`` and install them immediately.

Configuration
-------------

You need to define your configuration, this might depend on the framework you are using. How/where you define and load
the configuration array is entirely up to you. Opauth uses a configuration array that looks something like::

    $config = array(
        'Strategy' => array(
            'Twitter' => array(
                'key' => 'your_key',
                'secret' => 'your_secret'
            ),
        ),
        'path' => '/opauth/'
    );

Simple example
--------------------

Next we will create ``opauth.php`` with the following contents::

    <?php
    require 'vendor/autoload.php';
    $config = array(
        'Strategy' => array(
            'Facebook' => array(
                'app_id' => 'your_key',
                'app_secret' => 'your_secret'
            ),
        ),
        'path' => '/opauth.php/'
    );
    $Opauth = new Opauth\Opauth\Opauth($config);
    $response = $Opauth->run();
    echo "Authed as " . $response->name . " with uid" . $response->uid;

Set ``DocumentRoot`` of your web server to this directory, or create a vhost as this example does not work when opauth
is in a subdirectory.

Now point the browser to ``http://localhost/opauth.php/facebook`` to see it in action.

