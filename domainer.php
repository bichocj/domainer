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
    // $array_suggest = array('sd');
    // $table_name = $wpdb->prefix . "domainer";
    
    // $kjj = auth($keyword, $domain);
    // if ($kjj == 1) {
    //     echo $keyword . "." . $domain . " available" . "</br>";
    // } else {
    //     echo $keyword . "." . $domain . " no available" . "</br>";
    // }

    // $c_domain = $wpdb->get_results("SELECT extension FROM $table_name where type=1 ORDER BY rand()");

    // foreach ($c_domain as $row):
    //     echo $keyword . "." . $row->extension . "<br/>";
    //     array_push($array_suggest, $keyword . "." . $row->extension);
    // endforeach;
    // $c_coloquiales = $wpdb->get_results("SELECT extension FROM $table_name where type=2 order by rand() ");
    // foreach ($c_coloquiales as $row):
    //     echo $keyword . "." . $row->extension . "<br/>";
    //     array_push($array_suggest, $row->extension . $keyword . "." . $domain);
    // endforeach;

    // echo "</br><hr/>";
    // for ($i = 1; $i < count($array_suggest); $i++) {
    //     echo $array_suggest[$i] . "</br>";
    // }
    // request_domains($array_suggest);

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
}

function domainer_desinstala()
{
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

function format_domain($keyword, $extension='.com'){
    if(!stripos($keyword, '.')){
        $keyword .= $extension; 
    }
    return $keyword;
}

function search_domain($keyword, $extension='com')
{
    if(stripos($keyword, '.')){
        $tmp = $keyword;
        $i = stripos($keyword, '.');
        $keyword = substr($tmp, 0, $i);
        $extension = substr($tmp, strlen($keyword) + 1);
    }

    $gaUrl = "https://httpapi.com/api/domains/available.json?auth-userid=653362&api-key=lbWlFolOR1UVZUBF1AsJfAHfNL1Jlicc&domain-name=" . $keyword . "&tlds=" . $extension;

    $key = $extension;
    if($key[0]!='.'){
        $key = '.'.$extension;
    }

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
            $product = get_page_by_title( $key, OBJECT, 'product' );
            if($product){
                $_pf = new WC_Product_Factory();  
                $result = $_pf->get_product($product->ID);                
            }else{
                $result = false;
            }

        }

    }

    // close cURL resource, and free up system resources
    // curl_close($ch);
    // if($result){
    //     return $keyword . '.' . $extension;
    // }
    // return false;
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

function search_domain_extension($keyword, $extension='com')
{
    if(stripos($keyword, '.')){
        $tmp = $keyword;
        $i = stripos($keyword, '.');
        $keyword = substr($tmp, 0, $i);
        $extension = substr($tmp, strlen($keyword) + 1);
    }

    $fzpcr = new FZ_Product_Country_Restrictions();
    $params = '';

    $args = array( 'post_type' => 'product', 
        'posts_per_page' => 10, 'product_cat' => 'dominio-extension', 'orderby' => 'id' );
    $loop = new WP_Query( $args );
    $tmp_products = array();
    while ( $loop->have_posts() ) : $loop->the_post(); 
        global $product;             
        if(!$fzpcr->is_restricted($product)){
            $ext  = esc_attr($loop->post->post_title);
            if($ext[0] == '.'){
                $ext = substr($ext,1);
            }
            $params .= "&domain-name=" . $keyword. "&tlds=" . $ext;
            $key = $keyword.'.'.$ext;            
            $tmp_products[$key] = $product;
        }
    endwhile;
    wp_reset_query();
        
    $result = array();
    $tokens = get_domains_by_params($params);
    if($tokens){
    foreach ($tokens as $fila => $fila2):
        $status = $tokens->{$fila}->{'status'};
        $key = $tokens->{$fila}->{'classkey'};
        if($status=='available'){
            $result[$fila]=$tmp_products[$fila];
        }
    endforeach;    
    }
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

/*
function show_domain_result($keyword, $is_available){
    ?>
    <div class="hero-content text-center"><i class="fa fa-check available"></i><i class="fa fa-close not-available hidden"></i>
    <?php if($is_available): ?> 
              <h5 class="available text-primary">DOMINIO DISPONIBLE</h5>
    <?php else: ?>
              <h5 class="not-available text-primary">DOMINIO NO DISPONIBLE</h5>
    endif;?>
              <div class="encabezado"> 
                <h1><?php echo $keyword; ?></h1>
                <div class="wrapper-plan">                
                  <!--Precio -->
                  <div class="price"><span class="igv">IGV incluido*</span><span class="oferta">Oferta</span><span class="moneda">S/.</span><span class="precio">120.00</span>
                    <p class="price-before">Precio regular S/. <span class="tachado"> 140.00</span></p>
                  </div>
                  <div class="plazo">x 
                    <div class="time-limit dropdown"><a data-toggle="dropdown" class="dropdown-toggle"><span class="time">1 año  </span><span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">2 años</a></li>
                        <li><a href="#">3 años</a></li>
                        <li><a href="#">4 años</a></li>
                        <li><a href="#">5 años</a></li>
                      </ul>
                    </div>
                  </div>
                </div><a href="#" class="btn btn-primary"><i class="fa fa-shopping-cart"> </i> ADQUIRIR</a>
              </div>
              <form class="search-domain form-inline">
                <div class="form-group">
                  <input type="text" placeholder="dominio.mx" class="form-control">
                  <input type="submit" value="Buscar Dominio" class="form-control btn btn-primary">
                </div>
              </form>
            </div>
    <?php
*/


function insert_domain_search(){
    $keyword = $_GET['keyword'];
    $result = search_domain($keyword);        
    $_GET['keyword'] = format_domain($_GET['keyword']);
    if($result): $product = $result; $price = get_rounded_price($product->get_price()); ?>
        <i class="fa fa-check available domain-found"></i>
        <h5 class="available text-primary domain-found">DOMINIO DISPONIBLE</h5>
    <?php else: ?>    
        <i class="fa fa-close not-available domain-not-found"></i>
        <h5 class="not-available text-primary domain-not-found">DOMINIO NO DISPONIBLE</h5>
    <?php endif; ?>
            <div class="encabezado">
                <h1><?php echo $keyword; ?></h1>
                <?php if($product): ?>
                <div class="wrapper-plan domain-found">
                    <!--Precio -->
                    <div class="price">
                    <?php if(show_igv()): ?><span class="igv">IGV incluido*</span><?php endif; ?>
                    <?php if(has_offer($product)): ?><span class="oferta">Oferta</span><?php endif; ?>
                    <span class="moneda"><?php echo get_woocommerce_currency_symbol($currency); ?></span>
                    <span class="precio"><?php echo $price; ?></span>
                        <?php if(has_offer($product)): ?>
                        <p class="price-before">Precio regular <?php echo get_woocommerce_currency_symbol($currency); ?> 
                        <span class="tachado"><?php echo $product->get_regular_price(); ?></span>
                        </p>
                    <?php endif; ?>

                    </div>
                    <!--Dropdown de plazo-->
                    <div class="plazo">x
                        <div class="time-limit dropdown"><a data-toggle="dropdown" class="dropdown-toggle"><span class="time">1 año  </span><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">2 años</a></li>
                                <li><a href="#">3 años</a></li>
                                <li><a href="#">4 años</a></li>
                                <li><a href="#">5 años</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($product): ?>
                    <a href="#" class="btn btn-primary domain-found"><i class="fa fa-shopping-cart"> </i> ADQUIRIR</a>
                <?php endif; ?>
            </div>
            <form class="search-domain form-inline">
                <div class="form-group">
                    <input type="text" name="keyword" placeholder="dominio.mx" class="form-control">
                    <input type="submit" value="Buscar Dominio" class="form-control btn btn-primary">
                </div>
            </form>
<?php
}

function insert_domain_result_table(){   
    $result = search_domain_extension($_GET['keyword']);
    ?>
    <table class="table results-table">
            <tbody>
            <!--No disponible-->
            <!-- <tr class="not-available">
                <td class="domain-name">busquedapopcorn.com<i class="fa fa-close"></i></td>
                <td class="price">No disponible</td>
                <td class="plazo"><span class="null"></span></td>
                <td class="add"><a role="button" aria-disabled="true" class="btn-special btn-primary disabled">Agregar</a></td>
            </tr> -->
            <!--Disponible sin oferta-->
            <!-- <tr>
                <td class="domain-name">busqueda24.com<i class="fa fa-check"></i></td>
                <td class="price"><span class="igv">IGV incluido*</span><span class="moneda">S/.</span><span class="precio">120.00</span>
                </td>
                <td class="plazo">x
                    <div class="time-limit dropdown"><a data-toggle="dropdown" class="dropdown-toggle"><span class="time">1 año  </span><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">2 años</a></li>
                            <li><a href="#">3 años</a></li>
                            <li><a href="#">4 años</a></li>
                            <li><a href="#">5 años</a></li>
                        </ul>
                    </div>
                </td>
                <td class="add"><a href="#" class="btn-special btn-default">Agregar</a></td>
            </tr> -->
            <!--Disponible con Oferta-->
            <?php foreach ($result as $key => $product): $price = get_rounded_price($product->get_price()); ?>
            <tr>
                <td class="domain-name"><?php echo $key;?><i class="fa fa-check"></i>
                </td>
                <td class="price">
                <?php if(show_igv()): ?><span class="igv">IGV incluido*</span><?php endif; ?>
                <?php if(has_offer($product)): ?><span class="oferta">Oferta</span><?php endif; ?>
                <span class="moneda"><?php echo get_woocommerce_currency_symbol($currency); ?></span><span class="precio"><?php echo $price; ?></span>
                    <?php if(has_offer($product)): ?>
                        <p class="price-before">Precio regular <?php echo get_woocommerce_currency_symbol($currency); ?> 
                        <span class="tachado"><?php echo $product->get_regular_price(); ?></span>
                        </p>
                    <?php endif; ?>
                </td>
                <td class="plazo">x
                    <div class="time-limit dropdown"><a data-toggle="dropdown" class="dropdown-toggle"><span class="time">1 año  </span><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">2 años</a></li>
                            <li><a href="#">3 años</a></li>
                            <li><a href="#">4 años</a></li>
                            <li><a href="#">5 años</a></li>
                        </ul>
                    </div>
                </td>
                <td class="add"><a href="#" class="btn-special btn-default">Agregar</a></td>
            </tr>
            <?php endforeach; ?>
            <!--agregado para comprar-->
            <!-- <tr>
                <td class="domain-name">

                    busqueda.me<i class="fa fa-check"></i>
                </td>
                <td class="price"><span class="igv">IGV incluido*</span><span class="oferta">Oferta</span><span class="moneda">S/.</span><span class="precio">120.00</span>
                    <p class="price-before">Precio regular S/. <span class="tachado"> 140.00</span></p>
                </td>
                <td class="plazo">x
                    <div class="time-limit dropdown"><a data-toggle="dropdown" class="dropdown-toggle"><span class="time">1 año  </span><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">2 años</a></li>
                            <li><a href="#">3 años</a></li>
                            <li><a href="#">4 años</a></li>
                            <li><a href="#">5 años</a></li>
                        </ul>
                    </div>
                </td>
                <td class="add"><a href="#" class="btn-special btn-primary"><i class="fa fa-check"></i>Agregado</a></td>
            </tr> -->
            <!--para botón de ver más-->
            <tr class="more">
                <td colspan="4"><a href="#" class="btn-special btn-primary">Ver más</a></td>
            </tr>
            </tbody>
            <!--Botón comprar-->
            <tfoot>
            <tr>
                <td colspan="4"> <a href="#" class="btn btn-primary">Continuar compra</a></td>
            </tr>
            </tfoot>
        </table>
<?php
}