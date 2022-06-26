<?php

class WC_OMoney_Gateway extends WC_Payment_Gateway {
        
    public function __construct() {
        
        $this->id = 'omoney';
        $this->icon = plugins_url('logo/logo.png',__FILE__); 
        $this->has_fields = false;
        $this->method_title = 'Orange Money';
        $this->method_description = 'Orange money plugin to enable purchase by orange money.';
    
        $this->supports = array(
            'products'
        );
    
        $this->init_form_fields();
    
        $this->init_settings();
        $this->title = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->enabled = $this->get_option( 'enabled' );
    
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    public function init_form_fields(){
        $this->form_fields = array(
            'enabled' => array(
                'title'       => 'Enable/Disable',
                'label'       => 'Enable Orange Money payment',
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no'
            ),
            'title' => array(
                'title'       => 'Title',
                'type'        => 'text',
                'description' => 'Orange money payment',
                'default'     => 'Orange Money',
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => 'Description',
                'type'        => 'textarea',
                'description' => 'Orange money payment',
                'default'     => 'Payer avec votre numero orange money.',
            )
        );
    }

    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
            
        if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
            echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
        }
    }
    
    public function process_payment($id) {

        $order = new WC_Order($id);
        $order->update_status('on-hold', __( 'En Attente du paiement par Orange Money' ));

        return array(
            'result' => 'success',
            'redirect' => add_query_arg(array('id' => $id),plugins_url('request/om_paiement.php','../'.__FILE__))
        );

    }

}

?>
