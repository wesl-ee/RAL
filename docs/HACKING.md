Technical Documentation
=======================

Hello friends. If you are interested in contributing to the development of
RAL, this file will help you to get started.

First and foremost, ensure that you are on the `develop` branch. This is
the developer's branch. `master` is considered production-stable and is
only merged with develop for stable releases.

Submitting Patches / Pull Requests
----------------------------------

Either mail me at L<yumi@prettyboytellem.com|mailto:yumi@prettyboytellem.com> or join the IRC at
[howler.space:6667](https://irc.ral.space) #bigtown. A PR is also fine but
I would prefer that you either send a .patch (for small things), or link me
the repository with your feature branch (for larger things)

Style
-----

In general, I follow a K&R style when writing. If you see any mistakes or
trailing whitespace, you may send a .patch to my e-mail address. or link it
in the IRC. It's a great way to introduce yourself!

Bloody Details
--------------

This is a break-down of how it all works.

### Database Schema

The text is an important part of a forum / textboard! So the first thing I
will describe is the structure of the data inside our SQL database.

### Posting

When a user opens a postbox, either by clicking "Reply to topic" or "Create
a topic", a *Robocheck* image is generated using `CONFIG_WORDLIST`. If you
are able to verify the word engrained in the image, you win! And are allowed
to post,  given that your post length is somewhere between 0 and
`CONFIG_MAX_POSTLEN`.
