<?php
class NavBuilder
{
	private $navArr = [
		"container" => true,
		"brand" => [
			"title" => "",
			"href" => "",
			"src" => "",
			"alt" => "",
			"width" => 0,
			"height" => 0
		],
		"routes" => [
			// [
			//     "title" => String,
			//     "href" => String,
			//     "active" => Boolean,
			//     "disabled" => Boolean
			// ]
		],
		"icons" => [
			// [
			//     "href" => String,
			//     "class" => String,
			//     "tooltip" => [
			//          "title" => String
			//     ]
			// ]
		],
		"profile" => [
			"image" => [
				"src" => "",
				"alt" => ""
			],
			"name" => "",
			"items" => [
				[
					"title" => "",
					"href" => "",
					"class" => ""
				]
			]
		]
	];

	public function __construct()
	{
	}

	public function setNav()
	{
		print('<nav class="navbar navbar-expand-lg navbar-dark _bg-dark">' . PHP_EOL);
		if ($this->navArr['container']) print('<div class="container">' . PHP_EOL);

		foreach ($this->navArr as $key => $tags) {
			switch ($key) {
				case "brand":
					print($this->buildBrand($tags['title'], $tags['href'], $tags['src'], $tags['alt'], $tags['width'], $tags['height']));
					print($this->printToggleButton());
					break;
				case "routes":
					print($this->printNavCollapseWrap('begin'));
					print($this->printNavItemWrap('begin'));
					foreach ($tags as $skey => $tag) {
						print($this->buildRoutes($tag['title'], $tag['href'], $tag['active'], $tag['disabled']));
					}
					print($this->printNavItemWrap('end'));
					break;
				case "icons":
					print($this->printNavbarRightWrap('begin'));
					foreach ($tags as $skey => $tag) {
						print($this->buildIcons($tag['class'], $tag['href'], $tag['tooltip']));
					}
					break;
				case "profile":
					print($this->buildProfile());
					print($this->printNavbarRightWrap('end'));
					print($this->printNavCollapseWrap('end'));
					break;
			}
		}

		if ($this->navArr['container']) print('</div>' . PHP_EOL);
		print('</nav>' . PHP_EOL);
	}

	private function buildBrand(string $title, string $href = "", string $src = "", string $alt = "", int $width = 0, int $height = 0)
	{
		$ret = "<a class='navbar-brand' href='" . (!empty($href) ? $href : "#") . "'>";

		if (!empty($src) && $width > 0 && $height > 0) {
			$ret .= PHP_EOL . "<img src='{$src}' alt='" . (!empty($alt) ? $alt : "image") . "' width='{$width}' height='{$height}' class='d-inline-block align-top' />" . PHP_EOL;
		}

		if (empty($title)) return false;
		$ret .= $title;

		$ret .= "</a>";

		return $ret . PHP_EOL;
	}

	private function buildRoutes(string $title, string $href = "", bool $active = false, bool $disabled = false)
	{
		if (empty($title)) return false;
		if ($active && $disabled) return false;

		$ret = "<li class='nav-item " . ($active ? "_nav-item-active" : "") . "'>" . PHP_EOL;
		$ret .= "<a class='nav-link " . ($disabled ? "disabled" : "") . "' href='" . (empty($href) ? "#" : $href) . "'>{$title}</a>" . PHP_EOL;
		$ret .= "</li>";

		return $ret . PHP_EOL;
	}

	private function buildIcons(string $class, array $tooltip, string $href = "")
	{
		if (empty($class) || !sizeof($tooltip)) return false;

		$ret = "<div class='_navbar-icon'>" . PHP_EOL;
		$ret .= "<a href='" . (empty($href) ? "#" : $href) . "' data-toggle='tooltip' data-placement='bottom' title='" . $tooltip['title'] . "'>" . PHP_EOL;

		$ret .= "<i class='{$class}'></i>" . PHP_EOL;

		$ret .= "</a>" . PHP_EOL;
		$ret .= "</div>";

		return $ret . PHP_EOL;
	}

	private function buildProfile()
	{
		if (!isset($_SESSION['USER'])) return $this->printLoginButton();
	}

	private function printLoginButton()
	{
	}

	private function printToggleButton()
	{
		$ret = "<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>" . PHP_EOL;
		$ret .= "<span class='navbar-toggler-icon'></span>" . PHP_EOL . "</button>";

		return $ret;
	}

	private function printNavCollapseWrap(string $position)
	{
		switch ($position) {
			case 'begin':
				$ret = "<div class='collapse navbar-collapse' id='navbarNav'>";
				break;

			case 'end':
				$ret = "</div>";
				break;
		}

		return $ret . PHP_EOL;
	}

	private function printNavItemWrap(string $position)
	{
		switch ($position) {
			case 'begin':
				$ret = "<ul class='navbar-nav'>";
				break;

			case 'end':
				$ret = "</ul>";
				break;
		}

		return $ret . PHP_EOL;
	}

	private function printNavbarRightWrap(string $position)
	{
		switch ($position) {
			case 'begin':
				$ret = "<div class='_navbar-right'>";
				break;

			case 'end':
				$ret = "</div>";
				break;
		}

		return $ret . PHP_EOL;
	}
}
?>

<nav class="navbar navbar-expand-lg navbar-dark _bg-dark">
	<div class="container">
		<a class="navbar-brand" href="#">Navbar</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item _nav-item-active">
					<a class="nav-link" href="#">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Features</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Pricing</a>
				</li>
				<li class="nav-item">
					<a class="nav-link disabled" href="#">Disabled</a>
				</li>
			</ul>
			<div class="_navbar-right">
				<div class="_navbar-icons">
					<div class="_navbar-icon">
						<a href="#" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
							<i class="mdi mdi-24px mdi-google"></i>
						</a>
					</div>
					<div class="_navbar-icon">
						<a href="#">
							<i class="mdi mdi-24px mdi-facebook"></i>
						</a>
					</div>
				</div>
				<!--
				<div class="dropdown _navbar-profile">
					<a class="a-none _navbar-profile-toggler" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="https://i.imgur.com/6vja1Al.png" width="30" height="30" alt="zz" class="_navbar-profile-img" />
						User
						<i class="mdi mdi-24px mdi-menu-down"></i>
					</a>
					<div class="dropdown-menu _navbar-profile-dropdown-menu" aria-labelledby="dropdownMenuLink">
						<div class="dropdown-item _navbar-profile-dropdown-item a-none">
							<a href="#">Action</a>
						</div>
						<div class="dropdown-item _navbar-profile-dropdown-item a-none">
							<a href="#">Another action</a>
						</div>
						<div class="dropdown-item _navbar-profile-dropdown-item a-none">
							<a href="#">Something else here</a>
						</div>
					</div>
				</div>
				-->
				<div class="_navbar-profile">
					<a class="a-none" href="../pages/login.php">
						<button type="button" class="btn _button-default">
							Login here
						</button>
					</a>
				</div>
			</div>
		</div>
	</div>
</nav>

<script>
	$(function() {
		$('[data-toggle="tooltip"]').tooltip()
	})
</script>