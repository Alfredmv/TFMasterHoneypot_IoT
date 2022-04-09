// Autor:Alfredo Martinez Viso
// Fuciones javascrip de validacion y envio AJAX con JSON
// Marzo 2022: 


const Tmax = 5;                //Temperatura maxima programable
const Tmin = -5;               //Temperatura minima programable 
const Hmax = 99;               //Humedad maxima programable
const Hmin = 69;               //Humedad minima programable
const DefaultTempe = 0.52;     //Temperatura por defecto departida
const DefaultHume = 90.25;     //Humedad por defecto de partida

var ActualTempe = 0.52;        //Temperatura inicial 
var ActualHume = 90.25;        //humedad inicial
var ActualEstadoHume = "";     //estado del progreso de la temperatura proramada ("EN PROCESO", "FINALIZADO")
var ActualEstadoTempe = "";    //estado del progreso de la humedad proramada ("EN PROCESO", "FINALIZADO")
var Tpro = '';                 //Temperatura Programada
var Hpro = '';                 //Humedad Programada


//validar si la temperatura introducida es correcta
function ValidateTempe()
{
    var kk = document.getElementById('txttemperatura');
    var n1;
    //Validar Temperatura
    n1 = kk.value;
    ret = checkNumber(n1);
    //alert("ret tempe " + ret);
    if (ret === true)
    {
        //Validar valores admisibles
        ret = checkTempe(n1);
        return ret;
    }
    else
    {
        //alert("Valor de Temperatura incorrecto");
        return "Valor de Temperatura incorrecto";  
    }
    return true;

}
//validar si la Humedad introducida es correcta
function ValidateHume()
{
    kk = document.getElementById("txthumedad");
    var n2;
    n2 = kk.value;
    //kk.value = hume.value;
    ret = checkNumber(n2);
    n2 = kk.value;
    if (ret === true) {
        //Validar valores admisibles
        ret = checkHume(n2);
        return ret;
    }
    else
    {
        //alert("Valor de Humedad incorrecto");
        return "Valor de Humedad incorrecto";
    }
    return true;
}

function isFloat2(n)
{
    return Number(n) === n && n % 1 !== 0;
}

function checkNumber(x)
{
    var kk = "";
    if (x == "") return false;
    x = Number(x);
    // check if the passed value is a number
    if (typeof x == 'number' && !isNaN(x))
    {

        // check if it is integer
        if (Number.isInteger(x))
        {

            //kk = x +  "is integer.";
            return true;
        }
        else
        {
            //kk = x + "is a float value";
            return true;
        }
    }
    else
    {
        // kk = x + " is not a number";
        return false;
    }
    //return kk;
}
function checkTempe(x) {
    x = Number(x);
    if (typeof x == 'number' && !isNaN(x)) {
        if (x < Tmin || x > Tmax)
            return ("Valor de Temperatura debe estar entre " +  Tmin + " y " + Tmax)
        else
            return true;

    }
    else return ("Valor de Temperatura incorrecto")

}

function checkHume(x) {
    x = Number(x);
    if (typeof x == 'number' && !isNaN(x)) {
        if (x < Hmin || x > Hmax)
            return ("Valor de Humedad debe estar entre " + Hmin + " y " + Hmax)
        else
            return true;

    }
    else return ("Valor de Humedad incorrecto")

}

function TextItemTextBox(id, txt, color = 'Blue') {
    var campo = document.getElementById(id);
    campo.style.color = color;
    campo.value =  txt;
    
}

/// visualiza las etiquetas de estado
function showItemlabel(id, txt, color='Blue')
{
    kk = document.getElementById(id);
    kk.innerHTML = "<font color='" + color + "'>" + txt + "</font>";
    //kk.style.display = '';
    $("#"+ id).show();
}

// Programcion de una nueva temperatura y humedad
function Actualizar( Tpro, Hpro) 		//Ajax
{
    var ip = "re";
    var DatosEnvio =
    {
        xIp: ip,
        xTpro: Tpro,
        xHpro: Hpro
    };
    //alert(Tpro + " ....  " +  Hpro);
    $.ajax({
        type: 'POST',
        url: 'actualizar_sensores.php',
        cache: false,
        dataType: "json",
        data: DatosEnvio,
        success: function (response)
        {
            //$ValRe = array('rIp' => $IP, 'StatusT' => $Testado, 'StatusH' => $Hestado, 'Coment' => $data,
            //               'rTActual' => $TActual, 'rHActual'=> $HActual, 'rTpro'=> $Tpro, 'rHpro'=> $Hpro);
            var m1 = JSON.stringify(response);
            var json = JSON.parse(m1);
            //alert("response:" + m1);
            var DatosRecivo =
            {
                Ip: json.rIp,
                StatusT: json.StatusT,
                StatusH: json.StatusH,
                Coment: json.Coment,
                rTActual: json.rTActual,
                rHActual: json.rHActual,
                rTpro: json.rTpro,
                rHpro: json.rHpro
            };
            //DEBUG
            //alert(DatosRecivo.Coment);
            refresSensores(DatosRecivo);
        }
    }); 
}


//peticion  para obtener la temperatura y humedad actual
function Refrescar() 		//Ajax
{
    var ip = "re";
    var DatosEnvio= 
    {
        xIp: ip
    };

    $.ajax({
        type: 'POST',
        url: 'refrescar_sensores.php',
        cache: false,
        dataType: "json",
        data: DatosEnvio,
        success: function (response)
        {
           
            var m1 = JSON.stringify(response);
            var json = JSON.parse(m1);
            //alert("response:" + m1);
            var DatosRecivo =
            {
                Ip: json.rIp,
                StatusT: json.StatusT,
                StatusH: json.StatusH,
                Coment: json.Coment,
                rTActual: json.rTActual,
                rHActual: json.rHActual,
                rTpro: json.rTpro,
                rHpro: json.rHpro
            };
            //DEBUG *********
            //alert("Nola: " + DatosRecivo.StatusT + "DatosRecivo['rTActual']:" + DatosRecivo['rTActual']);
            refresSensores(DatosRecivo);
            //return DatosRecivo1;
        }
    });
}
//cambia los valore en lo elementos  Html
function refresSensores(DatosRecivo)
{
    var gauge = document.gauges[0];
    var hume = document.gauges[1];
    
    //DEBUG *************************************************
    //alert(DatosRecivo['Coment'] + '**** ' + DatosRecivo['rTActual'] + '*** ' + DatosRecivo['StatusT']);
    //alert("DatosRecivo['rTActual']" + DatosRecivo['rTActual'] + '*** '+  DatosRecivo['StatusT']);
    // $ValRe=array('rIp' => $IP,'StatusT' =>$Testado,'StatusH' =>$Hestado, 'Coment' =>$data,
    //'rTActual' => $TActual,'rHActual'=> $HActual, 'rTpro'=> $Tpro,'rHpro'=> $Hpro);

    var color='Blue';
    gauge.value = DatosRecivo['rTActual'];
    hume.value = DatosRecivo['rHActual'];
    if (DatosRecivo.StatusT != "FINALIZADO") color = 'Red'
    TextItemTextBox("txttemperatura", DatosRecivo.rTpro,color);
    showItemlabel("lbltemperaturaA", DatosRecivo.StatusT, color);
    color = 'Blue';
    if (DatosRecivo.StatusH != "FINALIZADO") color = 'Red'
    TextItemTextBox("txthumedad", DatosRecivo.rHpro,color);
    showItemlabel("lblhumedadA", DatosRecivo.StatusH, color);

    ActualTempe = DatosRecivo['rTActual'];;
    ActualHume = DatosRecivo['rHActual'];
    ActualEstadoHume = DatosRecivo.StatusH;
    ActualEstadoTempe = DatosRecivo.StatusT;
    Tpro = DatosRecivo.rTpro;
    Hpro = DatosRecivo.rHpro;
}
//peticion  para obtener la temperatura y humedad actual
function salidasesion() 		//Ajax
{
    var ip = "re";
    var DatosEnvio=
    {
        xIp: ip
    };

    $.ajax({
        type: 'POST',
        url: 'cierrasesion.php',
        cache: false,
        dataType: "json",
        data: DatosEnvio,
        success: function (response) {

            var m1 = JSON.stringify(response);
            var json = JSON.parse(m1);
            //alert("response:" + json);

        }
    });
}
//peticion  para obtener la temperatura y humedad actual
function get_geoloca(ip) 		//Ajax
{
    var titulo = "Informaci&oacute;n y Geolocalizaci&oacute;n " + ip;
    var DatosEnvio =
    {
        xIP: ip
    };
    //contentType: "application/json; charset=utf-8",
    $.ajax({
        type: 'POST',
        url: 'geoloca.php',
        cache: false,
        dataType: "html",
        data: DatosEnvio,
        success: function (response) {

            var msg = response;
            aviso(titulo, msg);
        },
            error: function(e) {
                alert('Error: ' + e);
            }

        
    });
}

function aviso(titulo, msg) {
    /*
        var alertList = document.querySelectorAll('.alert')
        alertList.forEach(function (alert) {
        new bootstrap.Alert(alert);
        */
    bootbox.alert({
        title: titulo,
        message: msg,
        centerVertical: true,
        //callback: function(result)
        //{
        // console.log(result);
        //}
    });
}




