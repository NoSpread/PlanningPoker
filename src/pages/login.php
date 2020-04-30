<?php

include_once "../assets/php/HeadBuilder.php";

$headTags = [
    "link" => [
		[
			"rel" => "stylesheet",
			"href" => "../assets/css/bootstrap/bootstrap.min.css",
			"type" => "text/css"
		]
	],
	"script" => [
		[
			"src" => "../assets/js/lodash/lodash.min.js"
		]
	],
    "title" => "login"
];
$head = new HeadBuilder($headTags);

?>

<!DOCTYPE html>
<html lang="en">
<?php $head->setTags() ?>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="#">Navbar</a>
		</div>
	</nav>
	<div class="container">
		<div>login</div>
	</div>


	<script src="../assets/js/jquery/jquery-3.5.0.min.js"></script>
	<script src="../assets/js/popper.js/popper.js-2.4.0.min.js"></script>
	<script src="../assets/js/bootstrap/bootstrap.min.js"></script>
</body>
</html>