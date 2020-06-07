var profileDropdownNav = $(
    '._navbar-profile .dropdown-item._navbar-profile-dropdown-item'
)[0];

$(profileDropdownNav).click(function () {
    $('#profile').modal('show');
});

$('i#revealCurrPassword').click(function () {
    revealPassword(this.id);
});

$('i#revealPassword1').click(function () {
    revealPassword(this.id);
});

$('i#revealPassword2').click(function () {
    revealPassword(this.id);
});

var inputChecksums = {
    checksums: {
        currPassword: false,
        password1: false,
        password2: false
    },
    get get() {
        return this.checksums;
    },
    set set(args) {
        this.checksums[args.id] = args.bool;
        handleSubmit('submitPassChange', this.checksums);
    }
};

$('#currPassword').keyup(function () {
    inputChecksums.set = {
        id: this.id,
        bool: $(this).val().length > 0 ? true : false
    };

    if ($(this).val().length > 0)
        $('#submitCloseAccount').prop('disabled', false);
    else $('#submitCloseAccount').prop('disabled', true);
});

addAfter(
    'password1',
    'info',
    'Requirements: 8+ characters, 1+ digit, 1+ special character'
);
addAfter('password2', 'info', 'Requirements: Passwords must match');

$('#password1').keyup(function () {
    let check = passwordCheck($(this).val());

    inputChecksums.set = {
        id: this.id,
        bool: check == true ? true : false
    };

    const compareCheck = passwordCompare(
        $('#password1').val(),
        $('#password2').val()
    );
    if (compareCheck == true)
        changeAfter(
            'password2',
            'Requirements: Passwords must match',
            'success'
        );
    else changeAfter('password2', compareCheck, 'error');

    if (check == true) {
        return changeAfter(
            'password1',
            'Requirements: 8+ characters, 1+ digit, 1+ special character',
            'success'
        );
    }

    return changeAfter('password1', check, 'error');
});

$('#password2').keyup(function () {
    let check = passwordCompare($('#password1').val(), $(this).val());

    inputChecksums.set = {
        id: this.id,
        bool: check == true ? true : false
    };

    if (check == true)
        return changeAfter(
            'password2',
            'Requirements: Passwords must match',
            'success'
        );

    return changeAfter('password2', check, 'error');
});

$('#closeAccountYes').click(function () {
    document.location.href =
        '../assets/php/logic/delete.php?password=' + $('#currPassword').val();
});

$('#closeAccountNo').click(function () {
    $('#closeAccount').modal('hide');
});
