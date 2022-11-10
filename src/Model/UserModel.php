<?php

require("bin/bdd.php");

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = connectDB();
        
        //var_dump($this->db);
    }

    public function create($row)
    {
        $query = "INSERT INTO users (name, email, description, admin) VALUES (:name, :email, :description, :admin)";

        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        if($row["admin"] == "admin")
            $admin = 1;
        else
        if($row["admin"] == "notadmin")
            $admin = 0;

        $res = $sth->execute([
            ":name" => $row["name"],
            ":email" => $row["email"],
            ":description" => $row["description"],
            ":admin" => $admin
        ]);

        if($res == true)
            return "success";
        else
            return "fail";

        /*$query = $this->db->query('SELECT * FROM users');
	
	    $res = $query->fetchAll();

        var_dump($res);*/
    }
};
