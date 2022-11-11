<?php

include "./conf/Controller.php";
include "./src/Entity/ArticleEntity.php";

class ArticleController extends Controller
{
    public function index()
    {
        return("Welcome in Harmony ! :)");
    }

    public function delete()
    {
        $entity = new ArticleEntity;

        $entity->delete(1);
    }
};
