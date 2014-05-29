Getting started
===============

The easiest way to start with Opauth is using composer. Choosing to do a manual installation of Opauth will require
additional code for autoloading in your application.

We will use the `Facebook <https://github.com/opauth/facebook>`_ strategy in the following example, but you
can use another :doc:`strategy <strategies>` as well to get started. Just make sure you check the strategy README for the correct `strategy
configuration <https://github.com/opauth/facebook#strategy-configuration>`_ keys. Also the strategy README files will include more info about `setting up <https://github.com/opauth/facebook#getting-started>`_
everything specific to the provider.

You can add Opauth and strategies to your applications ``composer.json``::

    {
        "require": {
            "opauth/opauth": "~1.0",
            "opauth/facebook": "~1.0"
        }
    }

.. note:: While Opauth and the strategies have not reached ``stable`` your root ``composer.json`` also needs to have::

  "minimum-stability": "dev"

Next you need to run::

    $ composer install

Alternatively you can use the command line::

   $ composer require opauth/opauth:~1.0
   $ composer require opauth/facebook:~1.0

This will add Opauth and Facebook strategy to your applications ``composer.json`` and install them immediately.

Configuration
-------------

Next you to define your configuration. How/where you define and load the configuration array is entirely up to you.
The easiest way to configure Opauth is to use a single array that contains the both the configs for Opauth itself and the
strategies. Opauth config values are at the root level of the array, while the strategy configurations are stored in the
``Strategy`` key. The array would look something like::

    $config = array(
        'Strategy' => array(
            'Facebook' => array(
                'app_id' => 'your_key',
                'app_secret' => 'your_secret'
            ),
        ),
        'path' => '/opauth/'
    );

Check the :doc:`configurations<configurations>` section for all possible configs.

Simple example
--------------

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
    try {
        $response = $Opauth->run();
        echo "Authed as " . $response->name . " with uid" . $response->uid;
    } catch (OpauthException $e) {
        echo "Authentication error: " . $e->getMessage();
    }

Set ``DocumentRoot`` of your web server to this directory, or create a vhost as this example does not work when opauth
is in a subdirectory.

Now point the browser to ``http://localhost/opauth.php/facebook`` to see it in action.

Advanced examples
-----------------

Opauth v1 is more flexible then the 0.4 series, meaning you can use your own request parser class and inject strategies
manually. If you want to handle the request parsing yourself, you can create a class for this, which must implement
``Opauth\Opauth\ParserInterface``

You can now inject your own parser into Opauth`s constructor::

    <?php
    use Opauth\Opauth\ParserInterface;

    class MyParser implements ParserInterface
    {

        public function __construct($path = '/')
        {
            //your implementation
        }

        public function action()
        {
            //your implementation
        }

        public function urlname()
        {
            //your implementation
        }

        public function providerUrl()
        {
            //your implementation
        }
    }

    //Inject your parser object into Opauth constructor
    $Opauth = new Opauth\Opauth\Opauth($config, new MyParser('opauth-path'));
    $Opauth->run();

You can also set a strategy manually, instead of letting Opauth decide which strategy to run based off the parsed request::

    $Opauth = new Opauth\Opauth\Opauth();
    $Opauth->setStrategy(new Opauth\Facebook\Strategy\Facebook($config['Strategy']['Facebook']));
    $Opauth->request();
    //or
    $Opauth->callback();

As you can see in the above example, we are not calling ``run()`` method here, but manually call ``request()`` or
``callback()`` methods on Opauth.
