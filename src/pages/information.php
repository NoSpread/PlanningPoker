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
        ]
    ],
    "title" => "PlanningPoker - Information"
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
            "href" => "#",
            "active" => true,
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
        if (!$_SESSION['LOGGEDIN'])
            include_once "../components/create_account_box.php";
        ?>

        <div class="_information-header">
            <div class="_information-header-brand">
                <svg id="poker-chip" style="width:50px;height:50px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M23,12C23,18.08 18.08,23 12,23C5.92,23 1,18.08 1,12C1,5.92 5.92,1 12,1C18.08,1 23,5.92 23,12M13,4.06C15.13,4.33 17.07,5.45 18.37,7.16L20.11,6.16C18.45,3.82 15.86,2.3 13,2V4.06M3.89,6.16L5.63,7.16C6.93,5.45 8.87,4.33 11,4.06V2C8.14,2.3 5.55,3.82 3.89,6.16M2.89,16.1L4.62,15.1C3.79,13.12 3.79,10.88 4.62,8.9L2.89,7.9C1.7,10.5 1.7,13.5 2.89,16.1M11,19.94C8.87,19.67 6.93,18.55 5.63,16.84L3.89,17.84C5.55,20.18 8.14,21.7 11,22V19.94M20.11,17.84L18.37,16.84C17.07,18.55 15.13,19.67 13,19.94V21.94C15.85,21.65 18.44,20.16 20.11,17.84M21.11,16.1C22.3,13.5 22.3,10.5 21.11,7.9L19.38,8.9C20.21,10.88 20.21,13.12 19.38,15.1L21.11,16.1M15,12L12,7L9,12L12,17L15,12Z" />
                </svg>
                <h1>PlanningPoker</h1>
            </div>
            <div class="_information-header-description">
                <div>Planning poker, also called Scrum poker, is a consensus-based, playful estimation technique that is mostly used to estimate the effort or relative size of development goals in software development.</div>
            </div>
        </div>
        <div class="_information-content row">
            <div class="col _information-content-image d-none d-md-flex d-lg-flex d-xl-flex">
                <svg style="width:150px;height:150px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M16 17V19H2V17S2 13 9 13 16 17 16 17M12.5 7.5A3.5 3.5 0 1 0 9 11A3.5 3.5 0 0 0 12.5 7.5M15.94 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13M15 4A3.39 3.39 0 0 0 13.07 4.59A5 5 0 0 1 13.07 10.41A3.39 3.39 0 0 0 15 11A3.5 3.5 0 0 0 15 4Z" />
                </svg>
            </div>
            <div class="col _information-content-description _description-left">
                <div>In Planning Poker, members of the group make estimates by playing numbered cards face down at the table instead of speaking them out loud. </div>
            </div>
        </div>
        <div class="_information-content row">
            <div class="col _information-content-description _description-right">
                <div>The cards are revealed, and the estimates are then discussed. By hiding the numbers in this way, the group can avoid the cognitive distortion of anchoring where the first number spoken aloud sets a precedent for subsequent estimates. </div>
            </div>
            <div class="col _information-content-image d-none d-md-flex d-lg-flex d-xl-flex">
                <svg style="width:150px;height:150px" viewBox="0 0 24 24">
                    <path id="poker-card" fill="currentColor" d="M11.19,2.25C11.97,2.26 12.71,2.73 13,3.5L18,15.45C18.09,15.71 18.14,16 18.13,16.25C18.11,17 17.65,17.74 16.9,18.05L9.53,21.1C9.27,21.22 9,21.25 8.74,21.25C7.97,21.23 7.24,20.77 6.93,20L1.97,8.05C1.55,7.04 2.04,5.87 3.06,5.45L10.42,2.4C10.67,2.31 10.93,2.25 11.19,2.25M14.67,2.25H16.12A2,2 0 0,1 18.12,4.25V10.6L14.67,2.25M20.13,3.79L21.47,4.36C22.5,4.78 22.97,5.94 22.56,6.96L20.13,12.82V3.79M11.19,4.22L3.8,7.29L8.77,19.3L16.17,16.24L11.19,4.22M8.65,8.54L11.88,10.95L11.44,14.96L8.21,12.54L8.65,8.54Z" />
                </svg>
            </div>
        </div>
    </div>
    <?php include_once "../assets/php/planningpoker.js.php"; ?>
    <?php include_once "../components/profile.php"; ?>
</body>

</html>