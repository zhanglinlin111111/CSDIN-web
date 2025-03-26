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

use Neve\Core\Settings\Mods;
use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Functions;
use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Options;

/**
 * ...
 */
class Catalog {
	const CHECKBOX_POSITION        = 'neve_comparison_table_compare_checkbox_position';
	const WISHLIST_BUTTON_POSITION = 'neve_wish_list';

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'neve_product_actions', array( $this, 'render_catalog_compare_button' ) );
	}

	/**
	 * View Add Compare Button
	 *
	 * @return void
	 */
	public function render_catalog_compare_button() {
		if ( isset( $_GET['is_woo_comparison_block'] ) ) {
			return;
		}

		global $product;

		// check product is suitable for restriction. (check if the product in any restricted category)
		$available_for_comparison_table = Functions::is_product_available_for_comparison( $product );

		// if product is not suitable for restrictions, do not show comparison table button.
		if ( ! $available_for_comparison_table ) {
			return;
		}

		$compare_checkbox_position = Mods::get( self::CHECKBOX_POSITION, 'top' );

		$style = $compare_checkbox_position === 'top' ? 'order: 1; align-self: start;' : 'order: 2; align-self: end;';

		?>
		<div class="nv-ct-compare-btn-wrap <?php echo esc_attr( $compare_checkbox_position ); ?>" style="<?php echo esc_attr( $style ); ?>">
			<a href="#" data-url="<?php echo esc_url( $product->get_permalink() ); ?>" data-img="<?php echo esc_url( Functions::get_product_image_url( $product ) ); ?>" data-pid="<?php echo esc_attr( (string) $product->get_id() ); ?>" class="nv-ct-compare-btn">
				<svg width="16" height="16" class="nv-ct-icon nv-ct-plus-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" fill="none" ><path d="M14.5 6.5V8.5H8.5V14.5H6.5V8.5H0.5V6.5H6.5V0.5H8.5V6.5H14.5Z" fill="white"/></svg>
				<svg height="16" class="nv-ct-icon nv-ct-check-icon" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5498 0.299805L4.7498 7.0998L1.9498 4.2998L0.549805 5.6998L4.7498 9.89981L12.9498 1.6998" fill="white"/></svg>

				<span class="nv-ct-catalog-compare-btn-tooltip nv-ct-catalog-compare-btn-tooltip-left">
					<span class="nv-ct-compare-tooltip-content tooltip">
						<?php esc_html_e( 'Compare', 'neve' ); ?>
					</span>

					<span class="nv-ct-remove-tooltip-content tooltip">
						<?php esc_html_e( 'Remove', 'neve' ); ?>
					</span>

					<span class="nv-ct-max-product-notice-tooltip-content tooltip">
						<?php
							/* translators: %s: product limit in comparison table.  */
							printf( esc_html__( 'You can compare a maximum of %d products.', 'neve' ), esc_html( (string) Options::get_number_of_products_limit() ) );
						?>
					</span>
				</span>
			</a>
		</div>
		<?php
	}
}
