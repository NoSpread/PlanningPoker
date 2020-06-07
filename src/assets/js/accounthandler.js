function deleteAccount(password) {
    $.post(
        '../assets/php/logic/delete.php',
        { password: password },
        (data, textStatus, jqXHR) => {
            return data;
        }
    );
}
