<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once('reunionModelo.php');

class ReunionDAO
{
    private $db;

    public function __construct()
    {
        require_once("conexion.php");
        $this->db = Conectar::conexion();
    }

    public function insertarReunion($reunion)
    {
        $stmt = $this->db->prepare("INSERT INTO reunion (idEmpleadoOrganizador, asunto, sala, fecha, inicio, fin, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $reunion->getIdEmpleadoOrganizador());
        $stmt->bindValue(2, $reunion->getAsunto());
        $stmt->bindValue(3, $reunion->getSala());
        $stmt->bindValue(4, $reunion->getFecha());
        $stmt->bindValue(5, $reunion->getInicio());
        $stmt->bindValue(6, $reunion->getFin());
        $stmt->bindValue(7, $reunion->getObservaciones());
        $resultadoRegistro = $stmt->execute();
        if ($resultadoRegistro) {
            return $this->db->lastInsertId();
        } else {
            return -1;
        }
    }

    public function insertar_coste_estimado($reunion)
    {
        $stmt = $this->db->prepare('UPDATE reunion SET costeEstimado=? WHERE id=?');
        $stmt->bindValue(1, $reunion->getCosteEstimado());
        $stmt->bindValue(2, $reunion->getId());
        $stmt->execute();
    }

    public function todasLasReuniones()
    {
        $arrayReuniones = [];
        $consulta = $this->db->query("SELECT * FROM reunion");

        foreach ($consulta->fetchAll() as $reu) {
            $reunion = new Reunion();
            $reunion->setId($reu['id']);
            $reunion->setIdEmpleadoOrganizador($reu['idEmpleadoOrganizador']);
            $reunion->setAsunto($reu['asunto']);
            $reunion->setSala($reu['sala']);
            $reunion->setFecha($reu['fecha']);
            $reunion->setInicio($reu['inicio']);
            $reunion->setFin($reu['fin']);
            $reunion->setObservaciones($reu['observaciones']);
            $reunion->setCosteEstimado($reu['costeEstimado']);
            $arrayReuniones[] = $reunion;
        }
        return $arrayReuniones;
    }

    public function obtener_reuniones_Excel()
    {
        $consulta = $this->db->prepare("SELECT * FROM reunion");
        $consulta->execute();
        $arrayReuniones = array();
        while ($row=$consulta->fetch(PDO::FETCH_ASSOC)) {
          $arrayReuniones[] = $row;
        }
        return $arrayReuniones;
    }

    public function borrarReunion($id)
    {
        $consulta = "DELETE FROM reunion WHERE id = ?";
        $stmt = $this->db->prepare($consulta);
        $stmt->execute(array($id));
    }

    public function obtenerReunion($id)
    {
        $consulta = $this->db->prepare('SELECT * FROM reunion WHERE ID=:id');
        $consulta->bindValue('id', $id);
        $consulta->execute();
        $reunion = $consulta->fetch();
        $reunionRecuperado = new Reunion();
        $reunionRecuperado->setId($reunion['id']);
        $reunionRecuperado->setIdEmpleadoOrganizador($reunion['idEmpleadoOrganizador']);
        $reunionRecuperado->setAsunto($reunion['asunto']);
        $reunionRecuperado->setSala($reunion['sala']);
        $reunionRecuperado->setFecha($reunion['fecha']);
        $reunionRecuperado->setInicio($reunion['inicio']);
        $reunionRecuperado->setFin($reunion['fin']);
        $reunionRecuperado->setObservaciones($reunion['observaciones']);
        $reunionRecuperado->setCosteEstimado($reunion['costeEstimado']);
        return $reunionRecuperado;
    }

    public function obtenerUltimoIdReunion()
    {
        $rs = $this->db->lastInsertId();
        return $rs;
    }

    public function obtenerCosteReunion($id)
    {
        $consulta = $this->db->prepare('SELECT * FROM reunion WHERE ID=:id');
        $consulta->bindValue('id', $id);
        $consulta->execute();
        $reunion = $consulta->fetch();
        $reunionRecuperado = new Reunion();
        $coste = $reunionRecuperado->setCosteEstimado($reunion['costeEstimado']);
        return $coste;
    }

    public function actualizarReunion($reunion)
    {
        $stmt = $this->db->prepare('UPDATE reunion SET idEmpleadoOrganizador=?, asunto=?, sala=?, fecha=?, inicio=?, fin=?, observaciones=? WHERE id=?');
        $stmt->bindValue(1, $reunion->getIdEmpleadoOrganizador());
        $stmt->bindValue(2, $reunion->getAsunto());
        $stmt->bindValue(3, $reunion->getSala());
        $stmt->bindValue(4, $reunion->getFecha());
        $stmt->bindValue(5, $reunion->getInicio());
        $stmt->bindValue(6, $reunion->getFin());
        $stmt->bindValue(7, $reunion->getObservaciones());
        $stmt->bindValue(8, $reunion->getId());
        $stmt->execute();
    }

    public function comprobar_sala_fecha($salaPropuesta, $fechaPropuesta)
    {
        $contador = 0;
        $consulta = $this->db->prepare("SELECT * FROM reunion");
        $consulta->execute();
        $reunion = new Reunion();
        $reunionDao = new ReunionDAO();
        $listaReuniones = $reunionDao->todasLasReuniones();

        foreach ($listaReuniones as $reunion) {
            $sala = $reunion->getSala();
            $fecha = $reunion->getFecha();

            if ($sala == $salaPropuesta && $fecha == $fechaPropuesta) {
                $contador++;
            }
        }

        if ($contador > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function recuperar_reuniones_sala_fecha($sala, $fecha)
    {
        $array_horas_reuniones=[];

        $consulta=$this->db->query("SELECT id, inicio, fin FROM reunion WHERE sala='$sala' and fecha='$fecha'");

        foreach ($consulta->fetchAll() as $datos) {

            $reunion= new Reunion();
            $reunion->setId($datos['id']);
            $reunion->setInicio($datos['inicio']);
            $reunion->setFin($datos['fin']);
            $array_horas_reuniones[]=$reunion;
        }

        //

        if (count($array_horas_reuniones) > 0) {
          return $array_horas_reuniones;
        }else {
          return false;
        }

    }

    public function calcular_duracion($id)
    {
        $consulta = $this->db->prepare('SELECT inicio, fin FROM reunion WHERE ID=:id');
        $consulta->bindValue('id', $id);
        $consulta->execute();
        $reunion = $consulta->fetch();
        $reunionRecuperado = new Reunion();
        $inicioR = $reunionRecuperado->setInicio($reunion['inicio']);
        $finR = $reunionRecuperado->setFin($reunion['fin']);
        $inicio=$reunionRecuperado->getInicio($inicioR);
        $fin=$reunionRecuperado->getFin($finR);

        list($horas, $minutos) = explode(':', $inicio);
        $hora_en_minutos_inicio = ($horas * 60) + $minutos;

        list($horas, $minutos) = explode(':', $fin);
        $hora_en_minutos_fin = ($horas * 60) + $minutos;

        $duracion = $hora_en_minutos_fin - $hora_en_minutos_inicio;

        //Esta variable la multiplico por el coste del empleado.
        $duracion_calculo_coste = $duracion/60;

        return $duracion_calculo_coste;
    }

}
