/**
 * Push update to server if client selected a card
 * @param {Number} cardid 
 * @param {Number} gameid 
 */
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

/**
 * Selects all gamedata from the server to be displayed on the game page
 * @param {Numver} gameid 
 */
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

/**
 * Sets enddate in DB
 * @param {Number} gameid 
 */
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

/**
 * Invites one or multiple players to a game
 * @param {Number} gameid 
 * @param {String} players 
 */
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

/**
 * Collects all cards from DB
 * @param {Number} gameid 
 */
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

