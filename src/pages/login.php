<?php

include_once "../assets/php/HeadBuilder.php";

$headTags = [
	"link" => [
		[
			"rel" => "stylesheet",
			"href" => "https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/css/bootstrap/bootstrap.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/dist/body.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/dist/main.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "//cdn.materialdesignicons.com/5.1.45/css/materialdesignicons.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/dist/materialdesignicons.helper.css",
			"type" => "text/css"
		]
	],
	"script" => [
		[
			"src" => "../assets/js/jquery/jquery-3.5.0.min.js",
		],
		[
			"src" => "../assets/js/popper.js/popper.js-2.4.0.min.js"
		],
		[
			"src" => "../assets/js/bootstrap/bootstrap.min.js"
		],
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
	<?php
	require_once "../layouts/nav.php";
	?>
	<div class="container px-4 py-4">
		<div id="login-box" class="main-box main-box-open">
			<div class="main-box-header">
				<div class="main-box-header-content">Login</div>
			</div>
			<div class="main-box-content">
				<div><i class="mdi mdi-account"></i> Content</div>
			</div>
			<div class="main-box-footer">
				<div class="main-box-footer-content">Footer</div>
				<div class="main-box-expand">
					<i class="mdi mdi-36px"></i>
				</div>
			</div>
		</div>
		<div id="what-box" class="main-box main-box-open">
			<div class="main-box-header">
				<div class="main-box-header-content">Register</div>
			</div>
			<div class="main-box-content">
				<div><i class="mdi mdi-account"></i> Content</div>
			</div>
			<div class="main-box-footer">
				<div class="main-box-footer-content">Footer</div>
				<div class="main-box-expand">
					<i class="mdi mdi-36px"></i>
				</div>
			</div>
		</div>
	</div>
	</div>

	<script src="../assets/js/mainBox.js"></script>
</body>

</html>