<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Talita1996/integracao-amadeus-wp
 * @since      1.0.0
 *
 * @package    AWP
 * @subpackage AWP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AWP
 * @subpackage AWP/admin
 * @author     Talita Mota <talita_mota@outlook.com>
 */
if ( !class_exists( 'AWP_Admin' ) ) {
	class AWP_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $awp    The ID of this plugin.
		 */
		private $awp;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $awp       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct( $awp, $version ) {

			$this->awp = $awp;
			$this->version = $version;

		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in AWP_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The AWP_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_style( $this->awp, plugin_dir_url( __FILE__ ) . 'css/awp-admin.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in AWP_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The AWP_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			
			
		}

		public function options_page() {
			add_menu_page(
				'AWP',
				'AWP Options',
				'manage_options',
				'awp',
				array( $this,'awp_options_page_html'),
				'dashicons-book',
				10	
			);
		}

		public function awp_options_page_html() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			_e('<h1>Teste</h1>', 'integracao-amadeus-wp');
			_e('<p>É de extrema importância que o slug dos produtos não sejam alterados de modo a evitar duplicações.</p>', 'integracao-amadeus-wp');

			$new_courses_api_response = AWP_Api_Caller::output('GET', 'https://amadeuslms.cf/api/subjects/list_subjects/');
			$new_courses_array = $new_courses_api_response['data']['subjects'];

			$unsynchronized_courses = array();
			foreach($new_courses_array as $course_info) {
				$args = array(
					'name'        => $course_info['slug'],
					'post_type'   => 'product',
					'post_status' => array('publish', 'future', 'draft', 'pending', 'private', 'trash', 'auto-draft'),
					'numberposts' => 1
				  );
				$course_exists = get_posts($args);

				if( empty($course_exists) ) {
					array_push($unsynchronized_courses, $course_info);
				}
			}

			wp_register_script( $this->awp, plugin_dir_url( __FILE__ ) . 'js/awp-admin-ajax.js');
			wp_enqueue_script( $this->awp, plugin_dir_url( __FILE__ ) . 'js/awp-admin-ajax.js', array( 'jquery' ), null, false );
			wp_localize_script($this->awp, 'ajax_data', 
				array( 
					'url' => admin_url('admin-ajax.php'),
					'unsynchronized_courses' => $unsynchronized_courses
				)
			);


			if($unsynchronized_courses != 0) {

				_e(sprintf('<p>Existem %d novos cursos que não foram sincronizados. Selecione abaixo os cursos que devem ser importados:</p>', sizeof($unsynchronized_courses)), 'integracao-amadeus-wp');
				
				$html = '<form id="courses" action="add_new_courses">';
				foreach ( $unsynchronized_courses as $course ) {
					$html .= '<input type="checkbox" id="' . $course['slug'] . '" name="' . $course['slug'] . '" value="' . $course['slug'] . '"><label for="' . $course['slug'] . '">' . $course['name'] . '</label><br>';//printf('<input type="checkbox" id="%s" name="%s" value="%s"><label for="%s">%s</label><br>', $course['slug'], $course['slug'], $course['slug'], $course['slug'], $course['name']);
				}
				$html .= '<input type="submit" value="Importar Cursos" class="button"></form><div id="ajax_response"></div>';

				echo $html;
			
			} else {

				_e('<p>Não há novos cursos no Amadeus</p>', 'integracao-amadeus-wp');

			}
/*
           
			foreach($new_courses_array as $course_info) {

				$args = array(
					'name'        => $course_info['slug'],
					'post_type'   => 'product',
					'post_status' => array('publish', 'future', 'draft', 'pending', 'private', 'trash', 'auto-draft'),
					'numberposts' => 1
				  );
				$course_exists = get_posts($args);
				
				if( !empty($course_exists) ) {
					continue;
				}

				$product = new WC_Product_Simple();
				$product->set_name( $course_info['name'] );
				$product->set_slug( $course_info['slug'] );
				$product->set_status( 'publish' ); 
				$product->set_catalog_visibility( 'visible' );
				$product->$course_info['price'];
				$product->$course_info['price'];
				$product->set_sold_individually( true );
				//$product->set_image_id( $image_id );
				$product->set_downloadable( false );
				$product->set_virtual( true );
				$product->set_description( $course_info['description_brief'] );
				$product->save(); 
			}*/
			if ( isset( $_GET['settings-updated'] ) ) {
				// add settings saved message with the class of "updated"
				add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
			}
		 
			// show error/update messages
			settings_errors( 'wporg_messages' );

		}
	}
}
