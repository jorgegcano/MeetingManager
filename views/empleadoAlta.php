<?php
include '../includes/header.php';
require_once('../model/empleadoDAO.php');
require_once('../model/empleadoModelo.php');
$empDAO= new EmpleadoDAO();
$empleado = new Empleado();
($_GET['id']) ? $id=$_GET['id'] : '';
$empleado=$empDAO->obtenerEmpleado($id);
?>

<div class="contenedor-alta-empleados">

    <h2><?php echo ($id) ? "Editar Datos/Foto" : "Altas"; ?></h2>

    <form name="altaEmpleados" id="formularioAltaEmpleados" action="#" method="POST" enctype="multipart/form-data">

        <div class="contenedor-campos">

            <input type="hidden" name="id" id="id" value="<?php echo ($id) ? $empleado->getId() : '' ?>">

            <?php

            if (!$_SESSION || $_SESSION['permisos'] == 1) {

            ?>

            <div class="campo-alta-empleados">
                <label for="idEmpleado">ID Empleado:</label>
                <input type="text" name="idEmpleado" id="idEmpleado" data-validation="required" value="<?php echo ($id) ? $empleado->getIdEmpleado() : '' ?>">
            </div>

            <?php
            $empleado->getPaswordFields($id);
            ?>

            <div class="campo-alta-empleados">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" data-validation="required" value="<?php echo ($id) ? $empleado->getNombre() : '' ?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" data-validation="required" value="<?php echo ($id) ? $empleado->getApellidos() : '' ?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" data-validation="email" data-validation-error-msg="Inserte un formato de E-mail válido" value="<?php echo ($id) ? $empleado->getEmail() : '' ?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="departamento">Departamento:</label>
                <input type="text" name="departamento" id="departamento" data-validation="required" value="<?php echo ($id) ? $empleado->getDepartamento() : '' ?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="costeHora">Coste por Hora:</label>
                <input type="number" min="0" max="100" step="any" id="costeHora" name="costeHora" data-validation="required" value="<?php echo ($id) ? $empleado->getCosteHora() : '' ?>">
            </div>

            <?php
              }
            ?>

            <div class="campo-alta-alta-empleados">
                <label id="<?php echo ($_SESSION['permisos'] == 1) ? 'fotoLbl' : 'fotoLblEmp'?>" for="foto" value="<?php echo ($id) ? $empleado->getPreviewImageUpdating($id, $empleado->getFoto()) : '' ?>"><i class="fas fa-camera"></i></label>
                <input type="file" name="foto" max="1" accept="image/*" id="foto">
            </div>

        </div>

        <div class="enviar-alta-empleados">
            <input type="hidden" name=accion id="accion" value="<?php echo ($id) ? 'actualizarEmpleado' : 'crearEmpleado' ?>">
            <input type="submit" name="botonAltaEmpleado" id="btn-enviar" class="boton" value="<?php echo ($id) ? 'Guardar Cambios' : 'Registrar' ?>">
        </div>

    </form>
</div>

<?php
include '../includes/footer.php';
?>
