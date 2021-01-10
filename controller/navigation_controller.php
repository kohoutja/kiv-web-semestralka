<?php

class Navigation
{
    private $navigation;

    public function __construct()
    {
        $this->navigation = '';
    }

    public function createNavigation($user)
    {
        $this->addNavigationElement("Úvod", "/semestralka/home/");
        $this->addNavigationElement("Pravidla", "/semestralka/rules/");
        $this->addNavigationElement("Publikováno", "/semestralka/published/");
        if ($user != 0) {
            if ($user <= 3) {
                $this->addNavigationElement("Přidat příspěvek", "/semestralka/create_post/");
                $this->addNavigationElement("Mé příspěvky", "/semestralka/my_posts/");
            }

            if ($user <= 2) {
                $this->addNavigationElement("Recenze", "/semestralka/review/");
            }

            if ($user <= 1) {
                $this->addNavigationElement("Přidělit recenzenty", "/semestralka/assign/");
                $this->addNavigationElement("Správa příspěvků", "/semestralka/posts_management/");
                $this->addNavigationElement("Správa uživatelů", "/semestralka/users_management/");
            }
        }
        $this->addNavigationElement("O nás", "/semestralka/about/");
    }

    private function addNavigationElement($title, $href)
    {
        $this->navigation = $this->navigation . "<li class='page nav-item list-unstyled'>
            <a href='$href' class='nav-link navigation-link'>$title</a></li><br>";
    }

    public function getNavigation()
    {
        return $this->navigation;
    }
}