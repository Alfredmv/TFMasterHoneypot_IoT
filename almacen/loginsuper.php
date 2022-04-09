<?php

if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}
$Error=false;
if(isset($_SESSION['ValidUser']))
{

    if($_SESSION['ValidUser']==false)
    {
        $Error=true ;
        $msg= "CREDENCIALES INCORRECTAS";
    }

}
?>

    <div class="container">
        <div class="row text-center login-page">
            <div class="col-md-12 login-form">
                <form action="reinicio.php?opcion=Supervision" method="post">
                    <div class="row">
                        <div class="col-md-12 login-form-header">
                            <p class="login-form-font-header">
                                Apple<span>Storage</span><p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 login-from-row">
                            <input name="usuario" id="usuario" type="text" placeholder="Usuario" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 login-from-row">
                            <input name="password" id="password" type="password" placeholder="Contrase&ntilde;a" required/>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-md-12 login-from-row">
                            <button class="btn btn-info" type="submit" id="botonsubmit" name="botonsubmit">Entrar</button>
                        </div>
                    </div>

                    <?php
                    if($Error == true)
                    {
                        echo"                   <div class=\"row\">\n";
                        echo"                       <div class=\"col-md-12 login-from-row\">\n";
                        echo"                           <label class=\"text-danger\">".$msg."</label>\n";
                        echo"                       </div>\n";
                        echo"                   </div>\n";
                    }
                    //echo $_SESSION['traza'];
                    ?>
                </form>
            </div>
        </div>
    </div>