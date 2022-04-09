<?php

// Autor:Alfredo Martinez Viso
// Descarga via HTTP elarchivo excel generado
// Marzo 2022:

    if (session_status() !== PHP_SESSION_ACTIVE)
    {
        session_start();
    }


    $exceltipo=$_SESSION['excel_tipo'];
    $datos=$_SESSION['datosexcel'];
    $nombre=$_SESSION['nombre_informe'];
    $cabecero=$_SESSION['cabecero'];


	$today = date("d/m/Y H:i:s");
    header('Content-Encoding: UTF-8');
    //header('Content-Encoding: ISO-8859-1');
    //header('Content-Encoding: windows-1251');
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    //header('Content-Type: application/vnd.ms-excel; charset=windows-1252');
    //header('Content-Type: application/vnd.ms-excel; charset=ISO-8859-1');
    //header('Content-Type: application/vnd.ms-excel; charset=windows-1251');
    //header('Content-Type: application/vnd.ms-excel; charset=ISO-8859-15');

	//header('Content-type: application/vnd.ms-excel');

    $exten='xls';
    if(isset($exceltipo))
    {
        if($exceltipo == 'CSV')
        {
         $exten='csv';
        }
    }


    header("Content-Disposition: attachment; filename=$nombre.$exten");

	header("Pragma: no-cache");
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
	header("Expires: 0");
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/

	/* Recibe cabecero personalizado */
	echo $cabecero;

	/* Datos de los cursos */
	echo "$datos";

	//echo "</table> ";
?>