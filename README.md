Opauth
=======
__This project is still under heavy development. DO NOT USE.__  
Initial stable release is scheduled for late May 2012.

Opauth is a multi-provider authentication framework for PHP, inspired by [OmniAuth for Ruby](https://github.com/intridea/omniauth).

What is Opauth?
---------------
Opauth provides a standardized way to interface with 3rd-party authentication providers. Unlike many current authentication frameworks, Opauth does not deal with database, so developers are not forced to adhere to predetermined database schema.

Opauth is a PHP library. It can be used as a standalone authentication interface (sending HTTP traffic to it), or as a plugin (instantiation of Opauth).

Opauth works well with other PHP frameworks, such as [CakePHP](https://github.com/uzyn/cakephp-opauth), [Yii](https://github.com/kahwee/yii-opauth), etc.

Opauth as a framework provides API that allows authentication providers to develop strategies that work in a predictable manner.

Available strategies
--------------------

Provider-specific:

- [Facebook](https://github.com/uzyn/opauth-facebook)
- [Twitter](https://github.com/uzyn/opauth-twitter)

Generic:

- [OAuth](https://github.com/uzyn/opauth-oauth)


License
---------
The MIT License  
Copyright © 2012 U-Zyn Chua (http://uzyn.com)