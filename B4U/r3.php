<?php
$ROOT = '../';
include $ROOT."includes/main.php";
header("Refresh: 3;max.php?$_SERVER[QUERY_STRING]");
?>
<HTML>
<head>
	<?php head($ROOT)?>
	<title>RAL</title>
</head>
<body>
<div id=welcome>
	<h1 class=xxx-success>Successful</h1>
</div>

<!-- Scripts -->
<script src='<?php print $ROOT?>js/esthetic.js'></script>
<!-- End of scripts -->
</body>
</HTML>
