<?php

class Omoney {

	private static $initiated = false;

	public static function init() {
		if (!self::$initiated) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
		self::$initiated = true;
	}

	public static function init_database_table(){
		
		global $wpdb;

		$table_name      = $wpdb->prefix . 'omoney_transaction';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_transaction` int(11) NOT NULL,
			`montant` text NOT NULL,
			`reference` text,
			`state` int(11) DEFAULT '1',
			`date_created` varchar(20) NOT NULL,
			PRIMARY KEY  (id)
		)$charset_collate;";


		$wpdb->query($sql);


	}
	
	public static function plugin_activation() {

		if ( ! empty( $_SERVER['SCRIPT_NAME'] ) && false !== strpos( $_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php' ) ) {

			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && is_admin() ) {
				wp_die('Sorry, but this plugin requires the Woocommerce Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
			}

			add_option( 'omoney_activated', true );
			add_option( 'omoney_version', OMONEY_VERSION);
			Omoney::init_database_table();
		}

	}

	public static function plugin_deactivation( ) {
	}

	public static function view( $name, array $args = array() ) {
		$args = apply_filters( 'omoney_view_arguments', $args, $name );
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}

		$file = OMONEY_PLUGIN_DIR . 'views/'. $name . '.php';

		include( $file );
	}

}
