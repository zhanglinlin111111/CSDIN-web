<?php
/**
 * Interface.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Fields
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Interface_Field { 
	/**
	 * Set label
	 *
	 * @return void
	 */
	public function set_label();
	
	/**
	 * Render
	 *
	 * @param \WC_Product $product that the WC Product instance.
	 * @return void
	 */
	public function render( \WC_Product $product );
}
