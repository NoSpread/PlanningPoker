<div id="play-box" class="main-box main-box-open">
    <div class="main-box-header">
        <div class="main-box-header-content">Play</div>
    </div>
    <div class="main-box-content">
        <div class="_play">
            <div class="_play-header">
                <h4>Start a Game</h4>
            </div>
            <div class="_play-content">
                <div class="_play-content-player flex-column flex-lg-row">
                    <h5 class="pr-2">Playing as:</h5>
                    <h5 class="_play-content-player-username">
                        <?php print($account->username) ?>
                    </h5>
                </div>
                <form class="_form w-100" action="../assets/php/logic/create.php" method="GET">
                    <div class="_form-field">
                        <div class="_input-single">
                            <input id="topic" class="_input" type="text" placeholder="Topic" name="topic" autocomplete="off"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-comment-text-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div class="_form-field">
                        <div class="_input-single">
                            <input id="players" class="_input" type="text" placeholder="Invite Players (seperated by ,)" name="players" autocomplete="off"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-account-multiple-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 pt-2">
                        <button id="submitStartGame" name="submitStartGame" type="submit" class="btn _button-default btn-block" disabled>
                            <h4>START</h4>
                        </button>
                    </div>
                </form>
            </div>
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
    $('#topic').keyup(function() {
        if ($(this).val().length == 0) $('#submitStartGame').prop('disabled', true);
        else $('#submitStartGame').prop('disabled', false);
    });
</script>