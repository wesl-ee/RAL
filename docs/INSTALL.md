Recommended Installation
========================

Overview
--------

This document provides a detailed guide on the best-practice of configuring
RAL. Since RAL is written (nearly) entirely in PHP, you may be able to guess
what sort of tools I will be using:

Tools
-----

* Webserver
* MariaDB (or other MySQL provider)
* Git
* PHP >= 5.1 (PHP provider)
	- MySQLi
	- fpm-PHP (PHP pool manager)
	- Graphics Draw (GD) Library for PHP
	- jBBCode
	- b8

Ensure that your webserver can serve PHP files. Then run the setup utility
(`bin/install.php`) which will automatically download and install jBBCode
and b8.

Example Setup
-------------

	/**************** My Setup ***************\
	*                                         *
	*         Internet                        *
	*    _______|_|_______                    *
	*    _______|_|_______ Firewall           *
	*           | |                           *
	*           | | (http/2)                  *
	*           | | (SSL Ciphered)            *
	*           | |                           *
	*    +------v--------+                    *
	*    |     Nginx     |  Reverse proxy     *
	*    +--------^------+                    *
	*           | | (plaintext)               *
	*           | | (http/1.1)                *
	*           | |                           *
	*    +------v--------+                    *
	*    |    Lighttpd   |  Back-end          *
	*    +--+---------+--+                    *
	*       | fpm-PHP |                       *
	*       +---------+                       *
	\*****************************************/

In our example, Nginx is the first stop for clients once they connect to the
server. Nginx will pass the client request on to lighttpd and send the client
lighttpd's response. We call such webservers *reverse proxies* since it is
proxying a request on behalf of the client.

Though a reverse proxy is not strictly necessary, configuring one will help
you down the road if you ever decide to expand your web services. A reverse
proxy helps you to collect all SSL certificates into a central place,
reducing the hassle when you deploy new keys.

### Example configurations

In `docs/config/` you will find some useful files for setting up the
back-end server. Whether or not you set up a reverse proxy is really up to
you, but I would _highly_ recommend it.

If you decide to deploy using http/2 remember that the modern interpretation
of http/2 [practically requires SSL](https://www.nginx.com/wp-content/uploads/2015/09/NGINX_HTTP2_White_Paper_v4.pdf>)
In this case, nginx will also act as a SSL termination proxy; you will need
to obtain an SSL certificate from a trusted CA in order to provide adequate
transport security.

### Actually Installing RAL

Copy the example configuration file from `includes/config.template.php` into
`includes/config.php`. This is where you will define (e.g.) database connection
parameters as well as canonical URLs for site resources.

```
cp includes/config.template.php includes/config.php
```

Edit this file to your liking, making sure that the MySQL parameters match the
database and connection address used above.

Next make sure you've got a SQL server running at the address specified in the
above configuration file, then run the install script.

```
php ./bin/install.php
```

Address any errors which come up (including installing any missing PHP modules)
and then try connecting to the site at your URL. You should at least see the RAL
banner and a small "about" blurb, followed by some rules. These are configurable
in the `info/About.txt` and `info/Rules.txt` files respectively. The contents of
these files will be displayed directly on the page so any HTML markup applied
here will be reflected on the website as is.

### Creating Continuities

The main organization unit of RAL is the "Continuity", essentially a centering
topic for the discussion within. This is pretty straightforward using the admin
script included in the `bin` directory.

```
y@fss$ php ./bin/admin.php
  _____            _
 |  __ \     /\   | |
 | |__) |   /  \  | |
 |  _  /   / /\ \ | |
 | | \ \  / ____ \| |____
 |_|  \_\/_/    \_\______|
   Welcome, Super-user.
1.) Content
2.) News
3.) Spam
4.) Bans
5.) Post Details
6.) Miscellany
7.) Quit
> 1
1.) Metrics
2.) Post Info
3.) Mark / Learn as Spam
4.) Unmark / Unlearn as Spam
5.) Delete a Post
6.) Create a Continuity
7.) Delete a Continuity
> 6
Name: Meta
Description: Discussion about Discussion
PHP Notice:  Undefined variable: ret in /pub/www/dev.ralee.org/includes/Ral.php on line 173
1.) Content
2.) News
3.) Spam
4.) Bans
5.) Post Details
6.) Miscellany
7.) Quit
> 7
```

You should see the new continuity on the front page!

Finally, you'll want to make a banner for this new continuity. Banners are
expected to be 380x150px, formatted as GIF, and placed at
`www/continuities/[Name]/banner.gif`.
