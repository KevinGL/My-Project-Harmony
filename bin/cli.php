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

            echo "\nUnique ? (Y/N) ";
            if(yes_or_no())
                $col["unique"] = true;
            else
                $col["unique"] = false;

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

        $unique = false;

        foreach($columns as $c)
        {
            if($c["unique"])
            {
                $unique = true;
                break;
            }
        }

        if($unique)
        {
            rtrim($request, "\n");
            
            foreach($columns as $c)
            {
                if($c["unique"])
                {
                    $request .= ",\n    UNIQUE (" . $c["name"] . ")";
                }
            }
        }

        $request .= "\n)";

        $res = $db->query($request);

        if($res)
        {
            createControllerModelEntity($tablename, $columns);
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

function createControllerModelEntity($modelName, $fields)
{
    if(!file_exists("src/Controller") || !is_dir("src/Controller"))
    {
        mkdir("src/Controller");
    }
    
    if(!file_exists("src/Model") || !is_dir("src/Model"))
    {
        mkdir("src/Model");
    }

    if(!file_exists("src/Entity") || !is_dir("src/Entity"))
    {
        mkdir("src/Entity");
    }

    $modelNameCap = ucfirst(strtolower($modelName));
    $modelNameOne = rtrim(strtolower($modelName), "s");

    if($modelNameCap[strlen($modelNameCap)-1] == "s")
    {
        $modelNameCap = rtrim($modelNameCap, "s");
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    $fileController = fopen("src/Controller/" . $modelNameCap . "Controller.php", "a+");

    fputs($fileController, "<?php\n\n");

    fputs($fileController, "include \"./conf/Controller.php\";\n");
    fputs($fileController, "include \"./src/Entity/" . $modelNameCap . "Entity.php\";\n\n");
    
    fputs($fileController, "class " . $modelNameCap . "Controller extends Controller\n");
    fputs($fileController, "{\n");

    fputs($fileController, "    public function index()\n");
    fputs($fileController, "    {\n");
    fputs($fileController, "        return(\"Welcome in Harmony ! :)\");\n");
    fputs($fileController, "    }\n");

    fputs($fileController, "};\n");

    fclose($fileController);

    //////////////////////////////////////////////////////////////////////////////////////////////////

    $fileModel = fopen("src/Model/" . $modelNameCap . ".php", "a+");

    fputs($fileModel, "<?php\n\n");

    fputs($fileModel, "class " . $modelNameCap . "\n");
    fputs($fileModel, "{\n");

    foreach($fields as $f)
    {
        fputs($fileModel, "    private $" . strtolower($f["name"]) . ";\n\n");
    }

    foreach($fields as $f)
    {
        fputs($fileModel, "    public function get" . ucfirst(strtolower($f["name"])) . "()\n");

        fputs($fileModel, "    {\n");
        fputs($fileModel, "        return \$this->" . strtolower($f["name"]) . ";\n");
        fputs($fileModel, "    }\n\n");
    }

    foreach($fields as $f)
    {
        fputs($fileModel, "    public function set" . ucfirst(strtolower($f["name"])) . "(\$value)\n");

        fputs($fileModel, "    {\n");
        fputs($fileModel, "        \$this->" . strtolower($f["name"]) . " = \$value;\n");
        fputs($fileModel, "    }\n\n");
    }
    
    fputs($fileModel, "};\n");

    fclose($fileModel);

    //////////////////////////////////////////////////////////////////////////////////////////////////

    $fileEntity = fopen("src/Entity/" . $modelNameCap . "Entity.php", "a+");

    fputs($fileEntity, "<?php\n\n");

    fputs($fileEntity, "require(\"bin/bdd.php\");\n");
    fputs($fileEntity, "require(\"src/Model/" . $modelNameCap . ".php\");\n\n");

    fputs($fileEntity, "class " . $modelNameCap . "Entity\n");
    fputs($fileEntity, "{\n");
        fputs($fileEntity, "    private \$db;\n\n");

        fputs($fileEntity, "    public function __construct()\n");
        fputs($fileEntity, "    {\n");
            fputs($fileEntity, "        \$this->db = connectDB();\n");
        fputs($fileEntity, "    }\n\n");

        fputs($fileEntity, "    public function create(\$row)\n");
        fputs($fileEntity, "    {\n");
            fputs($fileEntity, "        \$query = \"INSERT INTO " . $modelName . " (");

            $i=0;

            foreach($fields as $f)
            {
                fputs($fileEntity, $f["name"]);

                if($i < count($fields)-1)
                    fputs($fileEntity, ", ");
                else
                    fputs($fileEntity, ") VALUES (");

                $i++;
            }

            $i=0;

            foreach($fields as $f)
            {
                fputs($fileEntity, ":" . $f["name"]);

                if($i < count($fields)-1)
                    fputs($fileEntity, ", ");
                else
                    fputs($fileEntity, ")\";\n\n");

                $i++;
            }

            fputs($fileEntity, "        \$sth = \$this->db->prepare(\$query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);\n\n");

            fputs($fileEntity, "        \$res = \$sth->execute([\n");

            $i=0;

            foreach($fields as $f)
            {
                fputs($fileEntity, "            \":" . $f["name"] . "\" => \$row[\"" . $f["name"] . "\"]");

                if($i < count($fields)-1)
                    fputs($fileEntity, ",\n");
                else
                    fputs($fileEntity, "\n");

                $i=0;
            }

            fputs($fileEntity, "        ]);\n\n");

            fputs($fileEntity, "        if(\$res == true)\n");
            fputs($fileEntity, "            return \"success\";\n");
            fputs($fileEntity, "        else\n");
            fputs($fileEntity, "            return \"fail\";\n");
        fputs($fileEntity, "    }\n");

        fputs($fileEntity, "    public function findById(\$id)\n");
        fputs($fileEntity, "    {\n");

            fputs($fileEntity, "        \$" . $modelNameOne . " = new " . $modelNameCap . ";\n\n");

            fputs($fileEntity, "        \$query = \"SELECT * FROM " . strtolower($modelName) . " WHERE id = :id\";\n");

            fputs($fileEntity, "        \$sth = \$this->db->prepare(\$query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);\n");
            
            fputs($fileEntity, "        \$res = \$sth->execute([\n");
                fputs($fileEntity, "            \":id\" => \$id\n");
            fputs($fileEntity, "        ]);\n\n");

            fputs($fileEntity, "        if(!\$res)\n");
            fputs($fileEntity, "        {\n");
                fputs($fileEntity, "            return false;\n");
            fputs($fileEntity, "        }\n\n");

            fputs($fileEntity, "        \$data = \$sth->fetch();\n\n");

            foreach($fields as $f)
            {
                fputs($fileEntity, "        \$" . $modelNameOne . "->set" . ucfirst(strtolower($f["name"])) . "(\$data[\"" . strtolower($f["name"]) . "\"]);\n");
            }

            fputs($fileEntity, "\n        return \$" . $modelNameOne . ";\n");

        fputs($fileEntity, "    }\n");

        /////////////////////////////////////////////////////////////////////////

        fputs($fileEntity, "    public function findAll()\n");
        fputs($fileEntity, "    {\n");

            fputs($fileEntity, "        \$" . strtolower($modelName) . "s = [];\n\n");
            
            fputs($fileEntity, "        \$query = \"SELECT * FROM " . strtolower($modelName) . "\";\n\n");
            
            fputs($fileEntity, "        \$res = \$this->db->query(\$query);\n\n");

            fputs($fileEntity, "        if(!\$res)\n\n");
            fputs($fileEntity, "        {\n");
            fputs($fileEntity, "            return false;\n");
            fputs($fileEntity, "        }\n\n");
            
            fputs($fileEntity, "        \$datas = \$res->fetchAll();\n\n");
            
            fputs($fileEntity, "        foreach(\$datas as \$d)\n");
            fputs($fileEntity, "        {\n");
                fputs($fileEntity, "            \$" . $modelNameOne . " = new " . $modelNameCap . ";\n\n");
                
                foreach($fields as $f)
                {
                    fputs($fileEntity, "            $" . $modelNameOne . "->set" . ucfirst(strtolower($f["name"])) . "(\$d[\"" . strtolower($f["name"]) . "\"]);\n");
                }

                fputs($fileEntity, "\n            array_push(\$" . strtolower($modelName) . "s, \$" . $modelNameOne . ");\n");

            fputs($fileEntity, "        }\n\n");

            fputs($fileEntity, "        return \$" . strtolower($modelName) . "s;\n");

        fputs($fileEntity, "    }\n\n");

        fputs($fileEntity, "    public function update(\$id, " . $modelNameCap . " \$" . $modelNameOne . ")\n");
        fputs($fileEntity, "    {\n");

            fputs($fileEntity, "        \$query = \"UPDATE " . strtolower($modelName) . " SET \";\n\n");

            $i=0;

            foreach($fields as $f)
            {
                fputs($fileEntity, "        \$query .= \"" . strtolower($f["name"]) . " = '\" . \$" . $modelNameOne . "->get" . ucfirst(strtolower($f["name"])) . "()");

                if($i < count($fields)-1)
                    fputs($fileEntity, " . \"', \";\n");
                else
                    fputs($fileEntity, " . \"' WHERE id = :id\";\n\n");

                $i++;
            }

            fputs($fileEntity, "        \$sth = \$this->db->prepare(\$query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);\n\n");

            fputs($fileEntity, "        \$res = \$sth->execute([\n");
            fputs($fileEntity, "            \":id\" => \$id\n");
            fputs($fileEntity, "        ]);\n\n");

            fputs($fileEntity, "        if(!\$res)\n");
            fputs($fileEntity, "        {\n");
                fputs($fileEntity, "            return false;\n");
            fputs($fileEntity, "        }\n");
            fputs($fileEntity, "        else\n");
            fputs($fileEntity, "        {\n");
                fputs($fileEntity, "            return true;\n");
            fputs($fileEntity, "        }\n");

        fputs($fileEntity, "    }\n\n");

        fputs($fileEntity, "    public function delete(\$id)\n");
        fputs($fileEntity, "    {\n");

            fputs($fileEntity, "        \$query = \"DELETE FROM " . strtolower($modelName) . " WHERE id = :id\";\n\n");

            fputs($fileEntity, "        \$sth = \$this->db->prepare(\$query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);\n\n");

            fputs($fileEntity, "        \$res = \$sth->execute([\n");
            fputs($fileEntity, "            \":id\" => \$id\n");
            fputs($fileEntity, "        ]);\n\n");

            fputs($fileEntity, "        if(!\$res)\n");
            fputs($fileEntity, "        {\n");
            fputs($fileEntity, "            return false;\n");
            fputs($fileEntity, "        }\n");
            fputs($fileEntity, "        else\n");
            fputs($fileEntity, "        {\n");
                fputs($fileEntity, "            return true;\n");
            fputs($fileEntity, "        }\n");
        
        fputs($fileEntity, "    }\n");

        /////////////////////////////////////////////////////////////////////////

    fputs($fileEntity, "};\n");

    fclose($fileEntity);

}