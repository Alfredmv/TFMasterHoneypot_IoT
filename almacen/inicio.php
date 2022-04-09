<?php
/*
 * Created on 04/03/2022
 *
 * Estas tareas solo se ejecutan una vez al inicio
 *
 */
include_once "./Lib/mysql_inc.php";
include_once "./Lib/funciones.php";
include_once "./Lib/funciones_html.php";
headerNoCache();

 	//session_start();

	$opcion="Mostrar";  //opción por defecto
    if (session_status() !== PHP_SESSION_ACTIVE)
    {
        session_start();
    }
    $_SESSION['opcion']=$opcion;
    logacceso();
 	include_once "Main.php";
?>