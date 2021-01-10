<?php

class About extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = file_get_contents("view/html/about.html");
        $this->renderTemplate();
    }
}