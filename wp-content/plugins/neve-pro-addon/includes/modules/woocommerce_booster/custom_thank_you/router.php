<?php
/**
 * Router class decides which thank you page is responsible from the order.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Parse_Order;
use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Query;
use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Main;

/**
 * Router class
 */
class Router {    
	/**
	 * Prioritized thank you page id.
	 *
	 * @var int
	 */
	protected $prioritized_thank_you_page_id;
		
	/**
	 * WC Order
	 *
	 * @var \WC_Order WC Order instance that is used to redirect to custom thank you page.
	 */
	private $wc_order;
	
	/**
	 * Constructor method
	 *
	 * @param  \WC_Order $order that uses for the decide which thank you page is responsible from the order.
	 * @return void
	 */
	public function __construct( \WC_Order $order ) {
		$this->wc_order = $order;
	}
	
	/**
	 * Get prioritize engine class names.
	 *
	 * @return array that contains class names as string.
	 */
	protected function get_prioritize_engines() {
		// the prioritize classes are listed as ordered. The default prioritize order is Product > Product Category > Payment Gateway > Shipping Methods > General one
		$prioritize_classes = [
			'\Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Prioritize_Engine\Prioritize_By_Products',
			'\Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Prioritize_Engine\Prioritize_By_Product_Categories',
			'\Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Prioritize_Engine\Prioritize_By_Payment_Gateway',
			'\Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Prioritize_Engine\Prioritize_By_Shipping_Methods',
			'\Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Prioritize_Engine\Prioritize_By_General',
		];

		return apply_filters( 'neve_custom_thank_you_prioritize_engines', $prioritize_classes );
	}

	/**
	 * The method finds top prioritized custom thank you page id by using prioritize engine classes.
	 *
	 * @return int|false the WP post ID of the top prioritized custom thank you page
	 */
	public function get_prioritized_page_id() {
		$total_published_cty = (int) wp_count_posts( Main::CUSTOM_THANK_YOU_CPT )->publish;

		if ( $total_published_cty === 0 ) {
			return false;
		}

		// If number of the custom thank you page is equal to 1 and it'a generalized thank you page (has not a restriction), return it directly without the run prioritize engine.
		if ( $total_published_cty === 1 ) {
			// just return if the custom thank you page has not any restriction (if it's general).
			$posts = Query::get( false, array( Main::class, 'is_ty_page_general' ) );

			// check if the founded custom thank you page is a general one? Return it if only it's a generalized thank you page.
			if ( count( $posts ) === 1 ) {
				return reset( $posts )->ID;
			}
		}

		$prioritized_thank_you_page_id = false;

		foreach ( $this->get_prioritize_engines() as $priortize_engine ) {
			$prioritized_thank_you_page_id = ( new $priortize_engine( $this->wc_order ) )->find_top_prioritized_ty_page_id();

			if ( $prioritized_thank_you_page_id ) {
				break;
			}
		}

		return $prioritized_thank_you_page_id;
	}
}
