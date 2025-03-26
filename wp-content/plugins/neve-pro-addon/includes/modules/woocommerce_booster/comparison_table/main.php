<?php
/**
 * Main class of the Comparison Table.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Options
 */
class Main {
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init_activation();

		add_action( 'wp', array( $this, 'init' ) );
	}

	/**
	 * Initialization of the Comparison Table.
	 *
	 * @return void
	 */
	public function init() {
		if ( neve_is_amp() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

		$this->register_views();

		add_filter( 'woocommerce_continue_shopping_redirect', array( $this, 'update_continue_shopping_redirect_url' ) );

		add_filter( 'woocommerce_create_pages', array( $this, 'add_comparison_table_page_to_wc_default_pages' ) );

		add_filter( 'display_post_states', array( $this, 'add_comparison_table_page_to_post_states' ), 10, 2 );

		add_action( 'woocommerce_page_created', array( $this, 'update_recreated_comparison_page_content_width' ), 10, 2 );
	}

	/**
	 * Update the comparison table page that created by WC tools content width
	 *
	 * @param  int   $page_id that created page id.
	 * @param  array $page_data that data of the created page.
	 * @return void
	 */
	public function update_recreated_comparison_page_content_width( $page_id, $page_data ) {
		if ( isset( $page_data['post_name'] ) && $page_data['post_name'] === _x( 'comparison-table', 'Page slug', 'neve' ) ) {
			( new \Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Activation() )->set_page_options( $page_id );
		}
	}
	
	/**
	 * Add "post state" to comparison table page in the admin page list.
	 * It specifiy "-Comparison Table" description on the page list.
	 *
	 * @param  array    $post_states That current post states.
	 * @param  \WP_Post $post That WP_Post object.
	 * @return array
	 */
	public function add_comparison_table_page_to_post_states( $post_states, $post ) {
		if ( wc_get_page_id( 'neve_comparison_table' ) === $post->ID ) {
			$post_states['neve_page_for_comparison_table'] = __( 'Comparison Table', 'neve' );
		}

		return $post_states;
	}
	
	/**
	 * Add Neve Comparison Table page to WC pages that will be created.
	 * 
	 * It's used for re-create of the deleted comparison table page.
	 *
	 * @param  array $pages That current array of the pages.
	 * @return array
	 */
	public function add_comparison_table_page_to_wc_default_pages( $pages ) {
		$pages['neve_comparison_table'] = array(
			'name'    => _x( 'comparison-table', 'Page slug', 'neve' ),
			'title'   => _x( 'Comparison Table', 'Page title', 'neve' ),
			'content' => '',
		);

		return $pages;
	}

	/**
	 * If the user coming by click the add to cart button in comparison table iframe, Update the Continue Shopping Redirect URL. (Redirect the user to parent window url of the comparison table.)
	 * This method updates the 'continue shopping' button that in the cart url.
	 *
	 * @param  string $current_target that current target url of the continue shopping button.
	 * @return string
	 */
	public function update_continue_shopping_redirect_url( $current_target ) {
		$url_parts = wp_parse_url( $current_target );

		// if the current target url is invalid, return to shop url for continue shopping url
		if ( ! isset( $url_parts['query'] ) ) {
			return get_permalink( wc_get_page_id( 'shop' ) );
		}

		parse_str( $url_parts['query'], $url_query );

		// find the parent window url of the iframe and return to this as continue shopping url
		if ( isset( $url_query['comparison-table-iframe'] ) && isset( $url_query['parent-window-url'] ) ) {
			return $url_query['parent-window-url'];
		}

		return $current_target;
	}

	/**
	 * Comparison Table Module Activation Processes.
	 *
	 * @return void
	 */
	public function init_activation() {
		new \Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Activation();
	}

	/**
	 * Load View Classes of the Comparison Table.
	 *
	 * @return void
	 */
	public function register_views() {
		$view_classes = array(
			'Table',
			'Sticky_Bar',
			'Single_Product',
			'Catalog',
		);

		foreach ( $view_classes as $view_class ) {
			$path = 'Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\View\\' . $view_class;
			new $path();
		}
	}

	/**
	 * Load Comparison Table Assets
	 *
	 * @return void|false
	 */
	public function register_assets() {
		$body_classes = apply_filters( 'body_class', array() );

		if ( ! in_array( 'nv-ct-enabled', $body_classes, true ) ) {
			return false;
		}

		$this->enqueue_assets();
	}

	/**
	 * Enqueue Style and Script
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style( 'nv-ct-style', NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/comparison_table/assets/css/style.min.css', array(), NEVE_PRO_VERSION );
		wp_enqueue_script( 'nv-ct-script', NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/comparison_table/assets/js/script.js', array(), NEVE_PRO_VERSION, true );
	}
}
