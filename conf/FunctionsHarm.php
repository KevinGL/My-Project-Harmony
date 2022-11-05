<?php

function linkHarm($link)
{
    $params = explode(",", $link);

    $params[0] = str_replace("\"", "", $params[0]);
    $params[1] = str_replace("[", "", $params[1]);
    
    $dernier = count($params)-1;

    $params[$dernier] = str_replace("]", "", $params[$dernier]);

    $finalLink = $params[0] . "?";

    for($i=1 ; $i<count($params) ; $i++)
    {
        $params[$i] = str_replace(":", "=", $params[$i]);

        $finalLink .= $params[$i];

        if($i < count($params)-1)
            $finalLink .= "&";
    }

    //var_dump($finalLink);

    return $finalLink;
}