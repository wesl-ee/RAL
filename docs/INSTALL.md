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
	- ImageMagick
	- jBBCode

Ensure that your webserver can serve PHP files. Then install jBBCode into
`/includes/jBBCode`. jBBCode can be downloaded from
[the official website](http://jbbcode.com/).

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
