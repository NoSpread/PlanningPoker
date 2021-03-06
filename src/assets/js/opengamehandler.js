/**
 * Get all open games from a user
 */
function getAllGames() {
    return new Promise((resolve, reject) => {
        $.post("../assets/php/logic/games.php", (data, textStatus, jqXHR) => {
            resolve(JSON.parse(data));
        }) 
    })
}

/**
 * Joins a game
 * @param {Number} gameid 
 */
function joinGame(gameid) {
    return new Promise((resolve, reject) => {
        $.post("../assets/php/logic/join.php", {"gameid" : gameid}, (data, textStatus, jqXHR) => {
            resolve(data);
        }) 
    })
}