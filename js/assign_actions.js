$(document).on("click", ".add-reviewer", function () {
    let currentRow = $(this).closest("tr");
    let postId = currentRow.attr('id');
    let reviewerId = currentRow.find(".reviewer-select").val();
    
    if (reviewerId == null || reviewerId === "- vyberte -") {
        showAssignError("Recenzent nebyl vybrán.");
        return;
    }

    $.ajax("/semestralka/assign/addReviewer", {
        data: {post: postId, reviewer: reviewerId},
        type: "POST"
    }).done(function (retVal) {
        switch (retVal.charAt(retVal.length - 1)) {
            case "0":
                window.location.reload(false);
                break;
            case "1":
                showAssignError("Přidání selhalo.");
                break;
            case "2":
                showAssignError("Recenzent nebyl vybrán.");
                break;
            default:
                showAssignError("Neznámá chyba.");
                break;
        }
    });
});

function showAssignError(message) {
    $("#assignError").text(message);
}