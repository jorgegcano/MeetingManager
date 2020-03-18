<?php
include '../includes/header.php';

require_once("../model/empleado_reunion_modelo.php");

$idReunion_fk = $_GET['id'];

require_once("../model/reunionDAO.php");

?>

<div class= "contenedor-titulos"><h2>Empleados/as invitados/as</h2></div>

<?php

$dao = new ReunionDAO();

$duracion_reunion = $dao->calcular_duracion($idReunion_fk);

try {
    $vistaListaReuniones = new Empleado_reunion();

    if($_SESSION['permisos'] == 1){

        $vistaListaReuniones->listar_asistentes($idReunion_fk, $duracion_reunion);

    }else{

        $vistaListaReuniones->listar_asistentesSinPermisos($idReunion_fk, $duracion_reunion);

    }

} catch (Exception $e) {
    die("Error".$e->getMessage());
    echo "Error en la línea: ".$e->getLine();
}

include '../includes/footer.php';

?>
