<?php
// Autor:Alfredo Martinez Viso
// callback (jQuery)
// Anota en la BD la fecha y hora de salida  de la aplicación,cierre de sesion
// Marzo 2022:
 //include_once "./Lib/codigo_estandar.php";
 include_once "./Lib/funciones.php";
 include_once "./Lib/mysql_inc.php";

 if (session_status() !== PHP_SESSION_ACTIVE)
 {
     session_start();
 }

 $IP= get_client_ip();

//fecha local actual en España
 date_default_timezone_set('Europe/Madrid');
 $fechaactual = date('Y-m-d H:i:s', time());
 //guardar el cierre de sesion
 $entradafecha=$_SESSION['HORAENTRADA'];
 logsalida($IP, $entradafecha);

 $ValRe=array('rIp' => $IP,'salidaFechaHora' => $fechaactual ,'EntradaFechaHora' => $entradafecha);
 $jValRe= json_encode($ValRe);
 echo $jValRe; 

?>