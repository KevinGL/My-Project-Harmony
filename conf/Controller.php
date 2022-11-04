<?php

//namespace Controller;

class Controller
{
    public function render_view($template, $variables = [])
    {
        $path = "templates/" . $template;

        if(!file_exists($path))
            echo "Template does not exist :(";
        else
        {
            $content = file_get_contents($path);
            //var_dump($datas);

            $listeNomsVar = [];

            $i = 0;

            $lecture = false;
            $nomVar = "";

            while(1)
            {
                $paire = $content[$i] . $content[$i+1];
                $carac = $content[$i];

                //echo $paire . "<br>";

                if($paire == "{{")
                {
                    $lecture = true;
                }

                else
                if($paire == "}}")
                {
                    $lecture = false;
                    array_push($listeNomsVar, $nomVar . "}}");
                    $nomVar = "";
                }

                if($lecture)
                {
                    //if($carac != "{" && $carac != " ")
                        $nomVar .= $carac;
                }

                $i++;
                if($i >= strlen($content)-1)
                    break;
            }

            //var_dump($listeNomsVar);

            $rendu = $content;

            foreach($listeNomsVar as $nomVar)
            {
                $nomVarRaw = str_replace("{{", "", $nomVar);
                $nomVarRaw = str_replace("}}", "", $nomVarRaw);

                $nomVarRaw = str_replace(" ", "", $nomVarRaw);
                
                if(array_key_exists($nomVarRaw, $variables))
                {
                    $rendu = str_replace($nomVar, $variables[$nomVarRaw], $rendu);
                }
                else
                    $rendu = str_replace($nomVar, "", $rendu);
            }

            $rendu = str_replace("{%", "", $rendu);
            $rendu = str_replace("%}", "", $rendu);

            echo $rendu;
        }
    }
}