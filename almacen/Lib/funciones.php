<?php
// funciones.php
//  Autor:Alfredo Martinez Viso
//  Funciones PHP generales
//  Marzo 2022:

include_once "./Lib/mysql_inc.php";

//*******************    Creacion de BD ****************************************************

//CREATE DATABASE applesaved /*!40100 DEFAULT CHARACTER SET latin1 */;

///******************    Definicion de Tablas    *****************
//CREATE TABLE entradas (
//  IP varchar(15) NOT NULL,
//  FECHAHORA datetime NOT NULL,
//  FECHAHORA_FIN datetime DEFAULT NULL,
//  PRIMARY KEY (IP,FECHAHORA)
//) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='CONTROL DE ENTRADAS'

//CREATE TABLE operaciones (
//  IP varchar(15) NOT NULL,
//  I_FECHAHORA datetime NOT NULL,
//  F_FECHAHORA datetime DEFAULT NULL,
//  I_TEMPERATURA float DEFAULT NULL,
//  I_HUMEDAD float DEFAULT NULL,
//  F_TEMPERATURA float DEFAULT NULL,
//  F_HUMEDAD float DEFAULT NULL,
//  PRIMARY KEY (IP,I_FECHAHORA)
//) ENGINE=MyISAM DEFAULT CHARSET=latin1  COMMENT='OPERACIONES PROGRAMADAS'

//*******************************************************************************************

//datos admisibles de maximos y minimos de Temperatura y humedad
$Hmin= 69;
$Hmax= 99;
$Tmin= -5;
$Tmax= 5;

// valores por defecto
global $DefaultTempe;
global $DefaultHume;
$DefaultTempe = 0.52;  //temperatura
$DefaultHume = 90.25;  //Humedad

global $TunidadTiempo; //Unudad de tiempo para Temeperatura
global $HunidadTiempo; //Unidad de timpo para Humedad
global $TunidadPotencia;   //Unidad de Pontencia de instalcion en Temperatura expresdado en ºC
global $HunidadPotencia;   //Unidad de Potencia instalacion en Humedad expresdo en %)

$TunidadPotencia =1;  //1 grado centigrado por Unidad de tiempo
$HunidadPotencia =5;  //5 graso por unidad de tiempo
$TunidadTiempo =15;   //15 minutos para aumetar o diminuir una Unidad de Potencia
$HunidadTiempo =15;   //15 minutos para aumentar o disminuir una unidad de Potencia

//diferencia en minutos entre dos fechas
function minutosTranscurridos($fecha_i,$fecha_f)
{
    $minutos = (strtotime($fecha_f)-strtotime($fecha_i))/60;
    $minutos = abs($minutos); $minutos = floor($minutos);
    return $minutos;
}


//Obtiene la IP del cliente, Metodo simple
function getRealIpAddress2()
{
    $ip = "";
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
        if( $ip =='::1') $ip='127.0.0.1';
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else
        $ip=$_SERVER['REMOTE_ADDR'];
        if( $ip =='::1') $ip='127.0.0.1';
    return $ip;
}
//Obtiene la IP del cliente metodo Avanzado con funcion getenv()
//function get_client_ip()
//{
//    $ipaddress = '';
//    if (getenv('HTTP_CLIENT_IP'))
//    {
//        $ipaddress = getenv('HTTP_CLIENT_IP');
//        if( $ipaddress == '::1') $ipaddress='127.0.0.1';
//    }
//    else if(getenv('HTTP_X_FORWARDED_FOR'))
//        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
//    else if(getenv('HTTP_X_FORWARDED'))
//        $ipaddress = getenv('HTTP_X_FORWARDED');
//    else if(getenv('HTTP_FORWARDED_FOR'))
//        $ipaddress = getenv('HTTP_FORWARDED_FOR');
//    else if(getenv('HTTP_FORWARDED'))
//        $ipaddress = getenv('HTTP_FORWARDED');
//    else if(getenv('REMOTE_ADDR'))
//        $ipaddress = getenv('REMOTE_ADDR');
//        if( $ipaddress == '::1') $ipaddress='127.0.0.1';
//    else
//        $ipaddress = 'UNKNOWN';
//    return $ipaddress;
//}

//Obtiene la IP del cliente, M.avanzado con variables de Servidor
function get_client_ip(){

    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        $ipaddress=$_SERVER["HTTP_CLIENT_IP"];
        if( $ipaddress == '::1') $ipaddress='127.0.0.1';
        return $ipaddress;
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    elseif($_SERVER["REMOTE_ADDR"])
    {
        $ipaddress = $_SERVER["REMOTE_ADDR"];
        if( $ipaddress == '::1') $ipaddress='127.0.0.1';
        return $ipaddress;
    }
    else
        return  'UNKNOWN';
}



//Obtiene la info de la IP del cliente desde geoplugin
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE)
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect)
        {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support))
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2)
            {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;

}
// guarda la ip que entra en la web
 function logacceso()
 {
     $Ip=  get_client_ip();
     date_default_timezone_set('Europe/Madrid');
     $fecha_hora_actual = date('Y-m-d H:i:s', time());
     $_SESSION['HORAENTRADA']= $fecha_hora_actual;

     $cadena= $_SERVER['HTTP_USER_AGENT'];

     //INSERT INTO ENTRADAS value('127.0.0.1', '2022-03-03 10:25:07')
     $sql= "INSERT INTO applesaved.entradas value('".$Ip."', '".  $fecha_hora_actual."', NULL,'".$cadena."')";
     //$result = db_queryDML($sql) or db_die();
     db_queryDML($sql);
 }

 //guardar fehaHora de cierre de sesion
  function logsalida($Ip, $fechahoraEntrada)
  {
      date_default_timezone_set('Europe/Madrid');
      $Ip=  get_client_ip();
      $fecha_hora_actual = date('Y-m-d H:i:s', time());
      $sql=  " UPDATE entradas ";
      $sql.= "     SET  ";
      $sql.= "     FECHAHORA_FIN = '".$fecha_hora_actual."' ";
      $sql.= "     WHERE IP = '". $Ip."' AND FECHAHORA = '".$fechahoraEntrada."'";
      db_queryDML($sql) or db_die1($sql);
   }

 //obtener las ultima entrada realizada por una IP dada
 function maxIPentrada($IP)
 {
     $sql="SELECT IP, FECHAHORA ";
     $sql .=" FROM applesaved.entradas where IP='".$IP."' ";
     $sql .=" AND  FECHAHORA =(SELECT  max(FECHAHORA)  FROM entradas WHERE IP='".$IP."')";
     //$result = db_query($sql) or db_die();
     $result = db_queryselect($sql);
     $row=db_fetch_row($result);
     db_free_stmt($result);
     db_die_select();
     return $row;
 }

 //determinar si un IP dada, entro
 function existeIPentrada($IP)
 {
     $sql="SELECT count(IP)";
     $sql .=" FROM applesaved.entradas where IP='".$IP."' ";
     //$result = db_query($sql) or db_die();
     $result = db_queryselect($sql);
     $row=db_fetch_row($result);
     db_die_select();

     if($row[0] >0) return true;
     else return false;
 }

 //obtener las ultima operacion realizada por una IP dada
 function maxIPoperacion($IP)
 {
     $sql=  "SELECT IP, I_FECHAHORA, F_FECHAHORA, I_TEMPERATURA, I_HUMEDAD, F_TEMPERATURA, F_HUMEDAD";
     $sql .=" FROM operaciones where IP='".$IP."' ";
     $sql .=" AND  I_FECHAHORA =(SELECT  max(I_FECHAHORA)  FROM operaciones WHERE IP='".$IP."')";
     //$result = db_query($sql) or db_die();
     $result = db_queryselect($sql);
     $row=db_fetch_row($result);
     db_die_select();
     return $row;
 }

 // determinar si un IP dada hizo operaciones
 function existeIPoperacion($IP)
 {
     $sql="SELECT count(IP)";
     $sql .=" FROM operaciones WHERE IP='".$IP."'";
     //$result = db_query($sql) or db_die();
     $result = db_queryselect($sql) ;
     $row=db_fetch_row($result);
     db_die_select();
     if($row[0] >0) return true;
     else return false;
 }


 //calculo la teperatura actual partiendo de la temperatura inicial,
 //Temperatura  programada y los Tick de tiempo transcurreidos
 //$Ti Temperatura inicial de partida
 //$Tp Temperatura programada
 //$Ttick  unidades de tiempo transcurridas por grados,
 //TRACURRIDOS DESDE LA FECHA INICIAL
 function CalTnueva($Ti, $Tp, $Ttick)
 {
     global $Tmin;
     global $Tmax;

     if($Tp < $Ti)  //Disminuir
     {
         $Tn=$Ti - $Ttick;
         if($Tn <= $Tmin) $Tn=$Tp;
         elseif($Tn <= $Tp) $Tn=$Tp;
     }
     else  //Aumentar
     {
         $Tn=$Ti + $Ttick;
         if($Tn >= $Tmax) $Tn=$Tp;
         elseif($Tn >= $Tp) $Tn=$Tp;
     }
     return $Tn;
 }
 //calculo la Humedad actual partiendo de la Humedad inicial,la
 //Humedad programada y los Tick de tiempo transcurreidos
 //$Hi Humedad inicial de partida
 //$Hp Humedad programada
 //$Htick  unidades de tiempo transcurridas por grados Humedad,
 //TRACURRIDOS DESDE LA FECHA INICIAL
 function CalHnueva($Hi, $Hp, $Htick)
 {
     global $Hmin;
     global $Hmax;

     if($Hp < $Hi)  //Disminuir
     {
         $Hn=$Hi - $Htick;
         if($Hn <= $Hmin) $Hn=$Hp;
         elseif($Hn <= $Hp) $Hn=$Hp;
     }
     else //Aumentar
     {
         $Hn=$Hi + $Htick;
         if($Hn >= $Hmax) $Hn=$Hp;
         elseif($Hn >= $Hp) $Hn=$Hp;
     }
     return $Hn;
 }

 //devuelve lista de IPs de la tabla operaciones
 // para rellenar un combo
 function getIPs()
 {
        $IP= array();
        $i=0;
        $sql = "SELECT DISTINCT(IP) FROM operaciones";
        $sql .="  order by IP asc";

        $result = db_queryselect($sql);
        while($row = db_fetch_row($result))
        {
            list($IP[$i][0]) = $row;
            $i++;
        }
		db_die_select();
        return $IP;
 }


 //Para el listado de operaciones
 function operaciones($IP='-', $desde="-", $hasta='-',$chekegeo="" ,&$MSQL)
 {
     $operaciones = array();

     //WHERE   I_FECHAHORA between '2022-03-10' AND now();
     $WHERE="";
     $WHEREIP="";
     if($chekegeo =='')
         $query = "SELECT  IP,I_FECHAHORA,I_TEMPERATURA,I_HUMEDAD,F_TEMPERATURA,F_HUMEDAD FROM operaciones";
     else
         $query = "SELECT DISTINCT IP FROM operaciones";

     $operaciones = array();

     if($IP !="" and  $IP !='-')
     {
         $WHEREIP="  IP='".$IP."'";
     }

     if($desde !="" and $desde !='-')
     {
         $WHERE.=" I_FECHAHORA  between '".$desde."' and ";

         if($hasta !="" and $hasta !='-')
             $WHERE.="  '".$hasta."' ";
         else
             $WHERE.="  now() ";

     }
     else
     {
         if($hasta !="" and $hasta !='-')
             $WHERE.="I_FECHAHORA <=  '".$hasta."' ";
         else
             $WHERE.=" I_FECHAHORA  <=  now() ";
     }

     if($WHEREIP !="") $WHERE=$WHEREIP. " and ".$WHERE;
     if($WHERE !="")
         $query .= ' WHERE '.$WHERE."  order by IP asc, I_FECHAHORA DESC";
     else
         $query .= "  order by IP asc, I_FECHAHORA DESC";

     $MSQL=$query;
     //$result = mysqli_query($query);
     $result = db_queryselect($query);

     $i=0;
     if($chekegeo =='')
     {       //datos para listado de operaciones No geolocalizacion
             while($row = db_fetch_row($result))
             {
                 list($operaciones[$i][0],$operaciones[$i][1],$operaciones[$i][2],$operaciones[$i][3],$operaciones[$i][4], $operaciones[$i][5]) = $row;
                 $i++;
             }
     }
     else  // dato de para listado de geolocalizacion
     {
             while($row = db_fetch_row($result))
             {
                 list($operaciones[$i][0]) = $row;
                 $i++;
             }
     }
     db_die_select();
     return $operaciones;
     //DEBUG ********************************************************
     //echo operaciones('127.0.0.1', '2022-03-10', '2022-03-12')."\n";
     //echo operaciones('127.0.0.1', '-','2022-03-12')."\n";
     //echo operaciones('127.0.0.1', '2022-03-12','-')."\n";
     //echo operaciones('127.0.0.1', '-','-')."\n";
     //echo operaciones('-', '2022-03-10', '2022-03-12')."\n";
     //echo operaciones('-', '-', '2022-03-12')."\n";
     //echo operaciones('-', '-', '-')."\n";


 }
 //Para el listado de Entradas
 function entradas($IP='-', $desde="-", $hasta='-', $chekegeo='',&$MSQL)
 {

     $duracion="  (case when FECHAHORA_FIN is null THEN null ";
	 $duracion.="    ELSE concat(TIMESTAMPDIFF(HOUR,FECHAHORA,FECHAHORA_FIN),':', (TIMESTAMPDIFF(MINUTE,FECHAHORA,FECHAHORA_FIN) % 60),':',(TIMESTAMPDIFF(SECOND,FECHAHORA,FECHAHORA_FIN) % 60)) ";
     $duracion.=" end) as DURACION ";

     $WHERE="";
     $WHEREIP="";
     //$query = "SELECT  IP, FECHAHORA, FECHAHORA_FIN ,".$duracion. ", USER_AGENT FROM entradas";

     if($chekegeo =='')
         $query = "SELECT  IP, FECHAHORA, FECHAHORA_FIN ,".$duracion. ", USER_AGENT FROM entradas";
     else
         $query = "SELECT DISTINCT IP FROM entradas";


     $entradas = array();


     if($IP !="" and  $IP !='-')
     {
         $WHEREIP="  IP='".$IP."'";
     }

     if($desde !="" and $desde !='-')
     {
         $WHERE.=" FECHAHORA  between '".$desde."' and ";

         if($hasta !="" and $hasta !='-')
             $WHERE.="  '".$hasta."' ";
         else
             $WHERE.="  now() ";

     }
     else
     {
         if($hasta !="" and $hasta !='-')
             $WHERE.="FECHAHORA <=  '".$hasta."' ";
         else
             $WHERE.=" FECHAHORA  <=  now() ";
     }

     if($WHEREIP !="") $WHERE=$WHEREIP. " and ".$WHERE;
     if($WHERE !="")
         $query .= ' WHERE '.$WHERE."  order by IP asc, FECHAHORA DESC";
     else
         $query .= "  order by IP asc, FECHAHORA DESC";

     //$result = mysqli_query($query);
     $result = db_queryselect($query);
     $MSQL=$query;
     $i=0;
     if($chekegeo =='')
     {
        //datos para listado de operaciones No geolocalizacion
        while($row = db_fetch_row($result))
        {
            list( $entradas[$i][0], $entradas[$i][1], $entradas[$i][2],$entradas[$i][3], $entradas[$i][4]) = $row;
            $i++;
        }
     }
     else  // dato de para listado de geolocalizacion
     {
         while($row = db_fetch_row($result))
         {
             list($entradas[$i][0]) = $row;
             $i++;
         }
     }

     db_die_select();

     return $entradas ;
 }

?>