Opauth usage example
===============
This example is meant to help users unfamiliar with Opauth to quickly get started.

The files in this directory implements the steps required to [use Opauth](https://github.com/uzyn/opauth/wiki#wiki-Using-Opauth).

Getting started
----------------
1. Set `DocumentRoot` of your web server to this directory, so that this file is accessible at `http://localhost/README.md` and **not** `http://localhost/example/README.md`.

2. Configure Opauth.
   
   First, make a copy of opauth config's file by copying or renaming  
   `opauth.conf.php.default` to `opauth.conf.php`.

   Open up `opauth.conf.php` and make the necessary changes.

3. Install some [Opauth strategies](https://github.com/uzyn/opauth/wiki/List-of-strategies).  
   Place the strategy files in `../lib/Opauth/Strategy/`.  

   For this example, we recommend that you start with [Opauth-Facebook](https://github.com/uzyn/opauth-facebook):

   i. [Download the strategy files](https://github.com/uzyn/opauth-facebook/zipball/master) and place them at `../lib/Opauth/Strategy/Facebook/`.

   ii. Follow the steps at [Opauth-Facebook's README](https://github.com/uzyn/opauth-facebook/blob/master/README.md) to set up your Faceobok app.

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

Check out [the wiki](https://github.com/uzyn/opauth/wiki) for more in-depth details.