Recommended Installation
========================

Overview
--------

This document provides a detailed guide on the best-practice of configuring
RAL. Since RAL is written (nearly) entirely in PHP, you may be able to guess
what sort of tools I will be using:

### On Reverse Proxy

* Nginx

	For proxying requests and as a SSL termination proxy

### On Back-end Webserver

* lighttpd

	Serve client requests when they are passed from nginx

* MariaDB (or other MySQL provider)

	MariaDB stores post and continuity info for RAL so that it is
	persistent across reboots and accessible to any MySQL driver

* Git

	Since RAL is version-controlled using git, this is pretty necessary.
	Additionally, RAL calls `git` to fetch the version number and display
	it at the bottom of some pages.

* PHP >= 5.1 (PHP provider)

	Executes the RAL script. For RAL, PHP must be compiled with at least
	the following components:

	* MySQLi

		Access to the MySQL database is possible through PHP's
		MySQLi interface.

	* fpm-PHP (PHP pool manager)

		Provides greater flexibility for controlling the dynamic load
		and timouts of a long-polling load

	* ImageMagick

		PHP calls C<convert> and uses CONFIG_WORDLIST to generate
		random *robocheck* images to combat spam.

           Typical Implementation
	/*****************************************\
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

In `docs/config/` you will find two folders. One of these folders holds
configurations for the reverse proxy, and the other holds configurations for
the back-end webserver.

All of these softwares may run on the same computer. In any case, nginx
B<MUST> be set to listen on either 80 (http:) or 443 (https:) for regular
web clients to easily connect to your server.

If you decide to deploy using http/2 remember that the modern interpretation
of http/2 [practically requires SSL](https://www.nginx.com/wp-content/uploads/2015/09/NGINX_HTTP2_White_Paper_v4.pdf>)
In this case, nginx will also act as a SSL termination proxy; you will need
to obtain an SSL certificate from a trusted CA in order to provide adequate
transport security.
