<?php
include '../model/sesionEmpleado.php';
$url = $_SERVER["REQUEST_URI"];
$parts = explode("/", $url);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
    if (end($parts) == 'index.php'){
      ?>
      <link rel="stylesheet" href="css/normalize.css">
      <?php
    }
    else
    {
      ?>
      <link rel="stylesheet" href="../css/normalize.css">
      <?php
    }
    ?>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <?php
    if (end($parts) == 'index.php'){
      ?>
      <link rel="stylesheet" href="css/style.css">
      <?php
    }
    else
    {
      ?>
      <link rel="stylesheet" href="../css/style.css">
      <?php
    }
    ?>
    <title>APP</title>
</head>

<body>

  <?php
  if (end($parts) == 'index.php'){
    ?>
    <header class="cabecera">
        <a><img class="logo" src="img/imf_logo.jpg" alt="imf_logo" height="120" width="230"></a>
        <span class='cerrar_sesion_link'><a href="views/login.php?cerrarSesion=true">Cerrar Sesión de <?php echo $_SESSION['nombre'] ?></a></span>
    </header>
    <?php
  }
  else
  {
    ?>
    <header class="cabecera">
        <a href="../index.php"><img class="logo" src="../img/imf_logo.jpg" alt="imf_logo" height="120" width="230"></a>
    </header>
    <?php
  }
  ?>

    <section>
