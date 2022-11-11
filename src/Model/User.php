<?php

class User
{
    private $name;

    private $email;

    private $password;

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

    public function getPassword()
    {
        return $this->password;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function setAdmin($value)
    {
        $this->admin = $value;
    }

};
