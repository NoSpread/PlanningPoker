<?php
session_start();
require_once "../assets/php/auth.php";

require_once "../assets/php/Account.php";
require_once "../assets/php/Game.php";
require_once "../assets/php/HeadBuilder.php";

$account = unserialize($_SESSION['USER']);

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
            "src" => "../assets/libraries/lodash/lodash.min.js"
        ]
    ],
    "title" => "index"
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
            "title" => "How To Play",
            "href" => "#",
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
        "name" => $account->username,
        "items" => [
            [
                "title" => "Profile",
                "href" => "#"
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
    <div class="container view d-flex justify-content-center align-items-center px-4 py-4">
        <div style="color: white;">username: <?php echo $account->username; ?></div>
        <br>
        <div style="color: white;">OPEN GAMES:</div>
        <br>
        <?php

        $acc = new Account();
        $acc->getAccountByID($account->id);
        $games = $acc->fetchOpenInvites()->gameInvites;

        foreach ($games as $game) {
            echo "<div style='color: white;'>$game->topic</div><br>";
        }

        ?>
    </div>

    <?php include_once "../assets/php/planningpoker.js.php"; ?>
</body>

</html>