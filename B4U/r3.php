<?php
header("Refresh: 5;max.php?$_SERVER[QUERY_STRING]");
?>
<HTML>
<head>
	<link rel=stylesheet href="../css/base.css">
	<link rel=stylesheet href="../css/20XX.css">
	<meta name=viewport content="width=device-width; maximum-scale=1; minimum-scale=1">
	<title>RAL</title>
</head>
<body>
<div id=welcome>
	<h1 id=xxx>Successful</h1>
</div>

<!-- Scripts -->
<script src='../js/esthetic.js'></script>
<script>
var xxx = [
	'Sent',
	'Uploaded',
	'Exported',
	'Posted',
	'Exchanged',
	'Transmitted',
	'Uplinked',
	'Carried',
	'Relayed',
	'Delivered',
	'Accepted',
	'Entered',
	'Comunicated',
]
flashmessages(document.getElementById('xxx'), xxx, 100);
</script>
<!-- End of scripts -->
</body>
</HTML>
