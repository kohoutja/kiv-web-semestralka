$(document).on('click', '#signup', function (event) {
    event.preventDefault();
    let interrupt = false;

    interrupt = checkInputEmpty($('#username'), $('#usernameError'));
    interrupt |= checkInputEmpty($('#password'), $('#passwordError'));
    interrupt |= checkInputEmpty($('#passwordConfirm'), $('#passwordConfirmError'));
    interrupt |= checkPasswords($('#password'), $('#passwordConfirm'), $('#passwordConfirmError'));
    interrupt |= checkInputEmpty($('#fullName'), $('#fullNameError'));
    interrupt |= checkEmail($('#email'), $('#emailError'));

    if (interrupt) {
        return false;
    }

    $.ajax('/semestralka/signup/process', {
        data: $('#signupForm').serialize(),
        type: 'POST'
    }).done(function (retVal) {
        if (retVal.charAt(retVal.length - 1) === '1') {
            $('#signupError').text("Zadaný email je již obsazen. Prosím zvolte si jiný.");
            return;
        }
        window.location.replace("/semestralka/signup/success");
    });
});

function checkInputEmpty(input, error) {
    if (!input.val()) {
        input.addClass("form-control-error");
        error.text("Toto pole musí být vyplněné!");
        return true;
    }
    return false;
}

function checkEmail(input, error) {
    if (checkInputEmpty(input, error)) {
        return true;
    }
    if (!input.val().includes('@')) {
        input.addClass("form-control-error");
        error.text("Email musí obsahovat '@'.");
        return true;
    }
    return false;
}

function checkPasswords(password, confirm, confirmError) {
    if (password.val() !== confirm.val()) {
        confirm.addClass("form-control-error");
        password.addClass("form-control-error");
        confirmError.text("Zadaná hesla se musí shodovat!");
        return true;
    }
    return false;
}

function onInputChange(input) {
    if (input.classList.contains("form-control-error")) {
        input.classList.remove("form-control-error");
        document.getElementById(input.id + "Error").innerText = "";
        if (input.id === "passwordConfirm") {
            onInputChange(document.getElementById("password"));
        }
    }
}

function goToHome() {
    window.location.replace("/semestralka/home/");
}

$(document).on('click', '#cancel', function () {
    goToHome();
});