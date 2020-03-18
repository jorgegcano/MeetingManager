<?php
include_once '../model/empleadoDAO.php';
include_once '../model/conexion.php';

if(isset($_GET['cerrarSesion'])){
    session_start();
    session_destroy();
}
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/normalize.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="../css/style.css">
    <title>APP</title>
</head>

<body>

    <header class="cabecera">
        <a href="../index.php"><img class="logo" src="../img/imf_logo.jpg" alt="imf_logo" height="120" width="230"></a>
    </header>
    <section>

    <div class="contenedor">
        <h2>Acceso Usuario</h2>
        <form name="accesoEmpleados" id="formularioLogin" action="#" method="POST">

            <div class="centrar-etiqueta">
                <label for="">ID Empleado:</label>
            </div>
            <input type="text" name="idEmpleado" id="idEmpleadoLogin">
            <div class="centrar-etiqueta">
                <label for="">Contraseña:</label>
            </div>
            <input type="password" name="password" id="passwordLogin">

            <div class="enviar">
                <input type="hidden" name=accion id="accion" value="accesoEmpleado">
                <input type="submit" class="boton" value="Acceder">
            </div>
        </form>

        <div class="link_registro">
        <span class="texto_link_registro">¿Aún no tienes acceso?</span><a href="empleadoAlta.php"> Regístrate aquí</a>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>
