<?php

require_once "navigation_controller.php";
require_once "sign_controller.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected $twig;

    protected $navigation;

    protected $params;

    protected $loginBar;

    protected $database;

    protected $url_params = [];

    protected Model $model;

    public function __construct()
    {
        $this->params = array();
        $this->params["title"] = "Knižní konference";
        $this->params["content"] = null;
        $this->params["navigation"] = null;
        $this->params["login"] = null;

        $this->navigation = new Navigation();
        $this->initModel();

        $this->loadTemplatesFolder();
    }

    public function setUrlParams($url_params)
    {
        $this->url_params = $url_params;
    }

    private function loadTemplatesFolder()
    {
        require_once 'dependencies/vendor/autoload.php';

        $loader = new FilesystemLoader("view/templates");
        $this->twig = new Environment($loader);
    }

    protected function renderTemplate()
    {
        $template = "main.twig";

        echo $this->twig->render($template, $this->params);
    }

    protected function prepare_parts()
    {
        $signed = isset($_SESSION["signed"]) && $_SESSION["signed"] == true;
        if ($signed) {
            $this->navigation->createNavigation($_SESSION["role"]);
        } else {
            $this->navigation->createNavigation(0);
        }
        $this->loginBar = new Sign($signed, $_SESSION["fullName"]);

        $this->params["navigation"] = $this->navigation->getNavigation();
        $this->params["login"] = $this->loginBar->getSignBar();
    }

    protected function initModel()
    {
        require_once "db/db_info.php";

        try {
            $this->database = new PDO("mysql:host" . DB_HOST . "dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOException $exception) {
            echo "Database connection failed: " . $exception->getMessage();
            return;
        }

        require_once "model/model.php";
        $this->model = new Model($this->database);
    }
}