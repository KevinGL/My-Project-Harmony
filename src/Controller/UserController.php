<?php

include "./conf/Controller.php";
include "./src/Model/UserModel.php";

class UserController extends Controller
{
    public function form()
    {
        $this->render_view("formUser.harm");
    }

    public function createUser(Request $req)
    {
        //var_dump($req->body);

        $user = new UserModel;

        $res = $user->create([
            "name" => $req->body["name"],
            "email" => $req->body["email"],
            "description" => $req->body["description"],
            "admin" => $req->body["admin"] == "admin" ? 1 : 0
        ]);

        if($res)
            return "Inscription validée";
        else
            return "Une erreur s'est produite";
    }
};
