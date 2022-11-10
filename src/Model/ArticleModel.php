<?php

require("bin/bdd.php");

class ArticleModel
{
    private $db;

    public function __construct()
    {
        $this->db = connectDB();
    }

    public function create($row)
    {
        $query = "INSERT INTO articles (name, content) VALUES (:name, :content)";

        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $res = $sth->execute([
            ":name" => $row["name"],
            ":content" => $row["content"]
        ]);

        if($res == true)
            return "success";
        else
            return "fail";
    }
};
