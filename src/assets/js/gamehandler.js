function pickCard(cardid, gameid) {
    return new Promise((resolve, reject) => {
        $.post(
            '../assets/php/logic/drawcard.php',
            { cardid: cardid, gameid: gameid },
            (data, textStatus, jqXHR) => {
                resolve(data);
            }
        );
    });
}

function getGameData(gameid) {
    return new Promise((resolve, reject) => {
        $.post(
            '../assets/php/logic/gamedata.php',
            { gameid: gameid },
            (data, textStatus, jqXHR) => {
                resolve(data);
            }
        );
    });
}

function closeGame(gameid) {
    return new Promise((resolve, reject) => {
        $.post(
            '../assets/php/logic/close.php',
            { gameid: gameid },
            (data, textStatus, jqXHR) => {
                resolve(data);
            }
        );
    });
}

function inviteGame(gameid, players) {
    return new Promise((resolve, reject) => {
        $.post(
            '../assets/php/logic/invite.php',
            { gameid: gameid, players: players },
            (data, textStatus, jqXHR) => {
                resolve(data);
            }
        );
    });
}

function getCards(gameid) {
    return new Promise((resolve, reject) => {
        $.post(
            '../assets/php/logic/cards.php',
            { gameid: gameid },
            (data, textStatus, jqXHR) => {
                resolve(JSON.parse(data));
            }
        );
    });
}

