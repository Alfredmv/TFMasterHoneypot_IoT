<?php
// actualizar_sensores.php
//  Autor:Alfredo Martinez Viso
//  callback (jQuery AJAX), con datos de retorno en JSON
//  devuelve temperatura y humedad PROGRAMADA,
//  devuelve la temperatura y humedad calculada a fecha y hora  actual
//  devuelve el estado del programa en la Temperatura y Humedad programadas
//  ("EN PROCESO", "FINALIZADO")
//  Marzo 2022

 include_once "./Lib/funciones.php";
 include_once "./Lib/mysql_inc.php";
 if (session_status() !== PHP_SESSION_ACTIVE)
 {
     session_start();
 }

 $result="";

 date_default_timezone_set('Europe/Madrid');
 $fecha_hora_actual = date('Y-m-d H:i:s', time());

 $IP= get_client_ip();

 // valore por defecto
 global $DefaultTempe;
 global $DefaultHume ;
 $Tpro=$DefaultTempe;   //90.25
 $Hpro=$DefaultHume;    //0.52

 global $TunidadTiempo; //Unudad de tiempo para Temeperatura
 global $HunidadTiempo; //Unidad de tiempo para Humedad
 global $TunidadPotencia; //Unidad de Pontencia de instalcion en Temperatura expresdado en ºC
 global $HunidadPotencia; //Unidad de Potencia instalacion en Humedad expresdo en %)


 $programado=false;
 $row=array();

//fecha local actual en España
 date_default_timezone_set('Europe/Madrid');
 $fechaactual = date('Y-m-d H:i:s', time());
 $I_FECHAHORA_old= $fechaactual;
 if(existeIPoperacion($IP))
 {
     //ultima operacion realizada por IP registada en DB
     $row=maxIPoperacion($IP);
     $I_FECHAHORA_old=$row['I_FECHAHORA'];
     $I_TEMPERATURA_old=$row['I_TEMPERATURA'];
     $I_HUMEDAD_old=$row['I_HUMEDAD'];
     $F_TEMPERATURA_old=$row['F_TEMPERATURA'];
     $F_HUMEDAD_old=$row['F_HUMEDAD'];
     $Tpro=(float)$F_TEMPERATURA_old;
     $Hpro=(float)$F_HUMEDAD_old;
     //diferencia en minutos entre la fecha de la ultima
     //operación y la fecha actual
     $diferencia=minutosTranscurridos($I_FECHAHORA_old, $fechaactual);
     //Unidades de Temperatura tracurridos desde la fecha de inicio x UnidadPorencia
     //(1 grado de Temperatura por cada 15 minutos)
     $Ttick= ($diferencia/$TunidadTiempo) * $TunidadPotencia;
     //Unidades de Humedad transcurridos desde la fecha inicial x UnidadPorencia
     //(5 grados porcentuales de humedad por cada 15 minutos)
     $Htick= ($diferencia/$HunidadTiempo)*$HunidadPotencia;
     //CALCULO DE LA TEMPERATURA Y HUMEDAD ACTUAL
     $TActual=CalTnueva($I_TEMPERATURA_old, $Tpro, $Ttick);
     $HActual=CalHnueva($I_HUMEDAD_old, $Hpro, $Htick);
     $programado=true;
 }
 else
 {
     $TActual=$DefaultTempe;
     $HActual=$DefaultHume;
 }

 $Testado="FINALIZADO";
 $Hestado="FINALIZADO";

 // Si se ha llegado a la temperatura o humedad programada
 //entoces el estao = FIN
 if($programado == true)
 {
    if($TActual != $Tpro and ($TActual != $Tmax or $TActual != $Tmin ))
         $Testado="EN PROCESO";
    if($HActual != $Hpro and ($HActual != $Tmax or $HActual != $Tmin ))
         $Hestado="EN PROCESO";
 }

 //DEBUG, serializacion en string
 $data= $IP.';'.$TActual.';' .$HActual. ';'.$Tpro;
 $data.=';'.$Hpro.';'.$Testado;
 $data.=';'.$Hestado.';--'.$diferencia;
 $data.=';--'.$I_FECHAHORA_old.";--".$fechaactual;
 //JSON con datos a enviar
 $ValRe=array('rIp' => $IP,'StatusT' =>$Testado,'StatusH' =>$Hestado,
              'Coment' =>$data,'rTActual' => $TActual,
              'rHActual'=> $HActual, 'rTpro'=> $Tpro,'rHpro'=> $Hpro);
 //Actualizar variables de entorno
 //$_SESSION['TACTUAL']=$TActual;
 //$_SESSION['HACTUAL']=$HActual;
 //$_SESSION['TESTADO']=$Testado;
 //$_SESSION['HESTADO']=$Hestado;
 //$_SESSION["TPRO"]   =$Tpro;
 //$_SESSION['HPRO']   =$Hpro;
 //devolver datos calculados
$jValRe= json_encode($ValRe);
echo $jValRe;

?>