<?php

require("bin/bdd.php");
require("src/Model/User.php");

class UserEntity
{
    private $db;

    public function __construct()
    {
        $this->db = connectDB();
    }

    public function create($row)
    {
        $query = "INSERT INTO users (name, email, password, description, admin) VALUES (:name, :email, :password, :description, :admin)";

        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $res = $sth->execute([
            ":name" => $row["name"],
            ":email" => $row["email"],
            ":password" => $row["password"],
            ":description" => $row["description"],
            ":admin" => $row["admin"]
        ]);

        if($res == true)
            return "success";
        else
            return "fail";
    }

    public function findById($id)
    {
        $user = new User;

        $query = "SELECT * FROM users WHERE id = :id";

        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $res = $sth->execute([
            ":id" => $id
        ]);

        if(!$res)
        {
            return false;
        }

        $data = $sth->fetch();

        $user->setName($data["name"]);
        $user->setEmail($data["email"]);
        $user->setPassword($data["password"]);
        $user->setDescription($data["description"]);
        $user->setAdmin($data["admin"]);

        return $user;
    }

    public function findAll()
    {
        $users = [];

        $query = "SELECT * FROM users";

        $res = $this->db->query($query);

        if(!$res)
        {
            return false;
        }

        $datas = $res->fetchAll();

        foreach($datas as $d)
        {
            $user = new User;

            $user->setName($d["name"]);
            $user->setEmail($d["email"]);
            $user->setPassword($d["password"]);
            $user->setDescription($d["description"]);
            $user->setAdmin($d["admin"]);

            array_push($users, $user);
        }

        return $users;
    }

    public function update($id, User $user)
    {
        $query = "UPDATE users SET ";

        $query .= "name = '" . $user->getName() . "', ";
        $query .= "email = '" . $user->getEmail() . "', ";
        $query .= "password = '" . $user->getPassword() . "', ";
        $query .= "description = '" . $user->getDescription() . "', ";
        $query .= "admin = '" . $user->getAdmin() . "' WHERE id = :id";

        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $res = $sth->execute([
            ":id" => $id
        ]);

        if(!$res)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM users WHERE id = :id";

        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $res = $sth->execute([
            ":id" => $id
        ]);

        if(!$res)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
};