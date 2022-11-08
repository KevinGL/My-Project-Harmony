<?php

function createdb($db)
{
    /*while(1)
    {
        echo "Do you want to create a new database ? (Y/N) ";
        fscanf(STDIN, "%s", $rep);

        if($rep !== "y" && $rep !== "Y" && $rep !== "n" && $rep !== "N")
        {
            echo "Please use 'y'/'Y'/','/'N'\n";
        }

        else
        {
            break;
        }
    }*/

    echo "Name of your database : ";
    fscanf(STDIN, "%s", $dbname);

    $db->query('CREATE DATABASE ' . $dbname);

    echo "Database created successfully :)";
}

function createmodel($db)
{
    echo "Please give me what database we must change : ";
    fscanf(STDIN, "%s", $dbname);

    $db->query('use ' . $dbname);
}