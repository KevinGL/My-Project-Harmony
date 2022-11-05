<?php

session_start();

if(str_contains($_SERVER["REQUEST_URI"], "%7C"))
{
    $redirect = explode("%7C", $_SERVER["REQUEST_URI"]);

    $_SESSION["query"] = $redirect[1];

    header("Location: " . $redirect[0]);
}

require("routes/web.php");
require("conf/Request.php");

//var_dump($_SERVER["REQUEST_URI"]);

$methods = ["GET", "POST", "PUT", "DELETE"];

if(!array_key_exists($_SERVER["REQUEST_URI"], $routes))
{
    $keys = array_keys($routes);

    $routeParam = "";

    foreach($keys as $key)
    {
        if(substr_compare($_SERVER["REQUEST_URI"], $key, 0) == -1 && strpos($key, "{") && strpos($key, "}"))
        {
            $routeParam = $key;

            break;
        }
    }
    
    if($routeParam == "")
        echo 'ERREUR 404';

    else
    {
        //echo $routeParam;

        $datasRoute = explode("::", $routes[$routeParam]);

        $controller = $datasRoute[0];
        $function = $datasRoute[1];
        $method = "GET";

        if(count($datasRoute) == 3 && in_array($datasRoute[2], $methods))
        {
            $method = $datasRoute[2];

            if($method != "GET" && $method != "POST")
                $method = "GET";
        }

        if($_SERVER["REQUEST_METHOD"] != $method)
            echo "BAD METHOD !";
        
        else
        {
            $offset = strrpos($_SERVER["REQUEST_URI"], "/");

            $param = substr($_SERVER["REQUEST_URI"], $offset+1);

            /*$nomParamInRoute = substr($routeParam, strpos($routeParam, "{")+1);
            $nomParamInRoute = str_replace("}", "", $nomParamInRoute);*/

            require("src/Controller/" . $controller . ".php");

            $cont = new $controller;

            $query = [];

            $body = [];

            $queryRaw = $_SESSION["query"];

            $queryRaw = str_replace("[", "", $queryRaw);
            $queryRaw = str_replace("]", "", $queryRaw);

            $elements = explode(",", $queryRaw);

            foreach($elements as $el)
            {
                $key_value = explode(":", $el);

                $key = $key_value[0];
                $value = $key_value[1];

                $query[$key] = $value;
            }

            if(isset($_POST))
                $body = $_POST;

            $req = new Request($method, $query, $body);

            $view = $cont->{$function}($param, $req);

            echo $view;
        }
    }
}

else
{
    $datasRoute = explode("::", $routes[ $_SERVER["REQUEST_URI"] ]);

    $controller = $datasRoute[0];
    $function = $datasRoute[1];
    $method = "GET";

    if(count($datasRoute) == 3 && in_array($datasRoute[2], $methods))
    {
        $method = $datasRoute[2];

        if($method != "GET" && $method != "POST")
            $method = "GET";
    }

    if($_SERVER["REQUEST_METHOD"] != $method)
        echo "BAD METHOD !";

    else
    {
        require("src/Controller/" . $controller . ".php");

        $cont = new $controller;

        $body = [];

        $query = [];

        if(isset($_POST))
            $body = $_POST;
        
        $queryRaw = $_SESSION["query"];

        $queryRaw = str_replace("[", "", $queryRaw);
        $queryRaw = str_replace("]", "", $queryRaw);

        $elements = explode(",", $queryRaw);

        foreach($elements as $el)
        {
            $key_value = explode(":", $el);

            $key = $key_value[0];
            $value = $key_value[1];

            $query[$key] = $value;
        }

        $req = new Request($method, $query, $body);

        $view = $cont->{$function}($req);

        echo $view;
    }
}