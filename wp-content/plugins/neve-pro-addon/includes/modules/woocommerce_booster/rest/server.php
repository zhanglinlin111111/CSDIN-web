<?php
/**
 * Rest Endpoints Handler.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Rest
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Rest;

use Neve_Pro\Modules\Woocommerce_Booster\Views\Quick_View;
use Neve_Pro\Modules\Woocommerce_Booster\Views\Shop_Product;
use Neve_Pro\Modules\Woocommerce_Booster\Views\Single_Product;
use Neve_Pro\Modules\Woocommerce_Booster\Views\Wish_List;

/**
 * Class Server
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Rest
 */
class Server {
	/**
	 * Wish list class instance.
	 *
	 * @var Wish_List
	 */
	private $wish_list_instance;

	/**
	 *  Quick View class instance.
	 *
	 * @var Quick_View
	 */
	private $quick_view_instance;

	/**
	 *  Shop Product class instance.
	 *
	 * @var Shop_Product
	 */
	private $shop_product_instance;

	/**
	 * Server constructor.
	 */
	public function __construct() {
		$this->wish_list_instance = new Wish_List();

		$this->quick_view_instance = new Quick_View();

		$this->shop_product_instance = new Shop_Product();
	}

	/**
	 * Initialize the rest functionality.
	 */
	public function register_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );

		add_filter( 'rest_request_before_callbacks', array( $this, 'load_cart_before_wishlist_rendering' ), 10, 2 );
	}

	/**
	 * Load WC Cart before Wishlist rendering.
	 *
	 * @param  WP_REST_Response|WP_HTTP_Response|WP_Error|mixed $response That current response of the callback.
	 * @return WP_REST_Response|WP_HTTP_Response|WP_Error|mixed
	 */
	public function load_cart_before_wishlist_rendering( $response, $handler ) {
		// run only for get_products callback.
		if ( is_array( $handler['callback'] ) && $handler['callback'] === array( $this, 'get_product' ) ) {
			// For Only Quick View: load WC cart to initialize the Session. (Normally, it's initialized by WC for is_request is frontend. This REST API call requires manual load Session class. )
			wc_load_cart();
		}

		return $response;
	}

	/**
	 * Register endpoints.
	 */
	public function register_endpoints() {
		/**
		 * Quick View endpoint.
		 */
		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/products/post/(?P<product_id>\d+)/',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_product' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'product_id' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_numeric( $param );
						},
					),
				),
			)
		);

		/**
		 * Wish List update endpoint.
		 */
		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/update_wishlist/',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_wishlist' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);
	}

	/**
	 * Get quick view content.
	 *
	 * @param \WP_REST_Request $request the request.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_product( \WP_REST_Request $request ) {
		if ( empty( $request['product_id'] ) ) {
			return new \WP_REST_Response(
				array(
					'code'    => 'error',
					'message' => __( 'Quick View modal error: Product id is missing.', 'neve' ),
					'markup'  => '<p class="request-notice">' . __( 'Something went wrong while displaying the product.', 'neve' ) . '</p>',
				),
				200
			);
		}

		$product_id = intval( $request['product_id'] );

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'post__in'       => array( $product_id ),
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();
				$this->run_markup_changes( $product_id );
				echo '<div class="woocommerce single product">';
				echo '<div id="product-' . esc_attr( (string) $product_id ) . '" class="' . esc_attr( join( ' ', get_post_class( 'product', $product_id ) ) ) . '">';
				woocommerce_show_product_sale_flash();
				echo '<div class="nv-qv-gallery-wrap">';
				$this->render_gallery( $product_id );
				echo '</div>';
				echo '<div class="summary entry-summary">';
				echo '<div class="summary-content">';
				do_action( 'woocommerce_single_product_summary' );
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			$markup = ob_get_clean();
			$markup = str_replace( 'href="#reviews"', 'href="' . esc_url( get_permalink( $product_id ) ) . '#reviews"', $markup );

			return new \WP_REST_Response(
				array(
					'code'   => 'success',
					'markup' => $markup,
				),
				200
			);
		}

		return new \WP_REST_Response(
			array(
				'code'    => 'error',
				'message' => __( 'Quick View modal error: Product id is missing.', 'neve' ),
				'markup'  => '<p class="request-notice">' . __( 'Something went wrong while displaying the product.', 'neve' ) . '</p>',
			),
			400
		);
	}

	/**
	 * Run markup changes needed.
	 *
	 * @param int $product_id the product id.
	 */
	private function run_markup_changes( $product_id ) {
		// Run single product changes.
		$single = new Single_Product();
		$single->run();

		// Hook in the add to cart button as it's not always available.
		// [depends on hook priority which is not foreseeable]
		$product = wc_get_product( $product_id );
		if ( $product->get_type() === 'variable' ) {
			if ( ! has_action( 'woocommerce_single_variation', 'woocommerce_single_variation' ) ) {
				add_action( 'woocommerce_single_variation', 'woocommerce_single_variation' );
			}
			if ( ! has_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button' ) ) {
				add_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button' );
			}
		}
		add_action( 'woocommerce_' . $product->get_type() . '_add_to_cart', 'woocommerce_' . $product->get_type() . '_add_to_cart', 30 );
		add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

		// Wrap buttons.
		add_action(
			'woocommerce_' . $product->get_type() . '_add_to_cart',
			function () use ( $product ) {
				echo '<div class="qv-actions ' . esc_attr( $product->get_type() ) . '">';
			},
			29
		);

		// Add more details and close wrap
		$more_details_hook = $product->get_type() === 'variable' ? 'woocommerce_after_single_variation' : 'woocommerce_after_add_to_cart_button';
		add_action(
			$more_details_hook,
			function () use ( $product ) {
				echo '<a class="button button-secondary more-details" href="' . esc_url( $product->get_permalink() ) . '">' . esc_html__( 'More Details', 'neve' ) . '</a></div>';
			},
			31
		);

		// Remove quantity
		add_filter( 'woocommerce_is_sold_individually', '__return_true', 10, 2 );
	}

	/**
	 * Render the product gallery.
	 *
	 * @param int $product_id the product id.
	 */
	private function render_gallery( $product_id ) {
		
		$product = wc_get_product( $product_id );

		if ( empty( $product ) ) {
			echo '<div class="nv-slider-gallery">';
				echo '<div><p>"' . esc_html__( 'Product Unavailable', 'neve' ) . '"</div>';
			echo '</div>';
			return;
		}

		$attachment_ids = array();

		$product_thumbnail = get_post_thumbnail_id( $product_id );
		$attachment_ids[]  = ! empty( $product_thumbnail ) ? $product_thumbnail : 'placeholder';

		$gallery_image_ids = $product->get_gallery_image_ids();
		if ( ! empty( $gallery_image_ids ) ) {
			$attachment_ids = array_merge( $attachment_ids, $gallery_image_ids );
		}

		if ( $product->is_type( 'variable' ) ) {
			/** 
			 * WooCommerce Product Class.
			 * 
			 * @var object $product WC_Product
			*/
			$variations = $product->get_available_variations();

			foreach ( $variations as $variation ) {
				$attachment_ids[] = $variation['image_id'];
			}       
		}

		$attachment_ids = array_unique( $attachment_ids );

		$full_images = array();
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id === 'placeholder' && function_exists( 'wc_placeholder_img_src' ) ) {
				$full_images[] = wc_placeholder_img_src( 'woocommerce_single' );
			}

			if ( is_numeric( $attachment_id ) ) {
				$full_images[] = wp_get_attachment_image_url( $attachment_id, 'full' );
			}
		}

		echo '<div class="nv-slider-gallery">';
		foreach ( $full_images as $index => $url ) {
			echo '<img data-slide="' . esc_attr( (string) $index ) . '" src="' . esc_url( $url ) . '"/>';
		}
		echo '</div>';
		
		/**
		 *  Only show arrows if there is more than one image in the gallery.
		 */
		if ( (int) count( $full_images ) > 1 ) {
			echo neve_kses_svg( $this->get_gallery_arrows() );
		}
		
	}

	/**
	 * Get the gallery arrows markup.
	 *
	 * @return string
	 */
	private function get_gallery_arrows() {
		$arrow_map = [
			'left'  => '<svg width="25px" height="30px" viewBox="0 0 50 80"><polyline fill="none" stroke="currentColor" stroke-width="7" points="25,76 10,38 25,0"/></svg>',
			'right' => '<svg width="25px" height="30px" viewBox="0 0 50 80"><polyline fill="none" stroke="currentColor" stroke-width="7" points="25,0 40,38 25,75"/></svg>',
		];
		$markup    = '';

		$markup .= '<div class="nv-slider-controls">';
		$markup .= '<span aria-label="' . __( 'Previous image', 'neve' ) . '" class="prev">';
		$markup .= $arrow_map['left'];
		$markup .= '</span>';
		$markup .= '<span aria-label="' . __( 'Next image', 'neve' ) . '" class="next">';
		$markup .= $arrow_map['right'];
		$markup .= '</span>';
		$markup .= '</div>';

		return $markup;
	}

	/**
	 * Update the wishlist.
	 *
	 * @param \WP_REST_Request $request the rest request.
	 *
	 * @return \WP_REST_Response
	 */
	public function update_wishlist( \WP_REST_Request $request ) {
		$user_id       = get_current_user_id();
		$data          = $request->get_json_params() ? $request->get_json_params() : array();
		$current_value = $this->wish_list_instance->get_meta_wishlist_array( $user_id );

		if ( is_array( $current_value ) && ! empty( $current_value ) ) {
			$data = array_replace( $current_value, $data );
		}

		$data = array_filter( $data );

		if ( count( $data ) >= 50 ) {
			$first_element = array_keys( $data );
			unset( $data[ $first_element[0] ] );
		}

		update_user_meta( $user_id, 'wish_list_products', wp_json_encode( $data ) );

		return new \WP_REST_Response(
			array(
				'code'    => 'success',
				'message' => esc_html__( 'Wishlist updated', 'neve' ),
				'data'    => $data,
			)
		);
	}
}
