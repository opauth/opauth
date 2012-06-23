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


Issues & questions
-------------------
- Discussion group: [Google Groups](https://groups.google.com/group/opauth)  
  Feel free to post your questions to the discussion group. This is the primary channel for support.
- Issues: [Github Issues](https://github.com/uzyn/opauth/issues)  
- Twitter: [@uzyn](http://twitter.com/uzyn)  
- Email me: chua@uzyn.com  
- IRC: **#opauth** on [Freenode](http://webchat.freenode.net/?channels=opauth&uio=d4)

<p>Used Opauth in your project? Let us know!</p>

License
---------
The MIT License  
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

Footnote
---------
U-Zyn Chua is a Principal Consultant at [gladlyCode](http://gladlycode.com), a premier PHP web development firm.  
If you need consultation in web technologies and services, feel free to [talk to us](mailto:we@gladlycode.com).
