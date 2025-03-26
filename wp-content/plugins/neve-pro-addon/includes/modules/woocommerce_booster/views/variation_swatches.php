<?php
/**
 *  Class that add variation swatches functionalities.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

use Neve\Views\Base_View;
use WC_Product;

/**
 * Class Variation_Swatches
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Variation_Swatches extends Base_View {

	/**
	 * Initialize the module.
	 */
	public function init() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'define_public_hooks' ), 30 );
	}

	/**
	 * Check if the styles should enqueue.
	 *
	 * @return bool
	 */
	private function should_load() {

		$is_quick_view = get_theme_mod( 'neve_quick_view', 'none' ) !== 'none';
		if ( $this->is_product_archive() && ( $is_quick_view || $this->should_display_catalog_swatches() ) ) {
			return true;
		}

		$product_id = get_the_ID();
		$product    = wc_get_product( $product_id );
		if ( ! empty( $product ) && $product->is_type( 'variable' ) ) {
			return true;
		}

		$post_content = get_the_content();
		/**
		 * Filters for what shortcodes the variation swatches should load
		 *
		 * @since 2.0.3
		 * @param array $shortcodes The shortcodes array.
		 */
		$allowed_shortcodes = apply_filters( 'nv_vswatches_load_for_shortcodes', [ 'products', 'featured_products', 'sale_products', 'best_selling_products', 'recent_products', 'product_attribute', 'top_rated_products' ] );
		if ( is_singular() && $is_quick_view ) {
			foreach ( $allowed_shortcodes as $shortcode ) {
				if ( has_shortcode( $post_content, $shortcode ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if current page is shop or a product archive page/
	 *
	 * @return bool
	 */
	private function is_product_archive() {
		return ( is_shop() || is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) );
	}

	/**
	 * Enqueue public scripts.
	 */
	public function enqueue_scripts() {
		if ( ! $this->should_load() ) {
			return;
		}

		wp_register_style(
			'nv-vswatches-style',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/variation_swatches/css/style.min.css',
			array(),
			NEVE_PRO_VERSION
		);
		wp_style_add_data( 'nv-vswatches-style', 'rtl', 'replace' );
		wp_style_add_data( 'nv-vswatches-style', 'suffix', '.min' );

		wp_enqueue_style( 'nv-vswatches-style' );
	}

	/**
	 * Define public hooks.
	 */
	public function define_public_hooks() {
		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'swatches_display' ), 100, 2 );
		add_action( 'nv_shop_item_content_after', [ $this, 'render_catalog_swatches' ], 998 );
		add_filter( 'woocommerce_loop_add_to_cart_args', [ $this, 'add_to_cart_args' ], 20, 2 );
		add_filter( 'woocommerce_available_variation', [ $this, 'add_exta_variation_data' ], 100, 3 );
	}

	/**
	 * Add variation data to be able to see it in JS
	 *
	 * @param array  $variation Variation.
	 * @param object $product_object Product object.
	 * @param object $variation_object Variation object.
	 *
	 * @return array
	 */
	public function add_exta_variation_data( $variation, $product_object, $variation_object ) {
		$thumbnail_size = apply_filters( 'woocommerce_thumbnail_size', 'woocommerce_thumbnail' );

		if ( isset( $variation['image']['thumb_src'] ) && ! empty( $variation['image']['thumb_src'] ) ) {
			$variation['image']['thumb_srcset'] = wp_get_attachment_image_srcset( $variation_object->get_image_id(), $thumbnail_size );
			if ( $variation['image']['thumb_srcset'] === false ) {
				$variation['image']['thumb_srcset'] = $variation['image']['thumb_src'];
			}
			$variation['image']['thumb_sizes'] = wp_get_attachment_image_sizes( $variation_object->get_image_id(), $thumbnail_size );
		}
		return $variation;
	}

	/**
	 * Function that manages variation swatches display.
	 *
	 * @param string $html Swatches html code.
	 * @param array  $args Swatches arguments.
	 *
	 * @return string
	 */
	public function swatches_display( $html, $args ) {
		if ( ! $this->should_load() ) {
			return $html;
		}

		if ( ! array_key_exists( 'attribute', $args ) ) {
			return $html;
		}

		$type = $this->get_attribute_type( $args['attribute'] );
		if ( $type === false || $type === 'select' ) {
			return $html;
		}

		if ( in_array( $type, [ 'color', 'label', 'image' ], true ) ) {
			return $this->render_swatches( $html, $args, $type );
		}
		return $html;
	}

	/**
	 * Render variation swatches.
	 */
	private function render_swatches( $html, $args, $type ) {

		$options   = $args['options'];
		$attribute = $args['attribute'];
		$product   = $args['product'];
		$id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$markup    = '<div class="nv-variation-container">';
		$markup   .= $html;

		if ( empty( $options ) ) {
			return $html;
		}
		if ( empty( $product ) ) {
			return $html;
		}

		$terms   = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
		$markup .= '<ul class="nv-vswatches-wrapper variation-' . esc_attr( $type ) . '">';

		foreach ( $terms as $term ) {
			if ( ! in_array( $term->slug, $options, true ) ) {
				continue;
			}

			$term_value = get_term_meta( $term->term_id, 'product_' . $attribute, true );
			$name       = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );

			$item_classes   = array();
			$item_classes[] = sanitize_title( $args['selected'] ) === $term->slug ? 'nv-vswatch-active' : '';
			$item_classes[] = $type;
			$item_classes[] = empty( $term_value ) ? 'nv-vswatch-empty' : '';

			$markup .= '<li class="nv-vswatch-item ' . esc_attr( implode( ' ', $item_classes ) ) . '"  data-value="' . esc_attr( $term->slug ) . '" title="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( $id ) . '">';
			if ( $type === 'color' ) {
				$markup .= '<span class="nv-vswatch-overlay"></span>';
				if ( ! empty( $term_value ) ) {
					$markup .= '<span class="nv-vswatch-color" style="background-color: ' . esc_attr( $term_value ) . '"></span>';
				}
			}
			if ( $type === 'image' ) {
				$markup .= '<span class="nv-vswatch-overlay"></span>';
				if ( ! empty( $term_value ) ) {
					$markup .= '<img class="nv-vswatch-image" src="' . esc_url( $term_value ) . '">';
				}
			}
			if ( $type === 'label' ) {
				$term_value = empty( $term_value ) ? $name : $term_value;
				$markup    .= '<label class="nv-vswatch-label">' . wp_kses_post( $term_value ) . '</label>';
			}
			$markup .= '</li>';
		}
		$markup .= '</ul>';
		$markup .= '</div>';

		return $markup;
	}

	/**
	 * Get attribute type.
	 *
	 * @param string $attribute Attribute name.
	 *
	 * @return false | string
	 */
	private function get_attribute_type( $attribute ) {
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( ! taxonomy_exists( $attribute ) ) {
			return false;
		}

		$taxonomy_object = array_filter(
			$attribute_taxonomies,
			static function ( $taxonomy ) use ( $attribute ) {
				return $attribute === 'pa_' . $taxonomy->attribute_name;
			}
		);

		$taxonomy_object = array_pop( $taxonomy_object );
		if ( ! empty( $taxonomy_object ) && property_exists( $taxonomy_object, 'attribute_type' ) ) {
			return $taxonomy_object->attribute_type;
		}

		return false;
	}

	/**
	 * Render variation swatches on catalog page.
	 */
	public function render_catalog_swatches() {
		global $product;
		if ( ! $product ) {
			return;
		}

		if ( ! $product->is_type( 'variable' ) ) {
			return;
		}

		if ( ! $this->should_display_catalog_swatches() ) {
			return;
		}

		wp_enqueue_script( 'wc-add-to-cart-variation' );

		$get_variations       = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
		$available_variations = $get_variations ? $product->get_available_variations() : false;
		$variations_json      = wp_json_encode( $available_variations );
		$variations_attr      = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
		$attributes           = $product->get_variation_attributes();

		echo '<form class="nv-catalog-variation variations_form cart" data-product_id="' . absint( $product->get_id() ) . '" data-product_variations="' . esc_attr( $variations_attr ) . '">';
		echo '<ul class="variations">';
		foreach ( $attributes as $attribute_name => $options ) {
			$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			echo '<li class="nv-vswatch-item">';
			wc_dropdown_variation_attribute_options(
				array(
					'options'    => $options,
					'attribute'  => $attribute_name,
					'product'    => $product,
					'selected'   => $selected,
					'is_archive' => true,
				)
			);
			echo '</li>';
		}
		echo '</ul>';
		echo '</form>';
	}

	/**
	 * Check if variation swatches on catalog view should be visible.
	 *
	 * @return bool
	 */
	private function should_display_catalog_swatches() {
		$button_display = get_theme_mod( 'neve_add_to_cart_display', 'none' );
		if ( $button_display !== 'after' ) {
			return false;
		}

		$is_vs = get_theme_mod( 'neve_catalog_vs', false );
		if ( ! $is_vs ) {
			return false;
		}

		return true;
	}

	/**
	 * Arguments for the add to cart button on product catalog.
	 *
	 * @param array  $args Button arguments.
	 * @param object $product Current product.
	 *
	 * @return array
	 */
	public function add_to_cart_args( $args, $product ) {
		if ( ! $product->is_type( 'variable' ) ) {
			return $args;
		}

		if ( ! isset( $args['class'] ) ) {
			$args['class'] = '';
		}
		$args['class'] .= ' nv_add_to_cart_button';

		if ( ! isset( $args['attributes'] ) ) {
			$args['attributes'] = array();
		}

		$classname         = \WC_Product_Factory::get_classname_from_product_type( 'simple' );
		$as_single_product = new $classname( $product->get_id() );

		if ( isset( $args['attributes']['aria-label'] ) ) {
			$args['attributes']['data-add-to-cart-aria-label']    = wp_strip_all_tags( $as_single_product->add_to_cart_description() );
			$args['attributes']['data-select-options-aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
		}

		$args['attributes']['data-add-to-cart']    = $as_single_product->add_to_cart_text();
		$args['attributes']['data-select-options'] = $product->add_to_cart_text();

		$args['attributes']['data-product_permalink'] = $product->add_to_cart_url();
		$args['attributes']['data-add_to_cart_url']   = $product->is_purchasable() && $product->is_in_stock() ? $this->get_current_url() : esc_url( $product->get_permalink() );

		return $args;
	}

	/**
	 * Get the current url. Used for getting the shop url.
	 *
	 * @param array $args Current arguments.
	 *
	 * @return string
	 */
	private function get_current_url( $args = array() ) {
		global $wp;
		return esc_url( trailingslashit( home_url( add_query_arg( $args, $wp->request ) ) ) );
	}

}
