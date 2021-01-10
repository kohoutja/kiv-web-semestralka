<?php

class Rules extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = file_get_contents("view/html/rules.html");
        $this->renderTemplate();
    }
}