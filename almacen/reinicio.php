<?php



/* Alfredo Martinez Viso
 * Created on 10/03/2022
 *
 * alternativa a inicio.php, que solo se ejecuta una vez
 * para moverse por las viñetas del menu
 */

    include_once "./Lib/funciones_html.php";

    if (session_status() !== PHP_SESSION_ACTIVE)
    {
        session_start();
    }

    headerNoCache();

	//$sOpcion = $opcion;
	//$wfPanel = $panel;
    $traza='';
    $pgSupervison="loginsuper.php";
    $pwd='AlfredoTFM21-22';
    $username='root';

    //$_SESSION['opcion']=$opcion;
    $_SESSION['opcion']=$_GET['opcion'];
    $opcion =$_GET['opcion'];

    //*************   DEBUG *********************
    //$_SESSION['ValidUser']= false;
    //*******************************************
    //Determinar si se puden credenciales
    if($opcion == 'Supervision')
    {
        if(isset($_POST['botonsubmit']))
        {
              $usuario=$_POST['usuario'];
              $password=$_POST['password'];


              $usuario=htmlentities($usuario);
              $usuario=strip_tags($usuario);
              $password=htmlentities($password);
              $password=strip_tags($password);

            $traza= " submit "." usuario: ". $usuario. "   password: ".$password;
            if(trim($usuario) == $username and trim($password) == $pwd)
            {
                $_SESSION['ValidUser']=true;
                $pgSupervison= "gridreport.php";
                $traza.= " credenciales OK";
            }
            else
            {
                $_SESSION['ValidUser']=false;
                $pgSupervison= "loginsuper.php";
                $traza.= " NO_credenciaole ";
            }

        }
        else
        {
            $traza.= " NO_submit ";
            if(isset($_SESSION['ValidUser']))
            {
                if($_SESSION['ValidUser']== true)
                {
                    $pgSupervison= "gridreport.php";
                    $traza.= " credenciales_OLE ";
                }
                else
                {
                    $pgSupervison= "loginsuper.php";
                    $traza.= " credenciales_NO_OLE ";
                }

            }
            else
            {
                $pgSupervison= "loginsuper.php";
                $traza.= " NO_SESSION[ValidUser] ";
            }
        }


    }
    elseif($opcion == 'Buscar'  and $_SESSION['ValidUser']== true )
    {

        if(isset($_POST['botonbuscar']))
        {
            $_SESSION['BUSCARIP']=$_POST['Ipselect'];
            $_SESSION['BUSCARFECHADESDE']=$_POST['date_desde'];
            $_SESSION['BUSCARFECHAHASTA']=$_POST['date_hasta'];
            $_SESSION['RADIOINFO']=$_POST['radioinfo'];
            $_SESSION['CHECKGEO']="";
            if(isset($_POST['CheckGeoLoca'])) $_SESSION['CHECKGEO']=$_POST['CheckGeoLoca'];

            $traza.= " OPCION->BUSCAR-->submit";
        }

        $pgSupervison= "gridreport.php";

        $traza.= " OPCION->BUSCAR " . " -- CHECKGEO: " .$_SESSION['CHECKGEO'];
        //$traza.=print_r($_POST);
    }

    $_SESSION['traza']=$traza;
	include_once "Main.php"

?>