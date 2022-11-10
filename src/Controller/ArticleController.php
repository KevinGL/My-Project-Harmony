<?php

include "./conf/Controller.php";
include "./src/Model/ArticleModel.php";

class ArticleController extends Controller
{
    public function index()
    {
        $article = new ArticleModel;

        $res = $article->create([
            "name" => "test",
            "content" => "super article"
        ]);

        //var_dump($res);
        
        return("Welcome in Harmony ! :)");
    }
};
