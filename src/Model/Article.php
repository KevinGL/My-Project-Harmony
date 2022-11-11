<?php

class Article
{
    private $title;

    private $content;

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setTitle($value)
    {
        $this->title = $value;
    }

    public function setContent($value)
    {
        $this->content = $value;
    }

};
