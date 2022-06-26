<?php

session_start();
global $woocommerce, $wpdb;

if(isset($_SESSION["id"]) && !empty($_SESSION["id"])){

    $order_id = $_SESSION["id"];
    $order    = new WC_Order($order_id);
    $omoneyhelper = OmoneyAdmin::get_omoney_manager();

    $montant  = $order->get_total();
    $token    = $_SESSION["token_pay"];

    $state_paiment = $omoneyhelper->checkTransactionStatus($id,$montant,$token);

    if (isset($state_paiment["status"]) && !empty($state_paiment["status"])) {
        
        if($state_paiment["status"]=="SUCCESS"){
            $order->payment_complete();
            $woocommerce->cart->empty_cart();
            $wpdb->update($wpdb->prefix . Omoney::$table_omney_transaction, array('id'=>$order_id,'state'=>'2'),array('id'=>$order_id));
            wp_redirect($order->get_checkout_order_received_url());
        }else{
            $wpdb->update($wpdb->prefix . Omoney::$table_omney_transaction, array('id'=>$order_id,'state'=>'3'),array('id'=>$order_id));
        }
    }

    unset($_SESSION["id"]);
    unset($_SESSION["url_payment"]);
    unset($_SESSION["token_pay"]);

    wp_redirect(wc_get_page_permalink('myaccount'));

}else{
    wp_redirect(get_home_url());
}

?>