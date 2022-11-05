<?php

require("routes/web.php");

//var_dump($_SERVER["REQUEST_URI"]);

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
        
        $offset = strrpos($_SERVER["REQUEST_URI"], "/");

        $param = substr($_SERVER["REQUEST_URI"], $offset+1);

        /*$nomParamInRoute = substr($routeParam, strpos($routeParam, "{")+1);
        $nomParamInRoute = str_replace("}", "", $nomParamInRoute);*/

        require("src/Controller/" . $controller . ".php");

        $cont = new $controller;

        $view = $cont->{$function}($param);

        echo $view;
    }
}

else
{
    $datasRoute = explode("::", $routes[ $_SERVER["REQUEST_URI"] ]);

    $controller = $datasRoute[0];
    $function = $datasRoute[1];

    require("src/Controller/" . $controller . ".php");

    $cont = new $controller;

    $view = $cont->{$function}();

    echo $view;
}