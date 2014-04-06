Changelog
=========

1.x
----
1.0.0-alpha.1
    - Scheduled for release in April, 2014.
    - This release is not backward-compatible with v0.x strategies or consumer plugins.
    - Initial v1.x release.

0.x
----
0.4.4
    - Released on May 10, 2013.
    - Added HTTP User-Agent header.

0.4.3
    - Released on January 10, 2013.
    - Fixed a ``serverPost()`` bug where user-supplied options were not applied correctly.

0.4.2
    - Released on August 28, 2012.
    - Fix session to check for ``session_id()`` instead of ``$_SESSION``.

0.4.1
    - Released on July 22, 2012.
    - Not starting session if session is already started.
    - Fixed incorrect error message.
    - Removed ``@`` for ``file_get_contents``.

0.4.0
    - Released on 10 June 2012.
    - ``mapProfile()`` and ``clientGet()`` for OpauthStrategy class.

0.3.0
    - Released on May 30, 2012.
    - Introduced unit testing.
    - More consistent naming of Strategy's internal properties.
    - Smarter loading of strategy, able to make a few guesses on where the class file might be at.

0.2.0
    - Released on May 23, 2012.
    - Opauth is now Composer compatible and listed on `Packagist <http://packagist.org/packages/opauth/opauth>`_.
    - Opauth now supports autoloaders.
    - If a strategy is not autoloaded, Opauth falls back and searches for it at ``strategy_dir`` defined in config.
    - Class name for strategy Foo should now be FooStrategy instead of Foo.
    - This is to reduce the likelihood of class name collision due to Opauth not requiring the use of namespace.
    - v0.1.0-type class name, ie. Foo, still works, but is now deprecated.

0.1.0
    - Released on May 22, 2012.
    - Initial release
