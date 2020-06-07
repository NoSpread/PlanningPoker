<div class="modal fade _modal" id="profile" tabindex="-1" role="dialog" aria-labelledby="profileCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content _modal-content">
            <div class="modal-header _modal-header">
                <h5 class="modal-title" id="profileLongTitle">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body _modal-body">
                <form class="_form pb-3" action="../assets/php/logic/password.php" method="GET">
                    <div class="_form-field text-center">
                        <h4>Change Password</h4>
                    </div>
                    <div class="_form-field">
                        <div class="_input-single">
                            <input id="currPassword" name="currPassword" class="_input" type="password" placeholder="Current Password" autocomplete="off">
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-lock-outline"></i>
                            </div>
                            <div class="_input-icon _icon-right">
                                <i id="revealCurrPassword" class="mdi mdi-24px mdi-eye-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div class="_form-field">
                        <div class="_input-single">
                            <input id="password1" name="password1" class="_input" type="password" placeholder="New Password" autocomplete="off">
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-lock-outline"></i>
                            </div>
                            <div class="_input-icon _icon-right">
                                <i id="revealPassword1" class="mdi mdi-24px mdi-eye-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div class="_form-field">
                        <div class="_input-single">
                            <input id="password2" name="password2" class="_input" type="password" placeholder="Repeat Password" autocomplete="off">
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-lock-outline"></i>
                            </div>
                            <div class="_input-icon _icon-right">
                                <i id="revealPassword2" class="mdi mdi-24px mdi-eye-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input id="submitPassChange" name="submitPassChange" type="submit" value="submit" class="btn _button-default btn-block" disabled>
                    </div>
                    <input id="ref" name="ref" class="d-none" value="<?php echo Utils::getCurrentUrl()['filename']; ?>" autocomplete="off">
                </form>
                <div class="_closeAccount w-100">
                    <h4>Close Account</h4>
                    <button id="submitCloseAccount" name="submitCloseAccount" onclick="$('#profile').modal('hide'); $('#closeAccount').modal('show');" class="btn _button-default btn-block" disabled>submit</button>
                </div>
            </div>
            <div class="modal-footer _modal-footer">
                <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade _modal" id="closeAccount" tabindex="-1" role="dialog" aria-labelledby="closeAccountCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content _modal-content">
            <div class="modal-header _modal-header">
                <h5 class="modal-title" id="closeAccountLongTitle">Close Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span style="color: #ffffff; text-shadow: none;" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body _modal-body">
                <div>Are you sure you want to close your account?</div>
                <div>
                    <button id="closeAccountYes" name="closeAccountYes" class="btn _button-default">Yes</button>
                    <button id="closeAccountNo" name="closeAccountNo" class="btn _button-default">No</button>
                </div>
            </div>
            <div class="modal-footer _modal-footer">
                <button type="button" class="btn _button-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/accounthandler.js"></script>
<script src="../assets/js/profile.js"></script>