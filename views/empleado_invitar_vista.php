<?php
include '../includes/header.php';

?>

<div class= "contenedor-titulos"><h2>Invite a sus empleados/as</h2></div>

<?php

try{

    $idReunion_fk = $_GET['id'];

    require_once("../model/empleadoModelo.php");

    $vistaListaEmpleados = new Empleado();

    $vistaListaEmpleados->listar_para_invitar($idReunion_fk);

} catch (Exception $e){
    die("Error".$e->getMessage());
    echo "Error en la línea: ".$e->getLine();
}

include '../includes/footer.php';
