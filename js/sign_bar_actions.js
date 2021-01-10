function submitAction(action) {
    window.location.replace("/semestralka/" + action + "/");
}

function signout() {
    $.ajax({
        url: "/semestralka/signin/signoutUser/"
    }).done(function () {
        window.location.replace("/semestralka/home/");
    });
}