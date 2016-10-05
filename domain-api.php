<?php
/**
 * Plugin Name: domainapi
 * Plugin URI: http://inyaka.net/blog
 * Description: Sencillo plugin para fines didacticos que imprime un domainapi a elecci&oacute;n.
 * Version: 0.1
 * Author: Inyaka
 * Author URI: http://inyaka.net/blog/
 **/
function domainapi()
{
    global $wpdb;
    global $dominio;
    global $palabra;
    $array_suggest = array('sd');
    $table_name = $wpdb->prefix . "domainapi";
    //$domainapi= $wpdb->get_var("SELECT domainapi FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
//    $domainapi = $wpdb->get_var("SELECT domainapi FROM $table_name ORDER BY $table_name.id desc , 1; ");
    $kjj = auth($palabra, $dominio);
    if ($kjj == 1) {
        echo $palabra . "." . $dominio . " available" . "</br>";
    } else {
        echo $palabra . "." . $dominio . " no available" . "</br>";
    }

    $c_domain = $wpdb->get_results("SELECT extension FROM $table_name where type=1 ORDER BY rand()");
    foreach ($c_domain as $row):
        //echo $palabra.".".$row->dominio;
        array_push($array_suggest, $palabra . "." . $row->extension);
    endforeach;
    $c_coloquiales = $wpdb->get_results("SELECT word FROM $table_name where type=2 order by rand() ");
    foreach ($c_coloquiales as $row):
        //echo $palabra.".".$row->dominio;
        array_push($array_suggest, $row->extension . $palabra . "." . $dominio);
    endforeach;
    $c_prefix = $wpdb->get_results("SELECT extension FROM $table_name where type=3 ORDER BY RAND()");
    foreach ($c_prefix as $row):
        //echo $palabra.".".$row->dominio;
        array_push($array_suggest, $row->extension . $palabra . "." . $dominio);
    endforeach;
    echo "</br>";
    for ($i = 1; $i < count($array_suggest); $i++) {
        echo $array_suggest[$i] . "</br>";
    }
    auth_2($array_suggest);

    //print_r($array_suggest);
    /* ----------------------------------------
for($i=1; $i<count($array_suggest); $i++){
   $nueva=explode(".",$array_suggest[$i]);
   echo $nueva[0]."</br>";
       echo $nueva[1]."</br>";

}

/*------------------------------------------------
$array_suggest=array_fill(3,1,$c_domain);
print_r($array_suggest);
echo "</br>";
$c_coloquiales = $wpdb->get_results("SELECT coloquial FROM `wp_coloquiales` where pais = 'peru' or pais = 'todos' order by rand() " );
array_push($array_suggest,$c_coloquiales);
print_r($array_suggest);


foreach ( $array_suggest as $row ):
echo "</br>".$row->dominio;
endforeach;
    print_r($array_suggest);

/*foreach ( $c_domain as $row ):
    $array_suggest=array_fill(1,2,$palabra.".".$row->dominio);
endforeach;
print_r($array_suggest);
/*

$c_coloquiales = $wpdb->get_results("SELECT coloquial FROM `wp_coloquiales` where pais = 'peru' or pais = 'todos' order by rand() " );
foreach ( $c_coloquiales as $row ):
$array_suggest=array_fill(count($array_suggest)+1,count($c_coloquiales),$row->coloquial.$palabra.".".$dominio);
endforeach;
//print_r($array_suggest);
*/

    /*
    foreach ( $c_domain as $row ):
    $kjj = auth($palabra,$row->dominio);
    if($kjj == 1)
    {echo $palabra.".".$row->dominio." available"."</br>";}
    else
    {echo $palabra.".".$row->dominio." no available"."</br>";}
    endforeach;

    echo "</br>";
    $c_coloquiales = $wpdb->get_results("SELECT coloquial FROM `wp_coloquiales` where pais = 'peru' or pais = 'todos' order by rand() " );
    foreach ( $c_coloquiales as $row ):
    $kjj = auth($row->coloquial.$palabra,$dominio);
    if($kjj == 1)
    {echo $row->coloquial.$palabra.".".$dominio." available"."</br>";}
    else
    {echo $row->coloquial.$palabra.".".$dominio." no available"."</br>";}
    endforeach;

    echo "</br>";
    $c_prefix = $wpdb->get_results( "SELECT prefijo FROM `wp_prefijo` where pais = 'pe' or pais = 'todos' ORDER BY RAND()" );
    foreach ( $c_prefix as $row	 ):
    $kjj = auth($row->prefijo.$palabra,$dominio);
    if($kjj == 1)
    {echo $row->prefijo.$palabra.".".$dominio." available"."</br>";}
    else
    {echo $row->prefijo.$palabra.".".$dominio." no available"."</br>";}
    endforeach;

    //include('template/domainapi.html');
        //echo $palabra;
    //	$kjj = auth($palabra,$dominio);
    //	echo $kjj;

        */
}

function auth_2($array_sugest)
{

    global $ga_username;
    global $ga_password;

    //https://www.google.com/accounts/ClientLoginaccountType=GOOGLE&Email=derly249@gmail.com&service=analytics&source=My app&Passwd=dwn249//789
    //$gaUrl = "https://httpapi.com/api/domains/v5/suggest-names.json";
    //$gaUrl = "https://httpapi.com/api/domains/v5/suggest-names.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&keyword=".$domainapi."";

    //$gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=".$domainapi."&tlds=".$punto."";
    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc";
    for ($i = 1; $i < count($array_sugest); $i++) {
        $nueva = explode(".", $array_sugest[$i]);
        $gaUrl .= "&domain-name=" . $nueva[0];
        $gaUrl .= "&tlds=" . $nueva[1];

    }
    echo "</br>" . $gaUrl . "</br>";
    //$authData = "auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&keyword=eventus";

    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $gaUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    /* metodos para post
        //curl_setopt($ch, CURLOPT_POST, 1);
         //curl_setopt($ch, CURLOPT_POSTFIELDS, $authData);
     */
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
    $fileHandle = fopen(dirname(__FILE__) . "/error.txt", "w+");

    curl_setopt($ch, CURLOPT_VERBOSE, true);

    curl_setopt($ch, CURLOPT_STDERR, $fileHandle);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    $response = curl_exec($ch);

    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {

        echo "</br>";
        //var_dump($response);

        $tokensa = json_decode($response);
        //$respuesta = $tokensa->{'proeventus.la'}->{'status'};
        //echo "pruebaabab::". $respuesta;
        echo "</br>";
        foreach ($tokensa as $fila => $fila2):
            $respuesta = $tokensa->{$fila}->{'status'};
            $llave = $tokensa->{$fila}->{'classkey'};
            //echo $fila;
            echo $fila . " estado: " . $respuesta . " llave:" . $llave;
            echo "</br>";
        endforeach;
        /*foreach ( $tokensa as $fila => $fila2):
            echo "*</br>";
            print_r($fila2);
        endforeach;
*/
        /*foreach ( $tokensa as $fila => $status	 ):
            //var_dump($tokensa->$fila->$);
            echo "</br>--";

            foreach ( $tokensa->$fila as $filas => $stado  ):
                echo $tokensa->$fila->$filas;
                endforeach;
        endforeach;
        echo "</br>".$respuesta."</br>";
        */
        /*if(strcmp($respuesta,"available")!==0){
            //echo "no hay este dominio";
            $respuestaf=0;
        }
        else{$respuestaf=1;}
        */
        $tokensa = explode("\n", trim($response));

        //	print_r($tokensa);


        //echo var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR'])));
        //echo var_export( unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=200.37.124.213')));

        $ubicacion = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=200.37.124.213'));
        echo "ip :" . $ubicacion['geoplugin_request'];
        echo "pais :" . $ubicacion['geoplugin_countryName'];
        //var_dump($ubicacion);
        //echo $ubicacion;

        //$geoubi = json_decode($ubicacion);
        //$respuesta = $ubicacion->{'geoplugin_request'};
        //print_r($respuesta);

    }

    // close cURL resource, and free up system resources
    curl_close($ch);
    //return $respuestaf;

}

function auth($domainapi, $punto)
{

    global $ga_username;
    global $ga_password;

    //https://www.google.com/accounts/ClientLoginaccountType=GOOGLE&Email=derly249@gmail.com&service=analytics&source=My app&Passwd=dwn249//789
    //$gaUrl = "https://httpapi.com/api/domains/v5/suggest-names.json";
    //$gaUrl = "https://httpapi.com/api/domains/v5/suggest-names.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&keyword=".$domainapi."";
    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=" . $domainapi . "&tlds=" . $punto . "";


    //$authData = "auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&keyword=eventus";

    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $gaUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    /* metodos para post
        //curl_setopt($ch, CURLOPT_POST, 1);
         //curl_setopt($ch, CURLOPT_POSTFIELDS, $authData);
     */
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
    $fileHandle = fopen(dirname(__FILE__) . "/error.txt", "w+");

    curl_setopt($ch, CURLOPT_VERBOSE, true);

    curl_setopt($ch, CURLOPT_STDERR, $fileHandle);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    $response = curl_exec($ch);

    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        $objres = json_decode($response);
        //print_r($objres);
        //var_dump($objres);
        //echo "</br>";
        //print $objres->{''.$domainapi.'.'.$punto.''};
        $respuesta = $objres->{'' . $domainapi . '.' . $punto . ''}->{"status"};
        //echo $respuesta;
        if (strcmp($respuesta, "available") !== 0) {
            //echo "no hay este dominio";
            $respuestaf = 0;
        } else {
            $respuestaf = 1;
        }

        $tokens = explode("\n", trim($response));

        //print_r($tokens);


    }

    // close cURL resource, and free up system resources
    curl_close($ch);
    return $respuestaf;

}

function google()
{
    if (function_exists('curl_init')) // Comprobamos si hay soporte para cURL
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,
            "http://www.google.es/search?hl=es&q=''");
        //"https://httpapi.com/api/domains/v5/suggest-names.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&keyword=eventus");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $resultado = curl_exec($ch);

        //print_r($resultado);
        echo $resultado;
    } else
        echo "No hay soporte para cURL";
}

function domainapi_instala()
{

    global $wpdb;
    $table_name = $wpdb->prefix . "domainapi";
    $sql = "CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		extension tinytext NOT NULL ,
		type SMALLINT NOT NULL DEFAULT 0,
		PRIMARY KEY ( `id` )	
	) ;";

//    1 for all
//    2 for coloquials
//    3 for prefix

    $sql = "INSERT INTO $table_name VALUES (1,'com',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (1,'la',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (3,'io',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (4,'tech',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (5,'com',1);";
    $wpdb->query($sql);



}

function domainapi_desinstala()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "domainapi";
    $sql = "DROP TABLE $table_name";
    $wpdb->query($sql);
}

function domainapi_panel()
{
    include('template/panel.html');
    global $wpdb;
    global $dominio;
    global $palabra;
    $table_name = $wpdb->prefix . "domainapis";
    if (isset($_POST['domainapi_inserta'])) {
        $sql = "INSERT INTO $table_name (domainapi) VALUES ('{$_POST['domainapi_inserta']}');";
        $wpdb->query($sql);
    }
    if (isset($_POST['punto_inserta'])) {
        $dominio = $_POST['punto_inserta'];
        $palabra = $_POST['domainapi_inserta'];
    }
    //echo	 $dominio;
    //echo $palabra;
}

function ingresar_dominios()
{
    include('template/ing_dominios.html');
    global $wpdb;
    global $dominio;
    global $palabra;
    $table_name = $wpdb->prefix . "domainapis";
    if (isset($_POST['domainapi_inserta'])) {
        $sql = "INSERT INTO $table_name (domainapi) VALUES ('{$_POST['domainapi_inserta']}');";
        $wpdb->query($sql);
    }
    if (isset($_POST['punto_inserta'])) {
        $dominio = $_POST['punto_inserta'];
        $palabra = $_POST['domainapi_inserta'];
    }
    //echo	 $dominio;
    //echo $palabra;
}

function domainapi_add_menu()
{
    if (function_exists('add_options_page')) {
        //add_menu_page
        add_options_page('domainapi', 'domainapi', 8, basename(__FILE__), 'domainapi_panel');
        add_options_page('domainapi2', 'domainapi2', 9, basename(__FILE__), 'domainapi');
    }
}

function domainapi_add_ingresos()
{
    if (function_exists('add_options_page')) {
        add_options_page('ingresar dominios', 'ingresar dominios', 7, basename(__FILE__), 'ingresar_dominios');
    }
}

if (function_exists('add_action')) {
    add_action('admin_menu', 'domainapi_add_menu');
    //add_action('admin_menu', 'domainapi_add_ingresos');

    //add_menu_page( 'BufferCode plugin page', 'Menu plugin settings','manage_options', __FILE__,'buffercode_plugin',plugins_url( '/images/bf.png', __FILE__ ) );


}

function domainapiBK()
{
    global $wpdb;
    global $dominio;
    global $palabra;
    $table_name = $wpdb->prefix . "domainapis";
    //$domainapi= $wpdb->get_var("SELECT domainapi FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
    $domainapi = $wpdb->get_var("SELECT domainapi FROM $table_name ORDER BY $table_name.id desc , 1; ");
    $kjj = auth($palabra, $dominio);
    if ($kjj == 1) {
        echo $palabra . "." . $dominio . " available" . "</br>";
    } else {
        echo $palabra . "." . $dominio . " no available" . "</br>";
    }

    echo "</br>";
    $c_dominio = $wpdb->get_results("SELECT dominio FROM `wp_domains` where dominio = 'pe' or pais = 'todos' ORDER BY rand()");
    foreach ($c_dominio as $fila):
        $kjj = auth($palabra, $fila->dominio);
        if ($kjj == 1) {
            echo $palabra . "." . $fila->dominio . " available" . "</br>";
        } else {
            echo $palabra . "." . $fila->dominio . " no available" . "</br>";
        }
    endforeach;

    echo "</br>";
    $c_coloquiales = $wpdb->get_results("SELECT coloquial FROM `wp_coloquiales` where pais = 'peru' or pais = 'todos' order by rand() ");
    foreach ($c_coloquiales as $fila):
        $kjj = auth($fila->coloquial . $palabra, $dominio);
        if ($kjj == 1) {
            echo $fila->coloquial . $palabra . "." . $dominio . " available" . "</br>";
        } else {
            echo $fila->coloquial . $palabra . "." . $dominio . " no available" . "</br>";
        }
    endforeach;

    echo "</br>";
    $c_prefijo = $wpdb->get_results("SELECT prefijo FROM `wp_prefijo` where pais = 'pe' or pais = 'todos' ORDER BY RAND()");
    foreach ($c_prefijo as $fila):
        $kjj = auth($fila->prefijo . $palabra, $dominio);
        if ($kjj == 1) {
            echo $fila->prefijo . $palabra . "." . $dominio . " available" . "</br>";
        } else {
            echo $fila->prefijo . $palabra . "." . $dominio . " no available" . "</br>";
        }
    endforeach;

    //include('template/domainapi.html');
    //echo $palabra;
    //	$kjj = auth($palabra,$dominio);
    //	echo $kjj;


}

add_action('activate_domainapi/domainapi.php', 'domainapi_instala');
add_action('deactivate_domainapi/domainapi.php', 'domainapi_desinstala');


