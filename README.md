Opauth
=======
Opauth is a multi-provider authentication framework for PHP, inspired by [OmniAuth for Ruby](https://github.com/intridea/omniauth).

Opauth enables PHP applications to do *user authentication* with ease.

What is Opauth?
---------------
Opauth provides a standardized method for PHP applications to interface with authentication providers. 

Opauth as a framework provides a set of API that allows developers to [create strategies](https://github.com/uzyn/opauth/wiki/Strategy-Contribution-Guide) that work in a predictable manner across PHP frameworks and applications.

Opauth is designed works well with other PHP applications & frameworks.  
It is currently supported on [vanilla (plain) PHP applications](https://github.com/uzyn/opauth/tree/master/example) *(of course)*, [CakePHP](https://github.com/uzyn/cakephp-opauth), [Yii Framework](https://github.com/kahwee/yii-opauth) and more to come.  
If your PHP framework of choice is not yet listed, you can still use Opauth like you would a normal PHP component (class).

Quick start
-----------
1. Set `DocumentRoot` of your web server to `example/`.  
   (Opauth can be instantiated in your own PHP app, but we will leave that out of this quick start guide)

2. Configure Opauth  
   `cp example/opauth.conf.php.default example/opauth.conf.php`  
   and make the necessary changes.

3. Install some [Opauth strategies](https://github.com/uzyn/opauth/wiki/List-of-strategies).
   We recommend that you start with [Opauth-Facebook](https://github.com/uzyn/opauth-facebook)

4. Send user to `http://path_to_opauth/facebook` to authenticate.

Check out [the wiki](https://github.com/uzyn/opauth/wiki) for more technical details.

Available strategies
--------------------
A strategy is a set of instructions that interfaces with respective authentication providers and relays it back to Opauth.

Provider-specific:

- [Facebook](https://github.com/uzyn/opauth-facebook)
- [Google](https://github.com/uzyn/opauth-google)
- [Twitter](https://github.com/uzyn/opauth-twitter)

Generic strategy: [OAuth](https://github.com/uzyn/opauth-oauth)

Opauth is a relatively new framework, having only released its v0.1.0 in late May 2012.<br> We need your help in expanding the list of available strategies. Refer to [strategy contribution guide](https://github.com/uzyn/opauth/wiki/Strategy-contribution-guide) if you would like to contribute a strategy. Do notify us if you have developed an Opauth strategy and would like it to be listed.


Issues & questions
-------------------
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
