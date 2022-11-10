<?php

function connectDB()
{
    $db = false;
    
    $env = fopen("./.env", "r");
    
    if($env)
    {
        while(1)
        {
            $line = fgets($env);
            
            if(!$line)
                break;
            
            if($line[0] != "#")
            {
                if(str_contains($line, "DATABASE_URL"))
                {
                    $server = "";
                    $user = "";
                    $pw = "";
                    $host = "";
                    $dbname = "";
                    $version = "";

                    getConnectDB($line, $server, $user, $pw, $host, $dbname, $version);
                    
                    try
                    {
                        $db = new PDO($server . ':host=' . $host . ";dbname=" . $dbname . ';charset=utf8', $user, $pw);
                    }
                    catch(Exception $e)
                    {
                        echo $e->getMessage();
                    }

                    break;
                }
            }
        }
        
        fclose($env);
    }

    return $db;
}

function getConnectDB($line, &$server, &$user, &$pw, &$host, &$dbname, &$version)
{
    $i = 14;
    
    while(1)
    {
        if($line[$i] == ":")
            break;
        
        $server .= $line[$i];

        $i++;
    }

    $i += 3;

    while(1)
    {
        if($line[$i] == ":")
            break;
        
        $user .= $line[$i];

        $i++;
    }

    $i++;

    while(1)
    {
        if($line[$i] == "@")
            break;
        
        $pw .= $line[$i];

        $i++;
    }

    $i++;

    while(1)
    {
        if($line[$i] == "/")
            break;
        
        $host .= $line[$i];

        $i++;
    }

    $i++;

    while(1)
    {
        if($line[$i] == "?")
            break;
        
        $dbname .= $line[$i];

        $i++;
    }

    $i++;

    while(1)
    {
        if($i >= strlen($line))
            break;
        
        $version .= $line[$i];

        $i++;
    }
}