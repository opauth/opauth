Opauth OAuth
=============
A generic [Opauth][1] strategy implementing OAuth 1.0A, using [tmhOAuth](https://github.com/themattharris/tmhOAuth).

Getting started
---------------
This is a generic OAuth strategy intended as a helper for developers of Opauth strategies, especially those that are based on OAuth.

Strategy configuration
----------------------

Required parameters:

```php
<?php
'OAuth' => array(
	'consumer_key' => 'YOUR CONSUMER KEY',
	'consumer_secret' => 'YOUR CONSUMER SECRET',

	'request_token_url' => 'https://api.twitter.com/oauth/request_token',
	'access_token_url' => 'https://api.twitter.com/oauth/authenticate'
)
```

See OAuth.php for optional parameters.

Dependencies
------------
tmhOAuth requires hash_hmac and cURL.  
hash_hmac is available on PHP 5 >= 5.1.2.

Reference
---------
 - [OAuth Core 1.0](http://oauth.net/core/1.0/)
 - [Twitter Authentication & Authorization](https://dev.twitter.com/docs/auth)

License
---------
Opauth OAuth is MIT Licensed
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

tmhOAuth is [Apache 2 licensed](https://github.com/themattharris/tmhOAuth/blob/master/LICENSE).

[1]: https://github.com/uzyn/opauth	"Opauth"