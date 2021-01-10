<?php


class Create_post extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = file_get_contents("view/html/post_form.html");
        $this->renderTemplate();
    }

    public function submit()
    {
        $title = $_POST["title"];
        $description = $_POST["description"];

        if ($title == null || $title == "") {
            die(json_encode(array("message" => "EMPTY_TITLE", "code" => 1)));
        }
        if ($description == null || $description == "") {
            die(json_encode(array("message" => "EMPTY_DESCRIPTION", "code" => 2)));
        }

        $filename = $_FILES["attachment"]["name"];

        if ($filename == null || $filename == "") {
            $this->model->submitPost($_SESSION["id"], $title, $description, ""); // TODO
            die(json_encode(array("message" => "SUCCESS_NO_ATTACHMENT", "code" => 0)));
        }

        $uploadDestination = "uploads/$filename";
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if ($extension != "pdf") {
            die(json_encode(array("message" => "ATTACHMENT_TYPE_ERROR", "code" => 3)));
        }

        $size = $_FILES["attachment"]["size"];
        if ($size > 5000000) {
            die(json_encode(array("message" => "ATTACHMENT_SIZE_ERROR", "code" => 4)));
        }

        $tmpFile = $_FILES["attachment"]["tmp_name"];
        if (move_uploaded_file($tmpFile, $uploadDestination)) {
            $this->model->submitPost($_SESSION["id"], $title, $description, $filename);
            die(json_encode(array("message" => "SUCCESS", "code" => 0)));
        } else {
            die(json_encode(array("message" => "ATTACHMENT_UPLOAD_ERROR", "code" => 5)));
        }
    }

    public function success()
    {
        $this->prepare_parts();
        $this->params["content"] = file_get_contents("view/html/post_success.html");
        $this->renderTemplate();

    }

}