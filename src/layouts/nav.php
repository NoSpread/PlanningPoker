<?php
class NavBuilder
{
	private $navArr = [
		"container" => true,
		"brand" => [
			// "title" => "",
			// "href" => "",
			// "src" => "",
			// "alt" => "",
			// "width" => 0,
			// "height" => 0
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
			// "name" => "",
			// "items" => [
			//     [
			//          "title" => "",
			//          "href" => ""
			//     ]
			// ]
		]
	];

	public function __construct(array $navArr)
	{
		foreach ($navArr as $key => $navData) {
			if (isset($this->navArr[$key])) {
				$this->navArr[$key] = $navData;
			}
		}
	}

	public function setNav()
	{
		print('<nav class="navbar sticky-top navbar-expand-lg navbar-dark _bg-dark">' . PHP_EOL);
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
						print($this->buildIcons($tag['class'], $tag['tooltip'], $tag['href']));
					}
					break;
				case "profile":
					print($this->buildProfile($tags['name'], $tags['items']));
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
		if (empty($title)) return false;

		return "
			<a class='navbar-brand' href='" . (!empty($href) ? $href : "#") . "'>
				" . (!empty($src) && $width > 0 && $height > 0 ? "<img src='{$src}' alt='" . (!empty($alt) ? $alt : "image") . "' width='{$width}' height='{$height}' class='d-inline-block align-top' />" : "") . "
				{$title}
			</a>";
	}

	private function buildRoutes(string $title, string $href = "", bool $active = false, bool $disabled = false)
	{
		if (empty($title)) return false;
		if ($active && $disabled) return false;

		return "
			<li class='nav-item " . ($active ? "_nav-item-active" : "") . "'>
				<a class='nav-link " . ($disabled ? "disabled" : "") . "' href='" . (empty($href) ? "#" : $href) . "'>{$title}</a>
			</li>
		";
	}

	private function buildIcons(string $class, array $tooltip, string $href = "")
	{
		if (empty($class) || !sizeof($tooltip)) return false;

		return "
			<li class='nav-item'>
				<a class='nav-link _navbar-icon' href='" . (empty($href) ? "#" : $href) . "' data-toggle='tooltip' data-placement='bottom' title='" . $tooltip['title'] . "'>
					<i class='{$class}'></i>
				</a>
			</li>
		";
	}

	private function buildProfile(string $name, array $items)
	{
		if (empty($name)) return $this->printLoginButton();

		$ret = "
		<li class='nav-item dropdown _navbar-profile'>
			<a class='a-none _navbar-profile-toggler' href='#' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
				<span>{$name}</span>
				<i class='mdi mdi-24px mdi-menu-down'></i>
			</a>
			<div class='dropdown-menu _navbar-profile-dropdown-menu' aria-labelledby='dropdownMenuLink'>
		";

		foreach ($items as $skey => $tag) {
			$ret .= "
				<div class='dropdown-item _navbar-profile-dropdown-item'>
					<a href='{$tag['href']}'>{$tag['title']}</a>
				</div>
			";
		}

		if (sizeof($items) > 0)
			$ret .= "
				<div class='_navbar-profile-dropdown-divider'></div>
			";

		$ret .= "
					<div class='dropdown-item _navbar-profile-dropdown-item'>
						<a href='./logout.php'>Logout</a>
					</div>
				</div>
			</li>
		";

		return $ret;
	}

	private function printLoginButton()
	{
		return "
			<div class='_navbar-profile'>
				<a class='a-none' href='./login'>
					<button type='button' class='btn _button-default'>
						Login here
					</button>
				</a>
			</div>
		";
	}

	private function printToggleButton()
	{
		return "
			<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
				<span class='navbar-toggler-icon'></span>
			</button>
		";
	}

	private function printNavCollapseWrap(string $position)
	{
		switch ($position) {
			case 'begin':
				return "<div class='collapse navbar-collapse' id='navbarNav'>";
				break;

			case 'end':
				return "</div>";
				break;
		}
	}

	private function printNavItemWrap(string $position)
	{
		switch ($position) {
			case 'begin':
				return "<ul class='navbar-nav'>";
				break;

			case 'end':
				return "</ul>";
				break;
		}
	}

	private function printNavbarRightWrap(string $position)
	{
		switch ($position) {
			case 'begin':
				return "<ul class='navbar-nav _navbar-right'>";
				break;

			case 'end':
				return "</ul>";
				break;
		}
	}
}
