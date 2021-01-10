<?php

require_once "controller/posts_controller.php";

class My_posts extends Posts
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = $this->buildMyPosts();
        $this->renderTemplate();
    }

    private function buildMyPosts()
    {
        $posts["posts"] = array_values($this->model->getUserPosts($_SESSION["id"]));
        if (empty($posts["posts"])) {
            return file_get_contents("view/html/no_posts.html");
        }
        return $this->twig->render("my_posts_table.twig", $posts);
    }
}