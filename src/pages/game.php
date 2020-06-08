<?php

session_start();
require_once "../assets/php/logic/auth.php";

require_once "../assets/php/classes/Account.php";
require_once "../assets/php/classes/Game.php";
require_once "../assets/php/classes/HeadBuilder.php";
require_once "../assets/php/classes/Utils.php";

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN']) $account = unserialize($_SESSION['USER']);
if (!isset($_GET['id'])) Utils::redirect("index.php");

$acc = new Account();
//$acc->getAccountByID($account->id);
//$acc->fetchOwnGames();

$game = new Game();
$game->load($_GET['id']);


if (!$acc->partOfGame($game->id, $account->id)) {
    $_SESSION['LASTERROR'][] = "You are not part of this game!";
    Utils::redirect("index.php");
}

function getUserIndex($game, $user)
{
    $user_client_id = 0;
    foreach ($game->players as $player) {
        $user_client_id++;
        if ($player->id == $user->id) {
            echo $user_client_id++;
            break;
        }
    }
}

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
            "src" => "../assets/js/gamehandler.js"
        ]
    ],
    "title" => "PlanningPoker - Game"
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
            "href" => './',
            "active" => false,
            "disabled" => false
        ],
        [
            "title" => "Topic",
            "href" => '#',
            "active" => false,
            "disabled" => false
        ],
        [
            "title" => "Game Stats",
            "href" => '#',
            "active" => false,
            "disabled" => false
        ],
        [
            "title" => "Invite",
            "href" => '#',
            "active" => false,
            "disabled" => $game->players[0]->username !== $account->username ? true : false
        ],
        [
            "title" => "Game Settings",
            "href" => '#',
            "active" => false,
            "disabled" => $game->players[0]->username !== $account->username ? true : false
        ],
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
        <div class="_game">
            <div class="row">
                <div class="col _game-field">
                    <?php
                    for ($key = 0; $key < 4; $key++) {
                        $user_class = $key + 1;
                        echo "<div id=\"user-{$user_class}\" class=\"_game-field-user\">";
                        if (array_key_exists($key, $game->players)) {
                            echo "<div>{$game->players[$key]->username}</div>";
                        } else {
                            echo "<div>             </div>";
                        }
                        echo "</div>";
                    }

                    ?>
                    <!-- <div id="user-1" class="_game-field-user">
                        <div>user1</div>
                    </div>
                    <div id="user-2" class="_game-field-user">
                        <div>aWoRdWiThSoMeZeIcHeN</div>
                    </div>
                    <div id="user-3" class="_game-field-user">
                        <div>user3</div>
                    </div>
                    <div id="user-4" class="_game-field-user">
                        <div>user4</div>
                    </div> -->
                    <div class="_game-field-content">
                        <div class="row row-cols-1 row-cols-lg-2">
                            <div class="col">
                                <div id="card-1" class="_game-field-content-card">
                                    <div class="_game-field-content-card-responsive">
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                        <div class="_game-field-content-card-responsive-symbol">
                                            <h1>Waiting</h1>
                                        </div>
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="card-2" class="_game-field-content-card">
                                    <div class="_game-field-content-card-responsive">
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                        <div class="_game-field-content-card-responsive-symbol">
                                            <h1>Waiting</h1>
                                        </div>
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="card-3" class="_game-field-content-card">
                                    <div class="_game-field-content-card-responsive">
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                        <div class="_game-field-content-card-responsive-symbol">
                                            <h1>Waiting</h1>
                                        </div>
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="card-4" class="_game-field-content-card">
                                    <div class="_game-field-content-card-responsive">
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                        <div class="_game-field-content-card-responsive-symbol">
                                            <h1>Waiting</h1>
                                        </div>
                                        <div class="_game-field-content-card-responsive-points">
                                            <div>
                                                <div class="mdi mdi-loading"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 d-none d-lg-flex _game-feed">
                    <div class="_game-feed-topic">
                        <h4>Topic</h4>
                        <div class="_game-feed-topic-text">
                            <?php echo $game->topic ?>
                        </div>
                    </div>
                    <div class="_game-feed-host">
                        <h4>Host</h4>
                        <div class="_game-feed-host-username">
                            <?php echo $game->players[0]->username ?>
                        </div>
                    </div>
                    <div class="_game-feed-invite w-100 <?php if ($game->players[0]->username !== $account->username) echo 'd-none'; ?>">
                        <form class="_form">
                            <div class="_form-field">
                                <div class="_input-single">
                                    <input id="players" class="_input" type="text" placeholder="Invite Players (seperated by ,)" name="players" autocomplete="off">
                                    <div class="_input-icon _icon-left">
                                        <i class="mdi mdi-24px mdi-account-multiple-outline"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <input id="submit" name="submit" value="submit" class="btn _button-default btn-block" disabled>
                            </div>
                        </form>
                    </div>
                    <div class="_game-feed-gameSettings w-100 <?php if ($game->players[0]->username !== $account->username) echo 'd-none'; ?>">
                        <h4>Close Game</h4>
                        <button id="closeGame" name="closeGame" class="btn _button-default btn-block">submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade _modal" id="chooseCard" tabindex="-1" role="dialog" aria-labelledby="chooseCardCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content _modal-content">
                    <div class="modal-header _modal-header">
                        <h5 class="modal-title" id="chooseCardLongTitle">Choose a Card</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body _modal-body">
                        <div id="chooseCards" class="row row-cols-6 text-center">
                            <a id="0">
                                <div class="col">0</div>
                            </a>
                            <a id="1">
                                <div class="col">1</div>
                            </a>
                            <a id="2">
                                <div class="col">2</div>
                            </a>
                            <a id="3">
                                <div class="col">3</div>
                            </a>
                            <a id="5">
                                <div class="col">5</div>
                            </a>
                            <a id="8">
                                <div class="col">8</div>
                            </a>
                            <a id="13">
                                <div class="col">13</div>
                            </a>
                            <a id="20">
                                <div class="col">20</div>
                            </a>
                            <a id="40">
                                <div class="col">40</div>
                            </a>
                            <a id="100">
                                <div class="col">100</div>
                            </a>
                            <a id="404">
                                <div class="col">?</div>
                            </a>
                            <a id="1000">
                                <div class="col">
                                    <div class="mdi mdi-coffee"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer _modal-footer">
                        <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade _modal" id="getTopic" tabindex="-1" role="dialog" aria-labelledby="getTopicCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content _modal-content">
                    <div class="modal-header _modal-header">
                        <h5 class="modal-title" id="getTopicLongTitle">Topic</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body _modal-body">
                        <?php echo $game->topic ?>
                    </div>
                    <div class="modal-footer _modal-footer">
                        <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade _modal" id="gameStats" tabindex="-1" role="dialog" aria-labelledby="gameStatsCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content _modal-content">
                    <div class="modal-header _modal-header">
                        <h5 class="modal-title" id="gameStatsLongTitle">Game Stats</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body _modal-body">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <h4>Max Card Value</h4>
                            <h5 id="max-value" class="_game-stats-value mb-4">
                                No cards played yet.
                            </h5>
                            <h4>Mean Card Value</h4>
                            <h5 id="mean-value" class="_game-stats-value a-none mb-4">
                                No cards played yet.
                            </h5>
                            <h4>Min Card Value</h4>
                            <h5 id="min-value" class="_game-stats-value a-none">
                                No cards played yet.
                            </h5>
                        </div>
                    </div>
                    <div class="modal-footer _modal-footer">
                        <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade _modal" id="invitePlayers" tabindex="-1" role="dialog" aria-labelledby="invitePlayersCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content _modal-content">
                    <div class="modal-header _modal-header">
                        <h5 class="modal-title" id="invitePlayersLongTitle">Invite Players</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body _modal-body">
                        <form class="_form">
                            <div class="_form-field">
                                <div class="_input-single">
                                    <input id="playersMobile" name="playersMobile" class="_input" type="text" placeholder="Invite Players (seperated by ,)" autocomplete="off">
                                    <div class="_input-icon _icon-left">
                                        <i class="mdi mdi-24px mdi-account-multiple-outline"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <input id="submitMobile" name="submitMobile" value="submit" class="btn _button-default btn-block" disabled>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer _modal-footer">
                        <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade _modal" id="gameSettings" tabindex="-1" role="dialog" aria-labelledby="gameSettingsCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content _modal-content">
                    <div class="modal-header _modal-header">
                        <h5 class="modal-title" id="gameSettingsLongTitle">Game Settings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body _modal-body">
                        close game
                    </div>
                    <div class="modal-footer _modal-footer">
                        <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade _modal" id="inviteList" tabindex="-1" role="dialog" aria-labelledby="inviteListCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content _modal-content">
                    <div class="modal-header _modal-header">
                        <h5 class="modal-title" id="inviteListLongTitle">Invite List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body _modal-body">
                        invite list
                    </div>
                    <div class="modal-footer _modal-footer">
                        <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_SESSION['LASTERROR']) && sizeof($_SESSION['LASTERROR']) > 0)
        include_once "../components/last_error.php";
    ?>

    <?php include_once "../assets/php/planningpoker.js.php"; ?>
    <script src="../assets/js/game.js"></script>

    <script>
        var user_client_id = <?php getUserIndex($game, $account) ?>;

        $(`#card-${user_client_id} ._game-field-content-card-responsive-symbol > h1`).text('Choose');

        $(`#card-${user_client_id} > ._game-field-content-card-responsive`).click(function() {
            $('#chooseCard').modal('show');
        });

        $('#chooseCards a').click(function() {
            chooseCard(<?php getUserIndex($game, $account) ?>, this.id);
            pickCard(this.id, <?php echo $game->id ?>);
        });

        function closeGameClient() {
            closeGame(<?php echo $game->id ?>);
            document.location.replace("./");
        }

        function handleInvite(mobile = false) {
            inviteGame(<?php echo $game->id ?>, mobile ? $('#playersMobile').val() : $('#players').val()).then(invitedata => {
                if (!invitedata) {
                    $('#inviteList ._modal-body').html(`
                    <div>Invite Players responded unsuccessfully.</div>
                    <div>One of the players is already invited or does not exist.</div>
                `);
                    mobile ? $('#playersMobile').val('') : $('#players').val('');
                } else {
                    var players = []
                    if (mobile) {
                        players = $('#playersMobile').val().split(",");
                        $('#playersMobile').val('');
                    } else {
                        players = $('#players').val().split(",");
                        $('#players').val('');
                    }
                    $('#inviteList ._modal-body').html("<div>Invite Players successfully.</div>");
                    players.forEach(x => {
                        $('#inviteList ._modal-body').append(`<div>${x}</div>`);
                    });
                }
                $('#invitePlayers').modal('hide');
                $('#inviteList').modal('show');
            });
        }

        function refreshCards(init = false) {
            getCards(<?php echo $game->id ?>)
                .then(carddata => {
                    console.log(carddata);
                    if (carddata['userdata'].lenght !== 0) {
                        for (card of carddata['userdata']) {
                            if (card['userid'] != <?php echo $account->id ?> || init) {
                                chooseCard(card["index"], card["card"]);
                            }
                        }
                    }

                    if (carddata['stats'].lenght == undefined) {
                        setGameSettings(carddata['stats']);
                    }

                    if (carddata['players'].lenght !== 0) {
                        setPlayerNames(carddata['players']);
                    }

                    if (!carddata['game']) {
                        document.location.href = "./";
                    }
                })
        }
        refreshCards(true);
        setInterval(refreshCards, 8000)
    </script>
</body>

</html>