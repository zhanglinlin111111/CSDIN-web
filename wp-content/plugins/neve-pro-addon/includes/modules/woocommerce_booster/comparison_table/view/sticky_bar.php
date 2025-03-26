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
use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Options;

/**
 * ...
 */
class Sticky_Bar {

	const STICKY_BAR_BUTTON_TYPE = 'neve_comparison_table_sticky_bar_button_type';

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'render_sticky_bar' ) );
	}

	/**
	 * Should sticky bar is visible?
	 *
	 * @return bool
	 */
	public function should_sticky_bar_visible() {
		return Options::current_page_contains_wc_product() && ( Options::get_comparison_table_page_id() > 0 );
	}

	/**
	 * View Sticky Footer
	 *
	 * @return void
	 */
	public function render_sticky_bar() {
		if ( ! $this->should_sticky_bar_visible() ) {
			return;
		}

		$button_classes = Mods::get( self::STICKY_BAR_BUTTON_TYPE, 'primary' ) === 'secondary' ? 'added_to_cart' : 'button';

		?>
		<div class="nv-ct-sticky-bar hidden">
			<div id="nv-ct-product-template-container">
				<?php
				// print for JS cloning
				$this->output_sticky_bar_product_template();
				?>
			</div>

			<div class="container">
				<div class="ct-sticky-col description">
					<div>
						<span class="bar-title">
							<?php esc_html_e( 'Choose products to compare', 'neve' ); ?>
						</span>
					</div>
					<div>
						<span class="bar-desc">
							<span class="nv-ct-sticky-bar-total-product"></span> <?php esc_html_e( 'products selected.', 'neve' ); ?>
						</span>
						<a href="#" class="nv-ct-clear-all"><?php esc_html_e( 'Clear all', 'neve' ); ?></a>
					</div>
				</div>
				<div id="nv-ct-products" class="ct-sticky-col nv-ct-col-products">
				</div>
				<div class="ct-sticky-col nv-ct-col-button">
					<span class="min-prod nv-ct-hide-element"><?php esc_html_e( 'Please add one more product.', 'neve' ); ?></span>
					<a class="nv-ct-compare-btn-wrapper nv-ct-hide-element <?php echo esc_attr( $button_classes ); ?>">
						<?php esc_html_e( 'Compare', 'neve' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Output of the sticky bar product template
	 *
	 * @return void
	 */
	private function output_sticky_bar_product_template() {
		?>
		<div data-pid="{productId}" class="nv-ct-sticky-bar-product-container">
			<div class="nv-ct-product-image-buttons">
				<button value="{productId}" class="nv-ct-remove-product">×</button>
				<div class="nv-ct-product-image-wrapper">{productImage}</div>
			</div>
		</div>
		<?php
	}
}
