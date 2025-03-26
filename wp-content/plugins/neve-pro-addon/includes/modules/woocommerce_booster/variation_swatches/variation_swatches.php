<?php
/**
 *  Class that add variation swatches admin part.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Variation_Swatches
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Variation_Swatches;

use Neve_Pro\Traits\Core;

/**
 * Class Variation_Swatches
 */
class Variation_Swatches {
	use Core;

	/**
	 * Taxonomy attributes.
	 *
	 * @var array
	 */
	private $attr_taxonomies = array();

	/**
	 * Init function.
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'define_admin_hooks' ) );
	}

	/**
	 * Admin hooks.
	 */
	public function define_admin_hooks() {
		add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );
		$attribute_taxonomies  = wc_get_attribute_taxonomies();
		$this->attr_taxonomies = $attribute_taxonomies;
		foreach ( $attribute_taxonomies as $tax ) {
			add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_fields' ) );
			add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10, 2 );
			add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array( $this, 'add_attribute_column' ) );
			add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
		}
		add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 3 );

		add_action( 'woocommerce_product_option_terms', array( $this, 'render_product_option_terms' ), 20, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_swatches_scripts' ) );
	}

	/**
	 * Add types of the variation swatches.
	 *
	 * @param array $types Variation types.
	 *
	 * @return array
	 */
	public function add_attribute_types( $types ) {
		$more_types = array(
			'color' => __( 'Color', 'neve' ),
			'image' => __( 'Image', 'neve' ),
			'label' => __( 'Label', 'neve' ),
		);

		$types = array_merge( $types, $more_types );
		return $types;
	}

	/**
	 * Add the root div for custom fields that are added in react.
	 */
	public function add_attribute_fields( $taxonomy ) {
		$attribute_type = $this->get_attribute_type( $taxonomy );
		if ( false === $attribute_type ) {
			return false;
		}

		$settings = array(
			'attribute_type' => $attribute_type,
			'input_name'     => 'product_' . $taxonomy,
		);

		$this->render_custom_attributes( $settings );
		return true;
	}

	/**
	 * Render the custom controls when editing a swatch type term.
	 *
	 * @param \WP_Term $term Term object.
	 * @param string   $taxonomy Taxonomy name.
	 */
	public function edit_attribute_fields( $term, $taxonomy ) {
		$settings = array(
			'attribute_type' => $this->get_attribute_type( $taxonomy ),
			'term_value'     => get_term_meta( $term->term_id, 'product_' . $taxonomy, true ),
			'is_edit'        => true,
			'input_name'     => 'product_' . $taxonomy,
		);

		$this->render_custom_attributes( $settings );
	}

	/**
	 * Render custom controls based on the swatch type.
	 *
	 * @param array $settings Custom attribute settings.
	 *
	 * @return bool
	 */
	private function render_custom_attributes( $settings ) {

		if ( ! array_key_exists( 'attribute_type', $settings ) ) {
			return false;
		}

		if ( ! array_key_exists( 'input_name', $settings ) ) {
			return false;
		}

		$attribute_type      = $settings['attribute_type'];
		$is_edit             = array_key_exists( 'is_edit', $settings ) ? $settings['is_edit'] : false;
		$custom_field_markup = $this->get_custom_field_markup_wrapper( $settings );

		$label = $this->get_label_by_type( $attribute_type );
		if ( $is_edit ) {
			echo '<tr class="form-field gbl-attr-terms gbl-attr-terms-edit" >';
			echo '<th>' . esc_html( $label ) . '</th>';
			echo '<td>';
			echo wp_kses_post( $custom_field_markup );
			echo '</td>';
			echo '</tr>';
		} else {
			echo '<div class="form-field term-' . esc_attr( $attribute_type ) . '-wrap">';
			echo '<label>' . esc_html( $label ) . '</label>';
			echo wp_kses_post( $custom_field_markup );
			echo '</div>';
		}
		wp_nonce_field( 'add_swatch', 'swatches_nonce' );
		return true;
	}

	/**
	 * Get custom field markup wrapper.
	 *
	 * @param array $settings Field settings.
	 *
	 * @return string
	 */
	private function get_custom_field_markup_wrapper( $settings ) {
		$input_name     = $settings['input_name'];
		$attribute_type = $settings['attribute_type'];

		$markup = '<div id="nv-swatches-custom-fields" data-name="' . esc_attr( $input_name ) . '" data-type="' . esc_attr( $attribute_type ) . '" ';
		if ( array_key_exists( 'term_value', $settings ) ) {
			$markup .= 'data-value="' . esc_attr( $settings['term_value'] ) . '"';
		}
		$markup .= '></div>';
		return $markup;
	}

	/**
	 * Get label by input type.
	 *
	 * @param string $type Input type.
	 *
	 * @return string
	 */
	private function get_label_by_type( $type ) {
		switch ( $type ) {
			case 'color':
				return __( 'Color', 'neve' );
			case 'image':
				return __( 'Image', 'neve' );
			case 'label':
				return __( 'Label', 'neve' );
			default:
				return '';
		}
	}

	/**
	 * Add the preview column for terms.
	 *
	 * @param array $columns Current columns.
	 *
	 * @return array
	 */
	public function add_attribute_column( $columns ) {
		return $this->array_insert_after( $columns, 'cb', array( 'nv_preview' => '' ) );
	}

	/**
	 * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
	 * to the end of the array.
	 *
	 * @param array  $array Array where needed to insert.
	 * @param string $key Key after to insert.
	 * @param array  $new What to inset.
	 *
	 * @return array
	 */
	private function array_insert_after( array $array, $key, array $new ) {
		$keys  = array_keys( $array );
		$index = array_search( $key, $keys, true );
		$pos   = false === $index ? count( $array ) : $index + 1;

		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}

	/**
	 * Render preview for terms.
	 *
	 * @param string $columns Current columns.
	 * @param string $column  Current column.
	 * @param int    $term_id Term id.
	 *
	 * @return string|false
	 */
	public function add_attribute_column_content( $columns, $column, $term_id ) {
		if ( 'nv_preview' !== $column ) {
			return false;
		}

		if ( ! isset( $_REQUEST['taxonomy'] ) ) {
			return false;
		}

		$taxonomy    = sanitize_text_field( $_REQUEST['taxonomy'] );
		$attr_type   = $this->get_attribute_type( $taxonomy );
		$value       = get_term_meta( $term_id, 'product_' . $taxonomy, true );
		$has_value   = ! empty( $value );
		$empty_value = $has_value ? '' : 'nv-vswatch-empty';
		switch ( $attr_type ) {
			case 'color':
				$markup = '<div class="nv-vswatch-preview-wrap color round ' . esc_attr( $empty_value ) . '">';
				if ( $has_value ) {
					$markup .= '<span class="nv-vswatch-color-preview" style="background-color:' . esc_attr( $value ) . ';"></span>';
				}
				$markup .= '</div>';
				return $markup;
			case 'image':
				$markup = '<div class="nv-vswatch-preview-wrap image round ' . esc_attr( $empty_value ) . '">';
				if ( $has_value ) {
					$markup .= '<img class="nv-vswatch-image-preview" src="' . esc_url( $value ) . '"/>';
				}
				$markup .= '</div>';
				return $markup;
			case 'label':
				$term_name = get_term( $term_id )->name;
				$value     = empty( $value ) ? $term_name : $value;
				return '<div class="nv-vswatch-preview-wrap label"><label class="nv-vswatch-label-preview"> ' . wp_kses_post( $value ) . ' </label>';
			default:
				return '';
		}
	}

	/**
	 * Save swatch term meta.
	 *
	 * @param int    $term_id Term ID being saved.
	 * @param int    $tt_id Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	public function save_term_meta( $term_id, $tt_id, $taxonomy ) {

		if ( ! isset( $_POST['swatches_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['swatches_nonce'] ), 'add_swatch' ) ) {
			return false;
		}

		if ( isset( $_POST[ 'product_' . $taxonomy ] ) ) {
			$attr_type = $this->get_attribute_type( $taxonomy );
			$value     = wp_kses_post( $_POST[ 'product_' . $taxonomy ] );
			$value     = $this->sanitize_term_value( $value, $attr_type );
			update_term_meta( $term_id, 'product_' . $taxonomy, $value );
		}
	}

	/**
	 * Sanitize term value by type.
	 *
	 * @param string $value Term value.
	 * @param string $type Term type.
	 *
	 * @return string
	 */
	public function sanitize_term_value( $value, $type ) {
		switch ( $type ) {
			case 'color':
				if ( preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $value ) ) {
					return $value;
				}
				return '';
			case 'image':
				$attachment_id = $this->attachment_url_to_postid( $value );
				if ( ! empty( $attachment_id ) ) {
					$image_source = wp_get_attachment_image_src( $attachment_id );
					if ( is_array( $image_source ) ) {
						return $image_source[0];
					}
				}
				return '';
			case 'label':
				return wp_kses_post( $value );
			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Get taxonomy's type attribute.
	 *
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return mixed
	 */
	public function get_attribute_type( $taxonomy ) {
		foreach ( $this->attr_taxonomies as $tax ) {
			if ( 'pa_' . $tax->attribute_name === $taxonomy ) {
				return( $tax->attribute_type );
			}
		}
		return false;
	}

	/**
	 * Display the select field in attributes type.
	 *
	 * @param object $attribute_taxonomy Attribute taxonomy.
	 * @param int    $i Index.
	 *
	 * @return bool
	 */
	public function render_product_option_terms( $attribute_taxonomy, $i ) {
		if ( 'select' === $attribute_taxonomy->attribute_type ) {
			return false;
		}

		global $post;

		$product_id = $post->ID;
		if ( is_null( $product_id ) && isset( $_POST['post_id'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$product_id = absint( $_POST['post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		$taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );

		echo '<select multiple="multiple" data-placeholder="' . esc_attr__( 'Select terms', 'neve' ) . '" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[' . esc_attr( (string) $i ) . '][]">';
		$args      = array(
			'orderby'    => 'name',
			'hide_empty' => 0,
		);
		$all_terms = get_terms( $taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
		if ( ! empty( $all_terms ) ) {
			foreach ( $all_terms as $term ) {
				$selected = wc_selected( (int) has_term( absint( $term->term_id ), $taxonomy, $product_id ), 1 );
				echo '<option value="' . esc_attr( $term->term_id ) . '" ' . esc_attr( $selected ) . '>' . esc_html( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
			}
		}
		echo '</select>';

		echo '<button class="button plus select_all_attributes">';
		esc_html_e( 'Select all', 'neve' );
		echo '</button>';

		echo '<button class="button minus select_no_attributes">';
		esc_html_e( 'Select none', 'neve' );
		echo '</button>';

		echo '<button class="button fr plus add_new_attribute">';
		esc_html_e( 'Add new', 'neve' );
		echo '</button>';

		return true;
	}

	/**
	 * Enqueue admin script.
	 *
	 * @param string $current_page Current page.
	 *
	 * @return bool
	 */
	public function enqueue_swatches_scripts( $current_page ) {
		if ( $current_page !== 'edit-tags.php' && $current_page !== 'term.php' ) {
			return false;
		}

		// wp-components script is not loaded by WooCommerce on versions lower than 5.0 so we need to load it for the color picker
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '4.9.2', '<=' ) ) {
			wp_enqueue_style( 'wp-components' );
		}

		$asset_file = include plugin_dir_path( __FILE__ ) . 'react/build/app.asset.php';
		wp_register_script(
			'nv-vswatches-script',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/variation_swatches/react/build/app.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'nv-variation-swatches-script', 'neve' );
		}

		wp_register_style(
			'nv-vswatches-editor-style',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/variation_swatches/css/editor-style.min.css',
			array(),
			NEVE_PRO_VERSION
		);
		wp_style_add_data( 'nv-vswatches-editor-style', 'rtl', 'replace' );
		wp_style_add_data( 'nv-vswatches-editor-style', 'suffix', '.min' );

		wp_enqueue_script( 'nv-vswatches-script' );
		wp_enqueue_style( 'nv-vswatches-editor-style' );

		wp_enqueue_script( 'nv-vswatches-field-reset', NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/variation_swatches/js/reset-fields.js', array(), NEVE_PRO_VERSION, true );
		return true;
	}
}
