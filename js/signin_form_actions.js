$(document).on('click', '#signin', function (event) {
    event.preventDefault();

    let interrupt = false;
    interrupt |= checkEmail($('#email'), $('#emailError'));
    interrupt |= checkInputEmpty($('#password'), $('#passwordError'));

    if (interrupt) {
        return false;
    }

    $.ajax("/semestralka/signin/verify", {
        data: $("#signinForm").serialize(),
        type: "POST"
    }).done(function (retVal) {
        if (retVal !== '0') {
            signinError("Uživatelský E-mail nebo heslo nejsou správné. Zkuste to prosím znovu.");
            return;
        }

        window.location.replace("/semestralka/signin/signinUser");
        window.location.replace("/semestralka/home");
    });
});

function signinError(msg) {
    $('#signinError').text(msg);
}