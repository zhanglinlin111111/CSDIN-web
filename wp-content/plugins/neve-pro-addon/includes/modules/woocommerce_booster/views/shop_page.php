<?php
/**
 * Class that modify shop page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

use Neve\Compatibility\Woocommerce;

/**
 * Class Shop_Page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Shop_Page {

	/**
	 * Init function.
	 */
	public function register_hooks() {
		add_action( 'woocommerce_product_query', array( $this, 'products_per_page' ), 1, 50 );
		add_filter( 'loop_shop_columns', array( $this, 'shop_columns' ), 999 );
		add_filter( 'body_class', array( $this, 'shop_page_products_item_class' ) );
		add_action( 'wp', array( $this, 'run' ) );
	}

	/**
	 * Filter cols per row customizer control.
	 */
	public function shop_columns( $default ) {
		if ( neve_pro_is_new_skin() ) {
			return $default;
		}

		$products_per_row = get_theme_mod(
			'neve_products_per_row',
			wp_json_encode(
				array(
					'desktop' => 4,
					'tablet'  => 2,
					'mobile'  => 2,
				)
			)
		);
		$products_per_row = json_decode( $products_per_row, true );

		return $products_per_row['desktop'];
	}

	/**
	 * Modify products per page based on rows per page and products per row controls.
	 *
	 * @param \WP_Query $query Query object.
	 */
	public function products_per_page( $query ) {
		if ( $query->is_main_query() ) {
			$rows              = get_theme_mod( 'woocommerce_catalog_rows' );
			$cols              = get_theme_mod( 'woocommerce_catalog_columns' );
			$default           = ( $rows * $cols ) > 0 ? $rows * $cols : 12;
			$products_per_page = get_theme_mod( 'neve_products_per_page', $default );
			$query->set( 'posts_per_page', $products_per_page );
		}
	}

	/**
	 * Add class for responsive view on body.
	 *
	 * @param array $classes CSS classes to alter.
	 *
	 * @return array
	 */
	public function shop_page_products_item_class( $classes = array() ) {
		if ( neve_pro_is_new_skin() ) {
			return $classes;
		}

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return $classes;
		}

		$products_per_row = get_theme_mod( 'neve_products_per_row', '{"desktop":4,"tablet":2,"mobile":2}' );
		$products_per_row = json_decode( $products_per_row, true );
		$classes[]        = 'desktop-columns-' . $products_per_row['desktop'];
		$classes[]        = 'tablet-columns-' . $products_per_row['tablet'];
		$classes[]        = 'mobile-columns-' . $products_per_row['mobile'];

		return $classes;
	}

	/**
	 * Run the module.
	 */
	public function run() {
		$this->shop_pagination();
		$this->product_layout_toggle();
		$this->change_columns_class();
		$this->off_canvas();
	}

	/**
	 * Off Canvas filtering.
	 *
	 * @return void
	 */
	private function off_canvas() {
		$advanced_options = get_theme_mod( 'neve_advanced_layout_options', neve_pro_is_new_skin() );
		if ( $advanced_options === false ) {
			return;
		}

		if ( ! is_shop() && ! is_product_category() && ! is_product_taxonomy() && ! is_product_tag() ) {
			return;
		}

		$shop_sidebar = apply_filters( 'neve_sidebar_position', get_theme_mod( 'neve_shop_archive_sidebar_layout', 'right' ) );
		if ( $shop_sidebar !== 'off-canvas' ) {
			return;
		}

		add_filter( 'neve_sidebar_position', array( $this, 'set_off_canvas_position' ) );
		add_filter( 'body_class', array( $this, 'add_off_canvas_class' ), 0 );
	}

	/**
	 * Set off-canvas position.
	 *
	 * @return string
	 */
	public function set_off_canvas_position() {
		return 'left';
	}

	/**
	 * Off-canvas layout class.
	 *
	 * @return array
	 */
	public function add_off_canvas_class( $classes ) {
		$classes[] = 'neve-off-canvas';
		return $classes;
	}

	/**
	 * Set the neve value to the columns property in the woocommerce_loop.
	 */
	private function change_columns_class() {
		add_filter(
			'woocommerce_product_loop_start',
			function ( $html ) {
				return str_replace( 'columns-', 'columns-neve nv-shop-col-', $html );
			}
		);
	}

	/**
	 * Disable WooCommerce pagination if the user selected infinite scroll.
	 *
	 * @return bool
	 */
	public function shop_pagination() {

		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'add_pagination' ) );

		$pagination_type = get_theme_mod( 'neve_shop_pagination_type', 'number' );
		if ( $pagination_type === 'number' || neve_is_amp() ) {
			return false;
		}

		if ( is_shop() ) {
			$display_type = get_option( 'woocommerce_shop_page_display', '' );
			if ( $display_type === 'subcategories' ) {
				return false;
			}
		}

		if ( is_product_category() ) {
			$parent_id    = get_queried_object_id();
			$display_type = get_term_meta( $parent_id, 'display_type', true );
			$display_type = '' === $display_type ? get_option( 'woocommerce_category_archive_display', '' ) : $display_type;
			if ( $display_type === 'subcategories' ) {
				return false;
			}
		}

		add_action( 'woocommerce_after_shop_loop', array( $this, 'load_more_products_sentinel' ) );

		return true;
	}

	/**
	 * Render woocommerce pagination and add rel="next" and rel="prev".
	 */
	public function add_pagination() {
		ob_start();
		woocommerce_pagination();
		$pagination = ob_get_clean();
		$pagination = str_replace(
			array( '<a class="prev', '<a class="next' ),
			array(
				'<a rel="prev" class="prev',
				'<a rel="next" class="next',
			),
			$pagination
		);

		$pagination_type       = get_theme_mod( 'neve_shop_pagination_type', 'number' );
		$shoud_hide_pagination = $pagination_type === 'infinite' || neve_is_amp();

		echo $shoud_hide_pagination ? '<div style="display:none">' : '';
		echo wp_kses_post( $pagination );
		echo $shoud_hide_pagination ? '</div>' : '';
	}

	/**
	 * Add a sentinel to know when the request should happen.
	 */
	public function load_more_products_sentinel() {
		echo '<div class="load-more-products"><span class="nv-loader" style="display: none;"></span><span class="infinite-scroll-trigger"></span></div>';
	}

	/**
	 * Product layout toggle.
	 */
	private function product_layout_toggle() {
		$product_layout_toggle = get_theme_mod( 'neve_enable_product_layout_toggle', false );
		if ( $product_layout_toggle === false || neve_is_amp() ) {
			return;
		}
		add_action( 'nv_woo_header_bits', array( $this, 'render_layout_toggle_buttons' ), 40 );
	}

	/**
	 * Toggle buttons for product display.
	 */
	public function render_layout_toggle_buttons() {
		$shop_layout = isset( $_GET['ref'] ) ? sanitize_key( $_GET['ref'] ) : get_theme_mod( 'neve_product_card_layout', 'grid' );
		echo '<div class="nv-layout-toggle-wrapper">';
		echo '<a href="#" class="nv-toggle-list-view nv-toggle ' . ( $shop_layout === 'list' ? 'current' : '' ) . '">';
		echo '<svg width="15" height="15" viewBox="0 0 512 512"><path fill="currentColor" d="M128 116V76c0-8.837 7.163-16 16-16h352c8.837 0 16 7.163 16 16v40c0 8.837-7.163 16-16 16H144c-8.837 0-16-7.163-16-16zm16 176h352c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H144c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h352c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H144c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zM16 144h64c8.837 0 16-7.163 16-16V64c0-8.837-7.163-16-16-16H16C7.163 48 0 55.163 0 64v64c0 8.837 7.163 16 16 16zm0 160h64c8.837 0 16-7.163 16-16v-64c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v64c0 8.837 7.163 16 16 16zm0 160h64c8.837 0 16-7.163 16-16v-64c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v64c0 8.837 7.163 16 16 16z"/></svg>';
		echo '</a>';
		echo '<a href="#" class="nv-toggle-grid-view nv-toggle ' . ( $shop_layout === 'grid' ? 'current' : '' ) . '">';
		echo '<svg width="15" height="15" viewBox="0 0 512 512"><path fill="currentColor" d="M149.333 56v80c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24V56c0-13.255 10.745-24 24-24h101.333c13.255 0 24 10.745 24 24zm181.334 240v-80c0-13.255-10.745-24-24-24H205.333c-13.255 0-24 10.745-24 24v80c0 13.255 10.745 24 24 24h101.333c13.256 0 24.001-10.745 24.001-24zm32-240v80c0 13.255 10.745 24 24 24H488c13.255 0 24-10.745 24-24V56c0-13.255-10.745-24-24-24H386.667c-13.255 0-24 10.745-24 24zm-32 80V56c0-13.255-10.745-24-24-24H205.333c-13.255 0-24 10.745-24 24v80c0 13.255 10.745 24 24 24h101.333c13.256 0 24.001-10.745 24.001-24zm-205.334 56H24c-13.255 0-24 10.745-24 24v80c0 13.255 10.745 24 24 24h101.333c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24zM0 376v80c0 13.255 10.745 24 24 24h101.333c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24H24c-13.255 0-24 10.745-24 24zm386.667-56H488c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24H386.667c-13.255 0-24 10.745-24 24v80c0 13.255 10.745 24 24 24zm0 160H488c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24H386.667c-13.255 0-24 10.745-24 24v80c0 13.255 10.745 24 24 24zM181.333 376v80c0 13.255 10.745 24 24 24h101.333c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24H205.333c-13.255 0-24 10.745-24 24z"/></svg>';
		echo '</a>';
		echo '</div>';
	}

}
