var usernameCharacters =
    'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890ßäöüÄÖÜ';
var passwordCharacters =
    usernameCharacters + '[`!@#§$%^&*()_+-=[]{};\':"\\|,.<>/?~]';

var letters = /[a-zA-Z]/;
var digits = /\d+/;
var specialCharacters = /[`!@§$#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

function usernameCheck(username) {
    for (let i = 0; i < username.length; i++) {
        if (!usernameCharacters.includes(username.charAt(i)))
            return `Character ${username.charAt(i)} is not allowed.`;
    }

    if (username.length < 3) return 'Username must be at least 3 characters.';

    if (username.length > 20) return 'Username is too long.';

    return true;
}

function emailCheck(email) {
    return /.+@.+\..+/.test(email) || 'Requirements: E-Mail must be valid';
}

function passwordCheck(password) {
    for (let i = 0; i < password.length; i++) {
        if (!passwordCharacters.includes(password.charAt(i)))
            return `Character ${password.charAt(i)} is not allowed.`;
    }

    if (!letters.test(password))
        return 'Password must contain at least 1 letter.';

    if (!digits.test(password))
        return 'Password must contain at least 1 digit.';

    if (!specialCharacters.test(password))
        return 'Password must contain at least 1 special character.';

    if (password.length < 8) return 'Password must be at least 8 characters.';

    return true;
}

function passwordCompare(password_1, password_2) {
    if (password_2.length < 1) return 'Requirements: Passwords must match';
    return password_1 === password_2 || 'Requirements: Passwords must match';
}

function handleSubmit(id, args) {
    var checksum = 0;

    for (const i in args) args[i] ? checksum++ : null;

    if (checksum == Object.keys(args).length)
        $(`#${id}`).prop('disabled')
            ? $(`#${id}`).prop('disabled', false)
            : null;
    else
        $(`#${id}`).prop('disabled')
            ? null
            : $(`#${id}`).prop('disabled', true);

    return true;
}
