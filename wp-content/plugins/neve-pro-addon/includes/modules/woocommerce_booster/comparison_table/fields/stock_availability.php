<?php
/**
 * Name field of the comparison table.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Fields;
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field that provides show stock availability in comparison table functionality.
 */
class Stock_Availability extends Abstract_Field {
	/**
	 * Set label
	 */
	public function set_label() {
		$this->label = esc_html__( 'Stock Availability', 'neve' );
	}

	/**
	 * Get field value of the product.
	 *
	 * @param  \WC_Product $product is product instance.
	 * @return void
	 */
	public function render( \WC_Product $product ) {
		if ( $product->is_on_backorder() ) {
			$status = __( 'On backorder', 'neve' );
		} elseif ( $product->is_in_stock() ) {
			$status = __( 'In stock', 'neve' );
		} else {
			$status = __( 'Out of stock', 'neve' );
		}

		echo esc_attr( $status );
	}
}
