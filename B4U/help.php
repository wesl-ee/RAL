<?php
include "../includes/config.php";
include "../includes/post.php";
?>
<HTML>
<head>
	<meta name=viewport content="width=device-width, maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="../css/base.css">
	<link rel=stylesheet href="../css/20XX.css">
	<title>RAL</title>
</head>
<body>
<div class=sidebar>
	<h3>RAL</h3>
	<span>Configure</span>
	<span class=collection>
		<a href='?theme'>Theme</a>
		<a href='?staff'>Staff</a>
		<a href='?about'>About</a>
		<a href='?'>Help</a>
	</span>
	<a href=.>Back</a>
</div>
<div id=rightpanel>
	<?php if (isset($_GET['theme'])) {
		print "<h3>Theming</h3>";
	} else if (isset($_GET['staff'])) {
		print "<h3>Staff</h3>";
	} else if (isset($_GET['about'])) {
		print "<h3>About</h3>";
	} else {
		$mail = CONFIG_ADMIN_MAIL;
		// Probably read this in from a file
		print
<<<HTML
		<h3>Help</h3>
		<div class=reader>
		<h4>What is a continuity?</h4><p>

		When you enter RAL, you will see several different
		continuities. A continuity is like a chat-room or a
		sub-forum. The title of a continuity will often give you a
		clue as to what its focus of dicsussion is.

		</p><h4>How do I post?</h4><p>

		Within each continuity, there is a button labeled "Create a
		topic" which allows you to create a new discussion topic
		within a continuity. If you find an interesting topic, you
		can create a post within that topic by clicking "Reply to
		topic" once you have the topic open

		</p><h4>Can I style my posts?</h4><p>

		Yes! Posts on RAL can be styled by marking up your post with
		the following supported BBCode tags:</p>
		<ul>
		<li>[b] (Bold)</li>
		<li>[i] (Emphasis)</li>
		<li>[color=x]
			<ul><li>
				x can be any valid HTML color name, hex
				triplet, rgb() triple, or hsl() color space
			</li></ul>
		</li>
		<li>[code] (Monospace, left-align)</li>
		<li>[url=href]</li>
			<ul><li>
				href is a link to a resource on the
				internet. Protocols other than http(s): are
				allowed as well (e.g. ftp)
			</li></ul>
		</ul><p>

		For example, the following text:</p>
		<pre>Here is a link to my favorite [url=www.msn.com]website[/url]</pre><p>
		Will be parsed and the resulting post will look like:</p>
		<pre>Here is a link to my favorite <a
		href=http:www.msn.com>website</a></pre>

		<h4>What is not allowed?</h4><ul>
			<li>Spamming</li>
		</ul>

		<h4>Who can I contact about abuse / questions?</h4><p>
		The administrator, sole protector and guardian angel of RAL
		can be reached via prayer or by mail:
		<a href=mailto:$mail>$mail</a>
		</p></div>
HTML;
	} ?>
</div>
</body>
</HTML>
