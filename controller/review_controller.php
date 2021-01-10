<?php

class Review extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = $this->buildReviewsTable();
        $this->renderTemplate();
    }

    private function buildReviewsTable()
    {
        $posts = $this->model->getReviewerWaitingPosts($_SESSION["id"]);
        if (empty($posts)) {
            return file_get_contents("view/html/no_assigned_posts.html");
        }

        $usersParams = array("posts" => $posts, "roles" => $this->model->getUserRoles());
        return $this->twig->render("reviews_table.twig", $usersParams);
    }

    public function reviewPost()
    {
        $this->prepare_parts();
        $post = $this->model->getPostByTitle($this->url_params[2])[0];

        if (empty($post)) {
            $this->params["content"] = file_get_contents('view/html/post_error.html');
            $this->renderTemplate();
            return;
        }
        $_SESSION["postId"] = $post["id"];

        $attachment = "";
        if ($post["file"] != '') {
            $attachment .= "<div><b>Příloha: </b>";
            $file = $post["file"];

            if (file_exists("uploads/$file"))
                $attachment .= "<embed src='/semestralka/uploads/$file' type='application/pdf' width='100%' height='800px'/>";
            else
                $attachment .= "Přiložený soubor se bohužel nepodařilo načíst.";
            $attachment .= "</div>";
        }
        $post["options"] = file_get_contents("view/html/criterion_options.html");

        $html = $this->twig->render("review_post.twig", $post);
        $this->params["content"] = $html;

        $this->renderTemplate();
    }

    public function submit()
    {
        $criterion1 = $_POST["criterion1"];
        $criterion2 = $_POST["criterion2"];
        $criterion3 = $_POST["criterion3"];
        $overall = $_POST["overall"];
        $text = $_POST["reviewText"];
        if (!isset($text) || empty($text)) {
            $text = null;
        }

        $userId = $_SESSION["id"];
        $postId = $_SESSION["postId"];
        if ($this->model->wasAlreadyReviewed($userId, $postId)) {
            $this->model->updateReview($criterion1, $criterion2, $criterion3, $overall, $text, $postId, $userId);
        } else {
            $this->model->addReview($criterion1, $criterion2, $criterion3, $overall, $text, $postId, $userId);
            $this->model->setPostAsReviewed($userId, $postId);
            $this->checkReviewsDone($postId);
        }
    }

    private function checkReviewsDone($postId)
    {
        $reviewsCount = $this->model->checkReviewsDone($postId);

        if ($reviewsCount["COUNT(*)"] >= 3) {
            $this->model->updatePostState($postId, 3);
        }
    }

    public function success()
    {
        $this->prepare_parts();
        $this->params["content"] = file_get_contents("view/html/review_success.html");
        $this->renderTemplate();
    }

}