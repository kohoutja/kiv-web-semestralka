<?php

class Application
{
    protected $controller = "home";

    protected $method = "defaultMethod";

    protected $url_params = [];

    public function __construct()
    {
        $this->parseUrl();
        $this->createPage();

        call_user_func_array([$this->controller, $this->method], $this->url_params);
    }

    private function parseURL()
    {
        $url = explode('/', filter_var(rtrim(urldecode($_SERVER['REQUEST_URI']), '/')), FILTER_SANITIZE_URL);

        if (isset($url[2])) {
            if (file_exists("controller/" . $this->controller . "_controller.php")) {
                $this->controller = $url[2];
                unset($url[2]);

                if (isset($url[3])) {
                    $this->method = $url[3];
                    unset($url[3]);

                    $this->url_params = $url ? array_values($url) : array();
                } else {
                    $this->method = "defaultMethod";
                }
            } else {
                $this->controller = "error";
                $this->method = "defaultMethod";
            }
        }
    }

    private function createPage()
    {
        require_once "controller/" . $this->controller . "_controller.php";
        $this->controller = new $this->controller;
        if (!method_exists($this->controller, $this->method)) {
            $this->method = "methodName";
        }

        if (!empty($this->url_params)) {
            $this->controller->setUrlParams($this->url_params);
        }
    }
}
