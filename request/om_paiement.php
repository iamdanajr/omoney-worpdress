<?php
session_start();
global $woocommerce, $wpdb;

if(isset($_GET["id"]) && !empty($_GET["id"])){

    $order_id     = $_GET["id"];
    $order        = new WC_Order($order_id);
    $montant      = $order->get_total();
    $omoneyhelper = OmoneyAdmin::get_omoney_manager();

    $omoney_transaction = array('id_transaction' => $order_id,'montant'=>$montant,'date_created' => time());
    $wpdb->insert($wpdb->prefix . Omoney::$table_omney_transaction, $omoney_transaction);
    $id = $wpdb->insert_id; 
    
    $confirm_url  = plugins_url('request/om_confirmer.php','../'.__FILE__);
    $cancel_url   = wc_get_page_permalink('checkout');;

    $result_paiment = $omoneyhelper->webPayment($id, $montant, $confirm_url, $url, $cancel_url);

    if ($result_paiment["status"]==201) {

        $_SESSION["id"] = $id;
        $_SESSION["url_payment"] = $result_paiment["payment_url"];
        $_SESSION["token_pay"] = $result_paiment["pay_token"];

        $wpdb->update($wpdb->prefix . Omoney::$table_omney_transaction, array('id'=>$id,'reference'=>$result_paiment["pay_token"]),array('id'=>$id));
        wp_redirect($_SESSION["url_payment"]);

    }

    wp_redirect(get_home_url());

}else{
    wp_redirect(get_home_url());
}

?>