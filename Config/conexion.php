<?php
require_once "global.php";

if (!function_exists('Fn_getConnect')) {
    function Fn_getConnect() {
        $conexion1 = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($conexion1->connect_error) {
            die("Error conectando a la base de datos: " . $conexion1->connect_error);
        }

        return $conexion1;
    }
}
?>
