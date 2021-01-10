<?php

class Home extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = file_get_contents("view/html/home.html");
        $this->renderTemplate();
    }
}