<?php

    // Autor:Alfredo Martinez Viso
	// Conexión con la base de datos y funciones estándar de mySQL
	// Marzo 2022: */
    // PHP 7.3   22/10/2022

	global $gdDB_USER;
	global $gdDB_PASS;
	global $gdDB_NAME;
	global $gdLink;
    global $stmt;

    $stmt = null;

    // parametros de conexión
    $servername = "127.0.0.1";    // host de mySQL";  //192.168.202.1
    $gdDB_USER="root";            // Usuario
	$gdDB_PASS="Amvi1966";        // key
    $gdDB_NAME="applesaved";      // Base de Datos
    $socket=3306;                 // puerto por defecto

	// Conectar si no existe ya la conexión
    if(!isset($gdLink))
    {
        $gdLink = mysqli_connect($servername ,$gdDB_USER, $gdDB_PASS,  $gdDB_NAME, $socket);
        //$con = mysqli_connect("apdoc.empre.es","dokeos_lect","Doke2014","dokeos_main",3307);
        //new mysqli($host, $user, $password, $dbname, $port, $socket);
        if(!$gdLink) exit("Fall&oacute; la conexi&oacute;n a MariaDB");


    }
	if(!$gdLink) 		exit("La conexión con la BD ha fallado");


    function db_query($query)
    {
        global $gdLink;
		global $stmt;

        $f=preg_replace("/^\s+/","",$query);
        $query=$f;

        if(preg_match( '/'.'update|delete'.'/i', $query) && !preg_match( '/'.'where'.'/i', $query))
            db_die_msj("Program error: update/delete sentence without a where clause");

        if (preg_match('/'.'insert|update|delete|create'.'/i',$query))
            $stmt = mysqli_prepare($gdLink, $query) or db_die1($query);
        else
            $stmt = mysqli_prepare($gdLink, $query) or db_die1($query);



        mysqli_stmt_execute($stmt) or db_die1($query);

        if (preg_match('/'.'insert|update|delete|create'.'/i',$query))
            mysqli_commit($gdLink) or db_die1($query);

        return $stmt;
    }


    function db_query2($query)
    {
        global $gdLink;
		global $stmt;

        if (preg_match('/'.'insert|update|delete|create'.'/i',$query))
            $stmt = mysqli_prepare($gdLink, $query) or db_die1();
        else
            $stmt =mysqli_query($gdLink, $query) or db_die1();

        if (preg_match('/'.'insert|update|delete|create'.'/i',$query))
            mysqli_commit($gdLink) or db_die1();

        return $stmt;
    }
    //Para INSERT, DELETE, UPDATE
    function db_queryDML($query)
    {
        global $gdLink;
		global $stmt;

        //$stmt = mysqli_prepare($gdLink, $query) or db_die1();
       
        $stmt =mysqli_query($gdLink, $query);
        //db_free_stmt($stmt);
    }
    //Para Select
    function db_queryselect($query)
    {
        global $gdLink;
		global $stmt;


         //$stmt =mysqli_query($gdLink, $query) or db_die1();
        $stmt =mysqli_query($gdLink, $query);

        return $stmt;
    }

	//Fetch row statement array asociativo y con indices
	function db_fetch_row($resul)
	{
        $row = mysqli_fetch_array($resul, MYSQLI_BOTH);
	    return $row;
	}

   //Error-Messages
    function db_die1($sql="")
    {
        global $gdLink;
        global $stmt;

        //$er=print_r(mysqli_error($gdLink),true);
        $er=mysqli_error($gdLink);
        //mysqli_stmt_close($stmt);
        die("<br>Error de Mysql:  ". $er."\n". $sql."</body></html>");
    }



	// Error-Messages
	function db_die()
	{
         global $gdLink;
         global $stmt;

         $e=mysqli_error($gdLink);// Para errores de oci_execute, pase el gestor de sentencia
         echo "\n<pre>\n";
         echo printf("\n%".($e['offset']+1)."s", "^")."\n";
         echo  "\n</pre>\n";
         //************************************************* */
         /* liberar el conjunto de resultados */
         db_free_stmt($stmt);

         mysqli_stmt_close($stmt);
         die("<br>Database command error!</body></html>");
	}
    //Error-Messages
    function db_die_select()
	{
        global $gdLink;
        global $stmt;
        //************************************************* */
        /* liberar el conjunto de resultados */
        db_free_stmt($stmt);
        //cierra consulta
        //mysqli_stmt_close($stmt);

	}

    //Error-Messages
	function db_die_msj($msj)
	{
        global $gdLink;
        global $stmt;

        $er = print_r(mysqli_error($gdLink),true);
        echo $msj."  ".$er;
        mysqli_stmt_close($stmt);
        die("<br>Database command error!</body></html>");
	}


 	// Libera todos los recursos asociados con una sql de modificacion
    function db_free_stmt($stmt)
 	{
         mysqli_free_result($stmt);
 	}


?>