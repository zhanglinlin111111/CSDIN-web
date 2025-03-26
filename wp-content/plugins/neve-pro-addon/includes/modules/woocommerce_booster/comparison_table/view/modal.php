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

/**
 * ...
 */
class Modal {

	const DISPLAY_TYPE = 'neve_comparison_table_view_type';

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		if ( $this->should_load() ) {
			add_filter( 'woocommerce_get_script_data', array( $this, 'disabled_cart_redirect_after_add' ), 10, 2 );
			add_action( 'init', array( $this, 'render_iframe_comparison_table' ) );
		}

		add_action( 'wp_footer', array( $this, 'render_modal' ), 100 );
	}

	/**
	 * Decide if iframe should render.
	 *
	 * @return bool
	 */
	private function should_load() {
		return (bool) ( Mods::get( self::DISPLAY_TYPE, 'page' ) === 'popup' && isset( $_GET['comparison-table-iframe'] ) );
	}

	/**
	 * Forcefully disable the auto redirect after add a product to cart (should work only inside of the popup mode.)
	 *
	 * @param  array|bool $params Current script params.
	 * @param  string     $handle Part of the Woocommerce script group.
	 * @return array
	 */
	public function disabled_cart_redirect_after_add( $params, $handle ) {
		if ( $handle !== 'wc-add-to-cart' || ! is_array( $params ) || ! array_key_exists( 'cart_redirect_after_add', $params ) ) {
			return $params;
		}

		$params['cart_redirect_after_add'] = 'no';
		return $params;
	}

	/**
	 * Render Comparison Table for Iframe
	 *
	 * @return void
	 */
	function render_iframe_comparison_table() {
		// Gutenberg-Blocks Compatibility: to fix a Gutenberg-Blocks warning.
		global $wp_query;
		if ( is_null( $wp_query->posts ) ) {
			$wp_query->posts = array();
		}
		?>

		<html class="ct-html">
			<head><?php wp_head(); ?></head>
			<body class="woocommerce nv-ct-enabled nv-ct-iframe">
				<div class="ct-page-wrap">
				<?php
					// fire wp_loaded hook for trigger WooCommerce add-to-cart handler.
					do_action( 'wp_loaded' );

					wc_print_notices();
					( new Table() )->render_comparison_products_table( false );
				?>
				</div>
			</body>
			<?php
			wp_footer();
			?>
			<script>
				// if redirection is enabled after the add to cart, redirect the page.
				window.onclick = function(e) {
					var target = e.target.closest('a');

					if( ! target || ( neveWooBooster.comparisonTable.cartRedirectAfterAdd !== 'yes' && target.classList.contains('add_to_cart_button') ) || target.classList.contains('nv-ct-compare-btn') || typeof target.href === 'undefined' ) {
						return;
					}

					// redirect the parent
					window.parent.location = target.href;
					e.preventDefault();
				}
			</script>
		</html>
		<?php
		exit;
	}

	/**
	 * Comparison table modal view.
	 */
	public function render_modal() {
		if ( Mods::get( self::DISPLAY_TYPE, 'page' ) !== 'popup' ) {
			return;
		}
		?>
			<div id="nv-ct-view-modal" class="nv-modal" aria-modal="true">
				<div class="nv-modal-overlay jsOverlay"></div>

				<div class="nv-modal-container is-loading">
					<button class="nv-modal-close jsModalClose" aria-label="<?php esc_attr_e( 'Close comparison table', 'neve' ); ?>">&#10005;</button>
					<div id="nv-ct-iframe-container"></div>
				</div>
			</div>
		<?php
	}
}
