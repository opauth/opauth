![Opauth](https://github.com/uzyn/opauth.org/raw/master/images/opauth-logo-300px-transparent.png)
=================================
Opauth is a multi-provider authentication framework for PHP, inspired by [OmniAuth for Ruby](https://github.com/intridea/omniauth).

Opauth enables PHP applications to do *user authentication* with ease.

Try out Opauth for yourself at http://opauth.org

[![Build Status](https://secure.travis-ci.org/opauth/opauth.png?branch=master)](http://travis-ci.org/opauth/opauth)

What is Opauth?
---------------
Opauth provides a standardized method for PHP applications to interface with authentication providers.

Opauth as a framework provides a set of API that allows developers to [create strategies](https://github.com/opauth/opauth/wiki/Strategy-Contribution-Guide) that work in a predictable manner across PHP frameworks and applications.

Opauth works well with other PHP applications & frameworks. It is currently supported on:

- [vanilla (plain) PHP applications](https://github.com/opauth/opauth/tree/master/example)
- [CakePHP](https://github.com/uzyn/cakephp-opauth) (maintained by [uzyn](https://github.com/uzyn))
- [CodeIgniter](https://github.com/destinomultimedia/ci_opauth) (maintained by [destinomultimedia](https://github.com/destinomultimedia))
- [CodeIgniter](https://github.com/mcatm/Opauth-Plugin-for-Codeigniter) (maintained by [mcatm](https://github.com/mcatm))
- [FuelPHP](https://github.com/andreoav/fuel-opauth) (maintained by [andreoav](https://github.com/andreoav/))
- [Laravel](https://github.com/FakeHeal/opauth-laravel) (maintained by [FakeHeal](https://github.com/FakeHeal/))
- [PrestaShop](https://github.com/Onasusweb/PrestaShop-Opauth) (maintained by [Onasusweb](https://github.com/Onasusweb))
- [Silex](https://github.com/icehero/silex-opauth) (maintained by [icehero](https://github.com/icehero/))
- [SilverStripe](https://github.com/BetterBrief/silverstripe-opauth) (maintained by [Better Brief](https://github.com/BetterBrief))
- [Zend Framework 2](https://github.com/lorenzoferrarajr/LfjOpauth) (maintained by [lorenzoferrarajr](https://github.com/lorenzoferrarajr))
- and more to come.

If your PHP framework of choice is not yet listed, you can still use Opauth like you would a normal PHP component (class).

Quick start
-----------
Guide on how to run the bundled example.

1. Set `DocumentRoot` of your web server to `example/`.
   (Opauth can be instantiated in your own PHP app, but we will leave that out of this quick start guide)

2. Configure Opauth.

   First, make a copy of opauth config's file by copying or renaming
   `opauth.conf.php.default` to `opauth.conf.php`.

   Open up `opauth.conf.php` and make the necessary changes.

3. Install some [Opauth strategies](https://github.com/opauth/opauth/wiki/List-of-strategies).
   Place the strategy files in `lib/Opauth/Strategy/`.

   For this example, we recommend that you start with [Opauth-Facebook](https://github.com/opauth/facebook):

   i. [Download the strategy files](https://github.com/opauth/facebook/zipball/master) and place them at `lib/Opauth/Strategy/Facebook/`.

   ii. Follow the steps at [Opauth-Facebook's README](https://github.com/opauth/facebook/blob/master/README.md) to set up your Faceobok app.

   iii. Add the following at `opauth.conf.php` under `Strategy` as such:

```php
<?php
'Strategy' => array(
    // Define strategies here.

    'Facebook' => array(
        'app_id' => 'YOUR APP ID',
        'app_secret' => 'YOUR APP SECRET'
    ),
);
```

Finally, send user to `http://localhost/facebook` to authenticate.


Check out [the wiki](https://github.com/opauth/opauth/wiki) for more in-depth details, especially on how to use Opauth with your own PHP application.

Available strategies
--------------------
A strategy is a set of instructions that interfaces with respective authentication providers and relays it back to Opauth.

Provider-specific:

<table>
<tr>
	<th>Strategy</th>
	<th>Maintained by</th>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://bitbucket.org" alt="Bitbucket" width="16">&nbsp;&nbsp;
		<a href="http://github.com/fancyguy/opauth-bitbucket">Bitbucket</a></td>
	<td>fancyguy</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://disqus.com" alt="Disqus" width="16">&nbsp;&nbsp;
		<a href="https://github.com/rasa/opauth-disqus">Disqus</a></td>
	<td>rasa</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://do.com" alt="Do" width="16">&nbsp;&nbsp;
		<a href="https://github.com/arbales/opauth-do">Do</a></td>
	<td>arbales</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://facebook.com" alt="Facebook">&nbsp;&nbsp;
		<a href="https://github.com/opauth/facebook"><strong>Facebook</strong></a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://www.flickr.com" alt="Flickr" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/pocket7878/opauth-flickr">Flickr</a></td>
	<td>pocket7878</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://www.foursquare.com" alt="Foursquare" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/pocket7878/opauth-foursquare">Foursquare</a></td>
	<td>pocket7878</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://github.com" alt="GitHub" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/opauth/github">GitHub</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://google.com" alt="Google" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/opauth/google"><strong>Google</strong></a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://instagram.com" alt="Instagram" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/muhdazrain/opauth-instagram">Instagram</a></td>
	<td>muhdazrain</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://linkedin.com" alt="LinkedIn" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/opauth/linkedin">LinkedIn</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://live.com" alt="Live Connect" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/opauth/live">(Windows) Live</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://mixi.co.jp" alt="mixi" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/ritou/opauth-mixi">mixi</a></td>
	<td>ritou</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://openid.net" alt="OpenID" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/opauth/openid">OpenID</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://paypal.com" alt="PayPal" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/24hours/opauth-paypal">PayPal</a></td>
	<td>24hours</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://weibo.com" alt="Sina Weibo" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/dgrabla/opauth-sinaweibo">Sina Weibo (新浪微博)</a></td>
	<td>dgrabla</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://twitter.com" alt="Twitter" width="16" height="16">&nbsp;&nbsp;
		<a href="https://github.com/opauth/twitter"><strong>Twitter</strong></a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://vimeo.com" alt="Vimeo" width="16" height="16">&nbsp;&nbsp;
<a href="https://github.com/LubosRemplik/opauth-vimeo">Vimeo</a></td>
	<td>LubosRemplik</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://vk.com" alt="VK" width="16" height="16">&nbsp;&nbsp;
<a href="https://github.com/dgrabla/opauth-vkontakte">VKontakte</a></td>
	<td>dgrabla</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://developer.yahoo.co.jp" width="16" height="16">&nbsp;&nbsp;
<a href="https://github.com/ritou/opauth-yahoojp">Yahoo! Japan (YConnect)</a></td>
	<td>ritou</td>
</tr>

</table>

Generic strategy: [OAuth](https://github.com/uzyn/opauth-oauth)

See [wiki's list of strategies](https://github.com/opauth/opauth/wiki/List-of-strategies) for an updated list of Opauth strategies or to make requests.
Refer also to [strategy contribution guide](https://github.com/opauth/opauth/wiki/Strategy-contribution-guide) if you would like to contribute a strategy.

Requirements
-------------
PHP 5 (>= 5.2)
with [`allow_url_fopen`](http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen) enabled

Contribute
----------
Opauth needs your contributions, especially the following:

- More strategies
  Refer to [wiki](https://github.com/opauth/opauth/wiki) for contribution guide and inform us when your work is ready.

- Plugins for more PHP frameworks and CMSes
  eg. Symfony, Laravel, WordPress, Drupal, etc.

- Guides & tutorials
  On how to implement Opauth on CakePHP app, etc.


Issues & questions
-------------------
- Discussion group: [Google Groups](https://groups.google.com/group/opauth)
  _Primary channel for support, especially usage questions._
- Issues: [Github Issues](https://github.com/opauth/opauth/issues)
- Twitter: [@uzyn](http://twitter.com/uzyn)
- Email me: chua@uzyn.com
- IRC: **#opauth** on [Freenode](http://webchat.freenode.net/?channels=opauth&uio=d4)

<p>Used Opauth in your project? Let us know!</p>

Changelog
---------

####v0.4.4 _(10 May 2013)_
- Added HTTP User-Agent header. _(thanks @rkaldung #41)_

####v0.4.3 _(10 January 2013)_
- Fixed a `serverPost()` bug where user-supplied options were not applied correctly. _(thanks @ritou #26)_

####v0.4.2 _(28 August 2012)_
- Fix session to check for `session_id()` instead of `$_SESSION` _(thanks @sirikkoster #20)_

####v0.4.1 _(22 July 2012)_
- Not starting session if session is already started. _(thanks @Claymm)_
- Fixed incorrect error message. _(thanks @Claymm)_
- Removed `@` for `file_get_contents`. _(thanks @Takehiro-Adachi)_

####v0.4.0 _(10 June 2012)_
- `mapProfile()` and `clientGet()` for OpauthStrategy class.

####v0.3.0 _(30 May 2012)_
- Some unit testing
- More consistent naming of Strategy's internal properties
- Smarter loading of strategy, able to make a few guesses on where the class file might be at.

####v0.2.0 _(23 May 2012)_
- Opauth is now Composer compatible and listed on [Packagist](http://packagist.org/packages/opauth/opauth)
    - Opauth now supports autoloaders
    - If a strategy is not autoloaded, Opauth falls back and searches for it at `strategy_dir` defined in config.
- Class name for strategy Foo should now be FooStrategy instead of Foo.
    - This is to reduce the likelihood of class name collision due to Opauth not requiring the use of namespace.
    - v0.1.0-type class name, ie. Foo, still works, but is now deprecated.

####v0.1.0 _(22 May 2012)_
- Initial release

License
---------
The MIT License
Copyright © 2012-2013 U-Zyn Chua (http://uzyn.com)

Consultation
---------
U-Zyn Chua is a Principal Consultant at [Zynesis Consulting](http://zynesis.com).
