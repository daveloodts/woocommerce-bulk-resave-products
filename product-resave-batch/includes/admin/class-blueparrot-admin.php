<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @since 1.0.0
 */

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class BlueParrot_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'blueparrot_register_menu') );

		// Action to process Ajax for resave product
		add_action( 'wp_ajax_blueparrot_product_resave_action', array( $this, 'blueparrot_product_resave_action' ) );
	}

	/**
	 * Function to add menu
	 * 
	 * @since 1.0.0
	 */
	function blueparrot_register_menu() {

		add_menu_page( __( 'Product Resave in Batches', 'product-resave-in-batches' ), __( 'Product Save', 'product-resave-in-batches' ), 'manage_options', 'product-resave-batch', array( $this, 'blueparrot_product_resave_html' ) );
	}

	/**
	 * Function to add HTML of product resave
	 * 
	 * @since 1.0.0
	 */
	function blueparrot_product_resave_html() {

		include_once( BLUEPARROT_DIR . '/includes/admin/product-resave.php' );
	}

	/**
	 * Function to resave the product process
	 * 
	 * @since 1.0.0
	 */
	function blueparrot_product_resave_action() {

		global $wpdb;

		// Taking form data
		parse_str( $_POST['form_data'], $form_data );

		// Taking some variable
		$results = array(
							'status' 	=> 0,
							'message'	=> esc_html__('Sorry, Something happened wrong.', 'product-resave-in-batches')
						);
		$nonce			= isset( $form_data['nonce'] )			? blueparrot_clean( $form_data['nonce'] )				: '';
		$redirect_url	= isset( $form_data['redirect_url'] )	? blueparrot_clean_url( $form_data['redirect_url'] )	: '';
		$page 			= isset( $_POST['page'] )				? $_POST['page']								: 1;
		$total_count 	= isset( $_POST['total_count'] )		? $_POST['total_count']							: 0;
		$data_process 	= isset( $_POST['data_process'] )		? $_POST['data_process']						: 0;

		if( empty( $_POST['form_data'] ) || empty( $redirect_url ) || ! wp_verify_nonce( $nonce, "blueparrot-product-resave" ) ) {
			wp_send_json( $results );
		}

		// Gethering all data
		$form_data					= (array) $form_data;
		$form_data['limit']			= 30;
		$form_data['page']			= $page;
		$form_data['total_count']	= $total_count;
		$form_data['data_process']	= $data_process;

		// Get all products
		$results = $this->blueparrot_get_product_data( $form_data );

		wp_send_json( $results );
	}

	/**
	 * Function to export form entries
	 * 
	 * @since 2.0
	 */
	function blueparrot_get_product_data( $args = array() ) {

		global $wpdb;

		// Taking some variables
		$result			= array(
							'status'			=> 0,
							'result_message'	=> '',
							'message'			=> esc_html__( 'Sorry, No data found for export parameters.', 'product-resave-in-batches' )
						);

		$limit			= $args['limit'];
		$page			= ! empty( $args['page'] )			? $args['page']			: 1;
		$data_process	= isset( $args['data_process'] )	? $args['data_process']	: 0;
		$total_count	= isset( $args['total_count'] )		? $args['total_count']	: 0;

		if( $page == 1 ) {
			
			// Getting data
			$get_total_product	= "SELECT COUNT(ID) FROM `{$wpdb->prefix}posts` WHERE 1=1 AND `post_type` = 'product' AND `post_status` IN('draft', 'pending', 'private', 'publish')";
			$total_count		= $wpdb->get_var( $get_total_product );
		}

		// Get products
		$product_arr = wc_get_products(array(
			'status'	=> array( 'draft', 'pending', 'private', 'publish' ),
			'limit'		=> $limit,
			'page'		=> $page,
		));

		// If data found
		if( $product_arr ) {

			$product_count = count( $product_arr );

			foreach( $product_arr as $product_key => $product_obj ) {
				$product_obj->save();
			}

			// If process is newly started - Step 1
			if( $page < 2 ) {

				$result['result_message'] .= '<p>'. sprintf( __( 'Total %d Product found for save.', 'product-resave-in-batches' ), $total_count ) .'</p>';
				$result['result_message'] .= '<p style="color:green;">'. __('Percentage Completed', 'product-resave-in-batches') .' : <span class="blueparrot-product-result-percent">0</span>%</p>';
			}

			// Record total process data
			$data_process = ( $data_process + $product_count );

			// Calculate percentage
			$percentage = 100;

			if( $total_count > 0 ) {
				$percentage = ( ( $limit * $page ) / $total_count ) * 100;
			}

			if( $percentage > 100 ) {
				$percentage = 100;

				$result['result_message'] .= '<p>'.__( 'All looks good. All products has been save.', 'product-resave-in-batches' ).'</p>';
			}

			$result['status'] 		= 1;
			$result['message']		= esc_html__('Product Resave successfully.', 'product-resave-in-batches');
			$result['page']			= ( $page + 1 );
			$result['total_count'] 	= $total_count;
			$result['percentage'] 	= round( $percentage, 2 );
			$result['data_process'] = $data_process;
		}

		return $result;
	}
}

$blueparrot_admin = new BlueParrot_Admin();