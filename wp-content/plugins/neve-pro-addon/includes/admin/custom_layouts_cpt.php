<?php
/**
 * Handles the CPT Registration.
 *
 * Created on:      2020-01-21
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Admin;

use Neve_Pro\Modules\Header_Footer_Grid\Module;
use Neve_Pro\Modules\Custom_Layouts\Utilities;
use WP_Query;

/**
 * Class Custom_Layouts_Cpt
 *
 * @package Neve_Pro\Admin
 */
class Custom_Layouts_Cpt {
	use Utilities;

	/**
	 * CPT edit screen id.
	 *
	 * @var string
	 */
	private $cpt_screen_id = 'edit-neve_custom_layouts';

	/**
	 * Initialize the Custom Layouts CPT class.
	 */
	public function init() {
		$this->register_custom_post_type();
		add_filter( 'parse_query', [ $this, 'remove_conditional_headers' ] );
		add_filter( 'views_' . $this->cpt_screen_id, [ $this, 'recount_posts' ] );
		add_filter( 'wp_count_posts', [ $this, 'recount_posts' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'change_cpt_label' ], 100 );
	}

	/**
	 * Change CPT label and position to be under the last page registered inside Neve or TPC.
	 */
	public function change_cpt_label() {
		global $submenu;

		if ( ! isset( $submenu['themes.php'] ) ) {
			return;
		}

		$last_theme_page_position = false;
		$cpt_position             = false;
		foreach ( $submenu['themes.php'] as $index => $item ) {
			if ( $item[2] === 'neve-welcome' || $item[2] === 'tiob-starter-sites' ) {
				$last_theme_page_position = $index;
				continue;
			}

			if ( $item[2] === 'edit.php?post_type=neve_custom_layouts' ) {
				$cpt_position = $index;
				$style        = 'display:inline-block;';

				if ( ! is_rtl() ) {
					$style .= 'transform:scaleX(-1);margin-right:5px;';
				} else {
					$style .= 'margin-left:5px;';
				}

				$prefix = '<span style="' . esc_attr( $style ) . '">&crarr;</span>';

				$submenu['themes.php'][ $index ][0] = $prefix . $submenu['themes.php'][ $index ][0]; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		if ( $last_theme_page_position && $cpt_position ) {
			$cpt = $submenu['themes.php'][ $cpt_position ];
			unset( $submenu['themes.php'][ $cpt_position ] );
			array_splice( $submenu['themes.php'], $last_theme_page_position + 1, 0, [ $cpt ] );
		}
	}

	/**
	 * Register Custom Layouts post type.
	 */
	private function register_custom_post_type() {

		$labels = array(
			'name'          => esc_html_x( 'Custom Layouts', 'advanced-hooks general name', 'neve' ),
			'singular_name' => esc_html_x( 'Custom Layout', 'advanced-hooks singular name', 'neve' ),
			'search_items'  => esc_html__( 'Search Custom Layouts', 'neve' ),
			'all_items'     => esc_html__( 'Custom Layouts', 'neve' ),
			'edit_item'     => esc_html__( 'Edit Custom Layout', 'neve' ),
			'view_item'     => esc_html__( 'View Custom Layout', 'neve' ),
			'add_new'       => esc_html__( 'Add New', 'neve' ),
			'update_item'   => esc_html__( 'Update Custom Layout', 'neve' ),
			'add_new_item'  => esc_html__( 'Add New', 'neve' ),
			'new_item_name' => esc_html__( 'New Custom Layout Name', 'neve' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'query_var'           => true,
			'can_export'          => true,
			'exclude_from_search' => true,
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'elementor' ),
		);

		register_post_type( 'neve_custom_layouts', apply_filters( 'neve_custom_layouts_post_type_args', $args ) );
	}

	/**
	 * Remove conditional headers from the post list table in the admin.
	 *
	 * @param WP_Query $query the query.
	 *
	 * @return WP_Query
	 */
	public function remove_conditional_headers( WP_Query $query ) {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return $query;
		}
		$screen = get_current_screen();

		if ( ! $screen || $screen->id !== $this->cpt_screen_id ) {
			return $query;
		}

		$query->query_vars['meta_query'][] = [
			'key'     => 'header-layout',
			'value'   => '',
			'compare' => 'NOT EXISTS',
		];

		return $query;
	}

	/**
	 * Filter the count.
	 *
	 * @param object|array $count the count object.
	 *
	 * @return mixed
	 */
	public function recount_posts( $count ) {
		$post_type = get_post_type();
		if ( $post_type !== 'neve_custom_layouts' ) {
			return $count;
		}

		// TODO: Make sure to fix `Mine` here.
		if ( ! is_object( $count ) ) {
			return $count;
		}

		$count->publish = absint( $count->publish ) - count( self::get_conditional_headers() );

		return $count;
	}

	/**
	 * Get the custom layouts.
	 *
	 * @return array
	 */
	public static function get_custom_layouts() {
		$posts = self::get_posts();

		return $posts['custom_layouts'];
	}

	/**
	 * Get the conditional headers.
	 *
	 * @return array
	 */
	public static function get_conditional_headers() {
		$posts = self::get_posts();

		return $posts['conditional_headers'];
	}

	/**
	 * Get all the custom layouts post in array under two keys
	 *
	 * [custom_layouts, conditional_headers]
	 *
	 * @return array
	 */
	public static function get_posts() {
		$cache = get_transient( 'custom_layouts_post_map_v3' );
		if ( ! empty( $cache ) ) {
			return $cache;
		}
		$query = new \WP_Query(
			array(
				'post_type'              => 'neve_custom_layouts',
				'posts_per_page'         => 100,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'fields'                 => 'ids',
				'post_status'            => 'publish',
			)
		);
		$posts = [
			'custom_layouts'      => [],
			'conditional_headers' => [],
		];
		if ( ! $query->have_posts() ) {
			return $posts;
		}
		foreach ( $query->posts as $pid ) {
			$is_header_layout = get_post_meta( $pid, 'header-layout', true );
			if ( $is_header_layout ) {

				$posts['conditional_headers'][ $pid ] = get_post_meta( $pid, 'theme-mods', true );
				continue;
			}

			$layout = get_post_meta( $pid, 'custom-layout-options-layout', true );
			if ( ! ( $layout ) ) {
				continue;
			}

			$priority = self::get_priority( $pid );
			if ( $layout === 'hooks' ) {
				$layout = get_post_meta( $pid, 'custom-layout-options-hook', true );
			}
			if ( $layout === 'custom' ) {
				$layout = get_post_meta( $pid, 'custom-layout-specific-hook', true );
			}
			$posts['custom_layouts'][ $layout ][ $pid ] = $priority;
		}
		set_transient( 'custom_layouts_post_map_v3', $posts, 0 );

		return $posts;
	}
}
