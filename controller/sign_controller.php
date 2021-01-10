<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Sign
{
    private $signBar;
    private $twig;
    private $params;

    public function __construct($signed, $fullName)
    {
        if (!isset($this->twig)) {
            $this->loadTemplatesFolder();
        }

        if (isset($params)) {
            $this->params = array();
        }
        if ($signed) {
            $template = "signed.twig";
            $this->params["fullName"] = $fullName;
            $this->signBar = $this->twig->render($template, $this->params);
        } else {
            $this->signBar = file_get_contents("view/html/sign_bar.html");
        }
    }

    private function loadTemplatesFolder()
    {
        require_once 'dependencies/vendor/autoload.php';

        $loader = new FilesystemLoader("view/templates");
        $this->twig = new Environment($loader);
    }

    public function getSignBar()
    {
        return $this->signBar;
    }
}