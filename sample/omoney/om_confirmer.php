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

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {                                       
    $url = "https://".$_SERVER["HTTP_HOST"];
}else {
    $url = "http://".$_SERVER["HTTP_HOST"];
}


if(isset($_SESSION["id"]) && !empty($_SESSION["id"])){

    $order_id = $_SESSION["id"];
    $order    = new WC_Order($order_id);

    $montant  = $order->get_total();
    $token    = $_SESSION["token_pay"];


    $state_paiment = $OmHelper->checkTransactionStatus($id,$montant,$token);

    if (isset($state_paiment["status"]) && !empty($state_paiment["status"])) {
        
        if($Statut=="SUCCESS")
        {
            $order->update_status('completed','Paiement effectué avec succés par Orange Money => Commande OrangeMoneyID :'.$id.', TokenPaiement :'.$token.', Montant:'.$montant.'');

            $order->reduce_order_stock();
            $woocommerce->cart->empty_cart();

            $url = $order->get_checkout_order_received_url();

            Header("Location:".$url."");

        }else{

            $order->update_status('on-hold','Commande mis en attente de paiement. Paiement echoué par orange money => Commande OrangeMoneyID :'.$id.', TokenPaiement :'.$token.', Montant:'.$montant.'');
            Header("Location:".$url."/mon-compte");
        }

    }else{
        
        $order->update_status('on-hold','Commande mis en attente de paiement. Paiement echoué par orange money => Commande OrangeMoneyID :'.$id.', TokenPaiement :'.$token.', Montant:'.$montant.'');
        Header("Location:".$url."/mon-compte");

    }   


    unset($_SESSION["id"]);
    unset($_SESSION["url_payment"]);
    unset($_SESSION["token_pay"]);

}else{

    Header("Location:".$url);

}

?>