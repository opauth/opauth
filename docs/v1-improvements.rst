Improvements of v1
==================

The new release of Opauth has brought about the following changes and improvements as compared to the beta releases:

- Cleaner code base and API

- PSR-1, PSR-2 and PSR-4 compliance

  Opauth is now fully compliant with the following PSR's by PHP Framework Interop Group (PHP-FIG):

    - `PSR-1 <http://www.php-fig.org/psr/psr-1/>`_
    - `PSR-2 <http://www.php-fig.org/psr/psr-2/>`_
    - `PSR-4 <http://www.php-fig.org/psr/psr-4/>`_

- Extensible components

  Opauth is now more extensible than ever. Do not like how our parser works? You can easily extend or override it. The same can be said for many other components on Opauth. See :doc:`Extend Opauth <extend>`.

- More streamlined callbacks

  Opauth no longer does another internal callback to pass data back to your app. Now it simply returns the response. With this change, security components and v0.x transport mechanisms have been dropped, as they are no longer needed.

- PHP >= 5.3

  With the use of namespace, Opauth 1.0 is dropping support for PHP 5.2 and supports PHP >= 5.3.

- Tighter integration with Composer

  Opauth now makes full use of Composer for loading of strategies and any related dependencies.

- Response object

  Opauth now returns a more flexible and consistent :doc:`Response object <response>`.

- Moving beyond personal project

  Opauth welcomes `Ceeram <https://github.com/ceeram>`_ to the core team. With this addition, Opauth is no longer a
  personal project of `U-Zyn Chua <https://github.com/uzyn>`_ alone but `an organization <https://github.com/opauth>`_.
  Check out our `new web page <http://opauth.org>`_.
