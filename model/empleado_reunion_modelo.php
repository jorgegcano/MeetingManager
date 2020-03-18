<?php

include_once('empleadoModelo.php');
include_once('reunionModelo.php');

class Empleado_reunion
{
    private $id;
    private $idEmpleado_fk;
    private $idReunion_fk;
    private $confirmacion;
    /*----Atributos procedentes de los modelos----*/
    private $idEmpleado;
    private $nombre;
    private $apellidos;
    private $departamento;
    private $email;
    private $foto;
    private $costeHora;
    private $organizador;
    private $asunto;
    private $sala;
    private $fecha;
    private $inicio;
    private $fin;
    private $observaciones;
    /*--------------------------------------------*/

    /*----Atributos para guardar valores y recurrir a ellos después----*/
    private $costeEstimado;
    /*-----------------------------------------------------------------*/

    public function __construct()
    {
        $empleado = new Empleado();
        $reunion =  new Reunion();
        $idEmpleado = $empleado->getIdEmpleado();
        $nombre = $empleado->getNombre();
        $apellidos = $empleado->getApellidos();
        $costeHora = $empleado->getCosteHora();
        $departamento = $empleado->getDepartamento();
        $email = $empleado->getEmail();
        $foto = $empleado->getFoto();
        $organizador = $reunion->getIdEmpleadoOrganizador();
        $asunto = $reunion->getAsunto();
        $sala = $reunion->getSala();
        $costeEstimado = $reunion->getCosteEstimado();
        $fecha = $reunion->getFecha();
        $observaciones = $reunion->getObservaciones();
    }

    public function getIdEmpleadoModelo()
    {
        return $this->idEmpleado;
    }

    public function setIdEmpleadoModelo($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }

    public function getNombreModelo()
    {
        return $this->nombre;
    }

    public function setNombreModelo($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellidosModelo()
    {
        return $this->apellidos;
    }

    public function setApellidosModelo($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function getCosteHora()
    {
        return $this->costeHora;
    }

    public function setCosteHora($costeHora)
    {
        $this->costeHora = $costeHora;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }

    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    public function getOrganizadorModelo()
    {
        return $this->organizador;
    }

    public function setOrganizadorModelo($organizador)
    {
        $this->organizador = $organizador;
    }

    public function getAsuntoModelo()
    {
        return $this->asunto;
    }

    public function setAsuntoModelo($asunto)
    {
        $this->asunto = $asunto;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdEmpleado_fk()
    {
        return $this->idEmpleado_fk;
    }


    public function setIdEmpleado_fk($idEmpleado_fk)
    {
        $this->idEmpleado_fk = $idEmpleado_fk;
    }

    public function getIdReunion_fk()
    {
        return $this->idReunion_fk;
    }

    public function setIdReunion_fk($idReunion_fk)
    {
        $this->idReunion_fk = $idReunion_fk;
    }

    public function getConfirmacion()
    {
        return $this->confirmacion;
    }


    public function setConfirmacion($confirmacion)
    {
        $this->confirmacion = $confirmacion;
    }

    public function getCosteEstimadoModelo()
    {
        return $this->costeEstimado;
    }


    public function setCosteEstimadoModelo($costeEstimado)
    {
        $this->costeEstimado = $costeEstimado;
    }

    public function getSalaModelo()
    {
        return $this->sala;
    }

    public function setSalaModelo($sala)
    {
        $this->sala = $sala;
    }

    public function getFechaModelo()
    {
        return $this->fecha;
    }

    public function setFechaModelo($fecha)
    {
        $this->fecha = $fecha;
    }

    public function getInicioModelo()
    {
        return $this->inicio;
    }

    public function setInicioModelo($inicio)
    {
        $this->inicio = $inicio;
    }

    public function getFinModelo()
    {
        return $this->fin;
    }

    public function setFinModelo($fin)
    {
        $this->fin = $fin;
    }

    public function getObservacionesModelo()
    {
        return $this->observaciones;
    }

    public function setObservacionesModelo($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    public function pintarAsistencia($valorConfirmacion, $idNexo)
    {
        if ($valorConfirmacion == 0) {
            echo "<div id='questionIcon' class='btn-confirma-invitacion' data-id-nexo='".$idNexo."'><i class='fas fa-question-circle'></i><p class='target' style='text-align:initial;'>¿Confirmar Asistencia?</p></div>";
        } else {
            echo "<div class='btn-invitacion-confirmada'><i class='fas fa-check-circle'></i><p style='text-align:initial;'>Asistencia Confirmada</p></div>";
        }
    }

    /**
    * este es el bueno. Nos lista los empleados asistentes a una reunión
    */
    public function listar_asistentes($idReunion_fk, $duracion_reunion)
    {
        $gasto_total = 0;

        require_once 'empleado_reunion_DAO.php';

        $dao = new Empleado_reunion_DAO();

        $nexo = new Empleado_reunion();

        $listaReuniones = $dao->obtener_vista_asistentes($idReunion_fk, $duracion_reunion);
        echo "<div class='aux-cambio-direccion'>";
        echo "<div class='plans' id='plan-lista-asistentes'>";

        foreach ($listaReuniones as $nexo):

        ?>

            <div class='plan'>
                <h2 class='plan-title'><?php echo $nexo->getNombreModelo(). ' ' . $nexo->getApellidosModelo(). ' ' .$nexo->getId(). ' ' .$nexo->getConfirmacion() ?></h2>
                <h2 class='plan-title' id="costeHora"><?php echo round($nexo->getCosteHora(), PHP_ROUND_HALF_UP) . ' €/reunión' ?></h2>
                <img class='foto' src='../img/<?php echo $nexo->getFoto() ?>'>
                <ul class='datos-empleado'>
                    <li><strong>ID: </strong><?php echo $nexo->getIdEmpleadoModelo() ?></li>
                    <li><strong>DEPARTAMENTO: </strong><?php echo $nexo->getDepartamento() ?></li>
                    <li><strong>EMAIL: </strong> <?php echo $nexo->getEmail() ?> </li>
                </ul>
                <div class='botonesAnularConfirmar'>
                <?php echo $this->pintarAsistencia($nexo->getConfirmacion(), $nexo->getId()); ?>

                <div class="btn-anula-invitacion" data-id-costeEmpleado="<?php echo $nexo->getCosteHora() ?>"
                data-id-reunion="<?php echo $nexo->getIdReunion_fk() ?>"
                data-id-nexo="<?php echo $nexo->getId() ?>"><i class="fas fa-user-slash"></i><p style='text-align:initial;'>Anular invitación</p></div>
              </div>
            </div>
        <?php $gasto_total += $nexo->getCosteHora();

        endforeach;

        $nexo->setCosteEstimadoModelo($gasto_total);
        echo " </div>
        <div class='coste'>Coste Estimado:&nbsp<span id='costeEstimado'>".round($nexo->getCosteEstimadoModelo(), PHP_ROUND_HALF_UP)."</span>&nbsp€"."</div>";
        echo "</div>";

        return $listaReuniones;
    }


    public function listar_asistentesSinPermisos($idReunion_fk, $duracion_reunion)
    {

        require_once 'empleado_reunion_DAO.php';

        $dao = new Empleado_reunion_DAO();

        $nexo = new Empleado_reunion();

        $listaReuniones = $dao->obtener_vista_asistentes($idReunion_fk, $duracion_reunion);

        echo "<div class='plans' id='plan-lista-asistentes'>";

        foreach ($listaReuniones as $nexo):

        ?>

            <div class='plan'>
                <h2 class='plan-title'><?php echo $nexo->getNombreModelo(). ' ' . $nexo->getApellidosModelo(). ' ' .$nexo->getId(). ' ' .$nexo->getConfirmacion() ?></h2>
                <h2 class='plan-title' id="costeHora">
                <?php if ($_SESSION['id'] == $nexo->getIdEmpleado_fk()){
                    echo $nexo->getCosteHora() . ' €/reunión' ;
                }?>

                </h2>
                <img class='foto' src='../img/<?php echo $nexo->getFoto() ?>'>
                <ul class='datos-empleado'>
                    <li><strong>ID: </strong><?php echo $nexo->getIdEmpleadoModelo() ?></li>
                    <li><strong>DEPARTAMENTO: </strong><?php echo $nexo->getDepartamento() ?></li>
                    <li><strong>EMAIL: </strong> <?php echo $nexo->getEmail() ?> </li>

                    <?php if ($_SESSION['id'] == $nexo->getIdEmpleado_fk()){
                    echo $this->pintarAsistencia($nexo->getConfirmacion(), $nexo->getId());
                    }else if($nexo->getConfirmacion() == 1){
                        echo "<div><i class='fas fa-check-circle'></i></div>";
                    }

                    if ($_SESSION['id'] == $nexo->getIdEmpleado_fk()){
                    echo "<div class='btn-anula-invitacion' data-id-costeEmpleado='".$nexo->getCosteHora()."'
                    data-id-reunion='".$nexo->getIdReunion_fk()."'
                    data-id-nexo='".$nexo->getId()."'><i class='fas fa-user-slash'></i></div>";
                    }?>
                </ul>
            </div>
        <?php $gasto_total += $nexo->getCosteHora();

        endforeach;
        $nexo->setCosteEstimadoModelo($gasto_total);
        echo " </div>";


        return $listaReuniones;
    }

    public function listarReunionesPorEmpleado($idEmpleado_fk)
    {
        require_once 'empleado_reunion_DAO.php';

        $dao = new Empleado_reunion_DAO();

        $nexo = new Empleado_reunion();

        $listaReuniones = $dao->obtener_vista_reuniones_previstas_sin_permisos($idEmpleado_fk);

        echo   "<div class='plans' id='meeting-plan'>";

        foreach ($listaReuniones as $reunionNexo) {
            $fecha_reunion = $reunionNexo->getFechaModelo();
            $hora_reunion = $reunionNexo->getFinModelo();

            $date_meeting = date($fecha_reunion .' '. $hora_reunion);
            $date_now = date("Y-m-d H:i:s");
            if ($date_meeting > $date_now) { ?>

            <div class='meeting-plan'>
                <ul class="datos-empleado">
                    <li>Organizado por: <?php echo $reunionNexo->getOrganizadorModelo() ?></li>
                    <li> Asunto: <?php echo $reunionNexo->getAsuntoModelo() ?></li>
                    <li>Sala: <?php echo $reunionNexo->getSalaModelo() ?></li>
                    <li>Fecha: <?php echo $date_meeting ?></li>
                    <li>Fecha: <?php echo $date_now ?></li>
                    <li>Comienza: <?php echo $reunionNexo->getInicioModelo() ?></li>
                    <li>Termina: <?php echo $reunionNexo->getFinModelo() ?></li>
                    <li>Obervaciones: <?php echo $reunionNexo->getObservacionesModelo() ?></li>
                    </ul>
                    <div class="botones-opcion"><a href="../views/empleados_asistentes.php?id=<?php echo $reunionNexo->getIdReunion_fk() ?>">Ver Invitados</a></div>
                    <div class="botones-opcion"><a href="../views/empleado_invitar_vista.php?id=<?php echo $reunionNexo->getIdReunion_fk() ?>">Invitar</a></div>
                    <div class="botones-opcion"><a href="../views/reunionEditar.php?id=<?php echo $reunionNexo->getIdReunion_fk() ?>">Editar</a></div>
                    <div data_id_meeting='<?php echo $reunionNexo->getIdReunion_fk() ?>' class='meeting-plans-delete' ><a href="#">Eliminar</a></div>

            </div>

        <?php }
        }

        echo "</div>";


        return $listaReuniones;
    }

    public function reunionDetallada($idReunion_fk)
    {
        require_once 'empleado_reunion_DAO.php';

        $dao = new Empleado_reunion_DAO();

        $nexo = new Empleado_reunion();

        $listaReuniones = $dao->obtener_reunion_detallada($idReunion_fk);

        $listaReunionesExcel = $dao->obtener_reunion_detallada_Excel($idReunion_fk);

        $data = serialize($listaReunionesExcel);
        $data = urlencode($data);

        echo   "<table class='tabla_reuniones'>
				    <thead>
						<tr>
							<th>Asunto</th>
							<th>Fecha</th>
							<th>Comienzo</th>
							<th>Finalización</th>
              <th>Coste</th>
							<th>Informe</th>
						</tr>
					</thead>
					<tbody>

            <tr>
            <td>".$listaReuniones[0]->getAsuntoModelo()."</td>
            <td>".$listaReuniones[0]->getFechaModelo()."</td>
            <td>".$listaReuniones[0]->getInicioModelo()."</td>
            <td>".$listaReuniones[0]->getFinModelo()."</td>
            <td>".$listaReuniones[0]->getCosteEstimadoModelo()."</td>
            <td><a href='../model/meetingReport.php?meeting_detail=".$data."&tipo=individual'><i style='color: #fff' class='far fa-file-excel'</i></a></td>
            </tr>
                </tbody>
                    </table>

                    <table class='tabla_reuniones'>
            <thead>
                <tr>
                    <th>Nombre y Apellidos</th>
                    <th>Departamento</th>
                    <th>Coste por Hora</th>
                </tr>
            </thead>
            <tbody>";

        foreach ($listaReuniones as $nexo) {
            echo

    "<tr>
    <td>".$nexo->getNombreModelo()." ".$nexo->getApellidosModelo()."</td>
    <td>".$nexo->getDepartamento()."</td>
    <td>".$nexo->getCosteHora()."</td>
    </tr>";
        }

        return $listaReuniones;
    }


    public function listado_reuniones_guardadas_por_empleado($idEmpleado_fk)
    {
        require_once 'empleado_reunion_DAO.php';

        $dao = new Empleado_reunion_DAO();

        $nexo = new Empleado_reunion();

        $listaReuniones = $dao->obtener_vista_reuniones_guardadas_sin_permisos($idEmpleado_fk);

        echo   "<table class='tabla_reuniones'>
            <thead>
                <tr>
                    <th>Asunto</th>
                    <th>Sala</th>
                    <th>Fecha</th>
                    <th>Comienzo</th>
                    <th>Finalización</th>
                    <th>Nombre y Apellidos</th>
                    <th>Departamento</th>
                </tr>
            </thead>
            <tbody>";

        foreach ($listaReuniones as $nexo) {

            $fecha_reunion = $nexo->getFechaModelo();
            $hora_reunion = $nexo->getFinModelo();

            $date_meeting = date($fecha_reunion . ' ' . $hora_reunion);
            $date_now = date("Y-m-d H:i:s");

            if ($date_meeting < $date_now) {
                echo

        "<tr>
        <td>".$nexo->getAsuntoModelo(), $nexo->getIdReunion_fk()."</td>
        <td>".$nexo->getSalaModelo()."</td>
        <td>".$nexo->getFechaModelo()."</td>
        <td>".$nexo->getInicioModelo()."</td>
        <td>".$nexo->getFinModelo()."</td>
        <td>".$nexo->getNombreModelo()." ".$nexo->getApellidosModelo()."</td>
        <td>".$nexo->getDepartamento()."</td>
        </tr>";
            }
        }
        return $listaReuniones;
    }

}



?>
