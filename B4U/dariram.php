<?php
$ROOT = '../';
include $ROOT."includes/main.php";
header("Refresh: 3;max.php?$_SERVER[QUERY_STRING]");
?>
<HTML>
<head>
	<?php head('Post Failure')?>
</head>
<body>
<div id=welcome>
	<h1 class=xxx-failure>Failure</h1>
</div>

<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<!-- End of scripts -->
</body>
</HTML>
