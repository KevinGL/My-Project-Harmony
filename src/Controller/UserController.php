<?php

include "./conf/Controller.php";
include "./src/Entity/UserEntity.php";

class UserController extends Controller
{
    public function form()
    {
        $this->render_view("formUser.harm");
    }

    public function createUser(Request $req)
    {
        //var_dump($req->body);

        $userEntity = new UserEntity;

        $res = $userEntity->create([
            "name" => $req->body["name"],
            "email" => $req->body["email"],
            "password" => $req->body["password"],
            "description" => $req->body["description"],
            "admin" => $req->body["admin"]
        ]);

        if($res)
            return "Inscription validée";
        else
            return "Une erreur s'est produite";
    }

    public function getUser()
    {
        $userEntity = new UserEntity;

        $user = $userEntity->findById(2);

        var_dump($user->getName());
        var_dump($user->getEmail());
        var_dump($user->getDescription());
        var_dump($user->getAdmin());
        
        return "GET USER";
    }

    public function getAllUsers()
    {
        $userEntity = new UserEntity;

        $users = $userEntity->findAll();

        var_dump($users);
        
        return "GET ALL USERS";
    }

    public function form2()
    {
        $this->render_view("formUser2.harm");
    }

    public function updateUser(Request $req)
    {
        $userEntity = new UserEntity;

        $new = new User;

        $new->setName($req->body["name"]);
        $new->setEmail($req->body["email"]);
        $new->setPassword($req->body["password"]);
        $new->setDescription($req->body["description"]);
        $new->setAdmin($req->body["admin"]);

        $userEntity->update(1, $new);
    }

    public function deleteUser()
    {
        $userEntity = new UserEntity;

        var_dump( $userEntity->delete(4) );
    }
};
