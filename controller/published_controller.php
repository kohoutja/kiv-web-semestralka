<?php

require_once "controller/posts_controller.php";

class Published extends Posts
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = $this->buildPublications();
        $this->renderTemplate();
    }

    private function buildPublications()
    {
        $published["published"] = array_values($this->model->getPublishedPosts());
        if (empty($published["published"])) {
            return file_get_contents("view/html/no_published.html");
        }

        return $this->twig->render("published_table.twig", $published);
    }
}