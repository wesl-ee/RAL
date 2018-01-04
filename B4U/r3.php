<?php
$ROOT = '../';
include $ROOT."includes/main.php";
header("Refresh: 3;max.php?$_SERVER[QUERY_STRING]");
?>
<HTML>
<head>
	<?php head('Post Success')?>
</head>
<body>
<div id=welcome>
	<h1 class=xxx-success>Successful</h1>
</div>

<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<!-- End of scripts -->
</body>
</HTML>
