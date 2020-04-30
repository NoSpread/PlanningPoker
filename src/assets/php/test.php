<?php

include_once "meta.php";

$array = [];
$meta = new MetaBuilder($array);


$rel = "stylesheet";
$href = "../style.css";
$crossorigin = "anonymous";
$type = "application/430fu38D-32f0-D32ds";
$sizes = "100x100";
$okay = $meta->buildLink($rel, $href, $crossorigin, $type, $sizes);

printf($okay);