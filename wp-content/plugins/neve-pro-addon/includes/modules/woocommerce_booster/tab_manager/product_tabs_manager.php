<?php
/**
 *  Class that handles tab manager admin part.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager;

use ThemeIsle\GutenbergBlocks\CSS\Block_Frontend;
use ThemeIsle\GutenbergBlocks\Main;

/**
 * Class Tab_Manager
 */
class Product_Tabs_Manager {
	use Utilities;
	/**
	 * Init function.
	 */
	public function init() {

		/**
		 * Actions related to the CPT
		 */
		$product_tabs_cpt = new Product_Tabs_Cpt();
		$product_tabs_cpt->init();

		/**
		 * Actions related to individual tabs
		 */
		$product_tabs_individual = new Product_Tabs_Individual();
		$product_tabs_individual->init();

		// Initialize the views for custom tabs.
		add_action( 'wp', [ $this, 'init_views' ] );
	}

	/**
	 * Check if submodule should be loaded.
	 *
	 * @return bool
	 */
	private function should_load() {
		if ( ! class_exists( 'Woocommerce', false ) ) {
			return false;
		}

		if ( ! is_product() ) {
			return false;
		}

		$should_insert_default_tabs = get_option( 'neve_pt_default_tabs', 'yes' );
		if ( $should_insert_default_tabs === 'yes' ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize the views rendering.
	 */
	public function init_views() {
		if ( ! $this->should_load() ) {
			return;
		}

		add_filter( 'woocommerce_product_tabs', [ $this, 'manage_product_tabs' ], 100 );
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_otter_frontend_assets' ] );
	}

	/**
	 * Try to load assets from Otter so the blocks would render inside tabs content.
	 */
	public function enqueue_otter_frontend_assets() {
		if ( ! class_exists( '\ThemeIsle\GutenbergBlocks\Main', false ) ) {
			return;
		}

		if ( ! class_exists( '\ThemeIsle\GutenbergBlocks\CSS\Block_Frontend', false ) ) {
			return;
		}

		$args         = [
			'post_type'   => 'neve_product_tabs',
			'post_status' => 'publish',
			'orderby'     => 'menu_order title',
			'order'       => 'ASC',
			'numberposts' => - 1,
		];
		$general_tabs = get_posts( $args );
		foreach ( $general_tabs as $tab ) {
			if ( ! $this->check_tab( $tab ) ) {
				continue;
			}

			$main_instance = Main::instance();
			$main_instance->enqueue_dependencies( $tab );

			$block_frontend_instance = Block_Frontend::instance();
			$block_frontend_instance->enqueue_styles( $tab->ID, true );
		}
	}

	/**
	 * Check if tab has required properties and if it's visible.
	 *
	 * @param object $tab Tab post.
	 */
	private function check_tab( $tab ) {
		if ( ! $tab instanceof \WP_Post ) {
			return false;
		}

		return get_post_meta( $tab->ID, 'nv_tab_visibility', true ) !== 'no';
	}

	/**
	 * Display when the product does not have custom data.
	 *
	 * @param array $tabs Tabs array.
	 *
	 * @return array
	 */
	private function get_global_tabs( $tabs ) {

		$args = [
			'post_type'   => 'neve_product_tabs',
			'post_status' => 'publish',
			'orderby'     => 'menu_order title',
			'order'       => 'ASC',
			'numberposts' => - 1,
		];

		$general_tabs = get_posts( $args );
		$new_tab_data = [];
		foreach ( $general_tabs as $tab ) {
			if ( ! $this->check_tab( $tab ) ) {
				continue;
			}

			global $product;
			$product_id = $product->get_id();

			$product_categories = $this->get_categories( $product_id );
			$tab_categories     = $this->get_categories( $tab->ID );

			if ( ! empty( $tab_categories ) && empty( array_intersect( $tab_categories, $product_categories ) ) ) {
				continue;
			}

			$post_name   = $tab->post_name;
			$menu_oreder = $tab->menu_order;
			$title       = ! empty( $tab->post_title ) ? $tab->post_title : esc_html__( '(no title)', 'neve' );
			$is_core_tab = $this->is_core_tab( $tab->ID );

			if ( $is_core_tab && array_key_exists( $post_name, $tabs ) ) {
				$new_tab_data[ $post_name ] = [
					'title'    => $tabs[ $post_name ]['title'],
					'priority' => $menu_oreder,
					'callback' => $tabs[ $post_name ]['callback'],
				];
			}

			if ( ! $is_core_tab ) {
				$new_tab_data[ $post_name ] = [
					'title'    => $title,
					'priority' => $menu_oreder,
					'callback' => function() use ( $tab ) {
						echo wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $tab->ID ) ) );
					},
				];
			}
		}

		return $new_tab_data;
	}

	/**
	 * Get product categories asociated with a post id.
	 *
	 * @param int $id Post id.
	 *
	 * @return array
	 */
	private function get_categories( $id ) {
		$categories_terms = get_the_terms( $id, 'product_cat' );
		return empty( $categories_terms ) ? [] : array_map(
			function( $term ) {
				return $term->slug;
			},
			$categories_terms
		);
	}

	/**
	 * Display when the product have custom data.
	 *
	 * @param array $data Custom tabs array.
	 * @param array $tabs Tabs array.
	 *
	 * @return array
	 */
	private function get_specific_tabs( $data, $tabs ) {
		$new_tab_data = [];

		if ( empty( $data ) ) {
			return $new_tab_data;
		}

		foreach ( $data as $index => $tab ) {
			if ( ! array_key_exists( 'type', $tab ) ) {
				continue;
			}
			if ( ! array_key_exists( 'slug', $tab ) ) {
				continue;
			}

			if ( $tab['type'] === 'core' ) {
				if ( ! array_key_exists( $tab['slug'], $tabs ) ) {
					continue;
				}
				$new_tab_data[ $tab['slug'] ]             = $tabs[ $tab['slug'] ];
				$new_tab_data[ $tab['slug'] ]['priority'] = $index;
			}

			$title = ! empty( $tab['title'] ) ? $tab['title'] : esc_html__( '(no title)', 'neve' );
			if ( $tab['type'] === 'global' ) {
				$new_tab_data[ $tab['slug'] ] = [
					'title'    => $title,
					'priority' => $index,
					'callback' => [ $this, 'render_global_tab' ],
				];
			}

			if ( $tab['type'] === 'custom' ) {
				$new_tab_data[ $tab['slug'] ] = [
					'title'    => $title,
					'priority' => $index,
					'callback' => function() use ( $tab ) {
						if ( ! array_key_exists( 'content', $tab ) ) {
							return;
						}
						echo wp_kses_post( apply_filters( 'the_content', force_balance_tags( base64_decode( $tab['content'] ) ) ) );
					},
				];
			}
		}

		return $new_tab_data;
	}

	/**
	 * Wrapper fuction that wich tabs data to be used (global or post meta)
	 *
	 * @param array $tabs Tabs array.
	 */
	public function manage_product_tabs( $tabs ) {
		global $product;

		// Obtain 3rd party tabs and add them at the end.
		$other_tabs = $tabs;
		$core_tabs  = [ 'description', 'reviews', 'additional_information' ];
		foreach ( $core_tabs as $core_tab ) {
			if ( array_key_exists( $core_tab, $other_tabs ) ) {
				unset( $other_tabs[ $core_tab ] );
			}
		}

		$product_id           = $product->get_id();
		$specific_tab_enabled = get_post_meta( $product_id, 'neve_override_tab_layout', true );
		if ( empty( $specific_tab_enabled ) || $specific_tab_enabled === 'no' ) {
			return array_merge( $this->get_global_tabs( $tabs ), $other_tabs );
		}

		$specific_tab_data = get_post_meta( $product_id, 'neve_tabs_data', true );
		if ( $specific_tab_data === '' ) {
			return array_merge( $this->get_global_tabs( $tabs ), $other_tabs );

		}

		$specific_tab_data = json_decode( $specific_tab_data, true );
		return $this->get_specific_tabs( $specific_tab_data, $tabs );
	}

	/**
	 * Render function for global tabs.
	 *
	 * @param string $tab_name Tab name.
	 */
	public function render_global_tab( $tab_name ) {
		$tab = function_exists( 'wpcom_vip_get_page_by_path' )
			? wpcom_vip_get_page_by_path( $tab_name, OBJECT, 'neve_product_tabs' )
			: get_page_by_path( $tab_name, OBJECT, 'neve_product_tabs' ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_page_by_path_get_page_by_path
		if ( ! $tab instanceof \WP_Post ) {
			return;
		}
		$content = get_the_content( null, false, $tab );
		echo apply_filters( 'the_content', $content ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
