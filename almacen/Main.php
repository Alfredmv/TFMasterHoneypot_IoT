<?php


include_once "./Lib/mysql_inc.php";
include_once "./Lib/funciones.php";
include_once "./Lib/funciones_html.php";

if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}

$opcion=$_SESSION['opcion'];
$IP=get_client_ip();
$_SESSION['IP']=$IP;
$msg="Obtenci&oacute;n de datos";
if($opcion == "Mostrar") $msg="Control de Refrigeraci&oacute;n";

headerNoCache();

echo "<!DOCTYPE html>\n";
echo "<html lang=\"es\">\n";
echo "<head>\n";
echo "  <title>Monitor de Tareas de Workflow</title>\n";
echo "  <meta charset=\"utf-8\">\n";
echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
echo "  <link rel=\"shortcut icon\" href=\"#\">\n";



echo "<link href='../bootstrap_5_0_2/css/bootstrap.css' rel='stylesheet'>\n";
echo "<link href='../bootstrap_5_0_2/css/bootstrap-reboot.min.css' rel='stylesheet'>\n";
echo "<link href='../bootstrap_5_0_2/css/bootstrap-grid.min.css' rel='stylesheet'>\n";
echo "<link href='../bootstrap_5_0_2/fonts/glyphicon.css' rel='stylesheet'>\n";
//echo "<link href='../bootstrap-icons-1.5.0/bootstrap-icons.css' rel='stylesheet'>\n";
echo "<link href='../datetimepicker/css/bootstrap-datetimepicker.min.css' rel='stylesheet' media='screen'>\n";




echo "<link rel='stylesheet' href='../canvas-gauges-master/fonts/fonts.css'>\n";
echo "<link href='./css/mi.css'    rel='stylesheet'>\n";
echo "<link href='./css/login.css' rel='stylesheet'/>\n";

echo "<script src='../canvas-gauges-master/gauge.min.js'></script>\n";

echo "<script type='text/javascript' src='../jquery/jquery-3.5.1.min.js' charset='UTF-8'></script>\n";
echo "<script type='text/javascript' src='../bootstrap_5_0_2/js/bootstrap.min.js'></script>\n";
echo "<script type='text/javascript' src='../bootstrap_5_0_2/js/bootstrap.bundle.min.js'></script>\n";
echo "<script type='text/javascript' src='../datetimepicker/js/bootstrap-datetimepicker.js' charset='UTF-8'></script>\n";
echo "<script type='text/javascript' src='../datetimepicker/js/locales/bootstrap-datetimepicker.es.js' charset='UTF-8'></script>\n";
echo "<script type='text/javascript' src='../bootbox/bootbox.js' charset='UTF-8'></script>\n";


echo "<script type='text/javascript' src='./js/funciones.js'></script>\n";

// variables de sesion
echo "  <script>\n";

echo "  </script>\n";
echo "</head>\n";
echo "<body>\n";

//cabecero y menu, según derechos del usuario
//echo "	<nav class=\"navbar navbar-light\" style=\"background-color: #e3f2fd;\"\n";
echo "  <nav class=\"navbar navbar-expand-sm navbar-light\" style=\"background-color:AliceBlue;\">\n";
echo "		<div class=\"container-fluid  justify-content-start\">\n";
echo "          <a class='navbar-brand' href='#'>\n";
echo "             <img src='./img/roja_dos_mitades.png' alt='' width='40' height='30' class='d-inline-block align-top' />\n";
//echo "             Control de Refrigeraci&oacuten\n";
echo"                Apple<span style='color:#007C9B;font-weight:bold'>Storage\n";
echo "          </a>\n";
echo "          <ul class=\"navbar-nav  text-left\">\n";
if($opcion == "Mostrar")
    $clase ="class=\"nav-link active\" style=\"background-color:LightSkyBlue;\"";
else
    $clase ="class= \"nav-link\"";
echo "		        <li class=\"nav-item\"><a $clase href=\"reinicio.php?opcion=Mostrar\" title='Vista de Sensores' data-toggle=\"tooltip\">Mis sensores</a></li>\n";
if($opcion == "Supervision" or $opcion == "Buscar")
    $clase ="class=\"nav-link active\" style=\"background-color:LightSkyBlue;\"";
 else
     $clase ="class= \"nav-link\"";
echo "	            <li class=\"nav-item\"><a $clase href=\"reinicio.php?opcion=Supervision\" title='listados de supervisi&oacute;n'>Supervisi&oacute;n</a></li>\n";
echo "         </ul>\n";
echo "	    </div>\n";
echo "      <div class=\"container-fluid justify-content-end\">\n";
echo "             <label><span style=\"font-size:14px\">$msg</span>&nbsp;&nbsp;&nbsp;</label>\n";
echo "             <label id=\"fecha\"  style=\"color:#0000aa;font-weight:300\">20/03/2022&nbsp;</label>\n";
echo "             <label id=\"reloj\" style=\"color: #0000aa;font-weight:300;width:60px;\">12:24</label>\n";
echo "      </div>\n";
echo "	</nav>\n";


if($opcion == "Mostrar")
{
    echo "	<div class=\"container\">\n";
    echo "		<div class=\"abs-center0\">\n";

    $_SESSION['opcion']="Mostrar";
    include_once("./sensores2.php");
    echo "      </div>\n";
    echo "	</div>\n";
}
elseif($opcion == "Supervision" or $opcion == "Buscar")
{
    echo "	<div class=\"container-fluid\">\n";
    echo "		<div class=\"row\">\n";
    //$_SESSION['opcion']="Supervision";
    $_SESSION['traza'].= " LAMADADA Supervision ";
    include_once ($pgSupervison);
    echo "      </div>\n";
    echo "	</div>\n";
}

?>
    <script type="text/javascript">

        

        //window.addEventListener("beforeunload", function (e) {
        //    var e = e || window.event;
        //    if (e) {
        //        salidasesion();
        //        e.returnValue = 'Se perderan todos los datos que no hayas guardado';
                
        //    }
        //});
        document.onvisibilitychange = function() {
              if (document.visibilityState === 'hidden') {
                  //navigator.sendBeacon('/log', analyticsData);
                  salidasesion();
              }
        };

          $(document).ready(function ()
          {
                      <?php

                        //echo "mo=\"$opcion\"; \n";
               if($opcion == "Mostrar")
               {
                //echo "               var gauge = document.gauges[0];\n";
                //echo "               var hume = document.gauges[1];\n";
                //echo "               gauge.value= ActualTempe;\n";
                //echo "               hume.value = ActualHume;\n";
                echo "                 refresValues();\n";
               }
                
                      ?>
              $('[data-bs-toggle=tooltip]').tooltip();
              //poner fecha en el menu
              var fecha = document.getElementById('fecha');
	          fecha.innerHTML = getFecha()+"&nbsp";
              //poner en horqa el reloj del menu
		      mueveReloj(calcTimelocal('+1')); 
                    
          });

        //Debug  *********
        function prueba() {get_geoloca('80.168.88.1');}
        //****************
        
        
        function changeValues()
        {
            var gauge = document.gauges[0];
            var hume = document.gauges[1];
            var n1;
            var n2;
            //validar la temperatura y humedad de entrada
            ret = ValidateTempe();
            if (ret !== true)
            {
                //alert(ret);
                aviso("Selecci&oacute;n de nueva temperatura", ret);
                return;
            }
            
            ret = ValidateHume();
            if (ret !== true)
            {
                   //alert(ret);
                   aviso("Selecci&oacute;n de nueva Indice de Humedad", ret);
                   return;
            }
            // si son valores adecuados,se asignan en los controles graficos
            var kk = document.getElementById('txttemperatura');
            n1 = kk.value;
            //alert(n1);
            kk = document.getElementById("txthumedad");
            n2 = kk.value;
            
            gauge.value = n1;
            hume.value = n2;

            // se visualiza las teperatura  y humedad real
            showItemlabel("lbltemperaturaA", "( Actual: " + ActualTempe + ")", "Blue");
            $('#lbltemperaturaA').attr('data-bs-original-title', 'Temperatura Actual del Sensor');
            showItemlabel("lblhumedadA", "( Actual: " + ActualHume + ")", "Blue");
            $('#lblhumedadA').attr('data-bs-original-title', 'Indice de Humedad Actual del Sensor');
            color = 'SlateGray';
            TextItemTextBox("txttemperatura", n1,color);
            TextItemTextBox("txthumedad", n2,color);
            return true;
        }
       
        // establece un nuevo programa
        function  setUpValues()
        {
            //validar datos  datos de entrada(termperatura y humedad)
            //alert("aki");
            if(changeValues())
            {
                Tpro = document.getElementById('txttemperatura').value;
                Hpro = document.getElementById("txthumedad").value;
                //GUARDA     los nuevos valores programados via Ajax
                Actualizar(Tpro, Hpro);
            }
            //alert("aki 2");
        }


        function resetValues() {
            var gauge = document.gauges[0];
            var hume = document.gauges[1];


            $("#lblhumedadA").hide();
            $('#lbltemperaturaA').hide();
            gauge.value = ActualTempe;
            hume.value = ActualHume;
        }

        //obtener  valores actuales de los sensores
        function refresValues()
        {
            Refrescar();       
        }

       
   
        $('.form_datetime').datetimepicker({
            language:  'es',
            weekStart: 1,
            todayBtn:  1,
		    autoclose: 1,
		    todayHighlight: 1,
		    startView: 2,
		    forceParse: 0,
            showMeridian: 1
        });
	    $('.form_date').datetimepicker({
            language:  'es',
            weekStart: 1,
            todayBtn:  1,
		    autoclose: 1,
		    todayHighlight: 1,
		    startView: 2,
		    minView: 2,
		    forceParse: 0
        });
	    $('.form_time').datetimepicker({
            language:  'es',
            weekStart: 1,
            todayBtn:  1,
		    autoclose: 1,
		    todayHighlight: 1,
		    startView: 1,
		    minView: 0,
		    maxView: 1,
		    forceParse: 0
        });


        // convertir a Excel
        function proceexcel()
        {
            location.href='./Export.php';
        }

        //obtener fecha local
        function getFecha()
	    {
		    var f = new Date();

		    str_dia = new String (f.getDate());
		    if (str_dia.length == 1) 		str_dia = "0" + str_dia;

		    str_mes = new String (f.getMonth() + 1);
		    if (str_mes.length == 1)	   str_mes = "0" + str_mes;

		    return str_dia + "/" +  str_mes + "/" + f.getFullYear();
        }

        // Tick del reloj hora local
	    function mueveReloj(horalocal='')
        {
	        if (horalocal=='')
	        {
		        momentoActual = new Date();
	        }
	        else
	        {
		        momentoActual = horalocal;
	        }

	        hora = momentoActual.getHours();
	        minuto = momentoActual.getMinutes();
	        segundo = momentoActual.getSeconds();

	        str_segundo = new String (segundo)
	        if (str_segundo.length == 1)
	           segundo = "0" + segundo;

	        str_minuto = new String (minuto)
	        if (str_minuto.length == 1)
	           minuto = "0" + minuto;

	        str_hora = new String (hora)
	        if (str_hora.length == 1)
	           hora = "0" + hora;

	        horaImprimible = hora + ":" + minuto + ":" + segundo;
            //horaImprimible = hora + " : " + minuto + " : " + segundo;

            var reloj = document.getElementById('reloj');
	            reloj.innerHTML = horaImprimible;

	        setTimeout("mueveReloj(calcTimelocal('+1'))",1000);

        }

        // funcion para calcular la hora local en una ciudad dada la diferencia horaria.
        function calcTimelocal(offset)
        {
                // creamos el objeto Date (la selecciona de la máquina cliente)
                d = new Date();

                // lo convierte  a milisegundos
                // añade la dirferencia horaria
                // recupera la hora en formato UTC
                utc = d.getTime() + (d.getTimezoneOffset() * 60000);

                // crea un nuevo objeto Date usando la diferencia dada.
                nd = new Date(utc + (3600000*offset));

                // devuelve la hora como string.
                return nd
        }

       
    </script>


<?php

     echo "</body>\n";
     echo "</html>\n";

?>