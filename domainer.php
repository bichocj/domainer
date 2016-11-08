<?php
/**
 * Plugin Name: domainer
 * Plugin URI: http://inyaka.net/blog
 * Description: Sencillo plugin para fines didacticos que imprime un domainer a elecci&oacute;n.
 * Version: 0.1
 * Author: Inyaka
 * Author URI: http://inyaka.net/blog/
 **/
function domainer()
{
    global $wpdb;
    global $domain;
    global $keyword;
    $array_suggest = array('sd');
    $table_name = $wpdb->prefix . "domainer";
    //$domainer= $wpdb->get_var("SELECT domainer FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
//    $domainer = $wpdb->get_var("SELECT domainer FROM $table_name ORDER BY $table_name.id desc , 1; ");
    $kjj = auth($keyword, $domain);
    if ($kjj == 1) {
        echo $keyword . "." . $domain . " available" . "</br>";
    } else {
        echo $keyword . "." . $domain . " no available" . "</br>";
    }

    $c_domain = $wpdb->get_results("SELECT extension FROM $table_name where type=1 ORDER BY rand()");

    foreach ($c_domain as $row):
        echo $keyword . "." . $row->extension . "<br/>";
        array_push($array_suggest, $keyword . "." . $row->extension);
    endforeach;
    $c_coloquiales = $wpdb->get_results("SELECT extension FROM $table_name where type=2 order by rand() ");
    foreach ($c_coloquiales as $row):
        echo $keyword . "." . $row->extension . "<br/>";
        array_push($array_suggest, $row->extension . $keyword . "." . $domain);
    endforeach;

    echo "</br><hr/>";
    for ($i = 1; $i < count($array_suggest); $i++) {
        echo $array_suggest[$i] . "</br>";
    }
    request_domains($array_suggest);

}

function request_domains($array_sugest)
{
    $params = '';
    for ($i = 1; $i < count($array_sugest); $i++) {
        $nueva = explode(".", $array_sugest[$i]);
        $params .= "&domain-name=" . $nueva[0];
        $params .= "&tlds=" . $nueva[1];

    }

    $tokens = get_domains_by_params($params);
    foreach ($tokens as $fila => $fila2):
        $respuesta = $tokens->{$fila}->{'status'};
        $llave = $tokens->{$fila}->{'classkey'};
        //echo $fila;
        echo $fila . " estado: " . $respuesta . " llave:" . $llave;
        echo "</br>";
    endforeach;
//
//        $tokens = explode("\n", trim($response));
//
//        $ubicacion = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=200.37.124.213'));
//        echo "ip :" . $ubicacion['geoplugin_request'];
//        echo "pais :" . $ubicacion['geoplugin_countryName'];


//    curl_close($ch);
}

/**
 * @param $params
 * @return array|mixed|null|object
 */
function get_domains_by_params($params)
{
    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc" . $params;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gaUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
    $fileHandle = fopen(dirname(__FILE__) . "/error.txt", "w+");

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, $fileHandle);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
        $tokens = null;
    } else {
        $tokens = json_decode($response);
    }
    return $tokens;
}

function auth($domainer, $punto)
{

    global $ga_username;
    global $ga_password;

    //https://www.google.com/accounts/ClientLoginaccountType=GOOGLE&Email=derly249@gmail.com&service=analytics&source=My app&Passwd=dwn249//789
    //$gaUrl = "https://httpapi.com/api/domains/v5/suggest-names.json";
    //$gaUrl = "https://httpapi.com/api/domains/v5/suggest-names.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&keyword=".$domainer."";
    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=" . $domainer . "&tlds=" . $punto . "";


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
        //print $objres->{''.$domainer.'.'.$punto.''};
        $respuesta = $objres->{'' . $domainer . '.' . $punto . ''}->{"status"};
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

function domainer_instala()
{

    global $wpdb;
    $table_name = $wpdb->prefix . "domainer";
    $sql = "CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		extension tinytext NOT NULL ,
		type SMALLINT NOT NULL DEFAULT 0,
		PRIMARY KEY ( `id` )	
	);";

//    1 for all
//    2 for coloquials
//    3 for prefix
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (1,'com',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (2,'la',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (3,'io',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (4,'tech',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (5,'com',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (6,'pe',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (7,'net',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (8,'org',1);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (9,'super',2;";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (10,'hiper',2);";
    $wpdb->query($sql);

    $sql = "INSERT INTO $table_name VALUES (11,'last',2);";
    $wpdb->query($sql);

}

function domainer_desinstala()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "domainer";
    $sql = "DROP TABLE $table_name";
    $wpdb->query($sql);
}

function domainer_panel()
{
    include('template/panel.html');
    global $wpdb;
    global $domain;
    global $keyword;
//    $table_name = $wpdb->prefix . "domainers";
//    if (isset($_POST['domainer_inserta'])) {
//        $sql = "INSERT INTO $table_name (domainer) VALUES ('{$_POST['domainer_inserta']}');";
//        $wpdb->query($sql);
//    }
    if (isset($_POST['extension'])) {
        $domain = $_POST['extension'];
        $keyword = $_POST['keyword'];
    }
    //echo	 $domain;
    //echo $keyword;
}

function ingresar_dominios()
{
    include('template/ing_dominios.html');
    global $wpdb;
    global $domain;
    global $keyword;
    $table_name = $wpdb->prefix . "domainers";
    if (isset($_POST['domainer_inserta'])) {
        $sql = "INSERT INTO $table_name (domainer) VALUES ('{$_POST['domainer_inserta']}');";
        $wpdb->query($sql);
    }
    if (isset($_POST['punto_inserta'])) {
        $domain = $_POST['punto_inserta'];
        $keyword = $_POST['domainer_inserta'];
    }
    //echo	 $domain;
    //echo $keyword;
}

function domainer_add_menu()
{
    if (function_exists('add_options_page')) {
        //add_menu_page
        add_options_page('domainer', 'domainer', 8, basename(__FILE__), 'domainer_panel');
        add_options_page('domainer2', 'domainer2', 9, basename(__FILE__), 'domainer');
    }
}

function domainer_add_ingresos()
{
    if (function_exists('add_options_page')) {
        add_options_page('ingresar dominios', 'ingresar dominios', 7, basename(__FILE__), 'ingresar_dominios');
    }
}

if (function_exists('add_action')) {
    add_action('admin_menu', 'domainer_add_menu');
}


function search_domain($keyword, $extension)
{
    $tmp = $keyword;
    $i = stripos($keyword, '.');
    $keyword = substr($tmp, 0, $i);
    $extension = substr($tmp, strlen($keyword) + 1);

    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=" . $keyword . "&tlds=" . $extension;
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $gaUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
    $fileHandle = fopen(dirname(__FILE__) . "/error.txt", "w+");

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, $fileHandle);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    $response = curl_exec($ch);
    $result = false;

    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        $response_json = json_decode($response);
        $response_status = $response_json->{'' . $keyword . '.' . $extension . ''}->{"status"};
        //echo $response_status;
        if (strcmp($response_status, "available") == 0) {
            $result = true;
        }

    }

    // close cURL resource, and free up system resources
    curl_close($ch);
    return $result;
}

function search_domain_prefix($keyword, $extension)
{
    global $wpdb;
    $array_suggest = array('sd');
    $table_name = $wpdb->prefix . "domainer";

    $tmp = $keyword;
    $i = stripos($keyword, '.');
    $keyword = substr($tmp, 0, $i);
    $extension = substr($tmp, strlen($keyword) + 1);

    $c_prefix = $wpdb->get_results("SELECT extension FROM $table_name where type=3 ORDER BY RAND()");
    foreach ($c_prefix as $row):
        echo $keyword . "." . $row->extension . "<br/>";
        array_push($array_suggest, $row->extension . $keyword . "." . $array_suggest);
    endforeach;

    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=" . $keyword . "&tlds=" . $extension;
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $gaUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
    $fileHandle = fopen(dirname(__FILE__) . "/error.txt", "w+");

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, $fileHandle);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    $response = curl_exec($ch);
    $result = false;

    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        $response_json = json_decode($response);
        $response_status = $response_json->{'' . $keyword . '.' . $extension . ''}->{"status"};
        //echo $response_status;
        if (strcmp($response_status, "available") == 0) {
            $result = true;
        }

    }

    // close cURL resource, and free up system resources
    curl_close($ch);
    return $result;
}

function search_domain_extension($keyword, $extension)
{
    $tmp = $keyword;
    $i = stripos($keyword, '.');
    $keyword = substr($tmp, 0, $i);
    $extension = substr($tmp, strlen($keyword) + 1);

    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=" . $keyword . "&tlds=" . $extension;
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $gaUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
    $fileHandle = fopen(dirname(__FILE__) . "/error.txt", "w+");

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, $fileHandle);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    $response = curl_exec($ch);
    $result = false;

    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        $response_json = json_decode($response);
        $response_status = $response_json->{'' . $keyword . '.' . $extension . ''}->{"status"};
        //echo $response_status;
        if (strcmp($response_status, "available") == 0) {
            $result = true;
        }

    }

    // close cURL resource, and free up system resources
    curl_close($ch);
    return $result;
}

function search_domain_others($keyword, $extension)
{
    $tmp = $keyword;
    $i = stripos($keyword, '.');
    $keyword = substr($tmp, 0, $i);
    $extension = substr($tmp, strlen($keyword) + 1);

    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=" . $keyword . "&tlds=" . $extension;
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $gaUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
    $fileHandle = fopen(dirname(__FILE__) . "/error.txt", "w+");

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, $fileHandle);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    $response = curl_exec($ch);
    $result = false;

    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        $response_json = json_decode($response);
        $response_status = $response_json->{'' . $keyword . '.' . $extension . ''}->{"status"};
        //echo $response_status;
        if (strcmp($response_status, "available") == 0) {
            $result = true;
        }

    }

    // close cURL resource, and free up system resources
    curl_close($ch);
    return $result;
}


add_action('activate_domainer/domainer.php', 'domainer_instala');
add_action('deactivate_domainer/domainer.php', 'domainer_desinstala');


