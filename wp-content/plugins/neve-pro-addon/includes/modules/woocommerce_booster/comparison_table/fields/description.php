<?php
/**
 * Description field of the comparison table.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Fields;
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field that provides show product description in comparison table functionality.
 */
class Description extends Abstract_Field {
	/**
	 * Set label
	 */
	public function set_label() {
		$this->label = esc_html__( 'Description', 'neve' );
	}

	/**
	 * Get field value of the product.
	 *
	 * @param  \WC_Product $product is product instance.
	 * @return void
	 */
	public function render( \WC_Product $product ) {
		echo wp_kses_post( $product->get_short_description() );
	}
}
