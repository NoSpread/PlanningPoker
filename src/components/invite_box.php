<div id="history-box" class="main-box">
    <div class="main-box-header">
        <div class="main-box-header-content">History</div>
    </div>
    <div class="main-box-content">
        <div class="_invites">
            <h4 class="text-center">Game History</h4>
        </div>
    </div>
    <div class="main-box-footer">
        <div class="main-box-footer-content"></div>
        <div class="main-box-expand">
            <i class="mdi mdi-36px"></i>
        </div>
    </div>
</div>
<div id="invites-box" class="main-box">
    <div class="main-box-header">
        <div class="main-box-header-content">Invites</div>
    </div>
    <div class="main-box-content">
        <div class="_invites">
            <h4 class="text-center">Open Games</h4>
        </div>
    </div>
    <div class="main-box-footer">
        <div class="main-box-footer-content"></div>
        <div class="main-box-expand">
            <i class="mdi mdi-36px"></i>
        </div>
    </div>
</div>

<script>
    var gameIds = [];
    async function setAllGames() {
        const games = await getAllGames();
        const user = "<?php echo $account->username ?>";

        games['owngames'].forEach(x => {
            if (!gameIds.filter(y => {
                    return y == x.id
                }).length) {
                gameIds.push(x.id);

                $('#history-box ._invites').append(`
                <div class="_invites-open">
                    <div class="card _card">
                        <div class="card-body">
                            <h6 class="card-title mb-4">
                                <a class="a-none _invites-open-host">${user}</a>
                            </h6>
                            <h6 class="card-subtitle mt-2 mb-2 text-muted">GameID: ${x.id}</h6>
                            <p class="card-text">${x.topic}</p>
                            <a href="./game.php?id=${x.id}">
                                <button type="button" class="btn _button-default btn-block">Join</button>
                            </a>
                        </div>
                    </div>
                </div>
            `);
            }

        });

        games['invitedgames'].forEach(x => {
            if (!gameIds.filter(y => {
                    return y == x.id
                }).length) {
                gameIds.push(x.id);

                $('#invites-box ._invites').append(`
                <div class="_invites-open">
                    <div class="card _card">
                        <div class="card-body">
                            <h6 class="card-title mb-4">
                                <a class="a-none _invites-open-host">${x.inviter}</a>
                            </h6>
                            <h6 class="card-subtitle mt-2 mb-2 text-muted">GameID: ${x.id}</h6>
                            <p class="card-text">${x.topic}</p>
                            <a onclick="handleJoinGame(${x.id})">
                                <button type="button" class="btn _button-default btn-block">Accept</button>
                            </a>
                        </div>
                    </div>
                </div>
            `);
            }
        });
    }

    function handleJoinGame(gameid) {
        joinGame(gameid).then(joindata => {
            document.location.replace(`./game.php?id=${gameid}`);
        });
    }

    setAllGames();

    setInterval(function() {
        setAllGames();
    }, 10000);
</script>