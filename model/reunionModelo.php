<?php

class Reunion
{
    private $id;
    private $idEmpleadoOrganizador;
    private $asunto;
    private $sala;
    private $fecha;
    private $inicio;
    private $fin;
    private $observaciones;
    private $coste_estimado;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdEmpleadoOrganizador()
    {
        return $this->idEmpleadoOrganizador;
    }

    public function setIdEmpleadoOrganizador($idEmpleadoOrganizador)
    {
        $this->idEmpleadoOrganizador = $idEmpleadoOrganizador;
    }

    public function getAsunto()
    {
        return $this->asunto;
    }

    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    }

    public function getSala()
    {
        return $this->sala;
    }

    public function setSala($sala)
    {
        $this->sala = $sala;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }

    public function getFin()
    {
        return $this->fin;
    }

    public function setFin($fin)
    {
        $this->fin = $fin;
    }

    public function getObservaciones()
    {
        return $this->observaciones;
    }

    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    public function getCosteEstimado()
    {
        return $this->coste_estimado;
    }

    public function setCosteEstimado($coste_estimado)
    {
        $this->coste_estimado = $coste_estimado;
    }

    public function listado_reuniones_programadas()
    {
        require_once 'reunionDAO.php';

        require_once 'empleado_reunion_modelo.php';

        $dao = new ReunionDAO();

        $reunion = new Reunion();

        $listaReuniones = $dao->todasLasReuniones();

        echo   "<div class='plans' id='meeting-plan'>";
        foreach ($listaReuniones as $reunion) {
            $fecha_reunion = $reunion->getFecha();
            $hora_reunion = $reunion->getFin();

            $date_meeting = date($fecha_reunion .' '. $hora_reunion);
            $date_now = date("Y-m-d H:i:s");
            if ($date_meeting > $date_now) { ?>

            <div class='meeting-plan'>
                <ul class="datos-empleado">
                    <li>Organizado por: <?php echo $reunion->getIdEmpleadoOrganizador() ?></li>
                    <li> Asunto: <?php echo $reunion->getAsunto() ?></li>
                    <li>Sala: <?php echo $reunion->getSala() ?></li>
                    <li>Fecha: <?php echo date_format(new DateTime($date_meeting), 'd-F-Y') ?></li>
                    <li>Comienza: <?php echo $reunion->getInicio() ?></li>
                    <li>Termina: <?php echo $reunion->getFin() ?></li>
                    <li>Obervaciones: <?php echo $reunion->getObservaciones() ?></li>
                    <li>Coste Estimado: <?php echo $reunion->getCosteEstimado() ?>&nbsp€</li>
                    </ul>
                    <div class="botones-opcion"><a href="../views/empleados_asistentes.php?id=<?php echo $reunion->getId() ?>">Ver Invitados</a></div>
                    <div class="botones-opcion"><a href="../views/empleado_invitar_vista.php?id=<?php echo $reunion->getId() ?>">Invitar</a></div>
                    <div class="botones-opcion"><a href="../views/reunionOrganizar.php?id=<?php echo $reunion->getId() ?>">Editar</a></div>
                    <div data_id_meeting='<?php echo $reunion->getId() ?>' class='meeting-plans-delete' ><a href="#">Eliminar</a></div>

            </div>

        <?php }
        }

        echo "</div>";

        return $listaReuniones;
    }

    public function listado_reuniones_guardadas_busqueda() {

     require_once 'reunionDAO.php';

     require_once 'empleado_reunion_DAO.php';

     $dao = new ReunionDAO();

     $reunion = new Reunion();

     $nexoDao = new Empleado_reunion_DAO();

     $listaReuniones = $dao->todasLasReuniones();

     $gasto_total = 0;

     $idAux = 0;

     ?>

     <div style='margin-bottom: 200px;' class='aux-cambio-direccion'>

      <table class='tabla_reuniones'>
   		<thead>
   		 <tr>
        <th><input id="checks" type="checkbox"></th><th>Asunto</th><th>Fecha</th><th>Comienzo</th><th>Finalización</th><th>Coste Final</th><th id='toHide'>Ver Detalles</th>
   		 </tr>
   		</thead>
   		<tbody>

     <?php

     foreach ($listaReuniones as $reunion) {

       $listaReunionesExcel = $nexoDao->obtener_reunion_detallada_Excel($reunion->getId());

       foreach ($listaReunionesExcel as $detail) {

         $fecha_reunion = $detail['fecha'];
         $hora_reunion = $detail['fin'];
         $date_meeting = date($fecha_reunion . ' ' . $hora_reunion);
         $date_now = date("Y-m-d H:i:s");
         $id = $detail['idReunion_fk'];
         if ($date_meeting < $date_now) {
         if ($id != $idAux) {
           ?>
           <tr>
             <td><input id="mycheckbox" type="checkbox" value=<?php echo $detail['idReunion_fk']?>></td>
             <td><?php echo $detail['asunto'] ?></td>
             <td><?php echo $detail['fecha'] ?></td>
             <td><?php echo $detail['inicio'] ?></td>
             <td><?php echo $detail['fin'] ?></td>
             <td><?php echo $detail['costeEstimado'] ?></td>
             <td><i idReunionAttr=<?php echo $detail['idReunion_fk'] ?> class="details fas fa-search"></i>
           </tr>
             <?php
             $idAux = $id;
             $gasto_total +=  $detail['costeEstimado'];
           }

              ?>
          <tr id='idDetail' idDetailAttr=<?php echo $detail['idReunion_fk']?>>
            <td><?php echo $detail['nombre']." ".$detail['apellidos'] ?></td>
            <td><?php echo $detail['departamento'] ?></td>
            <td><?php echo $detail['costeHora'] ?></td>
          </tr>

       <?php
}

}
}
    ?>

   </tbody>
   </table>

   <div class=coste_botones>
   <div class='coste'>Gasto total acumulado:&nbsp<span id='costeEstimado'><?php echo $gasto_total?>&nbsp€</span></div>
   <a class="reportLink" href="#"><div id='reportLinkButton'><i style="margin-right:1rem;" class='far fa-file-excel'></i>Exportar Excel</div></a>
   </div>

   </div>

   <?php

  }

}
?>
