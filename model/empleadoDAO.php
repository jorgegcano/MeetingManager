<?php

include_once('empleadoModelo.php');

class EmpleadoDAO
{
    private $db;

    public function __construct()
    {
        require_once("conexion.php");
        $this->db=Conectar::conexion();
    }

    public function insertarEmpleado($empleado)
    {
        $password = password_hash($empleado->getPassword(), PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO empleado (idEmpleado, password, nombre, apellidos, email, departamento, costeHora, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $empleado->getIdEmpleado());
        $stmt->bindValue(2, $password);
        $stmt->bindValue(3, $empleado->getNombre());
        $stmt->bindValue(4, $empleado->getApellidos());
        $stmt->bindValue(5, $empleado->getEmail());
        $stmt->bindValue(6, $empleado->getDepartamento());
        $stmt->bindValue(7, $empleado->getCosteHora());
        $stmt->bindValue(8, $empleado->getFoto());
        $resultadoRegistro = $stmt->execute();
        if ($resultadoRegistro > 0) {
          echo 1;
            //return $this->db->lastInsertId();
        } else {
          echo 0;
            //return -1;
        }
    }

    public function todosLosEmpleados()
    {
        $arrayEmp=[];
        $consulta=$this->db->query("SELECT * FROM empleado");

        foreach ($consulta->fetchAll() as $emp) {
            $empleado= new Empleado();
            $empleado->setId($emp['id']);
            $empleado->setIdEmpleado($emp['idEmpleado']);
            $empleado->setPassword($emp['password']);
            $empleado->setNombre($emp['nombre']);
            $empleado->setApellidos($emp['apellidos']);
            $empleado->setEmail($emp['email']);
            $empleado->setDepartamento($emp['departamento']);
            $empleado->setCosteHora($emp['costeHora']);
            $empleado->setFoto($emp['foto']);
            $empleado->setActivo($emp['activo']);
            $arrayEmp[]=$empleado;
        }
        return $arrayEmp;
    }

    public function borrarEmpleado($id)
    {
        $consulta = "UPDATE empleado SET activo = 0 WHERE id = ?";
        $stmt = $this->db->prepare($consulta);
        $stmt->execute(array($id));
    }

    public function obtenerEmpleado($id)
    {
        $consulta=$this->db->prepare('SELECT * FROM empleado WHERE ID=:id');
        $consulta->bindValue('id', $id);
        $consulta->execute();
        $empleado=$consulta->fetch();
        $empleadoRecuperado= new Empleado();
        $empleadoRecuperado->setId($empleado['id']);
        $empleadoRecuperado->setIdEmpleado($empleado['idEmpleado']);
        $empleadoRecuperado->setPassword($empleado['password']);
        $empleadoRecuperado->setNombre($empleado['nombre']);
        $empleadoRecuperado->setApellidos($empleado['apellidos']);
        $empleadoRecuperado->setEmail($empleado['email']);
        $empleadoRecuperado->setDepartamento($empleado['departamento']);
        $empleadoRecuperado->setCosteHora($empleado['costeHora']);
        $empleadoRecuperado->setFoto($empleado['foto']);
        $empleadoRecuperado->setPermisos($empleado['permisos']);
        return $empleadoRecuperado;
    }

    public function obtenerIdPK($idEmpleado)
    {
        $consulta=$this->db->prepare('SELECT id FROM empleado WHERE idEmpleado=:idEmpleado');
        $consulta->bindValue('idEmpleado', $idEmpleado);
        $consulta->execute();
        $empleado=$consulta->fetch();
        $empleadoRecuperado= new Empleado();
        $empleadoRecuperado->setId($empleado['id']);
        return $empleadoRecuperado;
    }

    public function obtenerPermisos($idEmpleado)
    {
        $consulta=$this->db->prepare('SELECT id, nombre, permisos FROM empleado WHERE idEmpleado=:idEmpleado');
        $consulta->bindValue('idEmpleado', $idEmpleado);
        $consulta->execute();
        $empleado=$consulta->fetch();
        $empleadoRecuperado= new Empleado();
        $empleadoRecuperado->setId($empleado['id']);
        $empleadoRecuperado->setNombre($empleado['nombre']);
        $empleadoRecuperado->setPermisos($empleado['permisos']);
        return $empleadoRecuperado;
    }

    public function get_coste_hora_cada_empleado($idEmpleado)
    {
        $consulta=$this->db->prepare('SELECT costeHora FROM empleado WHERE idEmpleado=:idEmpleado');
        $consulta->bindValue('idEmpleado', $idEmpleado);
        $consulta->execute();
        $empleado=$consulta->fetch();
        $empleadoRecuperado= new Empleado();
        $empleadoRecuperado->setCosteHora($empleado['costeHora']);
        $coste = $empleadoRecuperado->getCosteHora();
        return $coste;
    }

    public function get_coste_hora_cada_empleado_by_pk($id)
    {
        $consulta=$this->db->prepare('SELECT costeHora FROM empleado WHERE id=:id');
        $consulta->bindValue('id', $id);
        $consulta->execute();
        $empleado=$consulta->fetch();
        $empleadoRecuperado= new Empleado();
        $empleadoRecuperado->setCosteHora($empleado['costeHora']);
        $coste = $empleadoRecuperado->getCosteHora();
        return $coste;
    }


    public function actualizarEmpleado($empleado)
    {
        $stmt = $this->db->prepare('UPDATE empleado SET idEmpleado=?, nombre=?, apellidos=?, email=?, departamento=?, costeHora=?, foto=?  WHERE id=?');
        $stmt->bindValue(1, $empleado->getIdEmpleado());
        $stmt->bindValue(2, $empleado->getNombre());
        $stmt->bindValue(3, $empleado->getApellidos());
        $stmt->bindValue(4, $empleado->getEmail());
        $stmt->bindValue(5, $empleado->getDepartamento());
        $stmt->bindValue(6, $empleado->getCosteHora());
        $stmt->bindValue(7, $empleado->getFoto());
        $stmt->bindValue(8, $empleado->getId());
        $resultadoRegistro = $stmt->execute();
        if ($resultadoRegistro > 0) {
          echo 1;
            //return $this->db->lastInsertId();
        } else {
          echo 0;
            //return -1;
        }
    }

    public function actualizarEmpleadoSinPermisos($empleado)
    {
        $stmt = $this->db->prepare('UPDATE empleado SET idEmpleado=?, nombre=?, apellidos=?, email=?, departamento=?, foto=?  WHERE id=?');
        $stmt->bindValue(1, $empleado->getIdEmpleado());
        $stmt->bindValue(2, $empleado->getNombre());
        $stmt->bindValue(3, $empleado->getApellidos());
        $stmt->bindValue(4, $empleado->getEmail());
        $stmt->bindValue(5, $empleado->getDepartamento());
        $stmt->bindValue(6, $empleado->getFoto());
        $stmt->bindValue(7, $empleado->getId());
        $stmt->execute();
    }

    public function compruebaEmpleado($idEmpleado, $password)
    {
        $consulta=$this->db->prepare("SELECT * FROM empleado WHERE idEmpleado=:idEmpleado");
        $consulta->bindValue('idEmpleado', $idEmpleado);
        $consulta->execute();
        $empleado = $consulta->fetch();
        if ($idEmpleado == $empleado['idEmpleado'] && password_verify($password, $empleado['password']))
        {
              echo 1;
        }
        else
        {
              echo 0;
        }

    }
}
