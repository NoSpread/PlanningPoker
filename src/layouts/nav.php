<?php
class NavBuilder
{
	private $navArr = [
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
			//     "id" => Boolean,
			//     "title" => String,
			//     "href": String,
			//     "exact": Boolean
			// ]
		],
		"icons" => [
			// [
			//     "id" => Number,
			//     "href" => String,
			//     "class" => String,
			//     "tooltip" => [
			//     "state" => Boolean,
			//     "title" => String
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
					"id" => 0,
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

		foreach ($this->navArr as $key => $tags) {
			switch ($key) {
				case "brand":
					$this->buildBrand($tags['title'], $tags['href'], $tags['src'], $tags['alt'], $tags['width'], $tags['height']);
					break;
				case "routes":
					break;
				case "icons":
					break;
				case "profile":
					break;
			}
		}


		print('</nav>');
	}

	private function buildBrand(string $title, string $href = "", string $src = "", string $alt = "", int $width = 0, int $height = 0)
	{
		$ret = "<a class='navbar-brand' href='" . !empty($href) ? $href : "#" . "'>";

		if (!empty($src) && $width > 0 && $height > 0) {
			$ret .= PHP_EOL . "<img src='{$src}' alt='" . !empty($alt) ? $alt : "image" . "' width='{$width}' height='{$height}' class='d-inline-block align-top' />" . PHP_EOL;
		}

		if (empty($title)) return false;
		$ret .= $title;

		$ret .= "</a>";

		return $ret . PHP_EOL;
	}

	private function buildRoutes()
	{
	}

	private function buildLinks()
	{
	}

	private function buildProfile()
	{
	}
}
?>

<nav class="navbar navbar-expand-lg navbar-dark _bg-dark">
	<div class="container">
		<a class="navbar-brand" href="#">Navbar</a>
	</div>
</nav>

<script>
	let navCSS = "<link rel='stylesheet' href='../assets/dist/nav.min.css' type='text/css'>";
	$("head").append(navCSS);
</script>