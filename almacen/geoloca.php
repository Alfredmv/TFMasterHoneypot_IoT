<?php
//*********************************************************************
//Calback  AJAX para obtener  informacion y Geolocalizcion de una IP
//********************************************************************

if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}

$ip=$_REQUEST["xIP"];
//$Tpro=(float)$_REQUEST["xTpro"];

//Funcion devuelve un JSON con informacion y Geolocalizacion de una IP dada
function ip_info($ip)
{
    $ipdat="";
    if (filter_var($ip, FILTER_VALIDATE_IP) )
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" .$ip));
	}
    return $ipdat;

}
//Funcion devuelve el JSON con los campos que interesan
function geoloca($p)
{   $geo=array(
	  "request"  		=>	$p->geoplugin_request,
	  "status"  		=>	$p->geoplugin_status,
	  "delay"  		=>	$p->geoplugin_delay,
	  "region"  		=>	$p->geoplugin_region,
	  "regionCode"  	=>	$p->geoplugin_regionCode,
	  "regionName"  	=>	$p->geoplugin_regionName,
	  "areaCode"  	=>	$p->geoplugin_areaCode,
	  "dmaCode"  		=>	$p->geoplugin_dmaCode,
	  "countryCode"  	=>	$p->geoplugin_countryCode,
	  "countryName"  	=>	$p->geoplugin_countryName,
	  "inEU"  		=>	$p->geoplugin_inEU,
	  "euVATrate"  	=>	$p->geoplugin_euVATrate,
	  "continentCode"  	=>	$p->geoplugin_continentCode,
	  "continentName"  	=>	$p->geoplugin_continentName,
	  "latitude"  		=>	$p->geoplugin_latitude,
	  "longitude"  		=>	$p->geoplugin_longitude,
	  "locationAccuracyRadius"  	=>	$p->geoplugin_locationAccuracyRadius,
	  "timezone"  		=>	$p->geoplugin_timezone,
	  "currencyCode"  	=>	$p->geoplugin_currencyCode,
	  "currencySymbol"  	=>	$p->geoplugin_currencySymbol,
	  "currencySymbol_UTF8" =>	$p->geoplugin_currencySymbol_UTF8,
	  "currencyConverter"  	=>	$p->geoplugin_currencyConverter);
  return $geo;
}
//Funcion devuelve el contenido del JSON de geolocalizacion en formato HTML
function geolocaHTML($p)
{
	$geo=  "<p>geoplugin_request 		:"  			.$p->geoplugin_request."<br>";
	$geo.=	  "geoplugin_status  		:"				.$p->geoplugin_status."<br>";
	$geo.=	  "geoplugin_delay			:"				.$p->geoplugin_delay."<br>";
	$geo.=	  "geoplugin_region			:"				.$p->geoplugin_region."<br>";
	$geo.=	  "geoplugin_regionCode		:"				.$p->geoplugin_regionCode."<br>";
	$geo.=	  "geoplugin_regionName		:"				.$p->geoplugin_regionName."<br>";
	$geo.=	  "geoplugin_areaCode		:"				.$p->geoplugin_areaCode."<br>";
	$geo.=	  "geoplugin_dmaCode		:"				.$p->geoplugin_dmaCode."<br>";
	$geo.=	  "geoplugin_countryCode	:"				.$p->geoplugin_countryCode."<br>";
	$geo.=	  "geoplugin_countryName	:"				.$p->geoplugin_countryName."<br>";
	$geo.=	  "geoplugin_inEU			:"				.$p->geoplugin_inEU."<br>";
	$geo.=	  "geoplugin_euVATrate		:"				.$p->geoplugin_euVATrate."<br>";
	$geo.=	  "geoplugin_continentCode	:"				.$p->geoplugin_continentCode."<br>";
	$geo.=	  "geoplugin_continentName	:"				.$p->geoplugin_continentName."<br>";
	$geo.=	  "geoplugin_latitude		:"				.$p->geoplugin_latitude."<br>";
	$geo.=	  "geoplugin_longitude		:"				.$p->geoplugin_longitude."<br>";
	$geo.=	  "geoplugin_locationAccuracyRadius:"		.$p->geoplugin_locationAccuracyRadius."<br>";
	$geo.=	  "geoplugin_timezone		:"				.$p->geoplugin_timezone."<br>";
	$geo.=	  "geoplugin_currencyCode	:"				.$p->geoplugin_currencyCode."<br>";
	$geo.=	  "geoplugin_currencySymbol	:"				.$p->geoplugin_currencySymbol."<br>";
	$geo.=	  "geoplugin_currencySymbol_UTF8:"			.$p->geoplugin_currencySymbol_UTF8."<br>";
	$geo.=	  "geoplugin_currencyConverter:"         	.$p->geoplugin_currencyConverter."<br>";
	$geo.="</p>";
	return  $geo;
}

//echo json_encode(geoloca(ip_info($ip)));
 //$kk=array ('info' => geolocaHTML(ip_info($ip)));
 $kk= geolocaHTML(ip_info($ip));
 echo $kk;

?>