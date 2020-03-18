<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once("../model/conexion.php");
include_once("../model/empleado_reunion_modelo.php");
include_once("../model/empleado_reunion_DAO.php");

include("class.phpmailer.php");
include("class.smtp.php");
include("funcionesMail.php");

if(isset($_POST['accion']) && $_POST['accion'] == 'findMeetings'){

    $fechaDesdeDatePicker = new DateTime($_POST['fechaDesde']);
    $fechaDesdeFormateada = $fechaDesdeDatePicker->format('Y-m-d');

    $fechaHastaDatePicker = new DateTime($_POST['fechaHasta']);
    $fechaHastaFormateada = $fechaHastaDatePicker->format('Y-m-d');

    $reunionDao = new Empleado_reunion_DAO();

    $reunionDao->allMeetingsBetweenDates($fechaDesdeFormateada, $fechaHastaFormateada);

}elseif (isset($_GET['accion'])){

if ($_GET['accion'] == 'invitar')
{
    $idReunion_fk = $_GET['idReunion_fk'];

    $idEmpleado_fk = $_GET['idEmpleado_fk'];

    $para = $_GET['email'];

    require_once '../model/empleadoDAO.php';

    $empleadoDAO = new EmpleadoDAO();

    $coste_por_hora = $empleadoDAO->get_coste_hora_cada_empleado_by_pk($idEmpleado_fk);

    require_once '../model/reunionDAO.php';

    $reunionDao = new ReunionDAO();

    $duracion = $reunionDao->calcular_duracion($idReunion_fk);

    $coste_estimado = $coste_por_hora * $duracion;

    $reunion_elegida = new Reunion();

    $reunion_elegida = $reunionDao->obtenerReunion($idReunion_fk);

    $coste_actual = $reunion_elegida->getCosteEstimado();

    $coste_total = $coste_estimado + $coste_actual;

    $reunion_elegida->setId($idReunion_fk);

    $reunion_elegida->setCosteEstimado($coste_total);

    $reunionDao->insertar_coste_estimado($reunion_elegida);

    $empleado_reunionDao = new Empleado_reunion_DAO();

    $nexo = new Empleado_reunion();

    $nexo->setIdReunion_fk($idReunion_fk);
    $nexo->setIdEmpleado_fk($idEmpleado_fk);
    $nexo->setConfirmacion(0);

    

    $empleado_reunionDao->insertarNexo($nexo);

    require_once '../model/empleadoModelo.php';

    $asunto = "asunto del mensaje";

    $mensaje = "<a href='http://jorge00.vl21361.dinaserver.com/views/empleados_asistentes.php?id=".$idReunion_fk."&linkMail=true'>aceptar invitación</a>";

    enviaMail($para,  $asunto, $mensaje);

} elseif ($_GET['accion'] == 'anular_invitacion') {
    $idNexo = $_GET['idNexo'];
    $idReunion = $_GET['idReunion'];
    $coste_empleado = $_GET['costeEmpleado'];

    $empleado_reunionDao = new Empleado_reunion_DAO();

    $empleado_reunionDao->borrarNexo($idNexo);

    require_once '../model/reunionDAO.php';

    $reunionDao = new ReunionDAO();

    $reunion = new Reunion();

    $reunion = $reunionDao->obtenerReunion($idReunion);

    $coste = $reunion->getCosteEstimado();

    $coste_actualizado = $coste - $coste_empleado;

    $reunion->setId($idReunion);
    $reunion->setCosteEstimado($coste_actualizado);

    $reunionDao->insertar_coste_estimado($reunion);

} elseif ($_GET['accion'] == 'confirmar'){

    $idNexo = $_GET['idNexo'];

    $empleado_reunionDao = new Empleado_reunion_DAO();

    $nexo = $empleado_reunionDao->recuperarNexo($idNexo);

    $nexo->setConfirmacion(1);

    $empleado_reunionDao->actualizarNexo($nexo);



}

}
