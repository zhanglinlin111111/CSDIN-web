<?php
/**
 * Class runs for create a new page to show comparison table.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Options;

/**
 * Activation Class
 */
class Activation {

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		if ( ! $this->is_available_for_activation() ) {
			return;
		}

		add_action( 'init', array( $this, 'activation' ) );
	}

	/**
	 * Checks if available the activation
	 *
	 * @return bool
	 */
	private function is_available_for_activation() {
		$is_comparison_table_enabled = Options::is_module_activated();
		$comparison_table_page_id    = Options::get_comparison_table_page_id();

		return ( $is_comparison_table_enabled && ! ( $comparison_table_page_id > 0 ) );
	}

	/**
	 * Creates a new page for comparison table.
	 *
	 * @return int|\WP_Error
	 */
	protected function create_page() {
		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => __( 'Comparison Table', 'neve' ),
				'post_status'  => 'publish',
				'post_content' => '',
			)
		);

		return $page_id;
	}

	/**
	 * Activation processes.
	 * Create a new page for comparison table
	 *
	 * @return bool
	 */
	public function activation() {
		// create new page
		$page_id = $this->create_page();

		if ( is_wp_error( $page_id ) || ( is_int( $page_id ) && ! ( $page_id > 0 ) ) ) {
			/** TODO: get feedback: we may want to throw an WP admin notice */
			return false;
		}

		$match_the_page   = $this->match_the_page( $page_id );
		$set_page_options = $this->set_page_options( $page_id );

		return ( $match_the_page && $set_page_options );
	}

	/**
	 * Match the comparison table page.
	 *
	 * @param  int $page_id WP Post ID.
	 * @return bool
	 */
	protected function match_the_page( $page_id ) {
		// set the comparison table page id
		return update_option( 'woocommerce_neve_comparison_table_page_id', $page_id );
	}

	/**
	 * Update page options (page width).
	 *
	 * @param  int $page_id WP Post ID.
	 * @return bool
	 */
	public function set_page_options( $page_id ) {
		// set neve content with of the post
		$update_neve_meta_enable_content_width = (bool) update_post_meta( $page_id, 'neve_meta_enable_content_width', 'on' );
		$update_neve_meta_content_width        = (bool) update_post_meta( $page_id, 'neve_meta_content_width', 100 );
		$update_neve_meta_no_sidebar           = (bool) update_post_meta( $page_id, 'neve_meta_sidebar', 'full-width' );

		return ( $update_neve_meta_enable_content_width && $update_neve_meta_content_width );
	}
}
