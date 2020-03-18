<?php

class Empleado
{
    private $id;
    private $idEmpleado;
    private $password;
    private $nombre;
    private $apellidos;
    private $email;
    private $departamento;
    private $costeHora;
    private $foto;
    private $permisos;
    private $activo;

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


    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
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

    public function getCosteHora()
    {
        return $this->costeHora;
    }

    public function setCosteHora($costeHora)
    {
        $this->costeHora = $costeHora;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    public function getPermisos(){
        return $this->permisos;
    }

    public function setPermisos($permisos){
        $this->permisos = $permisos;
    }

    public function getActivo(){
        return $this->activo;
    }

    public function setActivo($activo){
        $this->activo = $activo;
    }

    public function getPaswordFields($id){
        if (!$id) {
          echo "<div class='campo-alta-empleados'>
                  <label for='password'>Contraseña:</label>
                  <input type='password' name='password' id='password' data-validation='required'>
                </div>

                <div class='campo-alta-empleados'>
                  <label for='password2'> Confirmar Contraseña:</label>
                  <input type='password' name='password2' id='password2' data-validation='required'>
                </div>";
        }
    }

    public function getPreviewImageUpdating($id, $photo){
        if ($id && $photo) {
          echo '../img/'.$photo;
        }
    }

    public function cargarFoto($nombreArchivo)
    {
        $dir='../img/';

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dir.$nombreArchivo)) {
            return false;
        }

        return true;
    }

    public function checkPhoto($photo) {
      if ($photo == null) {
        echo "<div class='noPhoto'><i class='fas fa-camera'></i></div>";
      }else {
        echo "<img class='foto' src='../img/".$photo."'>";
      }
    }

    public function listado($idReunion)
    {
        require_once 'empleadoDAO.php';

        $dao = new EmpleadoDAO();

        $empleado = new Empleado();

        echo "<input type='text' id='buscar' class='buscador' placeholder='Buscar por nombre y/o apellidos...'>";

        $listaEmpleados = $dao->todosLosEmpleados();

        echo   "<div class='plans' id='employer-plan'>";

        foreach ($listaEmpleados as $empleado) :

        if (!$empleado->getActivo() == 0) {
                            ?>
        <div class='plan'>
            <h2 class='plan-title'><?php echo $empleado->getNombre() . ' ' . $empleado->getApellidos() ?></h2>
            <h2 class='plan-title'><?php echo $empleado->getCosteHora() . ' €/H' ?></h2>
            <?php echo $this->checkPhoto($empleado->getFoto()); ?>
            <ul class='datos-empleado'>
                <li><strong>ID: </strong><?php echo $empleado->getIdEmpleado() ?></li>
                <li><strong>DEPARTAMENTO: </strong><?php echo $empleado->getDepartamento() ?></li>
                <li><strong>EMAIL: </strong> <?php echo $empleado->getEmail() ?> </li>
            </ul>
            <a data_id_employer='<?php echo $empleado->getId() ?>' class='employer-plans-edit' href='#'>
            <i class="fas fa-user-edit"></i>
            </a>
            <a  data_id_employer='<?php echo $empleado->getId() ?>' class='employer-plans-delete' href='#'>
            <i class="fas fa-trash-alt"></i>
            </a>
        </div>
    <?php
  }
endforeach;

        echo "</div>";

        return $listaEmpleados;
    }

    public function listar_para_invitar($idReunion_fk)
    {
        require_once 'empleado_reunion_DAO.php';

        $comprobarInvitados = new Empleado_reunion_DAO();

        require_once 'empleadoDAO.php';

        $dao = new EmpleadoDAO();

        $empleado = new Empleado();

        $listaEmpleados = $dao->todosLosEmpleados();

        $contador = 0;

        echo   "<div class='plans' id='plans'>";

        foreach ($listaEmpleados as $empleado) {

            if (!$comprobarInvitados->compruebaInvitados($empleado->getId(), $idReunion_fk) && !$empleado->getactivo() == 0) { ?>

            <div style="position:relative;" class='planInvite' data-position='<?php echo $contador++ ?>' data-mail='<?php echo $empleado->getEmail()?>' data-id='<?php echo $empleado->getId() ?>'>
                <input type="hidden" id="accion" value="invitarEmpleado">
                <h2 class='plan-title cursor'><?php echo $empleado->getNombre() . ' ' . $empleado->getApellidos() ?></h2>
                <h2 class='plan-title cursor'><?php echo $empleado->getCosteHora() . ' €/H' ?></h2>
                <?php echo $this->checkPhoto($empleado->getFoto()); ?>
                <ul class='datos-empleado'>
                    <li><strong>ID: </strong><?php echo $empleado->getIdEmpleado() ?></li>
                    <li><strong>DEPARTAMENTO: </strong><?php echo $empleado->getDepartamento() ?></li>
                    <li><strong>EMAIL: </strong> <?php echo $empleado->getEmail() ?> </li>
                </ul>
            </div>

        <?php }
        }
        echo "</div>";
        return $listaEmpleados;
    }

    public function informacion_personal()
    {

        require_once 'empleadoDAO.php';

        $dao = new EmpleadoDAO();

        $empleado = new Empleado();

        $idEmpleadoPK = $_SESSION['id'];

        $datosEmpleado = $dao->obtenerEmpleado($idEmpleadoPK);

        echo  "<div style='display:flex;width:fit-content;background-color: #ffffff; margin: 0 2rem; padding: 2rem; border-radius: 1rem;'>";?>

        <div>
          <div style='display:flex;'>
          <?php echo $this->checkPhoto($datosEmpleado->getFoto()); ?>
          </div>
          <a style='display:flex;justify-content:center;' href="../views/empleadoAlta.php?id=<?php echo $datosEmpleado->getId() ?>">
          <i style='font-size: 3rem;color: #8f3237;' class="fas fa-edit"></i>
          </a>
            <h1 style="color:#4f4f4f"><?php echo $datosEmpleado->getNombre() . ' ' . $datosEmpleado->getApellidos() ?></h1>
        </div>
          <div style='padding-left: 5rem';>
            <h2 style="color:#4f4f4f"><strong>ID: </strong><?php echo $datosEmpleado->getIdEmpleado() ?></h2>
            <h2 style="color:#4f4f4f"><strong>DEPARTAMENTO: </strong><?php echo $datosEmpleado->getDepartamento() ?></h2>
            <h2 style="color:#4f4f4f"><strong>EMAIL: </strong> <?php echo $datosEmpleado->getEmail() ?></h2>
          </div>
        <?php
        echo "</div>";
        return $datosEmpleado;
    }
}
