var closeGameSurvey = `
    <div>Are you sure you want to close the game?</div>
    <div>You will not be able to join or review this game again!</div>
    <div>
        <button id="closeGameYes" name="closeGameYes" onclick="closeGameClient()" class="btn _button-default">Yes</button>
        <button id="closeGameNo" name="closeGameNo" onclick="$('#gameSettings').modal('hide');" class="btn _button-default">No</button>
    </div>
`;

var closeGameHtml = `
    <div class="_closeGameMobile w-100">
        <h4>Close Game</h4>
        <button id="closeGameMobile" name="closeGameMobile" onclick="$('#gameSettings ._modal-body').html(closeGameSurvey);" class="btn _button-default btn-block">submit</button>
    </div>
`;

$('#submit').click(function () {
    handleInvite(false);
});

$('#submitMobile').click(function () {
    handleInvite(true);
});

$('#closeGame').click(function () {
    $('#gameSettings ._modal-body').html(closeGameSurvey);
    $('#gameSettings').modal('show');
});

var topicNav = $('#navbarNav > .navbar-nav .nav-item')[1];
var statsNav = $('#navbarNav > .navbar-nav .nav-item')[2];
var invitePlayerNav = $('#navbarNav > .navbar-nav .nav-item')[3];
var gameSettingsNav = $('#navbarNav > .navbar-nav .nav-item')[4];

$(topicNav).addClass('d-lg-none');
$(invitePlayerNav).addClass('d-lg-none');
$(gameSettingsNav).addClass('d-lg-none');

$(topicNav)
    .children('.nav-link')
    .click(function () {
        $('#getTopic').modal('show');
    });

$(statsNav)
    .children('.nav-link')
    .click(function () {
        $('#gameStats').modal('show');
    });

$(invitePlayerNav)
    .children('.nav-link')
    .click(function () {
        $('#invitePlayers').modal('show');
    });

$(gameSettingsNav)
    .children('.nav-link')
    .click(function () {
        $('#gameSettings ._modal-body').html(closeGameHtml);
        $('#gameSettings').modal('show');
    });

/**
 * Player selects cards
 * @param {String} card
 * @param {Number} id
 */
function chooseCard(card, id) {
    var value;

    if (id == 404) value = '?';
    else if (id == 1000) value = "<div class='mdi mdi-coffee'></div>";
    else value = id;

    $(`#card-${card} ._game-field-content-card-responsive-points > div`).html(
        value
    );
    $(`#card-${card} ._game-field-content-card-responsive-symbol > h1`).html(
        value
    );

    $('#chooseCard').modal('hide');
}

$('#players').keyup(function () {
    if ($(this).val().length < 3) $('#submit').prop('disabled', true);
    else $('#submit').prop('disabled', false);
});

$('#playersMobile').keyup(function () {
    if ($(this).val().length < 3) $('#submitMobile').prop('disabled', true);
    else $('#submitMobile').prop('disabled', false);
});

/**
 * Displays gamestats
 * @param {String} stats 
 */
function setGameSettings(stats) {
    for (const key in stats) {
        $(`#${key}-value`).text(stats[key]);
    }
}

/**
 * Update playernames if the join
 * @param {Array} players 
 */
function setPlayerNames(players) {
    for (let i = 0; i < players.length; i++) {
        $(`#user-${i + 1} > div`).html(players[i]);
    }
}
