<?php
// controlador/menuController.php
session_start();
require "menu_config.php";

$rol = $_SESSION['tipo_rol'] ?? null; //verifica al rol que pertenece
$menuItems = $rol ? ($menuOptions[$rol] ?? []) : []; //asigna el menu correspondiente segun el rol
?>
