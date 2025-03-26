<?php
/**
 * ...
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\View
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\View;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Functions;
use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Options;

/**
 * ...
 */
class Single_Product {

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'woocommerce_product_meta_end', array( $this, 'render_single_product_compare_button' ) );
	}

	/**
	 * Show 'add product to comparison table' button.
	 */
	public function render_single_product_compare_button() {
		global $product;

		// check product is suitable for restriction. (check if the product in any restricted category)
		$available_for_comparison_table = Functions::is_product_available_for_comparison( $product );

		// if product is not suitable for restrictions, do not show comparison table button.
		if ( ! $available_for_comparison_table ) {
			return;
		}

		$comparison_page_id = Options::get_comparison_table_page_id();

		// do not show the 'view comparison' button if comparison table not selected.
		if ( ! $comparison_page_id ) {
			return;
		}

		?>
		<div class="nv-ct-compare-btn-wrap ct-single">
			<a href="#" data-img="<?php echo esc_url( Functions::get_product_image_url( $product ) ); ?>" data-pid="<?php echo esc_attr( (string) $product->get_id() ); ?>" class="nv-ct-compare-btn nv-ct-sp-compare-btn">
				<svg width="16" height="16" class="nv-ct-icon nv-ct-plus-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" fill="none" ><path d="M14.5 6.5V8.5H8.5V14.5H6.5V8.5H0.5V6.5H6.5V0.5H8.5V6.5H14.5Z" fill="white"/></svg>
				<svg height="16" class="nv-ct-icon nv-ct-check-icon" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5498 0.299805L4.7498 7.0998L1.9498 4.2998L0.549805 5.6998L4.7498 9.89981L12.9498 1.6998" fill="white"/></svg>

				<div class="nv-ct-catalog-compare-btn-tooltip nv-ct-catalog-compare-btn-tooltip-left">
					<div class="nv-ct-compare-tooltip-content tooltip">
						<?php esc_html_e( 'Compare', 'neve' ); ?>
					</div>

					<div class="nv-ct-remove-tooltip-content tooltip">
						<?php esc_html_e( 'Remove', 'neve' ); ?>
					</div>

					<div class="nv-ct-max-product-notice-tooltip-content tooltip">
						<span>
							<?php
							/* translators: %s: product limit in comparison table.  */
							printf( esc_html__( 'You can compare a maximum of %d products!', 'neve' ), esc_html( (string) Options::get_number_of_products_limit() ) );
							?>
						</span>
					</div>
				</div>
			</a>
		</div>
		<?php
	}
}
