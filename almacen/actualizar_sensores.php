<?php
// Autor:Alfredo Martinez Viso
// callback (jQuery), con datos de retorno en JSON
// devuelve la temperatura y humedad en fecha y hora actual
// devuelve el estado de la Temperatura y Humedad programadas
// ("EN PROCESO", "FINALIZADO")
// Marzo 2022:


 //include_once "./Lib/codigo_estandar.php";
 include_once "./Lib/funciones.php";
 include_once "./Lib/mysql_inc.php";
 if (session_status() !== PHP_SESSION_ACTIVE)
 {
     session_start();
 }

  
 $result="";

 date_default_timezone_set('Europe/Madrid');
 $fecha_hora_actual = date('Y-m-d H:i:s', time());

 // TEPERATURA Y HUMEDAD A PROGRAMAR recivido via ajax
 $IP= get_client_ip(); //$_REQUEST["xIp"];
 $Tpro=(float)$_REQUEST["xTpro"];
 $Hpro=(float)$_REQUEST["xHpro"];

 // valore por defecto
 global $DefaultTempe ;
 global $DefaultHume ;

 global $TunidadTiempo; //Unudad de tiempo para Temeperatura
 global $HunidadTiempo; //Unidad de timpo para Humedad
 global $TunidadPotencia; //Unidad de Pontencia de instalcion en Temperatura expresdado en ºC
 global $HunidadPotencia; //Unidad de Potencia instalacion en Humedad expresdo en %)

 $programado=false;
 $row=array();  
 //$IP=get_client_ip();
//fecha local actual en España
 date_default_timezone_set('Europe/Madrid');
 $fechaactual = date('Y-m-d H:i:s', time());

 if(existeIPoperacion($IP))
 {
     $row=maxIPoperacion($IP);                 //ultima operacion realizada para IP dada
     $I_FECHAHORA_old=$row['I_FECHAHORA'];
     $I_TEMPERATURA_old=(float)$row['I_TEMPERATURA'];
     $I_HUMEDAD_old=(float)$row['I_HUMEDAD'];
     $F_TEMPERATURA_old=$row['F_TEMPERATURA'];
     $F_HUMEDAD_old=$row['F_HUMEDAD'];

     //diferencia en minutos entre la fecha de la ultima operación y la fecha actual
     $diferencia=minutosTranscurridos($I_FECHAHORA_old, $fechaactual);
     //Unidades de Temperatura tracurridos desde la fecha de inicio x UnidadPorencia
     //(1 grado de Temperatura por cada 15 minutos)
     $Ttick= ($diferencia/$TunidadTiempo) * $TunidadPotencia;
     //Unidades de Humedad transcurridos desde la fecha inicial x UnidadPorencia
     //(5 grados porcentuales de humedad por cada 15 minutos)
     $Htick= ($diferencia/$HunidadTiempo)*$HunidadPotencia;

     //obtener la temperatura y humedada Actual,
     //partiendo desde la última programación
     $TActual=CalTnueva($I_TEMPERATURA_old, $F_TEMPERATURA_old, $Ttick);
     $HActual=CalHnueva($I_HUMEDAD_old, $F_HUMEDAD_old, $Htick);

     $programado=true;

 }
 else
 {
     $TActual=$DefaultTempe;
     $HActual=$DefaultHume;
 }
//isertar nuevos valores en la tabla de Operaciones
$sql= "INSERT INTO operaciones
       (IP,I_FECHAHORA,I_TEMPERATURA,I_HUMEDAD,F_TEMPERATURA,F_HUMEDAD)
        VALUES
       ('".$IP."' ,'".$fechaactual."',".$TActual.",".$HActual.",".$Tpro.",".$Hpro.")";
db_queryDML($sql);
//$result = db_query($sql) or db_die1();

$Testado="FINALIZADO";
$Hestado="FINALIZADO";

if($programado == true)
{
    if($TActual != $Tpro and ($TActual != $Tmax or $TActual != $Tmin )) $Testado="EN PROCESO";
    if($HActual != $Hpro and ($HActual != $Tmax or $HActual != $Tmin )) $Hestado="EN PROCESO";
}
else
{
    if($TActual != $Tpro) $Testado="EN PROCESO";
    if($HActual != $Hpro) $Testado="EN PROCESO";
}

//debug
$data= $IP.';'.$TActual.';' .$HActual. ';'.$Tpro.';'.$Hpro."; programado: ".$programado;
//JSON A DEVOLVER
$ValRe=array('rIp' => $IP,'StatusT' =>$Testado,'StatusH' =>$Hestado, 'Coment' =>$data,
             'rTActual' => $TActual,'rHActual'=> $HActual, 'rTpro'=> $Tpro,'rHpro'=> $Hpro);

//Actualizar variables de entorno
//$_SESSION['TACTUAL']=$TActual;
//$_SESSION['HACTUAL']=$HActual;
//$_SESSION['TESTADO']=$Testado;
//$_SESSION['HESTADO']=$Hestado;
//$_SESSION["TPRO"]   =$Tpro;
//$_SESSION['HPRO']   =$Hpro;

$jValRe= json_encode($ValRe);
echo $jValRe;


?>