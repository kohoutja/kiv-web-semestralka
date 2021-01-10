<?php


class Assign extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = $this->buildTable();
        $this->renderTemplate();

    }

    private function buildTable()
    {
        $posts = $this->model->getReviewerAssignWaitingPosts();
        if (empty($posts)) {
            return file_get_contents("view/html/no_review_need_posts.html");
        }

        $tableParams = array("posts" => $posts);
        $index = 0;
        foreach ($posts as $post) {
            $assignedReviewers = $this->model->getPostReviewersById($post["id"]);
            for ($i = 0; $i < 3; $i++) {
                $select = null;
                if ($i < count($assignedReviewers)) {
                    $select = $this->twig->render("assigned_reviewer_info.twig", $assignedReviewers[$i]);
                } else {
                    $availableReviewers["reviewers"] = $this->model->getAvailableReviewers($post["id"]);
                    $select = $this->twig->render("assign_reviewer_table_select.twig", $availableReviewers);
                }
                $tableParams["posts"]["$index"]["select$i"] = $select;
            }
            $index++;
        }
        return $this->twig->render("assign_reviewer_table.twig", $tableParams);
    }

    public function addReviewer()
    {
        $postId = $_POST["post"];
        $reviewerId = $_POST["reviewer"];

        if ($postId == "" || $postId == null) {
            echo 1;
            return;
        }
        
        if ($reviewerId == "" || $reviewerId == null) {
            echo 2;
            return;
        }

        $this->model->assignReviewer($postId, $reviewerId);

        $this->checkReviewsCount($postId);
        echo 0;
    }

    private function checkReviewsCount($postId)
    {
        $reviewsCount = $this->model->getAssignedReviewsCount($postId);

        if ($reviewsCount["COUNT(*)"] >= 3) {
            $this->model->updatePostState($postId, 2);
        }
    }
}