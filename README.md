![Opauth](https://github.com/uzyn/opauth.org/raw/master/images/opauth-logo-300px-transparent.png)
=================================
Opauth is a multi-provider authentication framework for PHP, inspired by [OmniAuth for Ruby](https://github.com/intridea/omniauth).

Opauth enables PHP applications to do *user authentication* with ease.

Try out Opauth for yourself at http://opauth.org


About this bundled package
===========================
This package is bundled with Opauth core and the following strategies to help users getting started quickly:
- [Facebook strategy](http://github.com/uzyn/opauth-facebook)
- [Google strategy](http://github.com/uzyn/opauth-google)
- [Twitter strategy](http://github.com/uzyn/opauth-twitter)

Once you are familiar with how Opauth works, check out [list of strategies](https://github.com/uzyn/opauth/wiki/List-of-strategies) for more Opauth-supported stragies.


Quick start
-----------
Guide on how to run this bundled example.

1. Set `DocumentRoot` of your web server to this directory so that this file is accessible at `http://localhost/README.md`.  
   _(Make sure your web server is set to allow `mod_rewrite`)_

2. Open `opauth.conf.php` and make the necessary changes.

3. Finally, send user to `http://localhost/facebook`, `http://localhost/google` or `http://localhost/twitter` to authenticate.

Check out [the wiki](https://github.com/uzyn/opauth/wiki) for more in-depth details, especially on how to use Opauth with your own PHP application.


Provider-specific:

<table>
<tr>
	<th>Strategy</th>
	<th>Maintained by</th>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://facebook.com" alt="Facebook">&nbsp;&nbsp;
		<a href="https://github.com/uzyn/opauth-facebook">Facebook</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://google.com" alt="Google">&nbsp;&nbsp;
		<a href="https://github.com/uzyn/opauth-google">Google</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://instagram.com" alt="Instagram">&nbsp;&nbsp;
		<a href="https://github.com/muhdazrain/opauth-instagram">Instagram</a></td>
	<td>muhdazrain</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://linkedin.com" alt="LinkedIn">&nbsp;&nbsp;
		<a href="https://github.com/uzyn/opauth-linkedin">LinkedIn</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://mixi.co.jp" alt="mixi">&nbsp;&nbsp;
		<a href="https://github.com/ritou/opauth-mixi">mixi</a></td>
	<td>ritou</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://openid.net" alt="OpenID">&nbsp;&nbsp;
		<a href="https://github.com/uzyn/opauth-openid">OpenID</a></td>
	<td>uzyn</td>
</tr>
<tr>
	<td><img src="http://g.etfv.co/http://twitter.com" alt="Twitter">&nbsp;&nbsp;
		<a href="https://github.com/uzyn/opauth-twitter">Twitter</a></td>
	<td>uzyn</td>
</tr>

</table>

Generic strategy: [OAuth](https://github.com/uzyn/opauth-oauth)

See [wiki's list of strategies](https://github.com/uzyn/opauth/wiki/List-of-strategies) for an updated list of Opauth strategies or to make requests.  Also, refer to [strategy contribution guide](https://github.com/uzyn/opauth/wiki/Strategy-contribution-guide) if you would like to contribute a strategy.

Requirements
-------------
PHP 5 (>= 5.2)

Contribute
----------
Opauth needs your contributions, especially the following:

- More strategies  
  Refer to [wiki](https://github.com/uzyn/opauth/wiki) for contribution guide and inform us when your work is ready.

- Plugins for more PHP frameworks and CMSes  
  eg. Symfony, Laravel, WordPress, Drupal, etc.

- Guides & tutorials  
  On how to implement Opauth on CakePHP app, etc.

- Unit testing  
  Coverage is only average at the moment.


Issues & questions
-------------------
- Discussion group: [Google Groups](https://groups.google.com/group/opauth)  
  _Primary channel for support, especially usage questions._
- Issues: [Github Issues](https://github.com/uzyn/opauth/issues)  
- Twitter: [@uzyn](http://twitter.com/uzyn)  
- Email me: chua@uzyn.com  
- IRC: **#opauth** on [Freenode](http://webchat.freenode.net/?channels=opauth&uio=d4)

<p>Used Opauth in your project? Let us know!</p>

License
---------
The MIT License  
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

Consultation
---------
U-Zyn Chua is a Principal Consultant at [Zynesis Consulting](http://zynesis.sg).  
Looking for PHP web development solutions or consultation? [Drop me a mail](mailto:chua@uzyn.com).
