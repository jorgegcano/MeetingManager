<?php
include '../includes/header.php';

if(isset($_GET['id'])){
    $idReunion= $_GET['id'];
}else{
    $idReunion=0; //Esto lo ponemos para que no dé error cuando no traemos el id de la reunión
}
//echo $idReunion;
?>

<div class= "contenedor-titulos"><h2>Empleados registrados</h2></div>

<?php
try{

    require_once("../model/empleadoModelo.php");

    $vistaListaEmpleados = new Empleado();

    if ($_SESSION['permisos'] == 1){
        $vistaListaEmpleados->listado($idReunion);
    }else{
        $vistaListaEmpleados->informacion_personal();
    }



} catch (Exception $e){
    die("Error".$e->getMessage());
    echo "Error en la línea: ".$e->getLine();
}


include '../includes/footer.php';

?>
