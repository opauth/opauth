Migration guide
===============

Migrating your application
--------------------------

To upgrade your application (or framework specific plugin) from v0.4 to v1.0 you need to use Composer and go through the following changes:

- Update the ``composer.json`` file of your application, if you had any, else you need to create the file in the root
  of your application. The ``composer.json`` should look something like::

    "require": {
        "opauth/opauth": "~1.0",
        "opauth/facebook": "~1.0",
        "opauth/twitter": "~1.0"
    },

  You need to point the versions for Opauth and the strategies you use to the 1.0 series.

  .. note:: While Opauth and the strategies have not reached ``stable`` your root ``composer.json`` also needs to have::

    "minimum-stability": "dev"

- Run the following command: ``composer update``, to get the correct versions installed into the ``vendor`` directory.

- Add ``require 'vendor/autoload.php';`` in your application to get composers autoloading, if you don't already have this.
  Make sure you have a recent composer version, which includes PSR4 support. If you run into errors, run:
  ``composer self-update``.

- You can keep the existing Opauth configuration array that you were using in v0.4, although many configuration options
  have been removed. Please check the :doc:`configurations </configurations>` section to see the current options.

- In the file where you create an Opauth instance, add the following line at the top::

    use Opauth\Opauth\Opauth;

- Update your code to use the new :doc:`Response</response>` object

- Benefit!

Migrating strategies
--------------------

For this example we show how to convert ``class ExampleStrategy`` from v0.4 to v1.0

To upgrade existing strategies to Opauth v1 you need to take the following steps:

- Update ``require`` and ``autoload`` in the strategy ``composer.json`` file::

    "require": {
        "php": ">=5.3.0",
        "opauth/opauth": "~1.0",
    },
    "autoload": {
        "psr-4": {
            "Opauth\\Example\\Strategy\\": "src"
        }
    }

  .. note:: While Opauth and the strategies have not reached ``stable`` your root ``composer.json`` also needs to have::

    "minimum-stability": "dev"

- Create ``src/`` directory in the root of the project

- Move ``ExampleStrategy.php`` to ``src/Example.php``

- Change the class declaration from::

    class ExampleStrategy extends OpauthStrategy {

  to::

    class Example extends AbstractStrategy {

  If you would choose not to extend AbstractStrategy, your strategy MUST implement StrategyInterface::

    class Example implements StrategyInterface {

- Add the following lines on the top of ``Example.php``::

    namespace Opauth\Example\Strategy;

    use Opauth\Opauth\AbstractStrategy;

- If your strategy overrides the constructor, you need to modify its signature to::

    public function __construct($config, $callbackUrl, HttpClientInterface $client)
    {
        parent::__construct($config, $callbackUrl, $client);
    }

- Next you need to make sure your strategy has both ``request()`` and ``callback()`` methods.

  The ``request()`` method handles
  the initial authentication request and MUST redirect or throw an ``OpauthException``. To redirect you can use
  ``AbstractStrategy::redirect($url, $data = array(), $exit = true)``.

  The ``callback()`` method handles the callback from the provider and MUST return a ``Response`` object or throw
  ``OpauthException``.

  For error handling ``AbstractStrategy`` has a convenience method ``error($message, $code, $raw = null)`` which will
  throw the exception.

  The ``AbstractStrategy`` also has a convenience method ``response($raw)`` for returning response objects.

- If your strategy needs to read/write session data, please use the ``AbstractStrategy::sessionData($data = null)``
  getter/setter method.

- To obtain the callback url you can use ``AbstractStrategy::callbackUrl()``

- ``Response`` attributes ``$uid``, ``$name`` and ``$credentials`` MUST be set.

  You can do this either using the response map::

    //in your ``callback()`` method
    $response = $this->response($credentials);
    $responseMap = array(
        'uid' => 'id',
        'name' => 'name',
        'info.name' => 'name',
        'info.nickname' => 'screen_name'
    );
    $response->setMap($responseMap);
    return $response;

  or directly assiging values to the attributes themselves::

    //in your ``callback()`` method
    $response->credentials = array(
        'token' => $results['oauth_token'],
        'secret' => $results['oauth_token_secret']
    );
    return $response;

  Opauth will use the response map to set values from the raw response to the ``Response`` class attributes.
  This replaces the multiple calls to ``OpauthStrategy::mapProfile($person, 'username._content', 'info.nickname');`` in
  version 0.4.

  The argument for ``AbstractStrategy::setMap($map)`` should be an array, with keys pointing to dotnotated paths to the
  ``Response`` attribute names and values containing the path to the raw data value.

- If your strategy uses tmhOauth library, please add it as composer required library, instead of adding it as gitmodule
  or including the code itself.

For more information about creating 1.0 strategies please check the :ref:`create` section

Now that you are done migrating your strategy we would like to ask you to take the following into account:

- Opauth itself now uses PSR2 coding standards. It is recommended to choose a coding standard for your strategy.
  Ofcourse you are free not to use this or any other standard. Please at least mention which standard to be used, if any.
  You can easily check if your strategy matches your standard with php-codesniffer.

  Just run from commandline: ``phpcs --standard=PSR2 --extensions=php src/`` and fix any errors/warnings if there are any.

  Using a standard helps readabilty for other developers to contribute.

- Please submit your strategy to packagist if you haven't already. The package name would be the Opauth vendorname and
  your strategyname, divided by a forward slash. The above example would result in ``opauth/example``. Once its added
  to packagist we can add your strategy to the list of supported strategies for version 1.0. Ofcourse you are free to
  use your own vendorname instead of Opauth's, but using opauth will make it more easy to be found.

If you need help with upgrading or you have other questions, please contact us for :doc:`support</support>`