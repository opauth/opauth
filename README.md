![Opauth](https://github.com/uzyn/opauth.org/raw/master/images/opauth-logo-300px-transparent.png)
=================================
Opauth is a multi-provider authentication framework for PHP, inspired by [OmniAuth for Ruby](https://github.com/intridea/omniauth).

Opauth enables PHP applications to do *user authentication* with ease.

Try out Opauth for yourself at http://opauth.org

[![Build Status](https://secure.travis-ci.org/uzyn/opauth.png?branch=master)](http://travis-ci.org/uzyn/opauth)

What is Opauth?
---------------
Opauth provides a standardized method for PHP applications to interface with authentication providers. 

Opauth as a framework provides a set of API that allows developers to [create strategies](https://github.com/uzyn/opauth/wiki/Strategy-Contribution-Guide) that work in a predictable manner across PHP frameworks and applications.

Opauth is designed works well with other PHP applications & frameworks. It is currently supported on:

- [vanilla (plain) PHP applications](https://github.com/uzyn/opauth/tree/master/example) *(of course)*
- [CakePHP](https://github.com/uzyn/cakephp-opauth) (maintained by [uzyn](https://github.com/uzyn))
- [CodeIgniter](https://github.com/destinomultimedia/ci_opauth) (maintained by [destinomultimedia](https://github.com/destinomultimedia))
- [CodeIgniter](https://github.com/mcatm/Opauth-Plugin-for-Codeigniter) (maintained by [mcatm](https://github.com/mcatm))
- [Yii Framework](https://github.com/kahwee/yii-opauth) (maintained by [kahwee](https://github.com/kahwee))
- and more to come.  
  If your PHP framework of choice is not yet listed, you can still use Opauth like you would a normal PHP component (class).

Quick start
-----------
Guide on how to run the bundled example.

1. Set `DocumentRoot` of your web server to `example/`.  
   (Opauth can be instantiated in your own PHP app, but we will leave that out of this quick start guide)

2. Configure Opauth  
   `cp example/opauth.conf.php.default example/opauth.conf.php`  
   and make the necessary changes.

3. Install some [Opauth strategies](https://github.com/uzyn/opauth/wiki/List-of-strategies).
   We recommend that you start with [Opauth-Facebook](https://github.com/uzyn/opauth-facebook)

   Place the strategy files in `lib/Opauth/Strategy/`.  
   For example, for Opauth-Facebook, place the downloaded files at `lib/Opauth/Strategy/Facebook/`.

4. Send user to `http://path_to_opauth/facebook` to authenticate.

Check out [the wiki](https://github.com/uzyn/opauth/wiki) for more in-depth details, especially on how to use Opauth with your own PHP application.

Available strategies
--------------------
A strategy is a set of instructions that interfaces with respective authentication providers and relays it back to Opauth.

Provider-specific:

- [Facebook](https://github.com/uzyn/opauth-facebook)
- [Google](https://github.com/uzyn/opauth-google)
- [Twitter](https://github.com/uzyn/opauth-twitter)

Generic strategy: [OAuth](https://github.com/uzyn/opauth-oauth)

Opauth is a relatively new framework, having only released its v0.1.0 in late May 2012.<br> We need your help in expanding the list of available strategies. Refer to [strategy contribution guide](https://github.com/uzyn/opauth/wiki/Strategy-contribution-guide) if you would like to contribute a strategy. Do notify us if you have developed an Opauth strategy and would like it to be listed.

Requirements
-------------
PHP 5 (>= 5.2)

Contribute
----------
Opauth needs your contributions, especially the following:

- More strategies  
  Refer to [wiki](https://github.com/uzyn/opauth/wiki) for contribution guide and inform us when your work is ready.

- Plugins for more PHP frameworks and CMSes  
  eg. CodeIgniter, Symfony, WordPress, Drupal, etc.

- Guides & tutorials  
  On how to implement Opauth on CakePHP app, etc.

- Unit testing  
  Coverage is only average at the moment.


Issues & questions
-------------------
- Issues: [Github Issues](https://github.com/uzyn/opauth/issues)  
- Discussion group: [Google Groups](https://groups.google.com/group/opauth)
- Twitter: [@uzyn](http://twitter.com/uzyn)  
- Email me: chua@uzyn.com  
- IRC: **#opauth** on [Freenode](http://webchat.freenode.net/?channels=opauth&uio=d4)

<p>Used Opauth in your project? Let us know!</p>

Changelog
---------
###v0.3.0 (30 May 2012)
- Some unit testing
- More consistent naming of Strategy's internal properties
- Smarter loading of strategy, able to make a few guesses on where the class file might be at.

###v0.2.0 (23 May 2012)
- Opauth is now Composer compatible and listed on [Packagist](http://packagist.org/packages/opauth/opauth)
    - Opauth now supports autoloaders
    - If a strategy is not autoloaded, Opauth falls back and searches for it at `strategy_dir` defined in config.
- Class name for strategy Foo should now be FooStrategy instead of Foo.
    - This is to reduce the likelihood of class name collision due to Opauth not requiring the use of namespace.
    - v0.1.0-type class name, ie. Foo, still works, but is now deprecated.

###v0.1.0 (22 May 2012)
- Initial release

License
---------
The MIT License  
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

Footnote
---------
U-Zyn Chua is a Principal Consultant at [gladlyCode](http://gladlycode.com), a premier PHP web development firm.  
If you need consultation in web technologies and services, feel free to [talk to us](mailto:we@gladlycode.com).
