Advanced Programmer's Interface
===============================

Only advanced programmers are allowed to use the Advanced Programmer's
Interface! **YOU HAVE BEEN WARNED**. If you still think you have the
necessary skill (and courage), continue reading.

Overview of the API
-------------------

The RAL API is available within the source tree at `B4U/api.php`. This is
exposed to the public for everyone to use! By exposing this interface, I
hope to extend the RAL community. Some use cases may be:

* BBS / Telnet
* Desktop Application
* SSH
* Smartphone Application
* Braile Reader

Here are all of the functions which the API responds to:

Fetch
-----

Fetches things (i.e. continuities, topics, posts). Pass the relevant
parameters.

### Parameters

All parameters are optional.

* continuity
* topic
* format
* linkify

### Response

Returns the queried material in a format matching the passed `format`
(currently only HTML may be specified). If no format is passed, the
response will be in JSON form.

### Examples

`https://ral.space/api?fetch`

Fetches all continuities in the JSON format

`https://ral.space/api?fetch&continuity=Chat`

Fetches all topics in the [Chat] continuity

`https://ral.space/api?fetch&continuity=Chat&topic=1&format=html`

Fetches all posts in the [Chat/1] topic and displays them in a basic HTML format

Verify
-----

Meant for realtime interaction. You can query this instead of doing a
full fetch to just get the relevant post numbers that should be on your
page. If the response differs from your page, you know that your content
is not up-to-date and must be refreshed.

### Parameters

All parameters are optional

* continuity
* topic
* mostpost

### Response

Expect a JSON array of numbers which represents the posts in the topic
or continuity you queried.

Preview
-------

A simple way to format BBCode text into HTML using RAL's specification.

### Parameters

* text

### Response

Expect a fragment of HTML which represents the BBCode text
