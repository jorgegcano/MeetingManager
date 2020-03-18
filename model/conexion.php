
<?php

class Conectar{

    public static function conexion(){

        try{
            $db=new PDO("mysql:host=localhost:8889; dbname=imf", "root", "root");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec("SET CHARACTER SET utf8");
        }catch (Exception $e){
            die("Error".$e->getMessage());
            echo "Error en la línea: ".$e->getLine();
        }

        return $db;
    }

}

?>
