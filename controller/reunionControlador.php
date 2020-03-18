<?php
include_once("../model/conexion.php");
include_once("../model/reunionDAO.php");
include_once("../model/reunionModelo.php");
include_once("../model/empleadoModelo.php");
include_once("../model/empleadoDAO.php");
include_once("../model/empleado_reunion_modelo.php");
include_once("../model/empleado_reunion_DAO.php");

if (isset($_POST['botonActualizarReunion'])) {

    try {

        $reunion=new Reunion();

        $reunionDao = new ReunionDAO();

        $nexo = new Empleado_reunion();

        $nexoDao = new Empleado_reunion_DAO();

        $empleado = new Empleado();

        $empleadoDao = new EmpleadoDAO();

        $idReunion = $_POST['id'];

        $inicio_sin_comprobar = new DateTime($_POST['inicio']);
        $inicio_sin_comprobar = $inicio_sin_comprobar->format('H:i:s');
        $fin_sin_comprobar = new DateTime($_POST['fin']);
        $fin_sin_comprobar = $fin_sin_comprobar->format('H:i:s');

        $datePickerJquery = new DateTime($_POST['fecha']);
        $fechaFormateada = $datePickerJquery->format('Y-m-d');
        $fechaHoraFormateada = date("$fechaFormateada $inicio_sin_comprobar");
        $today = date("Y-m-d H:i:s");

        if ($fechaHoraFormateada < $today) {
          echo "La fecha no puede ser anterior al día de hoy.";
          die();
        }

        if ($reunionDao->comprobar_sala_fecha($_POST['sala'], $fechaFormateada)) {

           $reunionExistente = new Reunion();

             if ($reunionesExistentes = $reunionDao->recuperar_reuniones_sala_fecha($_POST['sala'], $fechaFormateada)) {

               foreach ($reunionesExistentes as $data) {

                 // echo "La reunión que quiero es a las ".$inicio_sin_comprobar;
                 // echo "-".$fin_sin_comprobar;
                 // echo "<br>";
                 // echo "id ".$data->getId();
                 // echo "<br>";
                 // echo "GETINICIO ".$data->getInicio();
                 // echo "<br>";
                 // echo "GETFIN ".$data->getFin();
                 // echo "<br>";
                 // echo "<br>";
                 if ($data->getId() != $idReunion) {

                 if ($inicio_sin_comprobar < $data->getFin() &&  $inicio_sin_comprobar >= $data->getInicio()) {
                     echo "NO SE PUEDE HACER REUNIÓN, LA SALA ELEGIDA ESTÁ SOLICITADA PARA ESA FECHA Y HORA. INTÉNTELO DE NUEVO CON OTRA SALA O FECHA/HORA";
                     die();
                     // $contador++;
                     // echo "La hora de INICIO está".$contador." veces dentro del intervalo de alguna reunión";
                     // echo "<br>";
                     // echo "<br>";
                 }

                 if ($fin_sin_comprobar > $data->getInicio() && $fin_sin_comprobar <= $data->getFin()){
                     echo "NO SE PUEDE HACER REUNIÓN, LA SALA ELEGIDA ESTÁ SOLICITADA PARA ESA FECHA Y HORA. INTÉNTELO DE NUEVO CON OTRA SALA O FECHA/HORA";
                     die();
                     // $contadorFin++;
                     // echo "La hora de FIN está ".$contadorFin." veces dentro del intervalo de alguna reunión";
                     // echo "<br>";
                     // echo "<br>";

                 }

                 if ($inicio_sin_comprobar < $data->getInicio() && $fin_sin_comprobar >= $data->getFin()){
                     echo "NO SE PUEDE HACER REUNIÓN, LA SALA ELEGIDA ESTÁ SOLICITADA PARA ESA FECHA Y HORA. INTÉNTELO DE NUEVO CON OTRA SALA O FECHA/HORA";
                     die();
                     //$contadorFin2++;
                     // echo "La hora de FIN2 está ".$contadorFin2." veces dentro del intervalo de alguna reunión";
                     // echo "<br>";
                     // echo "<br>";
                 }
                }
               }
             }
           }

        $duracion = $reunionDao->calcular_duracion($idReunion);

        $listaCostesAsistentes = $nexoDao->obtener_vista_asistentes($idReunion, $duracion);

        foreach ($listaCostesAsistentes as $nexo){
         $empleado = $empleadoDao->obtenerEmpleado($nexo->getIdEmpleado_fk());
         $costeEmpleado = $empleado->getCosteHora();
        echo "<br>";
        }

        $reunion->setId($_POST['id']);
        $reunion->setIdEmpleadoOrganizador($_POST['idEmpleadoOrganizador']);
        $reunion->setAsunto($_POST['asunto']);
        $reunion->setSala($_POST['sala']);
        $reunion->setFecha($fechaFormateada);
        $reunion->setInicio($_POST['inicio']);
        $reunion->setFin($_POST['fin']);
        $reunion->setObservaciones($_POST['observaciones']);

        $reunionDao->actualizarReunion($reunion);

        echo $duracion = $reunionDao->calcular_duracion($idReunion);

        foreach ($listaCostesAsistentes as $nexo){
            $empleado = $empleadoDao->obtenerEmpleado($nexo->getIdEmpleado_fk());
            $costeEmpleado = $empleado->getCosteHora();
            $costeEmpleadoEnReunionActualizado = $costeEmpleado * $duracion;
            $costeActualizadoReunion += $costeEmpleadoEnReunionActualizado;
           }

           $reunion->setId($_POST['id']);
           $reunion->setCosteEstimado($costeActualizadoReunion);

           $reunionDao->insertar_coste_estimado($reunion);

        header("Location:../views/reunionHistorial.php");

    } catch (Exception $e) {
        die("Error".$e->getMessage());
        echo "Error en la línea: ".$e->getLine();
    }
} elseif (isset($_POST['invitarEmpleado'])) {
    $id_reunion_fk = $_POST['invitarEmpleado'];

    header("Location:../views/empleado_invitar_vista.php");

} elseif (isset($_POST['botonOrganizarReunion'])) {

    $idOrganizador = $_POST['idEmpleadoOrganizador'];

    $inicio_sin_comprobar = new DateTime($_POST['inicio']);
    $inicio_sin_comprobar = $inicio_sin_comprobar->format('H:i:s');
    $fin_sin_comprobar = new DateTime($_POST['fin']);
    $fin_sin_comprobar = $fin_sin_comprobar->format('H:i:s');

    $datePickerJquery = new DateTime($_POST['fecha']);
    $fechaFormateada = $datePickerJquery->format('Y-m-d');
    $fechaHoraFormateada = date("$fechaFormateada $inicio_sin_comprobar");
    $today = date("Y-m-d H:i:s");

    if ($fechaHoraFormateada < $today) {
      echo "La fecha no puede ser anterior al día de hoy.";
      die();
    }

    try {

        $reunionDao = new ReunionDAO();

        if ($fin_sin_comprobar <= $inicio_sin_comprobar) {
          echo "La hora de finalización debe ser posterior a la del comienzo.";
          die();
        }

       if ($reunionDao->comprobar_sala_fecha($_POST['sala'], $fechaFormateada)) {

          $reunionExistente = new Reunion();

            if ($reunionesExistentes = $reunionDao->recuperar_reuniones_sala_fecha($_POST['sala'], $fechaFormateada)) {

              foreach ($reunionesExistentes as $data) {

                // echo "La reunión que quiero es a las ".$inicio_sin_comprobar;
                // echo "-".$fin_sin_comprobar;
                // echo "<br>";
                // echo "id ".$data->getId();
                // echo "<br>";
                // echo "GETINICIO ".$data->getInicio();
                // echo "<br>";
                // echo "GETFIN ".$data->getFin();
                // echo "<br>";
                // echo "<br>";

                if ($inicio_sin_comprobar < $data->getFin() &&  $inicio_sin_comprobar >= $data->getInicio()) {
                    echo "NO SE PUEDE HACER REUNIÓN, LA SALA ELEGIDA ESTÁ SOLICITADA PARA ESA FECHA Y HORA. INTÉNTELO DE NUEVO CON OTRA SALA O FECHA/HORA";
                    die();
                    // $contador++;
                    // echo "La hora de INICIO está".$contador." veces dentro del intervalo de alguna reunión";
                    // echo "<br>";
                    // echo "<br>";
                }

                if ($fin_sin_comprobar > $data->getInicio() && $fin_sin_comprobar <= $data->getFin()){
                    echo "NO SE PUEDE HACER REUNIÓN, LA SALA ELEGIDA ESTÁ SOLICITADA PARA ESA FECHA Y HORA. INTÉNTELO DE NUEVO CON OTRA SALA O FECHA/HORA";
                    die();
                    // $contadorFin++;
                    // echo "La hora de FIN está ".$contadorFin." veces dentro del intervalo de alguna reunión";
                    // echo "<br>";
                    // echo "<br>";

                }

                if ($inicio_sin_comprobar < $data->getInicio() && $fin_sin_comprobar >= $data->getFin()){
                    echo "NO SE PUEDE HACER REUNIÓN, LA SALA ELEGIDA ESTÁ SOLICITADA PARA ESA FECHA Y HORA. INTÉNTELO DE NUEVO CON OTRA SALA O FECHA/HORA";
                    die();
                    //$contadorFin2++;
                    // echo "La hora de FIN2 está ".$contadorFin2." veces dentro del intervalo de alguna reunión";
                    // echo "<br>";
                    // echo "<br>";
                }
              }
            }
          }

          $reunion = new Reunion();

          $inicioFormateado = $inicio_sin_comprobar;

          $finFormateado = $fin_sin_comprobar;

          $idEmpleadoOrganizador = $reunion->setIdEmpleadoOrganizador($idOrganizador);
          $asunto = $reunion->setAsunto($_POST['asunto']);
          $sala = $reunion->setSala($_POST['sala']);
          $fecha = $reunion->setFecha($fechaFormateada);
          $inicio = $reunion->setInicio($inicioFormateado);
          $fin = $reunion->setFin($finFormateado);
          $observaciones = $reunion->setObservaciones($_POST['observaciones']);

          $reunionDao->insertarReunion($reunion);

          $organizador = new EmpleadoDAO();

          $empleado = new Empleado();

          $empleado = $organizador->obtenerIdPK($idOrganizador);

          $idOrganizadorDevuelto = $empleado->getId(); //Aquí tengo el id del empleado que organiza la reunión

          $reunion_id = $reunionDao->obtenerUltimoIdReunion(); //Aquí tengo el id de esta reunión.

          $confirmacion = true; //Aquí tengo la confirmación del organizador a true porque asiste.

          $tabla_nexo_DAO = new Empleado_reunion_DAO();

          $tabla_nexo_modelo = new Empleado_reunion();

          $tabla_nexo_modelo->setIdEmpleado_fk($idOrganizadorDevuelto);
          $tabla_nexo_modelo->setIdReunion_fk($reunion_id);
          $tabla_nexo_modelo->setConfirmacion($confirmacion);

          $tabla_nexo_DAO->insertarNexo($tabla_nexo_modelo);

          $duracion = $reunionDao->calcular_duracion($reunion_id);

          $coste_por_hora = $organizador->get_coste_hora_cada_empleado($idOrganizador);

          $coste_estimado = $coste_por_hora * $duracion;

          $reunion_con_coste_estimado = new Reunion();

          $reunion_con_coste_estimado->setId($reunion_id);
          $reunion_con_coste_estimado->setCosteEstimado($coste_estimado);

          $reunionDao->insertar_coste_estimado($reunion_con_coste_estimado);

          header("Location:../views/reunionHistorial.php");

    } catch (Exception $e) {
        die("Error" . $e->getMessage());
        echo "Error en la línea: " . $e->getLine();
    }

} elseif (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {

        $id = $_GET['id'];

        $reunionDao = new ReunionDAO();
        $reunionDao->borrarReunion($id);

}
