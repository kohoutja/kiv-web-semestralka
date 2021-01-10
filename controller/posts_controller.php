<?php

class Posts extends Controller
{
    public function openPost()
    {
        $this->prepare_parts();
        $post = $this->model->getPostByTitle($this->url_params[2])[0];

        if (empty($post)) {
            $this->params["content"] = file_get_contents('view/html/post_error.html');
            $this->renderTemplate();
            return;
        }

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
        $post["attachment"] = $attachment;
        $html = $this->twig->render("post_detail.twig", $post);
        $this->params["content"] = $html;

        $this->renderTemplate();
    }
}