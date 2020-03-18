<?php

function usuario_autenticado(){
    if(!revisar_usuario() && isset($_GET['linkMail'])){
            header("Location:login.php");
        exit;
    }else if(!revisar_usuario()){
        $url = $_SERVER["REQUEST_URI"];
        $parts = explode("/", $url);
        if (end($parts) == "empleadoAlta.php"){
          return true;
        }
        else
        {
          header("Location:views/login.php");
        }
        exit;
    }
}

function revisar_usuario(){
    return isset($_SESSION['idEmpleado']);
}

session_start();
usuario_autenticado();

?>
