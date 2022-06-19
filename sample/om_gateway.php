<?php
 /**
 * Plugin Name: Woocommerce Orange Money
 * Plugin URI: http://famibmali.com
 * Description: Autoriser le paiement des produits par le service orange money.
 * Author: DEvFamib
 * Author URI: http://www.famibmali.com/
 * Version: 1.0.1
 */


 /*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */

    function orangemoney_add_gateway_class( $gateways ) {
        $gateways[] = 'WC_OrangeMoney_Gateway'; // your class name is here
        return $gateways;
    }
    
    add_filter( 'woocommerce_payment_gateways', 'orangemoney_add_gateway_class' );
 
/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */

    add_action( 'plugins_loaded', 'orangemoney_init_gateway_class' );

    function orangemoney_init_gateway_class() {
    
        class WC_OrangeMoney_Gateway extends WC_Payment_Gateway {
    
            /**
             * Class constructor, more about it in Step 3
            */
            public function __construct() {
                
                $this->id = 'orangemoney'; // payment gateway plugin ID
                $this->icon = ''; // URL of the icon that will be displayed on checkout page near your gateway name
                $this->has_fields = false; // in case you need a custom credit card form
                $this->method_title = 'Orange Money';
                $this->method_description = 'Autoriser le paiement des produits par le service orange money'; // will be displayed on the options page
            
                // gateways can support subscriptions, refunds, saved payment methods,
                // but in this tutorial we begin with simple payments
                
                $this->supports = array(
                    'products'
                );
            
                // Method with all the options fields
                $this->init_form_fields();
            
                // Load the settings.
                $this->init_settings();
                $this->title = $this->get_option( 'title' );
                $this->description = $this->get_option( 'description' );
                $this->enabled = $this->get_option( 'enabled' );
            
                // This action hook saves the settings
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            }
    
            /**
             * Plugin options, we deal with it in Step 3 too
            */
            public function init_form_fields(){
                $this->form_fields = array(
                    'enabled' => array(
                        'title'       => 'Enable/Disable',
                        'label'       => 'Enable Orange Money Gateway',
                        'type'        => 'checkbox',
                        'description' => '',
                        'default'     => 'no'
                    ),
                    'title' => array(
                        'title'       => 'Title',
                        'type'        => 'text',
                        'description' => 'This controls the title which the user sees during checkout.',
                        'default'     => 'Orange Money',
                        'desc_tip'    => true,
                    ),
                    'description' => array(
                        'title'       => 'Description',
                        'type'        => 'textarea',
                        'description' => 'This controls the description which the user sees during checkout.',
                        'default'     => 'Payer avec votre numero orange money.',
                    )
                );
            }
                
            /**
             * Add content to the WC emails.
             *
             * @access public
             * @param WC_Order $order
             * @param bool $sent_to_admin
             * @param bool $plain_text
             */
            public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
                    
                if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
                    echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
                }
            }
            
            public function process_payment( $order_id ) {
                
                $order = new WC_Order( $order_id );
                $order->update_status('on-hold', __( 'En Attente du paiement par Orange Money' ));

                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {                                       
                    $url = "https://".$_SERVER["HTTP_HOST"];
                }else {
                    $url = "http://".$_SERVER["HTTP_HOST"];
                }
                
                return array(
                    'result' => 'success',
                    'redirect' => $url.'/wp-content/plugins/omoney/om_paiement.php?order='.$order_id.''
                );

            }
    
        }
    }

?>
