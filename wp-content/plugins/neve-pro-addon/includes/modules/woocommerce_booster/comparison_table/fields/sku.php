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
 * Field that provides show product SKU in comparison table functionality.
 */
class Sku extends Abstract_Field {
	/**
	 * Set label
	 */
	public function set_label() {
		$this->label = esc_html__( 'SKU', 'neve' );
	}

	/**
	 * Get field value of the product.
	 *
	 * @param  \WC_Product $product is product instance.
	 * @return void
	 */
	public function render( \WC_Product $product ) {
		echo esc_attr( $product->get_sku() );
	}
}
