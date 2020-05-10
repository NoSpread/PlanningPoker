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
			"href" => "../assets/dist/main-box.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/dist/form.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/dist/checkbox.min.css",
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
	<div class="container view d-flex justify-content-center align-items-center px-4 py-4">
		<div id="login-box" class="main-box main-box-open main-box-static">
			<div class="main-box-header">
				<div class="main-box-header-content">Login</div>
			</div>
			<div class="main-box-content">
				<form class="_form" action="submit">
					<div class="_form-field _input-single">
						<input class="_input" type="text" placeholder="Username/E-Mail"></input>
						<div class="_input-icon _icon-left">
							<i class="mdi mdi-24px mdi-account"></i>
						</div>
					</div>
					<div class="_form-field _input-single">
						<input class="_input" type="password" placeholder="Passwort"></input>
						<div class="_input-icon _icon-left">
							<i class="mdi mdi-24px mdi-lock"></i>
						</div>
						<div class="_input-icon _icon-right">
							<i id="clearPassword" class="mdi mdi-24px mdi-eye"></i>
						</div>
					</div>
					<div id="saveSession" class="_checkbox">
						<div class="_box"></div>
						<div class="_label">Remember Me</div>
					</div>
					<button class="btn btn-primary w-25 mt-5">submit</button>
				</form>
			</div>
			<div class="main-box-footer">
				<div class="main-box-footer-content">
					<a href="#">Don't have an account? Register here!</a>
				</div>
				<div class="main-box-expand">
					<i class="mdi mdi-36px"></i>
				</div>
			</div>
		</div>
	</div>

	<script src="../assets/js/mainBox.js"></script>
	<script>
		//$('._input').after('<div class="_input-after _input-error"><i class="mdi mdi-24px mdi-alert-octagon-outline"></i>Username already taken</div>');
		$('i#clearPassword').click(function() {
			if ($(this).hasClass('mdi-eye')) {
				$(this).removeClass('mdi-eye').addClass('mdi-eye-off');
				$(this).parents('._form-field').children('._input').attr('type', "text");
			} else {
				$(this).removeClass('mdi-eye-off').addClass('mdi-eye');
				$(this).parents('._form-field').children('._input').attr('type', 'password');
			}
		});
		$('#saveSession ._box').click(function() {
			if ($(this).hasClass('_checkbox-active')) {
				$(this).removeClass('_checkbox-active');
			} else {
				$(this).addClass('_checkbox-active');
			}
		});
	</script>
</body>

</html>

<!--
	info: mdi-information-outline
	error: mdi-alert-octagon-outline
	warning: mdi-alert-outline
	success: mdi-check-outline
 -->