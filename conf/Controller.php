<?php

require("FunctionsHarm.php");

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
            $listeNomsFonc = [];

            $i = 0;

            $lectureVar = false;
            $lectureFonc = false;
            $nomVar = "";
            $nomFonc = "";

            while(1)
            {
                $paire = $content[$i] . $content[$i+1];
                $carac = $content[$i];

                //echo $paire . "<br>";

                if($paire == "{{")
                {
                    $lectureVar = true;
                }

                else
                if($paire == "}}")
                {
                    $lectureVar = false;
                    array_push($listeNomsVar, $nomVar . "}}");
                    $nomVar = "";
                }

                if($lectureVar)
                {
                    $nomVar .= $carac;
                }

                ///////////////////////////////////////////////////////////

                if($paire == "{%")
                {
                    $lectureFonc = true;
                }

                else
                if($paire == "%}")
                {
                    $lectureFonc = false;
                    array_push($listeNomsFonc, $nomFonc . "%}");
                    $nomFonc = "";
                }

                if($lectureFonc)
                {
                    $nomFonc .= $carac;
                }

                $i++;
                if($i >= strlen($content)-1)
                    break;
            }

            //var_dump($listeNomsFonc);

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

            foreach($listeNomsFonc as $nomFonc)
            {
                $nomFoncRaw = str_replace("{%", "", $nomFonc);
                $nomFoncRaw = str_replace("%}", "", $nomFoncRaw);

                $nomFoncRaw = str_replace(" ", "", $nomFoncRaw);

                $datasFonc = explode("(", $nomFoncRaw);

                $datasFonc[1] = str_replace(")", "", $datasFonc[1]);

                $newLink = ($datasFonc[0])($datasFonc[1]);
                    
                $rendu = str_replace($nomFonc, $newLink, $rendu);

                //var_dump($datasFonc);
            }

            echo $rendu;
        }
    }

    public function render_json($datas)
    {
        echo json_encode($datas);
    }
}