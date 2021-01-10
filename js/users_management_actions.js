$(document).on("click", ".change-role", function () {
    let currentRow = $(this).closest("tr");
    let selected = currentRow.find(".role-select").prop("selectedIndex") + 1;
    let email = currentRow.find(".user-email").text();

    if (selected == null) {
        return;
    }

    $.ajax("/semestralka/users_management/changeRole", {
        data: {email: email, role: selected},
        type: "POST"
    }).done(function () {
        window.location.reload(false);
    });
});