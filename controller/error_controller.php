<?php

class Error extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = file_get_contents("view/html/error.html");
        $this->renderTemplate();
    }
}