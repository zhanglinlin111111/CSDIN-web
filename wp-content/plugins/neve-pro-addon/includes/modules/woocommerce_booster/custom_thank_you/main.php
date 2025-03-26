<?php
/**
 * WooCommerce Custom Thank You
 * 
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You;

use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Query;
use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Admin_Category;
use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Admin_Product;
use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Router;

/**
 * The class provides Custom Thank You Page feature to make customizable the WooCommerce Checkout
 */
class Main {
	const MODULE_DEFAULT_ACTIVATION_STATUS = false;
	/**
	 * The slug of the custom thank you page posts
	 *
	 * @var string
	 */
	const CUSTOM_THANK_YOU_CPT = 'neve_thank_you';

	/**
	 * Initialization method
	 *
	 * @return void
	 */
	public function init() {
		$this->register_custom_post_type();
		
		( new Frontend() )->init();
		( new Admin_Product() )->init();
		( new Admin_Category() )->init();

		add_filter( 'woocommerce_taxonomy_objects_product_cat', array( $this, 'add_product_category_support_to_thank_you_edit' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_meta_box_editor_assets' ) );
		add_action( 'admin_footer', array( $this, 'add_admin_inline_script' ) );

		add_action( 'init', array( $this, 'register_order_details_block' ) );
		add_action( 'init', array( $this, 'register_meta_box_for_shipping_payment_match' ) );

		add_filter( 'neve_custom_layout_evaluated_condition_page', array( $this, 'disable_checkout_page_custom_layout' ), 10, 3 );
	}
	
	/**
	 * If there is an custom layout that created for checkout page, disable it for the thank you page.
	 *
	 * @param  bool  $evaluated current evaluation value.
	 * @param  int   $post_id current post ID.
	 * @param  array $condition registered condition details.
	 * @return bool
	 */
	public function disable_checkout_page_custom_layout( $evaluated, $post_id, $condition ) {
		if ( $evaluated !== true || empty( get_query_var( 'order-received' ) ) ) {
			return $evaluated;
		}

		return false;
	}

	/**
	 * The method decides if the given custom thank you page is general (has not any restriction)?
	 *
	 * @param  \WP_Post $post that custom thank you page post.
	 * @return bool
	 */
	public static function is_ty_page_general( \WP_Post $post ) {
		// if the thank you page has payment gateway restriction, skip.
		$supported_payment_gateways = get_post_meta( $post->ID, 'nv_ty_payment_gateways', true );
		if ( is_array( $supported_payment_gateways ) && ! empty( $supported_payment_gateways ) ) {
			return false;
		}

		// if the thank you page has shipping method restriction, skip.
		$supported_shipping_methods = get_post_meta( $post->ID, 'nv_ty_shipping_methods', true );
		if ( is_array( $supported_shipping_methods ) && ! empty( $supported_shipping_methods ) ) {
			return false;
		}

		// if the thank you page has product category restriction, skip.
		$supported_product_categories = get_the_terms( $post, 'product_cat' );
		if ( is_array( $supported_product_categories ) && ! empty( $supported_product_categories ) ) {
			return false;
		}

		// if the thank you page has product restriction, skip.
		if ( Query::has_ty_page_contains_product_restriction( $post->ID ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Add inline admin scripts
	 *
	 * @return void
	 */
	public function add_admin_inline_script() {
		if ( ! in_array( get_current_screen()->id, array( 'product', 'edit-product_cat' ), true ) ) {
			return;
		}

		echo '<script>jQuery(document).ready(function(e){e(".nv-thank-you-select").selectWoo()});</script>';
	}

	/**
	 * Get template files where in the modules/woocommerce_booster/custom_thank_you/templates/
	 *
	 * @param  string $template_slug that template file name with file extension.
	 * @param  array  $vars dynamic variables to pass them to templates.
	 * @return void|false
	 */
	public static function get_template( $template_slug, $vars = array() ) {
		if ( empty( $template_slug ) ) {
			return false;
		}

		if ( ! is_array( $vars ) ) {
			$vars = array();
		}

		$path = trailingslashit( NEVE_PRO_SPL_ROOT . 'modules/woocommerce_booster/custom_thank_you/templates/' ) . $template_slug;

		if ( ! is_file( $path ) ) {
			return false;
		}

		// to able use array keys of the $vars as a variable in template files.
		extract( $vars );

		include $path;
	}

	/**
	 * Register block post metas
	 *
	 * @return void
	 */
	public function register_meta_box_for_shipping_payment_match() {
		register_post_meta(
			self::CUSTOM_THANK_YOU_CPT,
			'nv_ty_payment_gateways',
			array(
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type' => 'string',
						),
					),
				),
				'single'       => true,
				'type'         => 'array',
			) 
		);

		register_post_meta(
			self::CUSTOM_THANK_YOU_CPT,
			'nv_ty_shipping_methods',
			array(
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type' => 'number',
						),
					),
				),
				'single'       => true,
				'type'         => 'array',
			)
		);
	}

	/**
	 * Register the Gutenberg order details block and load assets
	 *
	 * @return void
	 */
	public function register_order_details_block() {
		register_block_type(
			'neve-pro-addon/neve-custom-thank-you',
			array(
				'api_version'     => 2,
				'attributes'      => array(
					'previewMode' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'orderId'     => array(
						'type'    => 'integer',
						'default' => 0,
					),
				),
				'render_callback' => array( $this, 'render_order_details_block' ),
			) 
		);
	}

	/**
	 * Render Gutenberg order details block
	 *
	 * @return string
	 */
	public function render_order_details_block( $attributes ) {
		if ( $attributes['previewMode'] === true && current_user_can( 'read_shop_order' ) ) {
			$order_id = $attributes['orderId'];

			if ( ! ( $order_id > 0 ) ) {
				return sprintf( '<div style="height:400px; background:#f3f3f3; display:flex; align-items: center; justify-content:center">%s</div>', esc_html__( 'In order to see the order details, please add the order ID in the input above.', 'neve' ) );
			}
		} else {
			$order = self::get_order_with_order_key_validation();

			if ( ! $order ) {
				return '';
			}

			$order_id = $order->get_id();
		}

		ob_start();
		require wc_locate_template( 'checkout/thankyou.php' );
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	/**
	 * Get Order by the URL (Order ID in the url path and key in the GET params.)
	 *
	 * @return \WC_Order|false
	 */
	public static function get_order_with_order_key_validation() {
		global $wp;

		if ( empty( get_query_var( 'order-received' ) ) ) {
			return false;
		}

		$order_id = absint( get_query_var( 'order-received' ) );

		$order_key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : false;

		if ( ! ( $order_id > 0 ) || empty( $order_key ) ) {
			return false;
		}

		$order = wc_get_order( $order_id );

		if ( ( ! $order ) || ( ! hash_equals( $order->get_order_key(), $order_key ) ) ) {
			return false;
		}

		return $order;
	}

	/**
	 * Register meta block editor assets
	 *
	 * @return void
	 */
	public function register_meta_box_editor_assets() {
		global $post_type;

		if ( $post_type !== self::CUSTOM_THANK_YOU_CPT ) {
			return;
		}

		$asset_file = include NEVE_PRO_SPL_ROOT . 'modules/woocommerce_booster/custom_thank_you/react/build/main.asset.php';

		wp_enqueue_script(
			'nv-cty',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/custom_thank_you/react/build/main.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		$localize_data = [
			'paymentGateway' => $this->get_mapped_payment_gateways_for_select_control(),
			'shipping'       => $this->get_mapped_shipping_methods_with_zone_prefix(),
		];

		wp_localize_script( 'nv-cty', 'nvCtyMetaOptions', $localize_data );
	}
	
	/**
	 * Get all shipping method titles with zone title prefixes.
	 *
	 * @return array
	 */
	private function get_mapped_shipping_methods_with_zone_prefix() {
		$shipping_zones = \WC_Shipping_Zones::get_zones();

		if ( empty( $shipping_zones ) ) {
			return array();
		}

		return call_user_func_array(
			'array_merge',
			array_map(
				function( $zone ) {
					$zone_name = $zone['zone_name'];

					return array_map(
						function( $shipping_method ) use ( $zone_name ) {
							return array(
								'label' => sprintf( '%s - %s', $zone_name, $shipping_method->get_title() ),
								'value' => $shipping_method->get_instance_id(),
							);
						},
						$zone['shipping_methods'] 
					);
				},
				$shipping_zones 
			) 
		);
	}

	/**
	 * Get mapped payment gateways to use in select control component
	 *
	 * @return array
	 */
	private function get_mapped_payment_gateways_for_select_control() {
		return array_map(
			function( $gateway_slug, $gateway_title ) {
				return array(
					'value' => $gateway_slug,
					'label' => $gateway_title,
				);
			},
			array_keys( $this->get_available_wc_payment_gateways() ),
			array_values( $this->get_available_wc_payment_gateways() ) 
		);
	}
	
	/**
	 * Get available payment gateway methods as array
	 *
	 * @return array
	 */
	private function get_available_wc_payment_gateways() {
		$available_gateways = ( new \WC_Payment_Gateways() )->get_available_payment_gateways();

		return wp_list_pluck( $available_gateways, 'title' );
	}
	
	/**
	 * Add product category support for the custom thank you post type.
	 *
	 * @param  array $post_types current supported post types.
	 * @return array
	 */
	public function add_product_category_support_to_thank_you_edit( $post_types ) {
		$post_types[] = self::CUSTOM_THANK_YOU_CPT;

		return $post_types;
	}

	/**
	 * Register the custom post type for thank you pages.
	 *
	 * @return void
	 */
	public function register_custom_post_type() {
		$labels = array(
			'name'          => esc_html_x( 'Thank You Pages', 'Post type general name', 'neve' ),
			'singular_name' => esc_html_x( 'Thank You Page', 'Post type general name', 'neve' ),
			'all_items'     => esc_html__( 'Thank You Pages', 'neve' ),
			'add_new_item'  => esc_html__( 'Add New Thank You Page', 'neve' ),
			'edit_item'     => esc_html__( 'Edit Thank You Page', 'neve' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => current_user_can( 'manage_options' ) ? 'woocommerce-marketing' : false,
			'rewrite'             => false,
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'page-attributes', 'custom-fields' ),
			'has_archive'         => false,
		);

		register_post_type( self::CUSTOM_THANK_YOU_CPT, $args );
	}
}
