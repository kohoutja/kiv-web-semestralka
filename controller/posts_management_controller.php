<?php

require_once "posts_controller.php";

class Posts_management extends Posts
{

    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = $this->buildReviewedPosts();
        $this->renderTemplate();
    }

    private function buildReviewedPosts()
    {
        $posts["posts"] = $this->model->getReviewedUnpublishedPosts();
        if (empty($posts["posts"])) {
            return file_get_contents("view/html/no_reviewed_posts.html");
        }
        for ($i = 0; $i < count($posts["posts"]); $i++) {
            $reviews["reviews"] = $this->model->getPostReviews($posts["posts"][$i]["id"]);
            $sum = 0;
            $count = 0;
            foreach ($reviews["reviews"] as $review) {
                $sum += $review["overall"];
                $count++;
            }
            $posts["posts"][$i]["overall"] = round($sum / $count, 2);
            $posts["posts"][$i]["reviews"] = $this->twig->render("posts_management_reviews_table.twig", $reviews);
        };

        return $this->twig->render("posts_management_table.twig", $posts);
    }

    public function accept()
    {
        $this->model->acceptPost($_POST["postId"]);
    }

    public function deny()
    {
        $this->model->denyPost($_POST["postId"]);
    }
}