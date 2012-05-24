Opauth usage example
===============
This example is meant to help users unfamiliar with Opauth to quickly get started.

The files in this directory imeplements the steps required to [use Opauth](https://github.com/uzyn/opauth/wiki#Using-Opauth).

Getting started
----------------
1. Set `DocumentRoot` of your web server to this directory.

2. Configure Opauth  
   `cp opauth.conf.php.default opauth.conf.php`, or
   `cp opauth.conf.php.advanced opauth.conf.php`
   and make the necessary changes.

3. Install some [Opauth strategies](https://github.com/uzyn/opauth/wiki/List-of-strategies).
   We recommend that you start with [Opauth-Facebook](https://github.com/uzyn/opauth-facebook)

   Place the strategy files in `../lib/Opauth/Strategy/`.  
   For example, for Opauth-Facebook, place the downloaded files at `../lib/Opauth/Strategy/Facebook/`.

4. Send user to `http://path_to_opauth/facebook` to authenticate.

Check out [the wiki](https://github.com/uzyn/opauth/wiki) for more in-depth details.