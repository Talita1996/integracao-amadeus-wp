<?php

/**
 * Ajax execution.
 *
 * @link       https://github.com/Talita1996/integracao-amadeus-wp
 * @since      1.0.0
 *
 * @package    AWP
 * @subpackage AWP/admin
 */

/**
 *
 * @package    AWP
 * @subpackage AWP/admin
 * @author     Talita Mota <talita_mota@outlook.com>
 */
if ( !class_exists( 'AWP_Ajax' ) ) {
	class AWP_Ajax {
        
        public static function add_new_courses()
        {
            $selected_courses_slug = $_POST['selected_courses'];
            $unsynchronized_courses = $_POST['unsynchronized_courses'];
            $res = 0;

            foreach($unsynchronized_courses as $course_info) {

                if ( !in_array($course_info['slug'], $selected_courses_slug ) ) {
                    continue;
                }

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
				$product->set_price($course_info['price']);
				$product->set_regular_price($course_info['price']);
				$product->set_sold_individually( true );
				//$product->set_image_id( $image_id );
				$product->set_downloadable( false );
				$product->set_virtual( true );
				$product->set_description( $course_info['description_brief'] );
				$saved = $product->save(); 

                if( $saved = $product->get_id() ) {
                    $res++;
                }
			}

            if( $res = sizeof($selected_courses_slug) ){
                exit(json_encode('Todos os cursos foram importados com sucesso'));
            } else {
                exit(json_encode('Houve erro na importação de ao menos um curso. Por favor, recarregue a página e tente importar os cursos novamente.'));
            }

            
        }
    }
}