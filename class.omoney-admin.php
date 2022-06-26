<?php

class OmoneyAdmin {

	const OMONEY_NONCE = 'omoney-config-nonce';

	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'update-omoney-config' ) {
			self::update_config();
		}
	}

	public static function init_hooks() {
		
		self::$initiated = true;
		add_action( 'admin_init', array( 'OmoneyAdmin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'OmoneyAdmin', 'admin_menu' ), 5 );

		wp_register_style( 'assets/css/bootstrap.min.css', plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap.min.css' );
		wp_enqueue_style( 'assets/css/bootstrap.min.css');

		wp_register_script( 'assets/js/bootstrap.min.js', plugin_dir_url( __FILE__ ) . 'assets/js/bootstrap.min.js');
		wp_enqueue_script( 'assets/js/bootstrap.min.js' );
	}

	public static function admin_init() {
		if ( get_option( 'omoney_activated' ) ) {
			
			delete_option( 'omoney_activated' );

			if ( ! headers_sent() ) {
				wp_redirect( add_query_arg( array( 'page' => 'omoney-configuration',), admin_url( 'admin.php' ) ) );
			}
		}
	}

	public static function admin_menu() {
		self::load_menu();
	}

	public static function admin_head() {
		if ( !current_user_can( 'manage_options' ) )
			return;
	}

	public static function load_menu() {
		add_menu_page( 'Orange money', 'Orange money', 'manage_options', 'omoney-configuration' , array( 'OmoneyAdmin', 'display_page' ),'dashicons-money-alt');
		add_submenu_page('omoney-configuration','Orange money - Configuration','Configuration','manage_options','omoney-configuration',array( 'OmoneyAdmin', 'display_page' ));
	}

	public static function update_config() {

		if ( ! current_user_can( 'manage_options' ) ) {
			die('You\'re not authorised to access to this page.');
		}

		if ( !wp_verify_nonce( $_POST['_wpnonce'], self::OMONEY_NONCE ) )
			return false;

		foreach( array( 'omoney_reference', 'omoney_prefix', 'omoney_merchant', 'omoney_secret', 'omoney_environnemnt' ) as $option ) {
			update_option( $option, isset( $_POST[$option] ) ? sanitize_text_field($_POST[$option]) : '' );
		}

		$_SESSION['omoney-config'] = true;

		return true;
	}
	
	public static function display_page() {

		/* if ( isset( $_GET['page'] ) && $_GET['page'] == 'omoney-transaction' )
			self::display_transaction_page();
		elseif ( isset( $_GET['page'] ) && $_GET['page'] == 'omoney-configuration' )
			self::display_configuration_page();
		else
			self::display_dashboard_page(); */

		self::display_configuration_page();
	}

	public static function display_dashboard_page() {
		Omoney::view( 'dashboard');
	}

	public static function display_transaction_page() {
		Omoney::view( 'transaction' );
	}

	public static function display_configuration_page() {
		
		$omoney_reference = get_option( 'omoney_reference', '');
		if ( $omoney_reference == '' ) {
			add_option( 'omoney_reference', '' );
		}

		$omoney_prefix = get_option( 'omoney_prefix', '');
		if ( $omoney_prefix == '' ) {
			add_option( 'omoney_prefix', '' );
		}

		$omoney_merchant = get_option( 'omoney_merchant', '');
		if ( $omoney_merchant == '' ) {
			add_option( 'omoney_merchant', '' );
		}

		$omoney_secret = get_option( 'omoney_secret', '');
		if ( $omoney_secret == '' ) {
			add_option( 'omoney_secret', '' );
		}

		$omoney_environnemnt = get_option( 'omoney_environnemnt', '');
		if ( $omoney_environnemnt == '' ) {
			add_option( 'omoney_environnemnt', '' );
		}
		
		Omoney::view( 'configuration', compact('omoney_reference', 'omoney_prefix', 'omoney_merchant', 'omoney_secret','omoney_environnemnt') );
	}

	public static function get_page_url( $page = 'omoney-dashboard' ) {

		$args = array( 'page' => 'omoney-configuration');

		/* if ( $page == 'transaction' )
			$args = array( 'page' => 'omoney-config', 'view' => 'transaction');
		elseif ( $page == 'configuration' )
			$args = array( 'page' => 'omoney-key-config', 'view' => 'configuration');
		elseif ( $page == 'reset_nonce' )
			$args = array( 'page' => 'omoney-key-config', 'view' => 'start', 'action' => 'reset-nonce', '_wpnonce' => wp_create_nonce( self::OMONEY_NONCE ) ); */

		$url = add_query_arg( $args, admin_url( 'options-general.php' ) );
		return $url;
	}

	public static function get_logo() {
		return plugins_url('assets/img/logo.png',__FILE__);
	}

	public static function get_omoney_manager(){

		if(empty(get_option( 'omoney_environnemnt')) &&
		   empty(get_option( 'omoney_reference')) &&
		   empty(get_option( 'omoney_merchant')) &&
		   empty(get_option( 'omoney_secret'))){

			wp_die('Sorry, but this orange money payment is not entirely configured yet <br><a href="' . self::get_page_url() . '">&laquo; Click here to configure.</a>');
			
		}

		return new OmoneyManager(get_option( 'omoney_environnemnt',0),
								 get_option( 'omoney_reference'),
								 get_option( 'omoney_merchant'),
								 get_option( 'omoney_secret'),
								 get_option( 'omoney_prefix','OM'));
	}

}
