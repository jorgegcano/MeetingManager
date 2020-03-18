<?php
include '../includes/header.php';
?>

<div class= "contenedor-titulos"><h2 style="margin-bottom: 0;">Reuniones realizadas</h2></div>

<form id="findMeetingForm" style="padding: 3rem; margin-left: 0;" class="contenedor-buscador-fecha">
<input autocomplete="off" style="margin-left: 0; margin-bottom:0; width:auto;" type="text" class="buscador-fecha" id='fechaDesde' name='fechaDesde' placeholder='Buscar reunión desde...'>
<input autocomplete="off" style="width:auto; margin-bottom:0;" type="text" class="buscador-fecha" id='fechaHasta' name='fechaHasta' placeholder='hasta...'>
<input type="hidden" name=accion id="accion" value="findMeetings">
<input id="btn-find-meeting" type='submit' value="Buscar">
</form>

<?php
try{

require_once("../model/reunionModelo.php");

$reunion = new Reunion();

if($_SESSION['permisos'] == 1){

    $reunion->listado_reuniones_guardadas_busqueda();

}else{

    require_once("../model/empleado_reunion_modelo.php");

    $idEmpleado_fk = $_SESSION['id'];

    $nexo = new Empleado_reunion();

    $nexo->listado_reuniones_guardadas_por_empleado($idEmpleado_fk);

}


} catch (Exception $e){
    die("Error".$e->getMessage());
    echo "Error en la línea: ".$e->getLine();
}

include '../includes/footer.php';

?>
