Opauth Twitter
=============
Twitter strategy for [Opauth][1], based on Opauth OAuth.

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
 - [Twitter AUthentication & Authorization](https://dev.twitter.com/docs/auth)

License
---------
Opauth Twitter is MIT Licensed
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

tmhOAuth is [Apache 2 licensed](https://github.com/themattharris/tmhOAuth/blob/master/LICENSE).

[1]: https://github.com/uzyn/opauth	"Opauth"