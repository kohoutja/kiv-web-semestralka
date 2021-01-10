function createPost() {
    let fileContent = $("#attachment").prop("files")[0];
    let formContent = new FormData($("#createPostForm")[0]);
    formContent.append("attachment", fileContent);

    $.ajax("/semestralka/create_post/submit/", {
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        data: formContent,
        type: "POST"
    }).done(function (retVal) {
        try {
            let array = JSON.parse(retVal);

            switch (array['code']) {
                case 0:
                    window.location.replace("/semestralka/create_post/success");
                    break;
                case 1:
                    showError("Název nesmí být prázdný");
                    break;
                case 2:
                    showError("Popis nesmí být prázdný");
                    break;
                case 3:
                    showError("Zvolený soubor není požadovaného (PDF)");
                    break;
                case 4:
                    showError("Zvolený soubor je příliš velký");
                    break;
                case 5:
                    showError("Nahrání souboru na server se nezdrařilo");
                    break;
            }
        } catch (e) {
            // empty
        }
    })
}

function createNew() {
    window.location.replace("/semestralka/create_post/");
}

function showError(message) {
    $("createPostError").text(message);
}