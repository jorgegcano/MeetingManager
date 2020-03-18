<?php
include "model/sesionEmpleado.php";
include "includes/header.php";
/*
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
*/
?>

<div class="contenedor-titulo-menu-ppal">
    <h2>Menú Principal</h2>
</div>

<div class="contenedor-botones">

<a href="views/reunion_guardada.php">
    <div class="botones-menu-ppal">
            <i class="fas fa-check-circle iconos"></i>
            <p>Reuniones Realizadas</p>
    </div>
</a>

<a href="views/reunionHistorial.php">
    <div class="botones-menu-ppal">
            <i class="fas fa-calendar-alt iconos"></i>
            <p>Reuniones Pendientes</p>
    </div>
</a>

<?php if ($_SESSION['permisos'] == 1){?>

    <a href="views/empleadoHistorial.php">
    <div class="botones-menu-ppal">
            <i class="fas fa-address-card iconos"></i>
            <p>Perfiles</p>
    </div>
</a>
<?php
}else{?>

    <a href="views/empleadoHistorial.php">
    <div class="botones-menu-ppal">
            <i class="fas fa-address-card iconos"></i>
            <p>Mi Perfil</p>
    </div>
</a>
<?php
}?>

<?php if ($_SESSION['permisos'] == 1){?>

    <a href="views/empleadoAlta.php">
    <div class="botones-menu-ppal">
            <i class="fas fa-user-plus iconos"></i>
            <p>Nuevo Registro</p>
    </div>
</a>
<?php
}?>
    <a href="views/reunionOrganizar.php">
    <div class="botones-menu-ppal">
            <i class="fas fa-calendar-plus iconos"></i>
            <p>Organizar Reunión</p>
    </div>
</a>


</div>

<?php include "includes/footer.php"; ?>
