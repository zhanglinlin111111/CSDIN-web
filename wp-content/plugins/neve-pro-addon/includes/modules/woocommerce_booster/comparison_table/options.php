<?php
/**
 * Class that manages options of the Comparison Table feature.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Neve\Core\Settings\Mods;

/**
 * Class Options
 */
class Options {
	const MODS_COMPARISON_TABLE_OPEN_POPUP_PRODUCT_LIMIT = 'neve_comparison_table_open_popup_product_limit';
	const MODS_COMPARISON_TABLE_NUMBER_OF_PRODUCTS_LIMIT = 'neve_comparison_table_number_of_products_limit';
	
	/**
	 * That specify if the module is activated on Neve Pro extra features tab.
	 *
	 * @return bool
	 */
	public static function is_module_activated() {
		return get_option( 'nv_pro_enable_comparison_table', false );
	}

	/**
	 * Does the current page have WooCommerce product?
	 *
	 * @return bool
	 */
	public static function current_page_contains_wc_product() {
		global $post;
		$has_shortcode = false;
		if ( ! empty( $post ) && property_exists( $post, 'post_content' ) ) {
			$has_shortcode = has_shortcode( $post->post_content, 'products' )
				|| has_shortcode( $post->post_content, 'featured_products' )
				|| has_shortcode( $post->post_content, 'sale_products' )
				|| has_shortcode( $post->post_content, 'best_selling_products' )
				|| has_shortcode( $post->post_content, 'recent_products' )
				|| has_shortcode( $post->post_content, 'top_rated_products' );
		}
		return ( is_woocommerce()
			|| $has_shortcode
			|| is_cart()
		);
	}

	/**
	 * Should comparison table assets and dynamic styles be loaded?
	 *
	 * @return bool
	 */
	public static function should_load_assets() {
		if ( ! neve_pro_is_new_skin() ) {
			return false;
		}

		global $post;
		$is_comparison_table  = is_a( $post, 'WP_Post' ) && ( self::get_comparison_table_page_id() === $post->ID );
		$has_comparison_block = is_a( $post, 'WP_Post' ) && has_block( 'themeisle-blocks/woo-comparison', $post );

		return $is_comparison_table || $has_comparison_block;
	}

	/**
	 * Get neve_comparison_table_number_of_products_limit theme mod as normalized.
	 *
	 * @return int
	 */
	public static function get_number_of_products_limit() {
		$number_of_products_limit = Mods::get( self::MODS_COMPARISON_TABLE_NUMBER_OF_PRODUCTS_LIMIT, 3 );

		if ( $number_of_products_limit > 4 || $number_of_products_limit < 2 ) {
			return 3;
		}

		return $number_of_products_limit;
	}

	/**
	 * Get neve_comparison_table_open_popup_product_limit theme mod as normalized.
	 *
	 * @return int
	 */
	public static function get_open_popup_product_limit() {
		$open_popup_product_limit = Mods::get( self::MODS_COMPARISON_TABLE_OPEN_POPUP_PRODUCT_LIMIT, 3 );

		if ( $open_popup_product_limit > 4 || $open_popup_product_limit < 2 ) {
			return 3;
		}

		return $open_popup_product_limit;
	}

	/**
	 * Get Matched Page ID
	 *
	 * @return int|false
	 */
	public static function get_comparison_table_page_id() {
		$page_id = get_option( 'woocommerce_neve_comparison_table_page_id', false );

		if ( $page_id === false ) {
			$page_id_from_theme_mod = Mods::get( 'neve_comparison_table_page_id', false );

			if ( $page_id_from_theme_mod !== false ) {
				if ( update_option( 'woocommerce_neve_comparison_table_page_id', $page_id_from_theme_mod ) ) {
					remove_theme_mod( 'neve_comparison_table_page_id' );
				}
			}

			return $page_id_from_theme_mod;
		}

		return (int) $page_id;
	}
}
