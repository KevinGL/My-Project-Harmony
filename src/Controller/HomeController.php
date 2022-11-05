<?php

//namespace HomeController;

include "./conf/Controller.php";

class HomeController extends Controller
{
    public function index()
    {
        $this->render_view("home.harm", ["name" => "Kevin", "age" => 34]);
    }

    public function user()
    {
        return "User";
    }

    public function users($id)
    {
        return $id;
    }
};
