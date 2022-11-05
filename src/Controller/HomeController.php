<?php

//namespace HomeController;

include "./conf/Controller.php";

class HomeController extends Controller
{
    public function index(Request $req)
    {
        //var_dump($req);
        
        $this->render_view("home.harm", ["name" => "Kevin", "age" => 34]);
    }

    public function user()
    {
        return "User";
    }

    public function users($id)
    {
        return "Paramètre passé en URL : " . $id;
    }

    public function login()
    {
        return "Login";
    }
};
