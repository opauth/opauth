Improvements of v1
==================

The new release of Opauth has brought about the following changes and improvements as compared to the beta releases:

No more internal callback
-------------------------
Opauth no longer does another internal callback to pass data back to your app. Now it simply returns the response. With
this change, security components and v0.x transport mechanisms have been dropped.

Cleaner code base and API
-------------------------
Opauth is now more extensible than ever. Do not like how our parser works? You can easily extend or override it. The same
can be said for many other components on Opauth.

Full PSR compliance
-------------------
Opauth is now fully compliant with the following PHP Coding Standards by PHP Framework Interop Group (PHP-FIG):

- `PSR-1 <http://www.php-fig.org/psr/psr-1/>`_
- `PSR-2 <http://www.php-fig.org/psr/psr-2/>`_
- `PSR-4 <http://www.php-fig.org/psr/psr-4/>`_

State support
-------------
Opauth 1.0 now supports state _(?)_

_(To be verified)_

Moving beyond personal project
------------------------------
Opauth welcomes `Ceeram <https://github.com/ceeram>`_ to the core team. With this addition, Opauth is no longer a
personal project of `U-Zyn Chua <https://github.com/uzyn>`_ alone but `an organization <https://github.com/opauth>`_.
Check out our `new web page <http://opauth.org>`_.
