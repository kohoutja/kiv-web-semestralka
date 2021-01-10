$(document).on('click', '#createReview', function () {
    let criterion1 = $('#criterion1 option:selected').val();
    let criterion2 = $('#criterion2 option:selected').val();
    let criterion3 = $('#criterion3 option:selected').val();
    let overall = $('#overall option:selected').val();

    if (criterion1 === '' || criterion2 === '' || criterion3 === '' || overall === '') {
        $('#reviewError').text("Vyplňte všechna dostupná hodnocení.")
        return;
    }

    $.ajax("/semestralka/review/submit/", {
        data: $('#reviewForm').serialize(),
        type: "POST"
    }).done(function () {
        window.location.replace("/semestralka/review/success");
    });
});

$(document).on('click', '#backToReviews', function () {
    window.location.replace("/semestralka/review/");
});