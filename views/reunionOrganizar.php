<?php
include '../includes/header.php';
require_once('../model/reunionDAO.php');
require_once('../model/reunionModelo.php');
$idEmpleado = $_SESSION['idEmpleado'];
$reunionDAO= new ReunionDAO();
$reunion = new Reunion();
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $reunion=$reunionDAO->obtenerReunion($id);
} else {
  $id = '';
}
?>

<div class="contenedor-alta-empleados">
    <h2><?php echo ($id) ? 'Modifique su Reunión' : 'Planifique su Reunión'?></h2>
    <form name="organizarReunion" action="../controller/reunionControlador.php" method="POST">

        <div class="contenedor-campos">

          <input type="hidden" name="id" id="id" value="<?php echo ($id) ? $reunion->getId() : ''?>">

            <div class="campo-alta-empleados">
                <label for="idEmpleadoOrganizador">Organizado por (ID):</label>
                <input type="text" readonly name="idEmpleadoOrganizador" id="idEmpleadoOrganizador" value="<?php echo ($id) ? $reunion->getIdEmpleadoOrganizador() : $idEmpleado ?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="asunto">Asunto:</label>
                <input type="text" name="asunto" id="asunto" value="<?php echo ($id) ? $reunion->getAsunto() : ''?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="sala">Sala:</label>
                <select type="text" name="sala" id="sala">
                <option value="<?php echo ($id) ? $reunion->getSala() : ''?>" <?php echo ($id) ? '' : 'disabled selected'?>><?php echo ($id) ? $reunion->getSala() : '--Selecciones una sala--'?></option>
                <option value="BIBLIOTECA">BIBLIOTECA</option>
                <option value="Aula 0.1">Aula 0.1</option>
                <option value="Aula 1.1">Aula 1.1</option>
                <option value="Aula 2.1">Aula 2.1</option>
                <option value="Aula 2.2">Aula 2.2</option>
                <option value="Aula 2.3">Aula 2.3</option>
                <option value="Aula 2.4">Aula 2.4</option>
                <option value="Aula 3.1">Aula 3.1</option>
                <option value="Aula 3.2">Aula 3.2</option>
                <option value="Aula 3.3">Aula 3.3</option>
                <option value="Aula 4.1">Aula 4.1</option>
                <option value="SALA GABINETE">GABINETE</option>
                <option value="SALA DE JUNTAS">SALA DE JUNTAS</option>
                <option value="Despacho 1">Despacho 1</option>
                <option value="Despacho 2">Despacho 2</option>
                <option value="Despacho 3">Despacho 3</option>
                <option value="Despacho 4">Despacho 4</option>
                <option value="Despacho 5">Despacho 5</option>
                <option value="Despacho 6">Despacho 6</option>
                <option value="Despacho 7">Despacho 7</option>
                <option value="Despacho 8">Despacho 8</option>
                <option value="Despacho 9">Despacho 9</option>
                <option value="Despacho 10">Despacho 10</option>
                </select>
            </div>

            <?php
            $datePickerJquery = $reunion->getFecha();
            $fechaFormateada = date('d-m-Y', strtotime($datePickerJquery));
            ?>

            <div class="campo-alta-empleados">
                <label for="fecha">Fecha:</label>
                <input type="text" name="fecha" id="fecha" autocomplete="off" value="<?php echo ($id) ? $fechaFormateada : ''?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="inicio">Comienza:</label>
                <input type="text" name="inicio" id="inicio" autocomplete="off" value="<?php echo ($id) ? $reunion->getInicio() : ''?>">
            </div>

            <div class="campo-alta-empleados">
                <label for="fin">Termina:</label>
                <input type="text" name="fin" id="fin" autocomplete="off" value="<?php echo ($id) ? $reunion->getFin() : ''?>">
            </div>

            <div class="campo-alta-alta-empleados">
                <label for="observaciones">Observaciones:</label>
                <textarea type="text" name="observaciones" id="observaciones" value=""><?php echo ($id) ? $reunion->getObservaciones() : ''?></textarea>
            </div>

        </div>

        <div class="enviar-alta-empleados">
            <input type="submit" name="<?php echo ($id) ? 'botonActualizarReunion' : 'botonOrganizarReunion'?>" class="boton" value="<?php echo ($id) ? 'Confirmar Cambios' : 'Organizar'?>">
        </div>

    </form>
</div>

<?php
include '../includes/footer.php';
?>
