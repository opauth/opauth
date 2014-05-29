![Opauth](https://github.com/opauth/opauth.org/raw/master/images/opauth-logo-300px-transparent.png)
=================================
Opauth is a multi-provider authentication framework for PHP.

Opauth enables PHP applications to perform *user authentication* really easily.

Try out Opauth for yourself at http://opauth.org

### Notes on 1.0.x
1.0.x is currently under preview release and not to be used for production yet. See [release notes](https://github.com/opauth/opauth/releases) for details.

Opauth API and functionalities are fully ready for evaluation. Please try it out and let us know what you think.

1.0.x is not backward-compatible with 0.x. See [migration guide](http://docs.opauth.org/en/1.0/migration-guide.html) on how to migrate Opauth 0.x applications and strategies.

For current stable release, see [0.4.x](https://github.com/opauth/opauth/tree/master).

[![Build Status](https://travis-ci.org/opauth/opauth.svg?branch=1.0)](https://travis-ci.org/opauth/opauth)
[![Coverage Status](https://coveralls.io/repos/opauth/opauth/badge.png?branch=1.0)](https://coveralls.io/r/opauth/opauth?branch=1.0)


What is Opauth?
---------------
Opauth provides a standardized interface between PHP applications and authentication providers.

Opauth as a framework provides a set of API that allows developers to [create strategies](https://github.com/uzyn/opauth/wiki/Strategy-Contribution-Guide) that work in a predictable manner across PHP frameworks and applications.

Opauth works well with other PHP applications & frameworks.

Quick start
-----------
1. Composer require Opauth core and [Opauth strategies](http://docs.opauth.org/en/1.0/strategies.html). Add the following to your application's `composer.json`:

    ```json
    {
        "minimum-stability": "dev",
        "require": {
            "opauth/opauth": "~1.0",
            "opauth/facebook": "~1.0"
        }
    }
    ```
    Note: While Opauth 1.0.x still is in development, your root `composer.json` will need to set its minimum-stability to `dev`.

    Install them

    ```bash
    $ composer install
    ```

1. Configure Opauth.  
   Create a file `opauth.php` that is accessible at `http://localhost/opauth.php/`.

    ```php
    <?php
    $config = array(
        'Strategy' => array(
            'Facebook' => array(
                'app_id' => 'YOUR APP ID',
                'app_secret' => 'YOUR APP SECRET'
            )
        ),
        'path' => '/opauth.php/'
    );
    ```

1. Instantiate and run Opauth

    ```php
    <?php
    $Opauth = new Opauth\Opauth\Opauth($config);

    try {
        $response = $Opauth->run();
        echo "Authed as " . $response->name . " with uid" . $response->uid;
    } catch (OpauthException $e) {
        echo "Authentication error: " . $e->getMessage();
    }
    ```

1. Point your browser to `http://localhost/opauth.php/facebook` to see it in action.

References
----------
See [Opauth documentations](http://docs.opauth.org/) for more infomation.

Requirements
-------------
- PHP 5 (>= 5.3)
- [Composer](https://getcomposer.org/)

Core contributors
-----------------
1. U-Zyn Chua (@uzyn on [GitHub](https://github.com/uzyn) & [Twitter](http://twitter.com/uzyn))
1. Marc Ypes (@ceeram on [GitHub](https://github.com/ceeram) & [Twitter](https://github.com/ceeram))

We welcome your contributions to Opauth:
- Be free to submit pull requests.
- Inform Opauth community via the [discussion group](https://groups.google.com/group/opauth) and/or [@opauth](http://twitter.com/opauth) if you have authored a new strategy, new plugin or done anything cool with Opauth.
- Join us at IRC: **#opauth** on [Freenode](http://webchat.freenode.net/?channels=opauth&uio=d4) for any discussions.

Issues & questions
-------------------
- Discussion group: [Google Groups](https://groups.google.com/group/opauth)  –
  Primary channel for support, especially usage questions.
- Issues: [Github Issues](https://github.com/opauth/opauth/issues)
- IRC: **#opauth** on [Freenode](http://webchat.freenode.net/?channels=opauth&uio=d4)
- Twitter: [@opauth](http://twitter.com/opauth)

License
---------
The MIT License  
Copyright © 2012-2014 U-Zyn Chua (http://uzyn.com)

Credits
-----
- [OmniAuth for Ruby](https://github.com/intridea/omniauth) – The project that inspired Opauth.
- [Homenaje](https://www.google.com/fonts/specimen/Homenaje) – The font for Opauth logo. Designed by Constanza Artigas Preller and Agustina Mingote. SIL Open Font Licence, 1.1.
