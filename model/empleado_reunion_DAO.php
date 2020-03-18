<?php

include_once('empleado_reunion_modelo.php');
include_once('empleadoModelo.php');

class Empleado_reunion_DAO
{
    private $db;

    public function __construct()
    {
        require_once("conexion.php");
        $this->db=Conectar::conexion();
    }


    /***
     * insertamos los nexos entre los id de los empleados y los de las reuniones.
     */
    public function insertarNexo($nexo)
    {
        $stmt = $this->db->prepare("INSERT INTO empleado_reunion (idEmpleado_fk, idReunion_fk, confirmacion) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $nexo->getIdEmpleado_fk());
        $stmt->bindValue(2, $nexo->getIdReunion_fk());
        $stmt->bindValue(3, $nexo->getConfirmacion());
        $resultadoRegistro = $stmt->execute();
        if ($resultadoRegistro) {
          echo 1;
        } else {
          echo 0;
        }
  }

  public function comprobarInvitado($idEmpleado, $idReunion)
  {
    $consulta=$this->db->query("SELECT * FROM empleado_reunion");
    foreach ($consulta->fetchAll() as $fks) {
      if ($idEmpleado == $fks['idEmpleado_fk'] && $idReunion == $fks['idReunion_fk']) {
        return false;
      }
      }
      return true;
  }

    /***
     * listamos todos los nexos existentes.
     */
    public function listar_empleados_reuniones()
    {
        $array_empleado_reunion=[];
        $consulta=$this->db->query("SELECT * FROM empleado_reunion");

        foreach ($consulta->fetchAll() as $fks) {
            $nexo= new Empleado_reunion();
            $nexo->setId($fks['id']);
            $nexo->setIdEmpleado_fk($fks['idEmpleado_fk']);
            $nexo->setIdReunion_fk($fks['idReunion_fk']);
            $nexo->setConfirmacion($fks['confirmacion']);
            $array_empleado_reunion[]=$nexo;
        }
        return $array_empleado_reunion;
    }

    public function actualizarNexo($nexo)
    {
        $stmt = $this->db->prepare('UPDATE empleado_reunion SET confirmacion=?  WHERE id=?');
        $stmt->bindValue(1, $nexo->getConfirmacion());
        $stmt->bindValue(2, $nexo->getId());
        $stmt->execute();
    }

    public function recuperarNexo($id)
    {
        $consulta = $this->db->prepare('SELECT * FROM empleado_reunion WHERE ID=:id');
        $consulta->bindValue('id', $id);
        $consulta->execute();
        $nexo = $consulta->fetch();
        $nexoRecuperado = new Empleado_Reunion();
        $nexoRecuperado->setId($nexo['id']);
        $nexoRecuperado->setIdEmpleado_fk($nexo['idEmpleado_fk']);
        $nexoRecuperado->setIdReunion_fk($nexo['idReunion_fk']);
        $nexoRecuperado->setConfirmacion($nexo['confirmacion']);
        return $nexoRecuperado;
    }


    public function obtener_reunion_por_idEmpleado($idEmpleado_fk)
    {
        $array_empleado_reunion=[];
        $consulta=$this->db->query('SELECT empleado_reunion.id, idEmpleado_fk, idReunion_fk, fecha, inicio, fin, costeEstimado FROM  empleado_reunion INNER JOIN empleado
        ON empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id
        WHERE idEmpleado_fk='.$idEmpleado_fk);

        foreach ($consulta->fetchAll() as $fks) {
            $nexo= new Empleado_reunion();
            $nexo->setId($fks['id']);
            $nexo->setIdReunion_fk($fks['idEmpleado_fk']);
            $nexo->setIdReunion_fk($fks['idReunion_fk']);
            $nexo->setFechaModelo($fks['fecha']);
            $nexo->setInicioModelo($fks['inicio']);
            $nexo->setFinModelo($fks['fin']);
            $nexo->setCosteEstimadoModelo($fks['costeEstimado']);
            $array_empleado_reunion[]=$nexo;
        }
        return $array_empleado_reunion;
    }

    /**
     * funciona
     */
    public function obtener_empleado_por_id_reunion($idReunion_fk)
    {
        $consulta=$this->db->prepare('SELECT idEmpleado, nombre, apellidos, confirmacion FROM empleado_reunion INNER JOIN empleado ON
        empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id WHERE
        idReunion_fk=reunion.id');
        $consulta->bindValue('idReunion_fk', $idReunion_fk);
        $consulta->execute();
        $empleado=$consulta->fetch();
        $empleadoRecuperado= new Empleado_reunion();
        $empleadoRecuperado->setIdEmpleadoModelo($empleado['idEmpleado']);
        $empleadoRecuperado->setNombreModelo($empleado['nombre']);
        $empleadoRecuperado->setApellidosModelo($empleado['apellidos']);
        $empleadoRecuperado->setConfirmacion($empleado['confirmacion']);
        return $empleadoRecuperado;
    }


    //funciona
    public function obtener_vista_nexo()
    {
        $array_empleado_reunion=[];
        $consulta=$this->db->query("SELECT empleado_reunion.id, idEmpleado_fk, idEmpleado, nombre, apellidos, asunto, idReunion_fk,
    confirmacion  FROM  empleado_reunion INNER JOIN empleado ON
    empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON
    empleado_reunion.idReunion_fk=reunion.id");

        foreach ($consulta->fetchAll() as $fks) {
            $nexo= new Empleado_reunion();
            $nexo->setId($fks['id']);
            $nexo->setIdEmpleado_fk($fks['idEmpleado_fk']);
            $nexo->setIdEmpleadoModelo($fks['idEmpleado']);
            $nexo->setNombreModelo($fks['nombre']);
            $nexo->setApellidosModelo($fks['apellidos']);
            $nexo->setAsuntoModelo($fks['asunto']);
            $nexo->setConfirmacion($fks['confirmacion']);
            $nexo->setIdReunion_fk($fks['idReunion_fk']);
            $array_empleado_reunion[]=$nexo;
        }
        return $array_empleado_reunion;
    }

    /**
     * Ver los empleados que asisten a una reunión. Le pasamos el id de la reunión.
     **/
    public function obtener_vista_asistentes($idReunion_fk, $duracion_reunion)
    {
        $array_empleado_reunion=[];

        $consulta=$this->db->query('SELECT empleado_reunion.id, idEmpleado_fk, idReunion_fk, idEmpleado, nombre, apellidos,
        email, departamento, foto, confirmacion, costeHora FROM  empleado_reunion INNER JOIN empleado
        ON empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id
        WHERE idReunion_fk='.$idReunion_fk);

        foreach ($consulta->fetchAll() as $fks) {
            $nexo= new Empleado_reunion();
            $nexo->setId($fks['id']);
            $nexo->setIdEmpleado_fk($fks['idEmpleado_fk']);
            $nexo->setIdReunion_fk($fks['idReunion_fk']);
            $nexo->setIdEmpleadoModelo($fks['idEmpleado']);
            $nexo->setNombreModelo($fks['nombre']);
            $nexo->setApellidosModelo($fks['apellidos']);
            $nexo->setDepartamento($fks['departamento']);
            $nexo->setEmail($fks['email']);
            $nexo->setFoto($fks['foto']);
            $nexo->setConfirmacion($fks['confirmacion']);
            $nexo->setCosteHora($fks['costeHora'] * $duracion_reunion);
            $array_empleado_reunion[]=$nexo;
        }
        return $array_empleado_reunion;
    }

    public function obtener_vista_reuniones_previstas_sin_permisos($idEmpleado_fk)
    {
        $array_empleado_reunion=[];
        $consulta=$this->db->query('SELECT empleado_reunion.id, idReunion_fk, idEmpleadoOrganizador, asunto, sala, fecha, inicio,
        fin, observaciones, costeEstimado FROM  empleado_reunion INNER JOIN empleado
        ON empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id
        WHERE idEmpleado_fk='.$idEmpleado_fk);

        foreach ($consulta->fetchAll() as $fks) {
            $nexo= new Empleado_reunion();
            $nexo->setId($fks['id']);
            $nexo->setIdReunion_fk($fks['idReunion_fk']);
            $nexo->setOrganizadorModelo($fks['idEmpleadoOrganizador']);
            $nexo->setAsuntoModelo($fks['asunto']);
            $nexo->setSalaModelo($fks['sala']);
            $nexo->setFechaModelo($fks['fecha']);
            $nexo->setInicioModelo($fks['inicio']);
            $nexo->setFinModelo($fks['fin']);
            $nexo->setObservacionesModelo($fks['observaciones']);
            $array_empleado_reunion[]=$nexo;
        }
        return $array_empleado_reunion;
    }


    public function obtener_vista_reuniones_guardadas_sin_permisos($idEmpleado_fk)
    {
        $array_empleado_reunion=[];
        $consulta=$this->db->query('SELECT empleado_reunion.id, idReunion_fk, idEmpleadoOrganizador, asunto, sala, fecha, inicio,
        fin, nombre, apellidos, departamento FROM  empleado_reunion INNER JOIN empleado
        ON empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id
        WHERE idEmpleado_fk='.$idEmpleado_fk);

        foreach ($consulta->fetchAll() as $fks) {
            $nexo= new Empleado_reunion();
            $nexo->setId($fks['id']);
            $nexo->setIdReunion_fk($fks['idReunion_fk']);
            $nexo->setOrganizadorModelo($fks['idEmpleadoOrganizador']);
            $nexo->setAsuntoModelo($fks['asunto']);
            $nexo->setSalaModelo($fks['sala']);
            $nexo->setFechaModelo($fks['fecha']);
            $nexo->setInicioModelo($fks['inicio']);
            $nexo->setFinModelo($fks['fin']);
            $nexo->setNombreModelo($fks['nombre']);
            $nexo->setApellidosModelo($fks['apellidos']);
            $nexo->setDepartamento($fks['departamento']);
            $array_empleado_reunion[]=$nexo;
        }
        return $array_empleado_reunion;
    }


    public function obtener_reunion_detallada($idReunion_fk){
        $array_empleado_reunion=[];
        $consulta=$this->db->query('SELECT empleado_reunion.id, idReunion_fk, asunto, fecha, inicio, fin, costeEstimado, nombre, apellidos,
        departamento, costeHora FROM  empleado_reunion INNER JOIN empleado
        ON empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id
        WHERE idReunion_fk='.$idReunion_fk);

        foreach ($consulta->fetchAll() as $fks) {
            $nexo= new Empleado_reunion();
            $nexo->setId($fks['id']);
            $nexo->setIdReunion_fk($fks['idReunion_fk']);
            $nexo->setAsuntoModelo($fks['asunto']);
            $nexo->setFechaModelo($fks['fecha']);
            $nexo->setInicioModelo($fks['inicio']);
            $nexo->setFinModelo($fks['fin']);
            $nexo->setCosteEstimadoModelo($fks['costeEstimado']);
            $nexo->setNombreModelo($fks['nombre']);
            $nexo->setApellidosModelo($fks['apellidos']);
            $nexo->setDepartamento($fks['departamento']);
            $nexo->setCosteHora($fks['costeHora']);

            $array_empleado_reunion[]=$nexo;
        }
        return $array_empleado_reunion;
    }

    public function obtener_reunion_detallada_Excel($idReunion_fk)
    {
        $consulta = $this->db->prepare("SELECT empleado_reunion.id, idReunion_fk, asunto, fecha, inicio, fin, costeEstimado, nombre, apellidos,
        departamento, costeHora FROM  empleado_reunion INNER JOIN empleado
        ON empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id
        WHERE idReunion_fk= :idReunionfk");
        $mParams = array(':idReunionfk'=>$idReunion_fk);
        $consulta->execute($mParams);
        $arrayReuniones = array();
        while ($row=$consulta->fetch(PDO::FETCH_ASSOC)) {
          $arrayReuniones[] = $row;
        }
        return $arrayReuniones;
    }

    public function allMeetingsBetweenDates($from, $to)
    {
        #Escribimos una consulta preparada con marcadores
        $consulta = $this->db->prepare("SELECT * FROM empleado_reunion INNER JOIN empleado
        ON empleado_reunion.idEmpleado_fk = empleado.id INNER JOIN reunion ON empleado_reunion.idReunion_fk=reunion.id
        WHERE fecha BETWEEN :from AND :to ");
        #Creamos un array que asocia marcadores y datos
        $mParams = array(':from'=>$from, ':to'=>$to);
        #Lo pasamos aquí para neutralizar ataques de hackers
        $consulta->execute($mParams);
        $arrayReuniones = array();
        #Implementamos una lectura más natural y más ligera
        while ($row=$consulta->fetch(PDO::FETCH_ASSOC)) {
          $arrayReuniones[] = $row;
        }
        $respuesta = array(
          'arrayReuniones' => $arrayReuniones
        );
        #Mandamos un JSON, que es lo que el cliente espera
        echo json_encode($arrayReuniones);
    }

    /**
     * comprueba qué empleados asisten a la reunion para que no se muestren como invitados si ya lo están.
     */
    public function compruebaInvitados($idEmpleado_fk, $idReunion_fk)
    {
        $consulta=$this->db->prepare('SELECT idEmpleado_fk, idReunion_fk FROM empleado_reunion WHERE idEmpleado_fk=:idEmpleado_fk and idReunion_fk=:idReunion_fk');
        $consulta->bindValue('idEmpleado_fk', $idEmpleado_fk);
        $consulta->bindValue('idReunion_fk', $idReunion_fk);
        $consulta->execute();
        if ($consulta->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    public function borrarNexo($idNexo)
    {
        $consulta = "DELETE FROM empleado_reunion WHERE id = ?";
        $stmt = $this->db->prepare($consulta);
        $stmt->execute(array($idNexo));
    }
}
