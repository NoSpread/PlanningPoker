<?php
session_start();
require_once "../assets/php/logic/auth.php";

require_once "../assets/php/classes/Account.php";
require_once "../assets/php/classes/Game.php";
require_once "../assets/php/classes/HeadBuilder.php";

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN']) $account = unserialize($_SESSION['USER']);

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
        ],
        [
            "src" => "../assets/js/opengamehandler.js"
        ]
    ],
    "title" => "PlanningPoker - Home"
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
            "href" => "#",
            "active" => true,
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
        "name" => $_SESSION['LOGGEDIN'] ? $account->username : "",
        "items" => [
            [
                "title" => "Profile",
                "href" => '#'
            ]
        ]
    ]
];
$nav = new NavBuilder($navArr);
?>

<!DOCTYPE html>
<html lang="en">
<?php $head->setTags() ?>

<body>
    <?php $nav->setNav(); ?>

    <div class="container view px-4 py-4">
        <?php
        if (!$_SESSION['LOGGEDIN']) {
            include_once "../components/create_account_box.php";
        } else {
            include_once "../components/play_box.php";
            include_once "../components/invite_box.php";
        }
        ?>
    </div>

    <?php
    if (isset($_SESSION['LASTERROR']) && sizeof($_SESSION['LASTERROR']) > 0)
        include_once "../components/last_error.php";
    ?>

    <?php include_once "../assets/php/planningpoker.js.php"; ?>
    <?php include_once "../components/profile.php"; ?>
</body>

</html>