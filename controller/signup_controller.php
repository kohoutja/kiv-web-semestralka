<?php

class Signup extends Controller
{

    public function defaultMethod()
    {
        $this->renderTemplate();
    }

    protected function renderTemplate()
    {
        $template = "sign.twig";
        $this->params["title"] = "Registrace";
        $this->params["content"] = file_get_contents("view/html/signup_form.html");
        echo $this->twig->render($template, $this->params);
    }

    public function process()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fullName = $_POST['fullName'];

        echo "$email $username $password $fullName";

        if ($this->model->isEmailOccupied($email)) {
            echo 1;
            return;
        }

        $this->model->addUser($email, $username, $password, $fullName);
    }

    public function success()
    {
        $this->params["title"] = "Registrace";
        $this->params["content"] = file_get_contents("view/html/signup_success.html");
        echo $this->twig->render("sign.twig", $this->params);
    }
}
