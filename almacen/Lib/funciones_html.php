<?php
// Autor:Alfredo Martinez Viso
// funciones HTM generales
// Marzo 2022: */

    function headerNoCache()
    {
        header('Expires: Sat, 30 Apr 1998 01:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
    }


  	 //inicio HTML 5
    function html5Tag()
    {
	    $html_tag = "<!DOCTYPE html>\n";
	    $html_tag = $html_tag ."<html lang='es'>\n";
	    return $html_tag;
    }
        //inicio HTML 5 Con lenguajeTag
    function html5LenTag($lenguaje)
  	{
        $html_tag = "<!DOCTYPE html>\n";
        $html_tag = $html_tag."<html lang='".$lenguaje."'>\n";
	    return $html_tag;
    }

    function simpleHeadTag($titulo)
    {
        $head_tag = "<head><title>".$titulo."</title></head>\n";
        return $head_tag;
    }

    function CloseHead()
    {
        return "</head>\n";
    }

    function bodyTag()
    {
        return "<body>\n";
    }
    function bodyTagOnLoad()
    {
        return "<body onload=\"doOnLoad();\">\n";
    }


    function bodyTagClase($clase)
    {
        return "<body class=".$clase.">\n";
    }

    function tableTag($clase)
    {
        return "<table class=".$clase.">\n";
    }

    function finalTag()
    {
        return "</body>\n</html>\n";
    }

    function resaltarBoton($id_boton)
    {
        return " id=\"".$id_boton."\" onmouseover=\"document.getElementById('".$id_boton."').style.color='#ff0000'\" onmouseout=\"document.getElementById('".$id_boton."').style.color='#000000'\"";
    }

    //function mensajeTag($mensaje, $clase, $opcion)
    //{
    //    $txt_ttt_btn_aceptar ='Aceptar mensaje';
    //    $txt_aceptar="ACEPTAR";

    //    $msj_tag = "<form action=indice.php?opcion=".$opcion." METHOD='post' ENCTYPE='multipart/form-data' NAME='aceptar' target=_top>\n";
    //    $msj_tag = $msj_tag."<table class=datos>\n";
    //    $msj_tag = $msj_tag."<tr>\n";
    //    $msj_tag = $msj_tag."<td align=center><LABEL class=".$clase.">".$mensaje."</LABEL></td>\n";
    //    $msj_tag = $msj_tag."<tr>\n</tr>\n";
    //    $msj_tag = $msj_tag."<td align=center><button TYPE=submit ENABLED title='$txt_ttt_btn_aceptar' align=center ";
    //    $msj_tag = $msj_tag.resaltarBoton("b_aceptar");
    //    $msj_tag = $msj_tag.">" . $txt_aceptar . "</button></td>\n";
    //    $msj_tag = $msj_tag."</tr>\n";
    //    $msj_tag = $msj_tag."</table>\n";
    //    $msj_tag = $msj_tag."</form>\n";
    //    return $msj_tag;
    //}

    //function selfMensajeTag($mensaje, $clase, $opciones)
    //{
    //    $txt_ttt_btn_aceptar ='Aceptar mensaje';
    //    $txt_aceptar="ACEPTAR";

    //    $msj_tag = "<form action=".$PHP_SELF."?".$opciones." METHOD='post' ENCTYPE='multipart/form-data' NAME='aceptar' target=_self>\n";
    //    $msj_tag = $msj_tag."<table class=datos>\n";
    //    $msj_tag = $msj_tag."<tr>\n";
    //    $msj_tag = $msj_tag."<td align=center><LABEL class=".$clase.">".$mensaje."</LABEL></td>\n";
    //    $msj_tag = $msj_tag."<tr>\n</tr>\n";
    //    $msj_tag = $msj_tag."<td align=center><button TYPE=submit ENABLED title='$txt_ttt_btn_aceptar' align=center ";
    //    $msj_tag = $msj_tag.resaltarBoton("b_aceptar");
    //    $msj_tag = $msj_tag.">" . $txt_aceptar . "</button></td>\n";
    //    $msj_tag = $msj_tag."</tr>\n";
    //    $msj_tag = $msj_tag."</table>\n";
    //    $msj_tag = $msj_tag."</form>\n";
    //    return $msj_tag;
    //}




?>
