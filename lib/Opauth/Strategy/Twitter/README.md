Opauth Twitter
=============
Twitter strategy for [Opauth][1], based on Opauth OAuth.

Getting started
----------------
Create Twitter application at https://dev.twitter.com/apps

Notes when registering application on Twitter:

 - Make sure to enter a Callback URL or callback will be disallowed.  
   Callback URL can be a made up one as Opauth will explicitly provide the correct one as part of the OAuth process.

 - Register your domains at @Anywhere domains.  
   Twitter only allows authentication from authorized domains.

 - Take note of `Consumer key` and `Consumer secret` and enter them at the Opauth Twitter strategy configuration.

Strategy configuration
----------------------

Required parameters:

```php
<?php
'Twitter' => array(
	'key' => 'YOUR CONSUMER KEY',
	'secret' => 'YOUR CONSUMER SECRET'
)
```
See Twitter.php for optional parameters.

Dependencies
------------
tmhOAuth requires hash_hmac and cURL.  
hash_hmac is available on PHP 5 >= 5.1.2.

Reference
---------
 - [Twitter Authentication & Authorization](https://dev.twitter.com/docs/auth)

License
---------
Opauth Twitter is MIT Licensed
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

tmhOAuth is [Apache 2 licensed](https://github.com/themattharris/tmhOAuth/blob/master/LICENSE).

[1]: https://github.com/uzyn/opauth	"Opauth"