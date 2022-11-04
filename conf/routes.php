<?php

require("routes/web.php");

if(!array_key_exists($_SERVER["REQUEST_URI"], $routes))
{
    echo 'ERREUR 404';
}

else
{
    $datasRoute = explode("::", $routes[ $_SERVER["REQUEST_URI"] ]);

    $controller = $datasRoute[0];
    $function = $datasRoute[1];

    //var_dump($routes[ $_SERVER["REQUEST_URI"] ]);

    require("src/Controller/" . $controller . ".php");

    $cont = new $controller;

    //var_dump($cont);

    $view = $cont->{$function}();

    echo $view;
}