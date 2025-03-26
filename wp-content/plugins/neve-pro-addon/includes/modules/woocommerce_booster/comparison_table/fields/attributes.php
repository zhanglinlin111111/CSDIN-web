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

use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Product_Attribute;

/**
 * Field that provides show product attributes in comparison table functionality.
 */
class Attributes extends Abstract_Field {
	/**
	 * Set label
	 */
	public function set_label() {
		$this->label = esc_html__( 'Attributes', 'neve' );
	}
	
	/**
	 * Set Field Key
	 *
	 * @param  string $key that indicates the field key.
	 * @return void
	 */
	public function set_attribute_key( $key ) {
		$this->key = $key;
	}
	
	/**
	 * Set Field Label
	 *
	 * @param  string $label that indicates the field label.
	 * @return void
	 */
	public function set_attribute_label( $label ) {
		$this->label = $label;
	}

	/**
	 * Get field value of the product.
	 *
	 * @param  \WC_Product $product is product instance.
	 * @return void
	 */
	public function render( \WC_Product $product ) {
		$product_attribute = new Product_Attribute( $product );

		echo wp_kses_post( $product_attribute->get_attribute_options_html( $this->key ) );
	}
}
