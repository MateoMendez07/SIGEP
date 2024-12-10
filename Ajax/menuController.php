<?php
// controlador/menuController.php
session_start();
require "menu_config.php";

$rol = $_SESSION['tipo_rol'] ?? null;
$menuItems = $rol ? ($menuOptions[$rol] ?? []) : [];
?>
