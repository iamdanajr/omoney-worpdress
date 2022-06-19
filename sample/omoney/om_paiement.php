<?php
session_start();

define("WEB_FOLDER",dirname(__DIR__));
define("PLUGIN_FOLDER",WEB_FOLDER."/wp-content/plugins/omoney/");

require_once(WEB_FOLDER."/wp-load.php");
require_once(WEB_FOLDER."/wp-blog-header.php");

require_once(PLUGIN_FOLDER."/config/OmConfig.php");
require_once(PLUGIN_FOLDER."/classe/OmHelper.php");

$OmHelper = new OmHelper();

global $woocommerce;

function generate_text($minlength = 20, $maxlength = 20, $uselower = true, $useupper = true, $usenumbers = true, $usespecial = false) {
        
    $charset = '';

    if ($uselower) {
        $charset .= "abcdefghijklmnopqrstuvwxyz";
    }
    if ($useupper) {
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($usenumbers) {
        $charset .= "1234567890";
    }
    if ($usespecial) {
        $charset .= "~@#$%^*()_+-={}|][";
    }

    if ($minlength > $maxlength) {
        $length = mt_rand($maxlength, $minlength);
    } else {
        $length = mt_rand($minlength, $maxlength);
    }

    $key = '';

    for ($i = 0; $i < $length; $i++) {
        $key .= $charset[(mt_rand(0, mb_strlen($charset) - 1))];
    }
    
    return $key;
}

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {                                       
    $url = "https://".$_SERVER["HTTP_HOST"];
}else {
    $url = "http://".$_SERVER["HTTP_HOST"];
}

if(isset($_GET["order"]) && !empty($_GET["order"])){

    $order_id = $_GET["order"];
    
    $order = new WC_Order($order_id);

    $id = generate_text(5);
    $montant = $order->get_total();


    $confirm_url  = $url."/omoney/om_confirmer.php";
    $cancel_url   = $url."/mon-compte/orders/";

    $result_paiment = $OmHelper->webPayment($id, $montant, $confirm_url, $url, $cancel_url);

    if ($result_paiment["status"]==201) {

        $_SESSION["id"] = $id;
        $_SESSION["url_payment"] = $result_paiment["payment_url"];
        $_SESSION["token_pay"] = $result_paiment["pay_token"];

        Header("Location:".$_SESSION["url_payment"]."");

    }else{
        Header("Location:".$url);
    }

}else{
    Header("Location:".$url);
}

?>