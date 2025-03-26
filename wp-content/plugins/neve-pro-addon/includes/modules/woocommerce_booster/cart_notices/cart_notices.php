<?php
/**
 * Cart notices class.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Cart_Notices
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Cart_Notices;

use WP_Post;
use WP_Query;

/**
 * Class Cart_Notices
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Cart_Notices
 */
class Cart_Notices {


	/**
	 * Init function.
	 */
	public function init() {

		$this->register_custom_post_type();
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'init', array( $this, 'init_cart_notices_block' ) );
		add_action( 'wp', array( $this, 'cart_notices_render_locations' ) );
		add_filter( 'render_block', array( $this, 'maybe_render_magic_tags' ), 10, 2 );
		add_filter( 'render_block', array( $this, 'maybe_change_to_add_to_cart' ), 15, 2 );
		add_filter( 'render_block', array( $this, 'maybe_parse_shortcodes' ), 20, 2 );
		add_action( 'woocommerce_before_cart', array( $this, 'maybe_apply_discount_code' ) );

		global $wp_version;
		$allowed_block_hook = 'allowed_block_types';
		$block_editor_hook  = 'block_editor_settings';
		if ( version_compare( $wp_version, '5.8-rc1', '>=' ) ) {
			$allowed_block_hook = 'allowed_block_types_all';
			$block_editor_hook  = 'block_editor_settings_all';
		}

		add_filter( $allowed_block_hook, array( $this, 'cart_notices_allowed_block_types' ), 100, 2 );
		add_filter( $block_editor_hook, array( $this, 'remove_patterns' ), 100, 2 );
	}

	/**
	 * Cart notices render locations.
	 *
	 * @return bool
	 */
	public function cart_notices_render_locations() {
		if ( is_admin() ) {
			return false;
		}

		add_action(
			'woocommerce_before_checkout_form',
			function () {
				do_action( 'render_notice', 'checkout' );
			}
		);

		$cart_notice_hook = isset( WC()->cart ) && WC()->cart->get_cart_contents_count() > 0 ? 'woocommerce_before_cart_contents' : 'woocommerce_cart_is_empty';
		add_action(
			$cart_notice_hook,
			function () {
				do_action( 'render_notice', 'cart' );
			}
		);
		add_action(
			'woocommerce_before_main_content',
			function () {
				if ( is_product() ) {
					do_action( 'render_notice', 'single' );
				}

				if ( is_shop() ) {
					do_action( 'render_notice', 'shop' );
				}
			},
			20
		);

		add_action( 'render_notice', array( $this, 'render_cart_notices' ), 10, 1 );

		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_notice_seamless_add_to_cart' ) );

		return true;
	}

	/**
	 * Handles seamless add to cart for cart notices.
	 *
	 * @param array $fragments Cart fragments.
	 *
	 * @return array
	 */
	public function cart_notice_seamless_add_to_cart( $fragments ) {

		ob_start();
		$this->render_cart_notices( 'shop' );
		$shop_notice_markup = ob_get_contents();
		ob_end_clean();

		ob_start();
		$this->render_cart_notices( 'single' );
		$single_notice_markup = ob_get_contents();
		ob_end_clean();

		ob_start();
		$this->render_cart_notices( 'cart' );
		$cart_notice_markup = ob_get_contents();
		ob_end_clean();

		$fragments['div.nv-cn-container.shop']   = $shop_notice_markup;
		$fragments['div.nv-cn-container.single'] = $single_notice_markup;
		$fragments['div.nv-cn-container.cart']   = $cart_notice_markup;

		return $fragments;
	}

	/**
	 * Register Custom Layouts post type.
	 */
	private function register_custom_post_type() {
		$labels = array(
			'name'          => esc_html_x( 'Announcement Bars', 'Post type general name', 'neve' ),
			'singular_name' => esc_html_x( 'Announcement Bar', 'Post type singular name', 'neve' ),
			'search_items'  => esc_html__( 'Search Announcement Bars', 'neve' ),
			'all_items'     => esc_html__( 'Announcement Bars', 'neve' ),
			'edit_item'     => esc_html__( 'Edit Announcement Bar', 'neve' ),
			'view_item'     => esc_html__( 'View Announcement Bar', 'neve' ),
			'add_new'       => esc_html__( 'Add New', 'neve' ),
			'update_item'   => esc_html__( 'Update Announcement Bar', 'neve' ),
			'add_new_item'  => esc_html__( 'Add New', 'neve' ),
			'new_item_name' => esc_html__( 'New Announcement Bar Name', 'neve' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'can_export'          => true,
			'publicly_queryable'  => false,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'show_in_nav_menus'   => false,
			'rewrite'             => false,
			'query_var'           => false,
			'has_archive'         => false,
			'supports'            => array( 'title', 'editor', 'custom-fields' ),
			'show_in_menu'        => current_user_can( 'manage_options' ) ? 'woocommerce-marketing' : false,
		);

		register_post_type( 'neve_cart_notices', apply_filters( 'neve_cart_notices_post_type_args', $args ) );
	}

	/**
	 * Register meta data for neve_cart_notices post type.
	 */
	public function register_meta() {
		$meta = array(
			'nv_cn_location_cart'            => array(
				'type' => 'boolean',
			),
			'nv_cn_location_checkout'        => array(
				'type' => 'boolean',
			),
			'nv_cn_location_single'          => array(
				'type' => 'boolean',
			),
			'nv_cn_location_shop'            => array(
				'type' => 'boolean',
			),
			'nv_cn_trigger'                  => array(
				'default' => 'no-trigger',
			),
			'nv_cn_trigger_amount_max'       => array(),
			'nv_cn_trigger_amount_min'       => array(),
			'nv_cn_trigger_amount_tax'       => array(
				'default' => 'yes',
			),
			'nv_cn_trigger_product_include'  => array(),
			'nv_cn_trigger_product_exclude'  => array(),
			'nv_cn_trigger_product_min_qty'  => array(),
			'nv_cn_trigger_product_max_qty'  => array(),
			'nv_cn_trigger_category_include' => array(),
			'nv_cn_trigger_category_exclude' => array(),
			'nv_cn_expiration_start'         => array(),
			'nv_cn_expiration_end'           => array(),
			'nv_cn_user_status'              => array(
				'default' => 'all',
			),
		);

		foreach ( $meta as $meta_id => $options ) {
			$type = array_key_exists( 'type', $options ) ? $options['type'] : 'string';

			$settings = array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => $type,
			);
			if ( array_key_exists( 'default', $options ) ) {
				$settings['default'] = $options['default'];
			}
			register_post_meta( 'neve_cart_notices', $meta_id, $settings );
		}
	}

	/**
	 * Initialize cart notices WordPress editor block.
	 *
	 * @return bool
	 */
	public function init_cart_notices_block() {

		if ( $this->get_current_post_type() !== 'neve_cart_notices' && is_admin() ) {
			return false;
		}

		$asset_file = include plugin_dir_path( __FILE__ ) . 'react/build/cart-notices-block.asset.php';
		wp_register_script(
			'nv-cn-editor-script',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/cart_notices/react/build/cart-notices-block.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		wp_localize_script(
			'nv-cn-editor-script',
			'neveCartNotice',
			array(
				'magicTagInfoImage' => NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/cart_notices/img/magictag.png',
			)
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'nv-cn-editor-script', 'neve' );
		}

		wp_register_style(
			'nv-cn-editor-style',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/cart_notices/css/editor-style.min.css',
			array(),
			NEVE_PRO_VERSION
		);
		wp_style_add_data( 'nv-cn-editor-style', 'rtl', 'replace' );
		wp_style_add_data( 'nv-cn-editor-style', 'suffix', '.min' );

		wp_register_style(
			'nv-cn-style',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/cart_notices/css/style.min.css',
			array(),
			NEVE_PRO_VERSION
		);
		wp_style_add_data( 'nv-cn-style', 'rtl', 'replace' );
		wp_style_add_data( 'nv-cn-style', 'suffix', '.min' );

		register_block_type(
			'neve-pro-addon/neve-cart-notices',
			array(
				'editor_style'  => 'nv-cn-editor-style',
				'editor_script' => 'nv-cn-editor-script',
				'style'         => 'nv-cn-style',
			)
		);

		return true;
	}

	/**
	 * Get current post type in admin.
	 *
	 * @return string
	 */
	private function get_current_post_type() {
		global $pagenow;

		$post_type = '';

		switch ( $pagenow ) {
			case 'post-new.php':
				$post_type = isset( $_REQUEST['post_type'] ) && post_type_exists( sanitize_key( $_REQUEST['post_type'] ) ) ? sanitize_key( $_REQUEST['post_type'] ) : $post_type;

				return $post_type;
			case 'post.php':
				$post_id = isset( $_GET['post'] ) ? sanitize_key( $_GET['post'] ) : ( isset( $_POST['post_ID'] ) ? (int) $_POST['post_ID'] : '' ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( $post_id ) {
					$post = get_post( $post_id );
					if ( ! ( $post instanceof \WP_Post ) ) {
						return $post_type;
					}
					$post_type = $post->post_type;
				}

				return $post_type;
			default:
				return $post_type;
		}
	}

	/**
	 * Limit the allowed blocks in this post type just to cart notice block.
	 *
	 * @param array                              $allowed_block_types Allowed block types.
	 * @param WP_Post | \WP_Block_Editor_Context $post Current post data.
	 *
	 * @return string[]
	 */
	public function cart_notices_allowed_block_types( $allowed_block_types, $post ) {

		$post_type = $this->get_post_type( $post );

		if ( 'neve_cart_notices' !== $post_type ) {
			return $allowed_block_types;
		}

		return array( 'neve-pro-addon/neve-cart-notices' );
	}

	/**
	 * Remove patterns tab.
	 * https://github.com/WordPress/gutenberg/issues/26667
	 *
	 * @param array                              $settings Patterns settings.
	 * @param WP_Post | \WP_Block_Editor_Context $post Current post data.
	 *
	 * @return mixed
	 */
	public function remove_patterns( $settings, $post ) {

		$post_type = $this->get_post_type( $post );

		if ( 'neve_cart_notices' !== $post_type ) {
			return $settings;
		}

		$settings['__experimentalBlockPatterns'] = array();

		return $settings;
	}

	/**
	 * Get the post type based on current data.
	 *
	 * @param WP_Post | \WP_Block_Editor_Context $data Current post data.
	 *
	 * @return string | false
	 */
	private function get_post_type( $data ) {
		$data_class = get_class( $data );
		if ( $data_class === 'WP_Block_Editor_Context' ) {
			$data = $data->post;
		}

		if ( empty( $data ) ) {
			return false;
		}

		if ( ! property_exists( $data, 'ID' ) ) {
			return false;
		}

		return get_post_type( $data->ID );
	}

	/**
	 * The render method for displaying the cart notice.
	 *
	 * @param string $location Notice location.
	 */
	public function render_cart_notices( $location ) {
		$args  = array(
			'post_type'      => 'neve_cart_notices',
			'posts_per_page' => 100,
			'meta_query'     => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => 'nv_cn_location_' . $location,
					'value' => true,
				),
			),
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			echo '<div class="nv-cn-container ' . esc_attr( $location ) . '">';
			while ( $query->have_posts() ) :
				$query->the_post();

				$notice_id = get_the_ID();
				if ( ! $this->should_display_notice( $notice_id ) ) {
					continue;
				}

				$this->get_cart_notice_content( $notice_id );
			endwhile;
			echo '</div>';
			wp_reset_postdata();
		}
	}

	/**
	 * Check if user settings pass.
	 *
	 * @param integer $notice_id Notice id.
	 *
	 * @return bool
	 */
	private function check_user_settings( $notice_id ) {
		$user_status = get_post_meta( $notice_id, 'nv_cn_user_status', true );
		if ( 'registered' === $user_status && ! is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if expiration settings pass.
	 *
	 * @param int $notice_id Notice id.
	 *
	 * @return bool
	 */
	private function check_expiration_settings( $notice_id ) {

		$time_now = current_time( 'mysql' );

		$expiration_start = get_post_meta( $notice_id, 'nv_cn_expiration_start', true );
		if ( ! empty( $expiration_start ) && strtotime( $time_now ) < strtotime( $expiration_start ) ) {
			return false;
		}

		$expiration_end = get_post_meta( $notice_id, 'nv_cn_expiration_end', true );
		if ( ! empty( $expiration_end ) && strtotime( $time_now ) > strtotime( $expiration_end ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if amount settings pass.
	 *
	 * @param int $notice_id Notice id.
	 *
	 * @return bool
	 */
	private function check_amount_settings( $notice_id ) {

		$trigger = get_post_meta( $notice_id, 'nv_cn_trigger', true );
		if ( 'amount' !== $trigger ) {
			return true;
		}

		$max = get_post_meta( $notice_id, 'nv_cn_trigger_amount_max', true );
		$min = get_post_meta( $notice_id, 'nv_cn_trigger_amount_min', true );

		if ( ! isset( $max ) && ! isset( $min ) ) {
			return false;
		}

		$tax        = get_post_meta( $notice_id, 'nv_cn_trigger_amount_tax', true );
		$cart_total = $this->get_cart_amount( $tax );

		if ( is_numeric( $max ) && floatval( $cart_total ) > $max ) {
			return false;
		}

		if ( is_numeric( $min ) && floatval( $cart_total ) < $min ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if product settings pass.
	 *
	 * @param int $notice_id Notice id.
	 *
	 * @return bool
	 */
	private function check_product_settings( $notice_id ) {

		$trigger = get_post_meta( $notice_id, 'nv_cn_trigger', true );
		if ( 'product' !== $trigger ) {
			return true;
		}

		$cart_content = $this->get_products_in_cart( WC()->cart->cart_contents );
		if ( empty( $cart_content ) ) {
			return false;
		}

		$cart_quantity  = WC()->cart->get_cart_contents_count();
		$should_include = json_decode( get_post_meta( $notice_id, 'nv_cn_trigger_product_include', true ) );
		$should_exclude = json_decode( get_post_meta( $notice_id, 'nv_cn_trigger_product_exclude', true ) );
		$min_qty        = get_post_meta( $notice_id, 'nv_cn_trigger_product_min_qty', true );
		$max_qty        = get_post_meta( $notice_id, 'nv_cn_trigger_product_max_qty', true );

		if ( empty( $should_exclude ) && empty( $should_include ) && empty( $min_qty ) && empty( $max_qty ) ) {
			return false;
		}

		if ( $this->check_include_exclude( 'nv_cn_trigger_product', $cart_content, $notice_id ) === false ) {
			return false;
		}

		if ( ! empty( $max_qty ) && $cart_quantity > floatval( $max_qty ) ) {
			return false;
		}

		if ( ! empty( $min_qty ) && $cart_quantity < floatval( $min_qty ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if category settings pass.
	 *
	 * @param integer $notice_id Notice id.
	 *
	 * @return bool
	 */
	private function check_category_settings( $notice_id ) {

		$trigger = get_post_meta( $notice_id, 'nv_cn_trigger', true );
		if ( 'category' !== $trigger ) {
			return true;
		}

		$cart_content       = $this->get_products_in_cart( WC()->cart->cart_contents );
		$categories_in_cart = $this->get_categories_in_cart( $cart_content );
		if ( empty( $categories_in_cart ) ) {
			return false;
		}

		if ( $this->check_include_exclude( 'nv_cn_trigger_category', $categories_in_cart, $notice_id ) === false ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if an array includes or not some items.
	 *
	 * @param string $slug_prefix Meta option slug prefix.
	 * @param array  $compare_array Array to search in.
	 * @param int    $notice_id Notice id.
	 *
	 * @return bool
	 */
	private function check_include_exclude( $slug_prefix, $compare_array, $notice_id ) {
		$should_include = json_decode( get_post_meta( $notice_id, $slug_prefix . '_include', true ) );
		$should_exclude = json_decode( get_post_meta( $notice_id, $slug_prefix . '_exclude', true ) );

		if ( 'nv_cn_trigger_product' !== $slug_prefix && empty( $should_exclude ) && empty( $should_include ) ) {
			return false;
		}

		if ( ! empty( $should_include ) ) {
			$should_include = array_column( $should_include, 'id' );
			if ( count( array_intersect( $should_include, $compare_array ) ) === 0 ) {
				return false;
			}
		}

		if ( ! empty( $should_exclude ) ) {
			$should_exclude = array_column( $should_exclude, 'id' );
			if ( count( array_intersect( $should_exclude, $compare_array ) ) > 0 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Checks the cart notice settings and decides if the notice should be rendered or not.
	 *
	 * @param int $notice_id Notice id.
	 *
	 * @return bool
	 */
	public function should_display_notice( $notice_id ) {

		if ( get_post_type( $notice_id ) !== 'neve_cart_notices' ) {
			return false;
		}

		if ( $this->check_user_settings( $notice_id ) === false ) {
			return false;
		}

		if ( $this->check_expiration_settings( $notice_id ) === false ) {
			return false;
		}

		if ( $this->check_amount_settings( $notice_id ) === false ) {
			return false;
		}

		if ( $this->check_product_settings( $notice_id ) === false ) {
			return false;
		}

		if ( $this->check_category_settings( $notice_id ) === false ) {
			return false;
		}

		return true;
	}

	/**
	 * Get total cart amount.
	 *
	 * @param string $tax Should include taxes.
	 *
	 * @return false|float
	 */
	private function get_cart_amount( $tax ) {
		if ( ! is_object( WC()->cart ) ) {
			return false;
		}
		$cart_total = WC()->cart->get_cart_contents_total();
		if ( 'yes' === $tax ) {
			$cart_total += $this->get_tax_total();
		}

		return $cart_total;
	}

	/**
	 * Get an array with all the category ids that products in cart have.
	 *
	 * @param array $cart_content Cart content.
	 *
	 * @return array
	 */
	private function get_categories_in_cart( $cart_content ) {
		$categories_in_cart = array();
		if ( empty( $cart_content ) ) {
			return $categories_in_cart;
		}

		foreach ( $cart_content as $product_id ) {
			$terms = get_the_terms( $product_id, 'product_cat' );
			if ( ! is_array( $terms ) ) {
				continue;
			}
			foreach ( $terms as $term ) {
				$categories_in_cart[] = $term->term_id;
			}
		}

		return $categories_in_cart;
	}

	/**
	 * Get an array with all the product ids in cart.
	 *
	 * @param array $cart_content Cart content.
	 *
	 * @return array
	 */
	private function get_products_in_cart( $cart_content ) {
		$products = array();
		foreach ( $cart_content as $product => $product_data ) {
			$products[] = $product_data['product_id'];
		}

		return $products;
	}

	/**
	 * Get total value of taxes.
	 *
	 * @return int|mixed
	 */
	private function get_tax_total() {
		$taxes = WC()->cart->get_taxes();
		$total = 0;
		if ( empty( $taxes ) ) {
			return $total;
		}
		foreach ( $taxes as $tax ) {
			$total += $tax;
		}

		return $total;
	}

	/**
	 * Get the content of a cart notice by its id.
	 *
	 * @param int $notice_id Notice id.
	 */
	private function get_cart_notice_content( $notice_id ) {
		$post   = get_post( $notice_id );
		$blocks = parse_blocks( $post->post_content );
		foreach ( $blocks as $block ) {
			if ( 'neve-pro-addon/neve-cart-notices' !== $block['blockName'] ) {
				continue;
			}
			echo render_block( $block ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Change the product url to add to cart url.
	 *
	 * @param string $block_content Notice markup.
	 * @param array  $block Block object.
	 *
	 * @return string
	 */
	public function maybe_change_to_add_to_cart( $block_content, $block ) {

		if ( 'neve-pro-addon/neve-cart-notices' !== $block['blockName'] ) {
			return $block_content;
		}

		if ( ! array_key_exists( 'innerBlocks', $block ) || empty( $block['innerBlocks'] ) ) {
			return $block_content;
		}

		$inner_block = $block['innerBlocks'][0];
		if ( ! array_key_exists( 'attrs', $inner_block ) || empty( $inner_block['attrs'] ) ) {
			return $block_content;
		}

		$is_auto_add_to_cart = array_key_exists( 'autoAddToCart', $inner_block['attrs'] ) && true === (bool) $inner_block['attrs']['autoAddToCart'];
		if ( ! $is_auto_add_to_cart ) {
			return $block_content;
		}

		// Get the link that needs to be replaced.
		preg_match_all( '/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $inner_block['innerHTML'], $old_url );
		if ( ! array_key_exists( 'href', $old_url ) ) {
			return $block_content;
		}
		$old_url = end( $old_url['href'] );
		if ( empty( $old_url ) ) {
			return $block_content;
		}

		$product_id = function_exists( 'wpcom_vip_url_to_postid' )
			? wpcom_vip_url_to_postid( $old_url )
			: url_to_postid( $old_url ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.url_to_postid_url_to_postid
		if ( empty( $product_id ) ) {
			return $block_content;
		}

		$product = wc_get_product( $product_id );
		if ( false === $product ) {
			return $block_content;
		}

		// Link that will replace the old one
		$new_link_url = add_query_arg( 'add-to-cart', $product_id, wc_get_cart_url() );

		$is_auto_discount = array_key_exists( 'autoApplyDiscount', $inner_block['attrs'] ) && true === (bool) $inner_block['attrs']['autoApplyDiscount'];
		if ( $is_auto_discount ) {
			$coupon = array_key_exists( 'discountCode', $inner_block['attrs'] ) ? $inner_block['attrs']['discountCode'] : '';
			if ( ! empty( $coupon ) ) {
				$new_link_url = add_query_arg( 'apply-discount', $coupon, $new_link_url );
			}
		}

		$new_link_url  = wp_nonce_url( $new_link_url, 'apply_coupon', 'coupon_nonce' );
		$block_content = str_replace( $old_url, $new_link_url, $block_content );
		return $block_content;
	}

	/**
	 * Run do_shortcode function for Cart notices block.
	 *
	 * @param string $block_content Notice markup.
	 * @param object $block Block object.
	 *
	 * @return string
	 */
	public function maybe_parse_shortcodes( $block_content, $block ) {
		if ( 'neve-pro-addon/neve-cart-notices' !== $block['blockName'] ) {
			return $block_content;
		}

		return do_shortcode( $block_content );
	}

	/**
	 * Get replacement string for magic tags.
	 *
	 * @param string $tag Magic tag.
	 * @param int    $notice_id Notice id.
	 *
	 * @return false|float|int|string
	 */
	private function get_replacement( $tag, $notice_id ) {

		$trigger = get_post_meta( $notice_id, 'nv_cn_trigger', true );
		switch ( $tag ) {
			case '{time_left}':
				$expiration_end_meta_val = get_post_meta( $notice_id, 'nv_cn_expiration_end', true );
				if ( empty( $expiration_end_meta_val ) ) {
					return false;
				}
				$end_date = strtotime( $expiration_end_meta_val );
				$time_now = strtotime( current_time( 'mysql' ) );

				return human_time_diff( $time_now, $end_date );

			case '{amount_left}':
				if ( 'amount' !== $trigger ) {
					return false;
				}

				$max = get_post_meta( $notice_id, 'nv_cn_trigger_amount_max', true );
				if ( empty( $max ) ) {
					return false;
				}
				$tax        = get_post_meta( $notice_id, 'nv_cn_trigger_amount_tax', true );
				$cart_total = $this->get_cart_amount( $tax );
				return $cart_total ? floatval( $max ) - floatval( $cart_total ) : false;

			case '{products_in_cart}':
				if ( 'product' !== $trigger ) {
					return false;
				}

				if ( ! is_object( WC()->cart ) ) {
					return false;
				}

				$products_in_cart = json_decode( get_post_meta( $notice_id, 'nv_cn_trigger_product_include', true ) );
				if ( empty( $products_in_cart ) ) {
					return false;
				}
				$products_in_cart = array_column( $products_in_cart, 'id' );

				$cart_content  = $this->get_products_in_cart( WC()->cart->cart_contents );
				$products      = array_intersect( $products_in_cart, $cart_content );
				$products_name = array();
				foreach ( $products as $id ) {
					$product         = wc_get_product( $id );
					$products_name[] = $product->get_title();
				}

				return implode( ', ', $products_name );

			case '{quantity_left}':
				if ( 'product' !== $trigger ) {
					return false;
				}

				$max_qty = get_post_meta( $notice_id, 'nv_cn_trigger_product_max_qty', true );
				if ( empty( $max_qty ) ) {
					return false;
				}

				if ( ! is_object( WC()->cart ) ) {
					return false;
				}

				$cart_quantity = WC()->cart->get_cart_contents_count();

				return floatval( $max_qty ) - $cart_quantity;

			case '{quantity_over}':
				if ( 'product' !== $trigger ) {
					return false;
				}

				$min_qty = get_post_meta( $notice_id, 'nv_cn_trigger_product_min_qty', true );
				if ( empty( $min_qty ) ) {
					return false;
				}

				if ( ! is_object( WC()->cart ) ) {
					return false;
				}

				$cart_quantity = WC()->cart->get_cart_contents_count();

				return $cart_quantity - intval( $min_qty );

			case '{categories_in_cart}':
				if ( 'category' !== $trigger ) {
					return false;
				}

				if ( ! is_object( WC()->cart ) ) {
					return false;
				}

				$should_include = json_decode( get_post_meta( $notice_id, 'nv_cn_trigger_category_include', true ) );
				if ( empty( $should_include ) ) {
					return false;
				}
				$should_include = array_column( $should_include, 'id' );

				$cart_content       = $this->get_products_in_cart( WC()->cart->cart_contents );
				$categories_in_cart = $this->get_categories_in_cart( $cart_content );
				$products           = array_intersect( $should_include, $categories_in_cart );
				$categories_name    = array();
				foreach ( $products as $id ) {
					$term              = get_term_by( 'id', $id, 'product_cat', 'ARRAY_A' );
					$categories_name[] = $term['name'];
				}

				return implode( ', ', $categories_name );
			default:
				return false;
		}
	}

	/**
	 * Replace magic tags if they exist.
	 *
	 * @param string $block_content Cart notice content.
	 * @param array  $block         Block data.
	 *
	 * @return string
	 */
	public function maybe_render_magic_tags( $block_content, $block ) {
		if ( 'neve-pro-addon/neve-cart-notices' !== $block['blockName'] ) {
			return $block_content;
		}

		global $post;

		$available_tags = array(
			'{time_left}',
			'{amount_left}',
			'{products_in_cart}',
			'{quantity_left}',
			'{quantity_over}',
			'{categories_in_cart}',
		);

		foreach ( $available_tags as $tag ) {
			if ( ! strpos( $block_content, $tag ) ) {
				continue;
			}

			$replacement = $this->get_replacement( $tag, $post->ID );
			if ( false !== $replacement ) {
				$block_content = str_replace( $tag, $replacement, $block_content );
			}
		}

		return $block_content;
	}

	/**
	 * Check if coupon should be applied and apply it.
	 *
	 * @return bool
	 */
	public function maybe_apply_discount_code() {
		if ( ! isset( $_GET['coupon_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['coupon_nonce'] ), 'apply_coupon' ) ) {
			return false;
		}

		if ( ! isset( $_GET['apply-discount'] ) ) {
			return false;
		}

		$coupon_code = wc_sanitize_coupon_code( $_GET['apply-discount'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( ! WC()->cart->has_discount( $coupon_code ) ) {
			WC()->cart->apply_coupon( $coupon_code );
		}

		return true;
	}
}
