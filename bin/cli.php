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

    $rep = $db->query('CREATE DATABASE ' . $dbname);

    if($rep)
        echo "Database created successfully :)";
    else
        echo "Sorry something was wrong :(";
}

function createmodel($db)
{
    echo "Please give me what database we must change : ";
    fscanf(STDIN, "%s", $dbname);

    $db->query('use ' . $dbname);

    echo "\n";

    while(1)
    {
        echo "Give me the name of your table : ";
        fscanf(STDIN, "%s", $tablename);

        $columns = [];

        while(1)
        {
            $col = [
                "name" => "",
                "type" => "",
                "null" => "",
                //"key" => ""
            ];
            
            echo "Please give the name of your column : ";
            fscanf(STDIN, "%s", $col["name"]);

            $types = ["1" => "INT", "2" => "VARCHAR(255)", "3" => "TEXT", "4" => "DATE"];

            echo "\nPlease choose the type of your column : \n1/ INT\n2/ VARCHAR\n3/ TEXT\n4/ DATE\n\n";
            $chose = "";
            fscanf(STDIN, "%s", $chose);

            in_interval($chose, 1, 4);

            $col["type"] = $types[$chose];

            echo "\nIs it null ? (Y/N) ";
            if(yes_or_no())
                $col["null"] = "";
            else
                $col["null"] = "NOT NULL";

            /*echo "\nForeign key ? (Y/N) ";
            if(yes_or_no())
                $col["key"] = "(FOREIGN KEY)";
            else
                $col["key"] = "";*/

            array_push($columns, $col);

            echo "\nDo you want to create an other column ? (Y/N) : ";
            
            if(!yes_or_no())
                break;
            
            echo "\n";
        }

        $request = "CREATE TABLE " . $tablename . "\n(\n    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,\n";

        $i = 0;

        foreach($columns as $c)
        {
            //$request .= "    " . $c["name"] . " " . $c["type"] . " " . $c["key"] . " " . $c["null"];
            $request .= "    " . $c["name"] . " " . $c["type"] . " " . $c["null"];

            if($i != count($columns)-1)
                $request .= ",\n";
            else
                $request .= "\n";

            $i++;
        }

        $request .= ")";

        $res = $db->query($request);

        if($res)
        {
            createControllerModel($tablename, $columns);
            echo "Table " . $tablename . " has been created successfully :)";
        }
        else
            echo "Sorry something was wrong :(";

        echo "\nDo you want to create an other model ? (Y/N) : ";
        
        if(!yes_or_no())
            break;
    }
}

function yes_or_no()
{
    $continue = true;
    
    while(1)
    {
        fscanf(STDIN, "%s", $value);

        if($value != "Y" && $value != "y" && $value != "N" && $value != "n")
            echo "Sorry I didn't understand your answer :/\n";
        else
        {
            if($value == "n" || $value == "N")
            {
                $continue = false;
                break;
            }
            else
            if($value == "y" || $value == "Y")
                break;
        }
    }

    return $continue;
}

function in_interval(&$value, $min, $max)
{
    while(1)
    {
        if($value<$min || $value>$max)
        {
            echo "Value not recognized, please enter a value between " . $min . " and " . $max . " : ";
            fscanf(STDIN, "%s", $value);
        }
        else
            break;
    }
}

function createControllerModel($modelName, $fields)
{
    if(!file_exists("src/Controller") || !is_dir("src/Controller"))
    {
        mkdir("src/Controller");
    }
    
    if(!file_exists("src/Model") || !is_dir("src/Model"))
    {
        mkdir("src/Model");
    }

    $modelNameCap = ucfirst(strtolower($modelName));

    if($modelNameCap[strlen($modelNameCap)-1] == "s")
    {
        $modelNameCap = rtrim($modelNameCap, "s");
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    $fileController = fopen("src/Controller/" . $modelNameCap . "Controller.php", "a+");

    fputs($fileController, "<?php\n\n");

    fputs($fileController, "include \"../conf/Controller.php\";\n\n");
    
    fputs($fileController, "class " . $modelNameCap . "Controller extends Controller\n");
    fputs($fileController, "{\n");

    fputs($fileController, "    public function index()\n");
    fputs($fileController, "    {\n");
    fputs($fileController, "        return(\"Welcome in Harmony ! :)\");\n");
    fputs($fileController, "    }\n");

    fputs($fileController, "};\n");

    fclose($fileController);

    //////////////////////////////////////////////////////////////////////////////////////////////////

    $fileModel = fopen("src/Model/" . $modelNameCap . "Model.php", "a+");

    fputs($fileModel, "<?php\n\n");

    fputs($fileModel, "include \"../conf/Model.php\";\n\n");

    fputs($fileModel, "class " . $modelNameCap . "Model extends Model\n");
    fputs($fileModel, "{\n");

    foreach($fields as $f)
    {
        fputs($fileModel, "    private $" . strtolower($f["name"]) .";\n\n");
    }

    foreach($fields as $f)
    {
        fputs($fileModel, "    public function get" . ucfirst(strtolower($f["name"])) ."()\n");
        fputs($fileModel, "    {\n");
        fputs($fileModel, "        return \$this->" . strtolower($f["name"]) . ";\n");
        fputs($fileModel, "    }\n\n");
    }

    fputs($fileModel, "};\n");

    fclose($fileModel);
}