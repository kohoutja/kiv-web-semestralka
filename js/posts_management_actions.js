$(document).on("click", "#accept", function () {
    let postId = $(this).closest("tr").attr("id");
    alert(postId);

    $.ajax("/semestralka/posts_management/accept/", {
        data: {postId: postId},
        type: "POST"
    }).done(function (retVal) {
        alert(retVal);
        window.location.reload(false);
    });
});

$(document).on("click", "#deny", function () {
    let postId = $(this).closest("tr").attr("id");
    alert(postId);

    $.ajax("/semestralka/posts_management/deny/", {
        data: {postId: postId},
        type: "POST"
    }).done(function (retVal) {
        alert(retVal);
        window.location.reload(false);
    });
});