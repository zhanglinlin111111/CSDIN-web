<?php
/**
 * Query operations related the Custom Thank You Fetaure.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Query
 */
class Query {
	/**
	 * Get custom thank you page posts (entire the WP_Post object or just pluck the single field)
	 * 
	 * @param string|false                   $pluck_field if set, returns only the requested field, otherwise entire post object.
	 * @param false|callable(\WP_Post): bool $filter_callback that is an optional filter callback.
	 *
	 * @return array|false
	 */
	public static function get( $pluck_field = false, $filter_callback = false ) {
		$valid_fields = array(
			'ID',
			'post_author',
			'post_date',
			'post_date_gmt',
			'post_content',
			'post_title',
			'post_status',
			'post_modified',
			'post_modified_gmt',
			'menu_order',
			'guid',
			'post_parent',
		);

		if ( $pluck_field !== false && ! in_array( $pluck_field, $valid_fields, true ) ) {
			return false;
		}

		$posts = get_posts(
			array(
				'post_type'      => Main::CUSTOM_THANK_YOU_CPT,
				'nopaging'       => false,
				'posts_per_page' => 100,
				'post_status'    => 'publish',
			)
		);

		$results = [];

		foreach ( $posts as $post ) {
			// if filter callback has defined, check that.
			if ( $filter_callback !== false && $filter_callback( $post ) !== true ) {
				continue;
			}

			if ( $pluck_field !== false ) {
				$value = $post->{$pluck_field};
			} else {
				$value = $post;
			}

			$results[ $post->ID ] = $value;
		}

		return $results;
	}

	/**
	 * Returns page titles of the custom thank you pages.
	 *
	 * @return array
	 */
	public static function get_page_titles() {
		return self::get( 'post_title' );
	}

	/**
	 * Has custom thank you page product based restriction?
	 *
	 * @throws \Exception If the provided custom thank you page post ID is not valid.
	 *
	 * @return bool
	 */
	public static function has_ty_page_contains_product_restriction( int $cty_post_id ) {
		global $wpdb;

		$cty_post_id = (int) $cty_post_id; // double check.

		if ( ! is_int( $cty_post_id ) || ! ( $cty_post_id ) > 0 ) {
			throw new \Exception( 'Invalid custom thank you page post ID.' );
		}

		$cache_group = 'nv_cty_restricts';
		$cache_key   = 'neve_cty_' . $cty_post_id . '_restricted';

		$has_restriction = wp_cache_get( $cache_key, $cache_group );

		if ( $has_restriction === false ) {
			$total           = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key='_nv_thank_you_page_id' AND meta_value=%d", $cty_post_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$has_restriction = ( $total > 0 );

			$cache_value = $has_restriction ? 'yes' : 'no';
			wp_cache_set( $cache_key, $cache_value, $cache_group );

			return $has_restriction;
		}

		return $has_restriction === 'yes';
	}
}
