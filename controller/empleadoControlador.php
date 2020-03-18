<?php
include("../model/conexion.php");
require_once("../model/empleadoDAO.php");
require_once("../model/empleadoModelo.php");

$empDao = new EmpleadoDAO();

if(isset($_POST['accion']) && $_POST['accion'] == 'accesoEmpleado') {

        $idLogin = $_POST['idEmpleadoLogin'];
        $passLogin = $_POST['passwordLogin'];

        $empDao->compruebaEmpleado($idLogin, $passLogin);
        $empleado = new Empleado();
        $empleado = $empDao->obtenerPermisos($idLogin);
        $permisos = $empleado->getPermisos();
        $idPK = $empleado->getId();
        $nombre = $empleado->getNombre();

            session_start();
            $_SESSION['idEmpleado'] = $idLogin;
            $_SESSION['permisos']= $permisos;
            $_SESSION['id']= $idPK;
            $_SESSION['nombre']= $nombre;

}elseif (isset($_POST['datos'])) {

  parse_str($_POST["datos"], $datos);

  if($datos && $datos['accion'] == 'crearEmpleado') {

    try {
        $empleado = new Empleado();

        if (isset($_FILES['foto'])){
        $nombreArchivo=$_FILES['foto']['name'];
        $empleado->cargarFoto($nombreArchivo);
          $foto = $empleado->setFoto($nombreArchivo);
        }else {
          $foto = $empleado->setFoto(null);
        }
        $idEmpleado = $empleado->setIdEmpleado($datos['idEmpleado']);
        $password = $empleado->setPassword($datos['password']);
        $nombre = $empleado->setNombre($datos['nombre']);
        $apellidos = $empleado->setApellidos($datos['apellidos']);
        $email = $empleado->setEmail($datos['email']);
        $departamento = $empleado->setDepartamento($datos['departamento']);
        $costeHora = $empleado->setCosteHora($datos['costeHora']);
        $empDao->insertarEmpleado($empleado);

    } catch (Exception $e) {
        die("Error".$e->getMessage());
        echo "Error en la línea: ".$e->getLine();
    }

  }elseif($datos && $datos['accion'] == "actualizarEmpleado"){

          try {
              require_once '../model/empleado_reunion_modelo.php';
              require_once '../model/empleado_reunion_DAO.php';
              require_once '../model/reunionModelo.php';
              require_once '../model/reunionDAO.php';

              $nexo = new Empleado_reunion();
              $nexoDAO = new Empleado_reunion_DAO();
              $empleado=new Empleado();

              $reunion = new Reunion();
              $reunionDAO = new ReunionDAO();
              $empleado = $empDao->obtenerEmpleado($datos['id']);
              $idEmpleado = $empleado->getId();
              $costeHoraEmpleado = $empleado->getCosteHora();
              $listaReuniones = $nexoDAO->obtener_reunion_por_idEmpleado($idEmpleado);

              if (isset($_FILES['foto'])){
              $nombreArchivo=$_FILES['foto']['name'];
              $empleado->cargarFoto($nombreArchivo);
                $foto = $empleado->setFoto($nombreArchivo);
              }

              foreach ($listaReuniones as $nexo){

              $fecha_reunion = $nexo->getFechaModelo();
              $hora_reunion = $nexo->getFinModelo();

              $date_meeting = date($fecha_reunion . ' ' . $hora_reunion);
              $date_now = date("Y-m-d H:i:s");

              if ($date_meeting > $date_now) {

                  $nexo->getIdReunion_fk();

                  $nexo->getInicioModelo();

                  $nexo->getFinModelo();

                  $listaDuraciones = $reunionDAO->calcular_duracion($nexo->getIdReunion_fk());

                  $nexo->getCosteEstimadoModelo();

                  $costeAntiguoEmpleadoEnReunion = (float)$empleado->getCosteHora() * (float)$listaDuraciones;

                  $nuevoCosteEmpleadoEnMismaReunion = (float)$datos['costeHora'] * (float)$listaDuraciones;

                  $costeEnReunionActualizado = (float)$nuevoCosteEmpleadoEnMismaReunion - (float)$costeAntiguoEmpleadoEnReunion;

                  $costeActualizadoReunion = (float)$nexo->getCosteEstimadoModelo() + (float)$costeEnReunionActualizado;

                  $reunion = $reunionDAO->obtenerReunion($nexo->getIdReunion_fk());
                  $idReunion = $reunion->getId();
                  $reunion->setId($idReunion);
                  $reunion->setCosteEstimado($costeActualizadoReunion);
                  $reunion = $reunionDAO->insertar_coste_estimado($reunion);
                  $reunion = $reunionDAO->obtenerReunion($nexo->getIdReunion_fk());
                  $reunion->getCosteEstimado();
              }
          }

            if( isset($datos['idEmpleado']) || isset($datos['nombre']) ||
            isset($datos['apellidos']) || isset($datos['email']) || isset($datos['departamento']) ||
            isset($datos['costeHora'])) {

              $empleado->setId($datos['id']);
              $empleado->setIdEmpleado($datos['idEmpleado']);
              $empleado->setNombre($datos['nombre']);
              $empleado->setApellidos($datos['apellidos']);
              $empleado->setEmail($datos['email']);
              $empleado->setDepartamento($datos['departamento']);
              $empleado->setCosteHora($datos['costeHora']);

              $empDao->actualizarEmpleado($empleado);
            }
            else
            {
              $empDao->actualizarFotoEmpleado($empleado);
            }


          } catch (Exception $e) {
              die("Error".$e->getMessage());
              echo "Error en la línea: ".$e->getLine();
          }

      }
}elseif(isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {

    try {

        require_once '../model/empleado_reunion_modelo.php';
        require_once '../model/empleado_reunion_DAO.php';
        require_once '../model/reunionDAO.php';
        require_once '../model/empleadoModelo.php';
        require_once '../model/empleadoDAO.php';
        $reunionDao = new ReunionDAO();
        $reunion = new Reunion();
        $nexo = new Empleado_reunion();
        $nexoDAO = new Empleado_reunion_DAO();
        $empleado = new Empleado();
        $empleadoDAO = new EmpleadoDAO();
        $date_now = date("Y-m-d H:i:s");

        $listaNexos = $nexoDAO->obtener_reunion_por_idEmpleado($_GET['id']);

        foreach ($listaNexos as $nexo) {
          $fecha = $nexo->getFechaModelo();
          $hora = $nexo->getInicioModelo();
          $fechaHora = date("$fecha $hora");
          echo "reunión ".$fechaHora;
          echo "now ".$date_now;
          if ($fechaHora > $date_now){
            $coste_por_hora = $empleadoDAO->get_coste_hora_cada_empleado_by_pk($_GET['id']);
            $duracion = $reunionDao->calcular_duracion($nexo->getIdReunion_fk());
            $coste_empleado_en_esta_reunion = $coste_por_hora * $duracion;
            $reunion = $reunionDao->obtenerReunion($nexo->getIdReunion_fk());
            $coste_de_esta_reunion = $reunion->getCosteEstimado();
            $coste_actualizado = $coste_de_esta_reunion - $coste_empleado_en_esta_reunion;
            $reunion->setId($nexo->getIdReunion_fk());
            $reunion->setCosteEstimado($coste_actualizado);
            $reunionDao->insertar_coste_estimado($reunion);
            $nexoDAO->borrarNexo($nexo->getId());
          }
        }
            $empDao->borrarEmpleado($_GET['id']);

        } catch (Exception $e) {
            die("Error".$e->getMessage());
            echo "Error en la línea: ".$e->getLine();
        }
    }

?>
