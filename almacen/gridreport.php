<?php

//include_once "./Lib/codigo_estandar.php";
include_once "./Lib/mysql_inc.php";
include_once "./Lib/funciones.php";

if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}


date_default_timezone_set('Europe/Madrid');
$fechaactual = date('Y-m-d', time());

$chekedoperaciones ="";
$chekedentradas= "";
$Ipselect="-";
$date_desde=$fechaactual;
$date_hasta=$fechaactual;

//$radioinfo=$_POST["radioinfo"];
//$KK=$_SESSION['BUSCARFECHADESDE'];
if($_SESSION['opcion'] =='Buscar')
{

    $chekegeo="";
    $Ipselect=$_SESSION['BUSCARIP'];
    $date_desde=$_SESSION['BUSCARFECHADESDE'];
    $date_hasta= $_SESSION['BUSCARFECHAHASTA'];
    $radioinfo=$_SESSION['RADIOINFO'];
    $geo=$_SESSION['CHECKGEO'];

    if($radioinfo == 'operaciones') $chekedoperaciones=  "checked";
    if($radioinfo == 'entradas') $chekedentradas=  "checked";
    If($geo == 'on') $chekegeo=  "checked";
}
else
{
    $Ipselect="-";
    $date_desde=$fechaactual;
    $date_hasta=$fechaactual;
    $chekedoperaciones="checked";
    $chekedentradas="";
    $radioinfo='operaciones';
    $chekegeo=  "";

}

//echo "Ipselect:".$Ipselect." date_desde:" .$date_desde." date_hasta:" .$date_hasta." radioinfo:". $radioinfo. " SESSION['opcion']: ". $_SESSION['opcion']. "SESSION['BUSCARIP']:".$_SESSION['BUSCARIP']." SESSION['BUSCARFECHADESDE']:". $KK;



$ListaIps=getIPs();


$excelestado="  class=\"btn btn-secondary btn-sm \" disabled "; //btn btn-secondary
if($_SESSION['opcion'] ==='Buscar')
{


    $MSQ="";
    if($radioinfo == 'operaciones')
        $listado = operaciones($Ipselect,$date_desde,$date_hasta, $chekegeo, $MSQL);
    else
        $listado =entradas($Ipselect,$date_desde,$date_hasta, $chekegeo, $MSQL);
    //Debug
    //echo  $MSQL. "\n";


    if(sizeof($listado)>0)
    {
        $excelestado="  class=\"btn-info btn-sm\" ";
    }
    //else
    //{
    //    $excelestado="  class=\"btn-info btn-sm  disabled\" disabled ";
    //}
}


//echo " Grid Repor ".sizeof($ListaIps)."   ".$ListaIps[0][0]."\n";
?>

<div  class="container-fluid">
    <div class="">
        <form action="reinicio.php?opcion=Buscar" method="post" class="border p-2">  
            <div class="row mx-1">

                <label for="Ipselect" class="col-auto col-form-label">IP:</label>
                <div class="form-group col-sm-2">
                    <?php
			    $style ="\"background-color:LightYellow;\"";
                echo "     <select id=\"Ipselect\" name=\"Ipselect\" class=\"form-control-sm form-select-sm\" style=".$style." data-bs-toggle=\"tooltip\" data-bs-placement=\"right\" title=\"Selecci&oacute;n IP\">\n";
			    $i=0;

			    if(sizeof($ListaIps)>0)
                        echo "             <option value=\"-\"  selected>-</option>\n";
                for($i=0;$i<sizeof($ListaIps);$i++)
			    {
                    if($Ipselect == $ListaIps[$i][0])		$sel = "selected";
				    else								   $sel = "";

                        echo "              <option value=" . $ListaIps[$i][0]. " style=".$style."  ".$sel.">" . $ListaIps[$i][0] . "</option>\n";
			    }
			    echo "      </select>\n";
                    ?>
                </div>

                <label for="date_desde" class="col-auto col-form-label">Desde:</label>
                <div class="input-group date form_date col-sm-2 mb-1" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_desde" data-link-format="yyyy-mm-dd">
                    <input class="form-control-sm" type="text" size="10" value=<?php echo "\"".$date_desde. "\"";  ?> readonly />
                    <button type="button" class="btn-xs btn-outline-danger">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span>
                        </span>
                    </button>
                    <button type="button" class="btn-xs btn-info">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </button>
                </div>
                <input type="hidden" id="date_desde" name="date_desde" value=<?php echo "\"".$date_desde. "\"";  ?> />


                <label for="date_hasta" class="col-auto col-form-label">Hasta:</label>
                <div class="input-group date form_date col-sm-2 mb-1" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_hasta" data-link-format="yyyy-mm-dd">
                    <input class="form-control-sm" type="text"  size="10" value=<?php echo "\"".$date_hasta. "\"";  ?> readonly />
                    <button type="button" class="btn-xs btn-outline-danger">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span>
                        </span>
                    </button>
                    <button type="button" class="btn-xs btn-info">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </button>
                </div>
                <input type="hidden" id="date_hasta" name="date_hasta" value=<?php echo "\"".$date_hasta. "\"";  ?> />

                <div class="form-group col-sm-1 align-self-end mb-1">
                    <button type="submit" class="btn-info btn-sm" id="botonbuscar" name="botonbuscar">
                        <span class="glyphicon glyphicon-search"></span> Buscar
                    </button>
                </div>
                <div class="form-group col-sm-1 align-self-end mb-1">
                    <button type="button" id="botonexcel" name="botonexcel"  <?php echo $excelestado; ?> onclick="proceexcel();">
                        <span class="glyphicon glyphicon-th"></span> Excel
                    </button>
                </div>


               
                <div class="row ">
                    <div class="col-auto">
                        <!-- RadioButton -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioinfo" id="radioinfoentradas" <?php echo " ".$chekedentradas;  ?> value="entradas" />
                            <label class="form-check-label" for="radioinfoEntradas">
                                Entradas
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioinfo" id="radioinfooperaciones" <?php echo " ".$chekedoperaciones;  ?> value="operaciones" />
                            <label class="form-check-label" for="radioinfoOperaciones">
                                Operaciones
                            </label>
                        </div>
                    </div>
                    <div class="col-auto align-self-end">
                        <!-- checkButton -->
                        <div class="form-check">
                            <label class="form-check-label" for="flexCheckDefault">
                                Geolocalizaci&oacute;n
                            </label>
                            <input class="form-check-input" type="checkbox" id="CheckGeoLoca" name="CheckGeoLoca"  <?php echo " ".$chekegeo; ?> />
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
        <hr/>
    </div>
</div>


<?php
 //debug
 //echo $_SESSION['traza'];
if($_SESSION['opcion'] ==='Buscar')
{

    if(sizeof($listado)>0)
    {
        echo "<br>\n";

        $nombrearchivo="accesos_".$fechaactual;
        if($chekegeo != "") /// con Geolocalizacion
        {
            $nombrearchivo="Geo_accesos_".$fechaactual;
            if($radioinfo == 'operaciones')
            {
                $nombrearchivo="GEO_operaciones_".$fechaactual ;
            }

            echo "<div class=\"row\"  style=\"text-align:center;\">\n";
            if($radioinfo == 'operaciones')
                 echo "      <h2>Informaci&oacute;n de IPs de Operaciones Programadas</h2>\n";
            else
                 echo "      <h2>Informaci&oacute;n de IPs de Accesos</h2>\n";
            echo "</div>\n";
            echo "<div class=\"table-responsive mx-auto align-items-center\" style=\"width:30%;\">\n";
            echo "<table  class=\"table table-bordered border-info  table-hover  table-striped \">\n";

            echo "<thead style=\"background-color:LightBlue;\">\n";
            echo "      <tr>\n";
            echo "          <th scope=\"col\" style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"IP\"><span style=\"font-family:Cambria;\">IP.</span></th>\n";
            $col=1;
            //tabla exportar a Excel
            $cabecero = "<table border=1>";
            $cabecero .= "<thead><tr>\n";
            $cabecero .= "  <th colspan=$col style=\"background-color:KHAKI;\">LISTADO DE OPERCIONES REALIZADAS</th>";
            $cabecero .= "</tr>";
            $cabecero .= "<tr><th>IP.</th>";
            echo "          </tr>\n";
            echo "     </thead>\n";
            $cabecero .= "</tr></thead>";
            $datos = "<tbody>";
            echo "      <tbody>\n";
            for($i=0;$i<sizeof($listado);$i++)
            {

                echo "      <tr>\n";
                echo "      <td style=\"text-align:center\"><a href=\"javascript:get_geoloca('".$listado[$i][0] ."')\";>".$listado[$i][0] ."</a></td>\n";
                echo "      </tr>\n";

                $datos .= "<tr><td>".$listado[$i][0]."</td></tr>";
            }
            //echo "</tr>";
            echo "   </tbody>\n";
            echo "</table>\n";
            $datos.="</tbody></table>";


        }
        else  /// sin Geolocalización  *******************************************************
        {
            //tabla operaciones
            if($radioinfo == 'operaciones')
            {
                $nombrearchivo="operaciones_".$fechaactual;
                echo "<div class=\"row\"  style=\"text-align:center;\">\n";
                echo "      <h2>Listado de Operaciones Programadas</h2>\n";
                echo "</div>\n";
                echo "<div class=\"table-responsive mx-auto align-items-center\" style=\"width:80%;\">\n";
                echo "<table  class=\"table table-bordered border-info  table-hover  table-striped \">\n";
                //cabecero
                echo "<thead style=\"background-color:LightBlue;\">\n";
                echo "      <tr>\n";
                echo "          <th scope=\"col\" style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"IP\"><span style=\"font-family:Cambria;\">IP.</span></th>\n";
                echo "          <th scope=\"col\" style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"Fecha de Cambio del Programa\"><span style=\"font-family:Cambria;\">F.CAMBIO</span></th>\n";
                echo "          <th scope=\"col\" style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"Temperatura de Partida\"><span style=\"font-family:Cambria;\">T.INICIAL</span></th>\n";
                echo "          <th scope=\"col\" style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"Indice de Humedad de Partida\"><span style=\"font-family:Cambria;\">H.INICIAL</span></th>\n";
                echo "          <th scope=\"col\" style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"Nueva Temperatura Programada\"><span style=\"font-family:Cambria;\">T.PRO</span></th>\n";
                echo "          <th scope=\"col\" style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"Nuevo indice de Humedad Programado\"><span style=\"font-family:Cambria;\">H.PRO</span></th>\n";


                $col=6;
                //tabla exportar a Excel
                $cabecero = "<table border=1>";
                $cabecero .= "<thead><tr>";
                $cabecero .= "  <th colspan=$col style=\"background-color:KHAKI;\">LISTADO DE OPERCIONES REALIZADAS</th>";
                $cabecero .= "</tr>";
                $cabecero .= "<tr><th>IP.</th><th>F.CAMBIO</th><th>T.INICIAL</th><th>H.INICIAL</th><th>T.PRO</th><th>H.PRO</th>";

                echo "          </tr>\n";
                echo "     </thead>\n";
                $cabecero .= "</tr></thead>";
                $datos = "<tbody>";
                echo "      <tbody>\n";

                for($i=0;$i<sizeof($listado);$i++)
                {

                    echo "      <tr>\n";
                    echo "      <td style=\"text-align:center\"><span style=\"font-family: Cambria;\">".$listado[$i][0] ."</span></td>\n";
                    echo "      <td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][1] ."</span></td>\n";
                    echo "      <td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][2] ."</span></td>\n";
                    echo "      <td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][3] ."</span></td>\n";
                    echo "      <td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][4] ."</span></td>\n";
                    echo "      <td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][5] ."</span></td>\n";
                    echo "      </tr>\n";

                    $datos .= "<tr><td>".$listado[$i][0]."</td> <td>".$listado[$i][1]."</td> <td>".$listado[$i][2]."</td> <td>".$listado[$i][3]."</td><td>".$listado[$i][4]."</td><td>".$listado[$i][5]."</td></tr>";
                }

                echo "   </tbody>\n";
                echo "</table>\n";
                $datos.="</tbody></table>";
            }
            else
            {
                //Tabla de accesos
                echo "<div class=\"row\"  style=\"text-align:center;\">\n";//style=\"text-align:center;\"
                echo "      <h2>Listado de Accesos</h2>\n";
                echo "</div>\n";
                echo "<div class=\"table-responsive mx-auto\" style=\"width:85%;\">\n";
                echo "<table  class=\"table table-bordered border-warning  table-hover  table-striped \">\n";
                //listado de ENTRADAS
                //IP, FECHAHORA, FECHAHORA_FIN
                /* Cabecero de la tabla */
                echo "<thead style=\"background-color:khaki;\">\n";
                echo "      <tr>\n";
                echo "          <th style=\"text-align:center;width:5%;\"><span style=\"font-family:Cambria;\">IP.</span></th>\n";
                echo "          <th style=\"text-align:center;width:5%;\"><span style=\"font-family:Cambria;\">F.ENTRADA</span></th>\n";
                echo "          <th style=\"text-align:center;width:5%;\"><span style=\"font-family:Cambria;\">F.SALIDA</span></th>\n";
                echo "          <th style=\"text-align:center;width:5%;\" data-bs-toggle=\"tooltip\" data-bs-placement=\"Top\" title=\"Duraci&oacute;n de la sesi&oacute;n 'h:m:s'\"><span style=\"font-family:Cambria;\"><span style=\"font-family: Cambria;\">Durci&oacute;n</span></th>\n";
                echo "          <th style=\"text-align:center;width:25%;\"><span style=\"font-family:Cambria;\">USER_AGENT</span></th>\n";

                $col=5;
                //tabla export Excel
                $cabecero = "<table border=1>";
                $cabecero .= "<thead><tr>";
                $cabecero .= "   <th colspan=$col style=\"background-color:KHAKI;\">LISTADO DE VISITAS REALIZADAS</th>";
                $cabecero .= "</tr>";
                $cabecero .= "<tr><th>IP.</th> <th>F.ENTRADA</th><th>T.SALIDA</th><th>DURACION</th><th>USER_AGENT</th>";


                echo "          </tr>\n";
                echo "      </thead>\n";
                $cabecero .= "</tr></thead>";
                $datos = "<tbody>";
                echo "      <tbody>\n";
                for($i=0;$i<sizeof($listado);$i++)
                {
                    echo "<tr>\n";
                    echo "<td style=\"text-align:center\"><span style=\"font-family: Cambria;\">".$listado[$i][0] ."</span></td>\n";
                    echo "<td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][1] ."</span></td>\n";
                    echo "<td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][2] ."</span></td>\n";
                    echo "<td style=\"text-align:right\"><span style=\"font-family: Cambria;\">" .$listado[$i][3] ."</span></td>\n";
                    echo "<td style=\"text-align:left\"><span style=\"font-family: Cambria;\">"  .$listado[$i][4] ."</span></td>\n";
                    echo "</tr>\n";
                    $datos .= "<tr><td>".$listado[$i][0]."</td><td>".$listado[$i][1]."</td><td>".$listado[$i][2]."</td><td>".$listado[$i][3]."</td><td>".$listado[$i][4]."</td></tr>";
                }
                echo "      </tbody>\n";
                echo "      </table>\n";
                $datos.="</tbody></table>";
            }
        }

        // Exportar
        $_SESSION['excel_tipo'] = 'xls';
        $_SESSION['datosexcel'] = $datos;
        $_SESSION['nombre_informe'] = $nombrearchivo;
        $_SESSION['cabecero'] = $cabecero;


        //if($j>0)
        //    echo "<a href=\"Export.php\" title='Exportar a hoja excel el listado de cursos'><img src=\"img/excel.png\" style='vertical-align: middle; border:0' width=\"30\" height=\"30\" alt=\"excel\"></a>\n";

        echo "</div>";
        //AAI DEBUG echo "SQL : ".$MSQL;
    }
    else
    {
        echo "<div class='centerTable'>\n";
        echo "<br><br><span class=\"error\">Sin resultados</span>";
        echo "</div>";
    }
    //debug

}

?>