Advanced Programmer's Interface
===============================

Only advanced programmers are allowed to use the Advanced Programmer's
Interface! **YOU HAVE BEEN WARNED**. If you still think you have the
necessary skill (and courage), continue reading.

Overview of the API
-------------------

The RAL API is available within the source tree at `www/api.php`. This is
exposed to the public for everyone to use! By exposing this interface, I
hope to extend the RAL community. Some use cases may be:

* BBS / Telnet
* Desktop Application
* SSH
* Smartphone Application
* Braile Reader

Here are all of the functions which the API responds to:

View
-----

Fetches things (i.e. continuities, topics, posts). Pass the relevant
parameters.

### Parameters

All parameters are optional.

* continuity
* year
* topic
* format

### Response

Returns the queried material in a format matching the passed `format`
(currently only HTML may be specified). If no format is passed, the
response will be in JSON form.

### Examples

`https://ral.space/api.php?a=view`

Fetches all continuities in the default format (typically text)

`https://ral.space/api.php?a=view&continuity=Chat`

Fetches all topics in the [Chat] continuity in the default format

`https://ral.space/api.php?fetch&continuity=Chat&year=2018&topic=1&format=html`

Fetches all posts in the [Chat/2018/1] topic and displays them in a basic
HTML format.
