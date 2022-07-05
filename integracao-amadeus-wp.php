<?php

/**
 *
 * @link              https://github.com/Talita1996/integracao-amadeus-wp
 * @since             1.0.0
 * @package           Integracao_Amadeus_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Integração entre Wordpress e Amadeus
 * Plugin URI:        https://github.com/Talita1996/integracao-amadeus-wp
 * Description:       Realiza a integração entre o Amadeus LMS e o Wordpress
 * Version:           1.0.0
 * Author:            Talita Mota
 * Author URI:        https://www.linkedin.com/in/talita-mota-942134188/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       integracao-amadeus-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'AWP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-awp-activator.php
 */
function activate_awp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-awp-activator.php';
	AWP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-awp-deactivator.php
 */
function deactivate_awp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-awp-deactivator.php';
	AWP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_awp' );
register_deactivation_hook( __FILE__, 'deactivate_awp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-awp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_awp() {

	$plugin = new AWP();
	$plugin->run();

}
run_awp();
