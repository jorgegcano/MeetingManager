<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/header.php';
?>

<div class= "contenedor-titulos"><h2>Reuniónes programadas</h2></div>

<?php
try{

    //echo $_SESSION['id'];

    if ($_SESSION['permisos'] == 1){

    require_once("../model/reunionModelo.php");

    $vistaListaReuniones = new Reunion();

    $vistaListaReuniones->listado_reuniones_programadas();

    }else{

    require_once("../model/empleado_reunion_modelo.php");

    $vistaListaReuniones = new Empleado_reunion();

    $vistaListaReuniones->listarReunionesPorEmpleado($_SESSION['id']);

    }



} catch (Exception $e){
    die("Error".$e->getMessage());
    echo "Error en la línea: ".$e->getLine();
}

include '../includes/footer.php';

?>
