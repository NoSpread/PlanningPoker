<?php

session_start();
include_once "../assets/php/logic/auth.php";
include_once "../assets/php/classes/HeadBuilder.php";

$headTags = [
	"link" => [
		[
			"rel" => "stylesheet",
			"href" => "https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/libraries/bootstrap/bootstrap.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/dist/planningpoker.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "//cdn.materialdesignicons.com/5.1.45/css/materialdesignicons.min.css",
			"type" => "text/css"
		],
		[
			"rel" => "stylesheet",
			"href" => "../assets/css/materialdesignicons.helper.css",
			"type" => "text/css"
		]
	],
	"script" => [
		[
			"src" => "../assets/libraries/jquery/jquery-3.5.1.min.js",
		],
		[
			"src" => "../assets/libraries/bootstrap/bootstrap.bundle.min.js"
		]
	],
	"title" => "PlanningPoker - Login"
];
$head = new HeadBuilder($headTags);

include_once "../layouts/nav.php";
$navArr = [
	"container" => true,
	"brand" => [
		"title" => "Nav",
		"href" => "#",
		"src" => "",
		"alt" => "",
		"width" => 0,
		"height" => 0
	],
	"routes" => [
		[
			"title" => "Home",
			"href" => "./",
			"active" => false,
			"disabled" => false
		],
		[
			"title" => "Information",
			"href" => "./information",
			"active" => false,
			"disabled" => false
		]
	],
	"icons" => [
		[
			"href" => "",
			"class" => "mdi mdi-24px mdi-facebook",
			"tooltip" => [
				"title" => "is this facebook"
			]
		],
		[
			"href" => "",
			"class" => "mdi mdi-24px mdi-google",
			"tooltip" => [
				"title" => "is this google"
			]
		]
	],
	"profile" => [
		"name" => "",
		"items" => []
	]
];
$nav = new NavBuilder($navArr);
?>

<!DOCTYPE html>
<html lang="en">
<?php $head->setTags() ?>

<body>
	<?php $nav->setNav() ?>
	<div class="container view d-flex justify-content-center align-items-center px-4 py-4">
		<div id="login-box" class="main-box main-box-open main-box-static">
			<div class="main-box-header">
				<div class="main-box-header-content">Login</div>
			</div>
			<div class="main-box-content">
				<form class="_form" action="../assets/php/logic/login.php" method="GET">
					<div class="_form-field">
						<div class="_input-single">
							<input id="name" class="_input" type="text" placeholder="Username/E-Mail" name="name" autocomplete="off"></input>
							<div class="_input-icon _icon-left">
								<i class="mdi mdi-24px mdi-account-outline"></i>
							</div>
						</div>
					</div>
					<div class="_form-field">
						<div class="_input-single">
							<input id="password" class="_input" type="password" placeholder="Password" name="password" autocomplete="off"></input>
							<div class="_input-icon _icon-left">
								<i class="mdi mdi-24px mdi-lock-outline"></i>
							</div>
							<div class="_input-icon _icon-right">
								<i id="revealPassword" class="mdi mdi-24px mdi-eye-outline"></i>
							</div>
						</div>
					</div>
					<div class="_checkbox">
						<div id="rememberMe" class="_box"></div>
						<label class="_label">Remember Me</label>
					</div>
					<div>
						<input id="submit" type="submit" value="submit" class="btn _button-default btn-block" disabled>
					</div>
				</form>
			</div>
			<div class="main-box-footer">
				<div class="main-box-footer-content">
					<a href="./register">Don't have an account? Register here!</a>
				</div>
				<div class="main-box-expand">
					<i class="mdi mdi-36px"></i>
				</div>
			</div>
		</div>
	</div>

	<?php
	if (isset($_SESSION['LASTERROR']) && sizeof($_SESSION['LASTERROR']) > 0)
		include_once "../components/last_error.php";
	?>

	<?php include_once "../assets/php/planningpoker.js.php"; ?>
	<script>
		$('i#revealPassword').click(function() {
			revealPassword(this.id);
		});
	</script>
	<script>
		var inputChecksums = {
			checksums: {
				'name': false,
				'password': false
			},
			get get() {
				return this.checksums;
			},
			set set(args) {
				this.checksums[args.id] = args.bool;
				handleSubmit('submit', this.checksums);
			}
		};

		$('#name').keyup(function() {
			inputChecksums.set = {
				id: this.id,
				bool: $(this).val().length > 0 ? true : false
			};
		});

		$('#password').keyup(function() {
			inputChecksums.set = {
				id: this.id,
				bool: $(this).val().length > 0 ? true : false
			};
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