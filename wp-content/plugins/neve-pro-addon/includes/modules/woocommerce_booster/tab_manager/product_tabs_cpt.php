<?php
/**
 * Handles the product tabs custom post type.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager;
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager;

/**
 * Class Product_Tabs_Cpt
 */
class Product_Tabs_Cpt {
	use Utilities;

	/**
	 * Init function.
	 */
	public function init() {

		/**
		 * Actions related to cpt.
		 */
		add_action( 'init', [ $this, 'register_product_tabs_cpt' ], 5 );
		add_filter(
			'woocommerce_taxonomy_objects_product_cat',
			function ( $post_types ) {
				$post_types[] = 'neve_product_tabs';
				return $post_types;
			}
		);
		add_filter( 'bulk_actions-edit-neve_product_tabs', '__return_empty_array' );
		add_filter( 'months_dropdown_results', [ $this, 'remove_months_filter' ] );
		add_action( 'save_post', [ $this, 'insert_menu_order' ] );
		add_action( 'admin_init', [ $this, 'register_default_tabs' ] );
		add_action( 'admin_init', [ $this, 'disable_core_tabs_editable_data' ] );
		add_action( 'edit_post', [ $this, 'restrict_core_tabs_deletion' ] );
		add_action( 'before_edit_post', [ $this, 'restrict_core_tabs_deletion' ] );
		add_action( 'wp_trash_post', [ $this, 'restrict_core_tabs_deletion' ] );
		add_action( 'before_delete_post', [ $this, 'restrict_core_tabs_deletion' ] );
		add_filter( 'post_row_actions', [ $this, 'remove_core_tabs_row_actions' ], 10, 2 );
		add_filter( 'pre_get_posts', [ $this, 'order_post_type' ] );

		/**
		 * Run actions inside neve_product_tabs cpt edit screen.
		 */
		add_action( 'current_screen', [ $this, 'run_product_tabs_edit_screen_actions' ] );
		add_action( 'check_ajax_referer', [ $this, 'run_product_tabs_edit_screen_actions' ] );

		/**
		 * Actions related to the sorting tab
		 */
		add_filter( 'views_edit-neve_product_tabs', [ $this, 'add_sorting_tab' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_sorting_scripts' ] );
		add_action( 'rest_api_init', [ $this, 'register_tabs_routes' ] );
	}

	/**
	 * Register Custom Layouts post type.
	 */
	public function register_product_tabs_cpt() {
		$labels = [
			'name'          => esc_html_x( 'Product Tabs', 'Post type general name', 'neve' ),
			'singular_name' => esc_html_x( 'Product Tab', 'Post type singular name', 'neve' ),
			'search_items'  => esc_html__( 'Search Product Tabs', 'neve' ),
			'all_items'     => esc_html__( 'Product Tabs', 'neve' ),
			'edit_item'     => esc_html__( 'Edit Product Tab', 'neve' ),
			'view_item'     => esc_html__( 'View Product Tab', 'neve' ),
			'add_new'       => esc_html__( 'Add New', 'neve' ),
			'update_item'   => esc_html__( 'Update Product Tab', 'neve' ),
			'add_new_item'  => esc_html__( 'Add New', 'neve' ),
			'new_item_name' => esc_html__( 'New Product Tab Name', 'neve' ),
		];

		$args = [
			'labels'              => $labels,
			'public'              => true,
			'can_export'          => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'rewrite'             => false,
			'query_var'           => false,
			'supports'            => [ 'title', 'editor' ],
			'show_in_menu'        => current_user_can( 'manage_options' ) ? 'woocommerce' : false,
			'has_archive'         => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
		];

		register_post_type( 'neve_product_tabs', apply_filters( 'neve_product_tabs_post_type_args', $args ) );
	}

	/**
	 * Remove months filter for product tabs cpt.
	 *
	 * @param array $months Months options in the dropdown.
	 *
	 * @return array
	 */
	public function remove_months_filter( $months ) {
		global $typenow;
		if ( $typenow === 'neve_product_tabs' ) {
			return array();
		}
		return $months;
	}

	/**
	 * Add menu order parameter when creating a new tab.
	 *
	 * @param int $post_id Post id.
	 */
	public function insert_menu_order( $post_id ) {
		if ( get_post_type( $post_id ) !== 'neve_product_tabs' ) {
			return;
		}

		global $pagenow;
		if ( ! in_array( $pagenow, [ 'post-new.php' ] ) ) {
			return;
		}

		remove_action( 'save_post', [ $this, 'insert_menu_order' ] );
		wp_update_post(
			array(
				'ID'         => $post_id,
				'menu_order' => $post_id,
			)
		);
		add_action( 'save_post', [ $this, 'insert_menu_order' ] );
	}

	/**
	 * Insert the default tabs into custom post type.
	 */
	public function register_default_tabs() {
		$should_insert_default_tabs = get_option( 'neve_pt_default_tabs', 'yes' );
		if ( $should_insert_default_tabs !== 'yes' ) {
			return;
		}

		$core_tabs = $this->get_core_tabs();
		array_map(
			function( $slug, $title ) {
				$new_tab = [
					'post_type'   => 'neve_product_tabs',
					'post_name'   => $slug,
					'post_title'  => $title,
					'post_status' => 'publish',
				];
				$post_id = wp_insert_post( $new_tab );
				wp_update_post(
					[
						'ID'         => $post_id,
						'menu_order' => $post_id,
					]
				);
			},
			array_keys( $core_tabs ),
			$core_tabs
		);
		update_option( 'neve_pt_default_tabs', 'no' );
	}

	/**
	 * Remove editable data for core tabs.
	 */
	public function disable_core_tabs_editable_data() {

		if ( isset( $_GET['post'] ) ) {
			$post_id = absint( $_GET['post'] );
		} elseif ( isset( $_POST['post_ID'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$post_id = absint( $_POST['post_ID'] ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		if ( ! isset( $post_id ) || empty( $post_id ) ) {
			return;
		}

		if ( $this->is_core_tab( $post_id ) ) {
			remove_post_type_support( 'neve_product_tabs', 'title' );
			remove_post_type_support( 'neve_product_tabs', 'editor' );
			remove_meta_box( 'product_catdiv', 'neve_product_tabs', 'side' );
		}
	}

	/**
	 * Restrict the deletion of core tabs.
	 */
	public function restrict_core_tabs_deletion() {
		global $post;
		if ( $post instanceof \WP_Post && $this->is_core_tab( $post->ID ) ) {
			do_action( 'admin_page_access_denied' );
			wp_die( esc_html__( 'You cannot modify or delete this entry.', 'neve' ) );
		}
	}

	/**
	 * Remove row actions for core tabs.
	 *
	 * @param array  $actions Available actions.
	 * @param object $post Current post.
	 *
	 * @return array
	 */
	public function remove_core_tabs_row_actions( $actions, $post ) {
		if ( $this->is_core_tab( $post->ID ) ) {
			return array();
		}
		return $actions;
	}

	/**
	 * Reorder tabs.
	 *
	 * @param \WP_Query $wp_query Current query.
	 *
	 * @return mixed
	 */
	public function order_post_type( $wp_query ) {

		if ( ! $wp_query instanceof \WP_Query ) {
			return $wp_query;
		}

		if ( ! $wp_query->is_admin ) {
			return $wp_query;
		}

		if ( $wp_query->query['post_type'] !== 'neve_product_tabs' ) {
			return $wp_query;
		}

		if ( 'menu_order title' === $wp_query->query['orderby'] ) {
			return $wp_query;
		}

		$wp_query->set( 'orderby', 'date' );
		$wp_query->set( 'order', 'ASC' );

		return $wp_query;
	}

	/**
	 * Run actions inside edit screen of neve_product_tabs cpt.
	 */
	public function run_product_tabs_edit_screen_actions() {
		$request_data = $_REQUEST;

		$screen_id = false;

		if ( function_exists( 'get_current_screen' ) ) {
			$screen    = get_current_screen();
			$screen_id = isset( $screen, $screen->id ) ? $screen->id : '';
		}

		if ( ! empty( $request_data['screen'] ) ) {
			$screen_id = wc_clean( wp_unslash( $request_data['screen'] ) );
		}

		if ( $screen_id === 'edit-neve_product_tabs' ) {
			add_filter( 'manage_neve_product_tabs_posts_columns', [ $this, 'define_cpt_columns' ] );
			add_action( 'manage_neve_product_tabs_posts_custom_column', [ $this, 'render_cpt_columns' ], 10, 2 );
			add_action( 'admin_head', [ $this, 'cpt_admin_style' ] );
			add_action( 'admin_notices', [ $this, 'order_update_error_notice' ] );
		}

		remove_action( 'current_screen', array( $this, 'run_product_tabs_edit_screen_actions' ) );
		remove_action( 'check_ajax_referer', array( $this, 'run_product_tabs_edit_screen_actions' ) );
	}

	/**
	 * Define columns for product tabs cpt.
	 *
	 * @param array $columns Existing columns.
	 *
	 * @return array
	 */
	public function define_cpt_columns( $columns ) {
		if ( empty( $columns ) || ! is_array( $columns ) ) {
			$columns = array();
		}

		unset( $columns['title'], $columns['comments'], $columns['date'], $columns['cb'] );

		$show_columns               = array();
		$show_columns['name']       = esc_html__( 'Name', 'neve' );
		$show_columns['tab_cat']    = esc_html__( 'Categories', 'neve' );
		$show_columns['visibility'] = esc_html__( 'Visibility', 'neve' );

		return array_merge( $show_columns, $columns );
	}

	/**
	 * Wrapper for rendering columns content.
	 *
	 * @param string $column Column ID to render.
	 * @param int    $post_id Post ID being shown.
	 */
	public function render_cpt_columns( $column, $post_id ) {
		if ( is_callable( [ $this, 'render_' . $column . '_column' ] ) ) {
			$this->{"render_{$column}_column"}();
		}
	}

	/**
	 * Style for the neve_product_tabs table.
	 */
	public function cpt_admin_style() {
		echo '<style>';
		echo 'table.wp-list-table .column-name{
			width: 65%
		}';
		echo 'table.wp-list-table .column-tab_cat{
			width: 25%
		}';
		echo '.ui-sortable-helper .column-visibility{
			width: 3%;
		}';
		echo '.ui-sortable-helper .column-visibility{
			width: 3%;
		}';
		echo '.striped>tbody>:nth-child(even){
			background-color: #fff;
		}';
		echo '</style>';
	}

	/**
	 * Notice in case sorting action fails.
	 */
	public function order_update_error_notice() {
		$class   = 'notice notice-error hidden nv-order-error';
		$message = esc_html__( 'An error has occurred. Please reload the page and try again.', 'neve' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
	/**
	 * Decide if the current screen is the sorting tab.
	 *
	 * @param \WP_Query | string $query_object Query object.
	 * @return bool
	 */
	private function is_edit_screen( $query_object = '' ) {
		if ( ! current_user_can( 'edit_others_pages' ) ) {
			return false;
		}

		global $wp_query;
		if ( empty( $query_object ) ) {
			$query_object = $wp_query;
		}

		if ( ! $query_object instanceof \WP_Query ) {
			return false;
		}

		if ( ! property_exists( $query_object, 'query' ) || ! isset( $query_object->query ) ) {
			return false;
		}

		if ( 'menu_order title' !== $query_object->query['orderby'] ) {
			return false;
		}

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		if ( $screen_id !== 'edit-neve_product_tabs' ) {
			return false;
		}

		return true;
	}

	/**
	 * Renders the Name column.
	 */
	private function render_name_column() {
		global $post;
		$edit_link = get_edit_post_link( $post->ID );
		$title     = _draft_or_post_title();
		echo '<strong>';
		echo $this->is_core_tab( $post->ID ) ? esc_html( $title ) : '<a class="row-title" href="' . esc_url( $edit_link ) . '">' . esc_html( $title ) . '</a>';
		_post_states( $post );
		echo '</strong>';
		get_inline_data( $post );
		echo '<span class="check-column"><input type="hidden" value="' . esc_attr( $post->ID ) . '"></span>';
	}

	/**
	 * Renders the Category column.
	 */
	private function render_tab_cat_column() {
		global $post;
		$terms = get_the_terms( $post->ID, 'product_cat' );
		if ( ! $terms ) {
			echo '<span class="na">&ndash;</span>';
		} else {
			$termlist = array();
			foreach ( $terms as $term ) {
				$termlist[] = '<a href="' . esc_url( admin_url( 'edit.php?product_cat=' . $term->slug . '&post_type=neve_product_tabs' ) ) . ' ">' . esc_html( $term->name ) . '</a>';
			}
			echo wp_kses_post( apply_filters( 'woocommerce_admin_product_term_list', implode( ', ', $termlist ), 'product_cat', $post->ID, $termlist, $terms ) );
		}
	}

	/**
	 * Renders the visibility column.
	 */
	private function render_visibility_column() {
		global $post;

		$style = '';
		if ( $this->is_edit_screen() ) {
			$style = 'cursor: pointer';
		}

		$visible = get_post_meta( $post->ID, 'nv_tab_visibility', true );
		if ( ! isset( $visible ) ) {
			$visible = true;
		}

		$icon = 'dashicons-' . ( $visible === 'no' ? 'hidden' : 'visibility' );
		echo wp_kses_post( '<span class="dashicons ' . esc_attr( $icon ) . '" ' . ( $style ? 'style="' . esc_attr( $style ) . '"' : '' ) . '></span>' );
	}

	/**
	 * Change views on the edit product tab screen.
	 *
	 * @param  array $views Array of views.
	 * @return array
	 */
	public function add_sorting_tab( $views ) {

		global $wp_query;

		unset( $views['mine'] );

		if ( current_user_can( 'edit_products' ) ) {
			$class            = ( isset( $wp_query->query['orderby'] ) && 'menu_order title' === $wp_query->query['orderby'] ) ? 'current' : '';
			$query_string     = remove_query_arg( array( 'orderby', 'order' ) );
			$query_string     = add_query_arg( 'orderby', rawurlencode( 'menu_order title' ), $query_string );
			$query_string     = add_query_arg( 'order', rawurlencode( 'ASC' ), $query_string );
			$query_string     = add_query_arg( 'post_status', 'publish', $query_string );
			$views['byorder'] = '<a href="' . esc_url( $query_string ) . '" class="' . esc_attr( $class ) . '">' . esc_html__( 'Sorting', 'neve' ) . '</a>';
		}

		return $views;
	}

	/**
	 * Load the sorting script.
	 */
	public function load_sorting_scripts() {
		if ( ! $this->is_edit_screen() ) {
			return;
		}

		wp_register_script(
			'nv-tm-script',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/tab_manager/assets/js/build/tab-manager-global.js',
			[],
			NEVE_PRO_VERSION,
			true
		);
		wp_localize_script(
			'nv-tm-script',
			'tmData',
			[
				'tabsEndpoint' => rest_url( NEVE_PRO_REST_NAMESPACE . '/neve_product_tabs' ),
				'nonce'        => wp_create_nonce( 'wp_rest' ),
			]
		);
		wp_enqueue_script( 'nv-tm-script' );
	}

	/**
	 * Register tabs REST routes.
	 */
	public function register_tabs_routes() {
		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/neve_product_tabs/update_tab_order',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'update_order' ],
				'permission_callback' => function() {
					return current_user_can( 'edit_products' );
				},
			]
		);

		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/neve_product_tabs/update_tab_visibility/(?P<tab_id>\d+)',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'update_visibility' ],
				'permission_callback' => function() {
					return current_user_can( 'edit_products' );
				},
				'args'                => array(
					'tab_id' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_numeric( $param );
						},
					),
				),
			]
		);
	}

	/**
	 * Function for saving product tabs ordering.
	 *
	 * @param \WP_REST_Request $request Request.
	 *
	 * @return \WP_REST_Response
	 */
	public function update_order( \WP_REST_Request $request ) {
		$fields = $request->get_json_params();

		if ( ! array_key_exists( 'id', $fields ) || ! array_key_exists( 'prevId', $fields ) || ! array_key_exists( 'nextId', $fields ) ) {
			return new \WP_REST_Response(
				array(
					'code'    => 'error',
					'message' => esc_html__( 'Missing parameter for sorting tabs.', 'neve' ),
				),
				400
			);
		}

		global $wpdb;

		$sorting_id = $fields['id'];
		$previd     = $fields['prevId'];
		$nextid     = $fields['nextId'];

		$menu_orders = wp_cache_get( 'neve_pt_menu_orders_cache' );
		if ( false === $menu_orders ) {
			$menu_orders = wp_list_pluck( $wpdb->get_results( "SELECT ID, menu_order FROM {$wpdb->posts} WHERE post_type = 'neve_product_tabs' ORDER BY menu_order ASC, post_title ASC" ), 'menu_order', 'ID' ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			wp_cache_set( 'neve_pt_menu_orders_cache', $menu_orders );
		}

		$index = 0;
		foreach ( $menu_orders as $id => $menu_order ) {
			$id = absint( $id );

			if ( $sorting_id === $id ) {
				continue;
			}
			if ( $nextid === $id ) {
				$index ++;
			}
			$index ++;
			$menu_orders[ $id ] = $index;

			// phpcs:ignore
			$wpdb->update( $wpdb->posts, array( 'menu_order' => $index ), array( 'ID' => $id ) );
		}

		if ( isset( $menu_orders[ $previd ] ) ) {
			$menu_orders[ $sorting_id ] = $menu_orders[ $previd ] + 1;
		} elseif ( isset( $menu_orders[ $nextid ] ) ) {
			$menu_orders[ $sorting_id ] = $menu_orders[ $nextid ] - 1;
		} else {
			$menu_orders[ $sorting_id ] = 0;
		}

		// phpcs:ignore
		$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_orders[ $sorting_id ] ), array( 'ID' => $sorting_id ) );
		return new \WP_REST_Response(
			array(
				'code'    => 'success',
				'message' => esc_html__( 'Tab order updated', 'neve' ),
				'data'    => $menu_orders,
			)
		);
	}

	/**
	 * Function for saving product tabs visibility.
	 *
	 * @param \WP_REST_Request $request Request.
	 *
	 * @return \WP_REST_Response
	 */
	public function update_visibility( \WP_REST_Request $request ) {
		$fields = $request->get_json_params();

		if ( empty( $request['tab_id'] ) ) {
			return new \WP_REST_Response(
				array(
					'success' => false,
					'message' => esc_html__( 'Not allowed.', 'neve' ),
				),
				200
			);
		}

		if ( ! array_key_exists( 'visibility', $fields ) ) {
			return new \WP_REST_Response(
				array(
					'code'    => 'error',
					'message' => esc_html__( 'Missing visibility value.', 'neve' ),
				),
				400
			);
		}

		update_post_meta( $request['tab_id'], 'nv_tab_visibility', $fields['visibility'] === true ? 'yes' : 'no' );
		return new \WP_REST_Response(
			array(
				'code'    => 'success',
				'message' => esc_html__( 'Tab visibility updated', 'neve' ),
			)
		);
	}
}
