<?php


class Signin extends Controller
{
    public function defaultMethod()
    {
        $this->renderTemplate();
    }

    protected function renderTemplate()
    {
        $template = "sign.twig";
        $this->params["title"] = "Přihlášení";
        $this->params["content"] = file_get_contents("view/html/signin_form.html");
        echo $this->twig->render($template, $this->params);
    }

    public function verify()
    {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $code = $this->model->signin($email, $password);
        echo $code;
    }

    function signinUser()
    {
        header("Refresh:0");
    }

    function signoutUser()
    {
        $_SESSION["signed"] = false;
    }
}