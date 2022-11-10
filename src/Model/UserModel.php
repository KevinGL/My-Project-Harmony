<?php

include "../conf/Model.php";

class UserModel extends Model
{
    private $name;

    private $email;

    private $description;

    private $admin;

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

};
