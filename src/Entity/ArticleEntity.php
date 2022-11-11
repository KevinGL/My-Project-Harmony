<?php

require("bin/bdd.php");
require("src/Model/Article.php");

class ArticleEntity
{
    private $db;

    public function __construct()
    {
        $this->db = connectDB();
    }

    public function create($row)
    {
        $query = "INSERT INTO article (title, content) VALUES (:title, :content)";

        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $res = $sth->execute([
            ":title" => $row["title"],
            ":content" => $row["content"],
        ]);

        if($res == true)
            return "success";
        else
            return "fail";
    }
    public function findById($id)
    {
        $article = new Article;

        $query = "SELECT * FROM article WHERE id = :id";
        $sth = $this->db->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $res = $sth->execute([
            ":id" => $id
        ]);

        if(!$res)
        {
            return false;
        }

        $data = $sth->fetch();

        $article->setTitle($data["title"]);
        $article->setContent($data["content"]);

        return $article;
    }
    public function findAll()
    {
        $articles = [];

        $query = "SELECT * FROM article";

        $res = $this->db->query($query);

        if(!$res)

        {
            return false;
        }

        $datas = $res->fetchAll();

        foreach($datas as $d)
        {
            $article = new Article;

            $article->setTitle($d["title"]);
            $article->setContent($d["content"]);

            array_push($articles, $article);
        }

        return $articles;
    }

    public function update($id, Article $article)
    {
        $query = "UPDATE article SET ";

        $query .= "title = '" . $article->getTitle() . "', ";
        $query .= "content = '" . $article->getContent() . "' WHERE id = :id";

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
        $query = "DELETE FROM article WHERE id = :id";

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
