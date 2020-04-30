<?php

include_once "meta.php";

$metaTags = [
    "meta" => [
        [
            "name" => "meta1",
            "content" => "cont1" 
        ],
        [
            "name" => "meta2",
            "content" => "cont2" 
        ]
    ],
    "base" => [
        [
            "href" => "https://bfgsfdgsdf.com",
            "target" => "_blank"
        ],
        [
            "href" => "href",
            "target" => "_blank"
        ]
    ],
    "link" => [
        [
            "rel" => "stylesheet",
            "type" => "text/css",
            "href" => "../style.css",
            "sizes" => "100x100",
            "crossorigin" => "anonymous"
        ],
        [
            "rel" => "stylesheet",
            "type" => "text/css",
            "href" => "../style2.css",
            "sizes" => "200x200",
            "crossorigin" => "anonymous"
        ]
    ],
    "title" => "TEST TITLE",
    "script" => [
        [
            "path" => "bla.js",
            "async" => false
        ],
        [
            "path" => "bl2a.js",
            "async" => true
        ]
    ]
];
$meta = new MetaBuilder($metaTags);

$meta->setTags();
