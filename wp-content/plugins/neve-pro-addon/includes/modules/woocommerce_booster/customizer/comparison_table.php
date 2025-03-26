<?php
/**
 * Product Comparison Table options WooCommerce Booster Module
 *
 * @package WooCommerce Booster
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve\Customizer\Types\Section;
use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Fields;
use Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Options;
use Neve_Pro\Modules\Woocommerce_Booster\Module;

/**
 * Class Comparison_Table
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Comparison_Table extends Base_Customizer {
	/**
	 * Add Controls
	 *
	 * @return void
	 */
	public function add_controls() {
		if ( ! $this->should_load() ) {
			return;
		}

		// sections
		$this->section_comparison_table();

		// controls
		$this->neve_comparison_table_compare_checkbox_position_controll();
		$this->add_comparison_table_view_type();
		$this->add_auto_open_modal_product_limit();
		$this->add_comparison_table_page_id_control();
		$this->add_comparison_table_number_of_products_limit_control();
		$this->add_category_restrict_multiselect_control();
		$this->add_category_restrict_type_control();
		$this->add_manage_table_fields_control();
		$this->add_colors();
		$this->add_enable_alternating_row_bg_color();
		$this->add_enable_related_products_control();
		$this->add_sticky_bar_bg_color_control();
		$this->add_sticky_bar_text_color_control();
		$this->add_sticky_bar_button_type_control();

		// accordions
		$this->add_general_accordion();
		$this->add_sticky_bar_accordion();
		$this->add_table_layout_accordion();
		$this->add_table_style_accordion();
		$this->add_category_restrict_accordion();
		$this->add_related_products_accordion();
	}

	/**
	 * Check WooCommerce is loaded and Comparison Table is enabled?
	 */
	private function should_load() {
		return class_exists( 'WooCommerce', false ) && Options::is_module_activated();
	}

	/**
	 * Adds new section for Product Comparison Table.
	 */
	private function section_comparison_table() {
		$this->add_section(
			new Section(
				'neve_product_comparison_table',
				array(
					'priority'    => 10,
					'title'       => esc_html__( 'Product Comparison', 'neve' ),
					'description' => __( 'Allow users to compare fast and easy products across your store.', 'neve' ) . ' ' . apply_filters( 'neve_external_link', 'https://bit.ly/neve-woo-comp', __( 'Learn more', 'neve' ) ),
					'panel'       => 'woocommerce',
				)
			)
		);
	}

	/**
	 * Compare Checkbox Position Control
	 *
	 * @return void
	 */
	private function neve_comparison_table_compare_checkbox_position_controll() {
		$this->add_control(
			new Control(
				'neve_comparison_table_compare_checkbox_position',
				array(
					'default'           => 'top',
					'sanitize_callback' => array( $this, 'sanitize_neve_comparison_table_compare_checkbox_position' ),
				),
				array(
					'label'    => esc_html__( 'Compare Checkbox Position', 'neve' ),
					'section'  => 'neve_product_comparison_table',
					'priority' => 14,
					'choices'  => array(
						'top'    => array(
							'name' => __( 'Top', 'neve' ),
							'url'  => 'data:image/png;base64,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',
						),
						'bottom' => array(
							'name' => __( 'Bottom', 'neve' ),
							'url'  => 'data:image/png;base64,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',
						),
					),
				),
				version_compare( NEVE_VERSION, '2.10.1', '<' ) ? 'Neve\Customizer\Controls\Radio_Image' : '\Neve\Customizer\Controls\React\Radio_Image'
			)
		);
	}

	/**
	 * Note: the popup feature is disabled for now.
	 * Adds comparison table view type
	 *
	 * @return void
	 */
	private function add_comparison_table_view_type_control() {
		$this->add_control(
			new Control(
				'neve_comparison_table_view_type',
				array(
					'sanitize_callback' => array( $this, 'sanitize_neve_comparison_table_view_type' ),
					'default'           => 'page',
				),
				array(
					'priority' => 12,
					'section'  => 'neve_product_comparison_table',
					'label'    => esc_html__( 'Table View', 'neve' ),
					'type'     => 'select',
					'choices'  => [
						'page'  => __( 'Show as page', 'neve' ),
						'popup' => __( 'Show as popup', 'neve' ),
					],
				)
			)
		);
	}

	/**
	 * Adds table view type option to neve_product_comparison_table section.
	 *
	 * @return void
	 */
	private function add_comparison_table_view_type() {
		$options = [
			'column' => __( 'Show as column', 'neve' ),
			'row'    => __( 'Show as row', 'neve' ),
		];

		$this->add_control(
			new Control(
				'neve_comparison_table_product_listing_type',
				array(
					'default'           => 'column',
					'sanitize_callback' => array( $this, 'sanitize_neve_comparison_table_product_listing_type' ),
				),
				array(
					'priority' => 102,
					'section'  => 'neve_product_comparison_table',
					'label'    => esc_html__( 'Table View Type', 'neve' ),
					'type'     => 'select',
					'choices'  => $options,
				)
			)
		);
	}

	/**
	 * Open modal if number of products is equal to x.
	 *
	 * @return void
	 */
	private function add_auto_open_modal_product_limit() {
		$this->add_control(
			new Control(
				'neve_comparison_table_open_popup_product_limit',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 3,
				),
				array(
					'priority'        => 13,
					'section'         => 'neve_product_comparison_table',
					'label'           => esc_html__( 'Auto Open Modal Product Limit', 'neve' ),
					'step'            => 1,
					'input_attr'      => array(
						'min'     => 2,
						'max'     => 4,
						'default' => 3,
					),
					'input_attrs'     => array(
						'min'     => 2,
						'max'     => 4,
						'default' => 3,
					),
					'active_callback' => array( $this, 'open_popup_product_limit_control_callback' ),
				),
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);
	}

	/**
	 * Adds comparison table page id to neve_product_comparison_table section.
	 *
	 * @return void
	 */
	private function add_comparison_table_page_id_control() {
		// list all WP pages.
		$page_options = wp_list_pluck( get_pages(), 'post_title', 'ID' );

		$this->add_control(
			new Control(
				'woocommerce_neve_comparison_table_page_id',
				array(
					'sanitize_callback' => 'absint',
					'type'              => 'option',
				),
				array(
					'priority' => 11,
					'section'  => 'neve_product_comparison_table',
					'label'    => esc_html__( 'Choose Comparison Product Page', 'neve' ),
					'type'     => 'select',
					'choices'  => $page_options,
				)
			)
		);
	}

	/**
	 * Adds number of the products limit control.
	 *
	 * @return void
	 */
	private function add_comparison_table_number_of_products_limit_control() {
		$this->add_control(
			new Control(
				'neve_comparison_table_number_of_products_limit',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 3,
				),
				array(
					'label'       => __( 'Number of Products Limit', 'neve' ),
					'section'     => 'neve_product_comparison_table',
					'priority'    => 13,
					'step'        => 1,
					'input_attr'  => array(
						'min'     => 2,
						'max'     => 4,
						'default' => 3,
					),
					'input_attrs' => array(
						'min'     => 2,
						'max'     => 4,
						'default' => 3,
					),
				),
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);
	}

	/**
	 * Adds category based resctrict multiselect options to neve_product_comparison_table section.
	 *
	 * @return void
	 */
	private function add_category_restrict_multiselect_control() {
		// list all categories with empty categories.
		$category_restrict_options = wp_list_pluck(
			get_terms(
				'product_cat',
				[
					'orderby'    => 'title',
					'hide_empty' => false,
				]
			),
			'name',
			'term_id'
		);

		$this->add_control(
			new Control(
				'neve_comparison_table_restricted_categories',
				array(
					'default'           => [],
					'sanitize_callback' => function ( $category_ids ) {
						return array_map( 'absint', $category_ids );
					},
				),
				array(
					'priority'        => 92,
					'section'         => 'neve_product_comparison_table',
					'label'           => esc_html__( 'Restricted Categories', 'neve' ),
					'choices'         => $category_restrict_options,
					'active_callback' => array( $this, 'neve_comparison_table_restricted_categories_active_callback' ),
				),
				'\Neve\Customizer\Controls\React\Multiselect'
			)
		);
	}

	/**
	 * Add Category restrict type option to neve_product_comparison_table section.
	 *
	 * @return void
	 */
	private function add_category_restrict_type_control() {
		$category_restrict_type_options = [
			'none'    => __( 'None', 'neve' ),
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'exclude' => __( 'Exclude', 'neve' ),
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'include' => __( 'Include', 'neve' ),
		];

		$this->add_control(
			new Control(
				'neve_comparison_table_category_restrict_type',
				array(
					'sanitize_callback' => array( $this, 'sanitize_neve_comparison_table_category_restrict_type' ),
					'default'           => 'none',
				),
				array(
					'priority' => 91,
					'section'  => 'neve_product_comparison_table',
					'label'    => esc_html__( 'Category Restrict Type', 'neve' ),
					'type'     => 'select',
					'choices'  => $category_restrict_type_options,
				)
			)
		);
	}

	/**
	 * Adds table fields sortable controller to neve_product_comparison_table section.
	 *
	 * @return void
	 */
	private function add_manage_table_fields_control() {
		$components = $this->get_default_table_fields();

		$order_default_compontents = array_keys( $components );

		$this->add_control(
			new Control(
				'neve_comparison_table_fields',
				array(
					'default' => wp_json_encode( $order_default_compontents ),
				),
				array(
					'label'      => esc_html__( 'Fields Order', 'neve' ),
					'priority'   => 101,
					'section'    => 'neve_product_comparison_table',
					'components' => $components,
				),
				'\Neve\Customizer\Controls\React\Ordering'
			)
		);
	}

	/**
	 * Get ordered default fields of Comparison Table.
	 *
	 * An example array:
	 *
	 * <code>
	 *  $array = (
	 *      [remove_button] => Remove Button
	 *      [image] => Image
	 *      [name] => Name
	 *      [add_to_cart_button] => Add to Cart Button
	 *      [price] => Price
	 *      [rating] => Rating
	 *      [description] => Description
	 *      [sku] => SKU
	 *      [stock_availability] => Stock Availability
	 *      [attributes] => Attributes
	 *  );
	 * </code>
	 *
	 * @return array
	 */
	private function get_default_table_fields() {
		$default_fields = [];

		$fields = ( new Fields() )->get_fields();

		foreach ( $fields as $field ) {
			$default_fields[ $field->get_key() ] = $field->get_label();
		}

		return $default_fields;
	}

	/**
	 * Adds color controls for table styling.
	 */
	private function add_colors() {
		$colors = array(
			'neve_comparison_table_rows_background_color'  => array(
				'label'                 => __( 'Table rows background', 'neve' ),
				'default'               => 'var(--nv-site-bg)',
				'priority'              => 81,
				'live_refresh_css_prop' => [
					'cssVar' => [
						'vars'     => '--bgColor',
						'selector' => '.nv-ct-container',
					],
				],
			),
			'neve_comparison_table_header_text_color'      => array(
				'label'                 => __( 'Table header text', 'neve' ),
				'default'               => 'var(--nv-text-color)',
				'priority'              => 82,
				'live_refresh_css_prop' => [
					'cssVar' => [
						'vars'     => '--headerColor',
						'selector' => '.nv-ct-container',
					],
				],
			),
			'neve_comparison_table_text_color'             => array(
				'label'                 => __( 'Table text', 'neve' ),
				'default'               => 'var(--nv-text-color)',
				'priority'              => 83,
				'live_refresh_css_prop' => [
					'cssVar' => [
						'vars'     => '--color',
						'selector' => '.nv-ct-container',
					],
				],
			),
			'neve_comparison_table_borders_color'          => array(
				'label'                 => __( 'Table borders', 'neve' ),
				'default'               => '#BDC7CB',
				'priority'              => 84,
				'live_refresh_css_prop' => [
					'cssVar' => [
						'vars'     => '--borderColor',
						'selector' => '.nv-ct-container',
					],
				],
			),
			'neve_comparison_table_alternate_row_bg_color' => array(
				'label'                 => __( 'Alternating row background color', 'neve' ),
				'default'               => 'var(--nv-light-bg)',
				'priority'              => 86,
				'active_callback'       => array( $this, 'table_alternate_row_bg_color_active_callback' ),
				'live_refresh_css_prop' => [
					'cssVar' => [
						'vars'     => '--alternateBg',
						'selector' => '.nv-ct-container',
					],
				],
			),
		);

		foreach ( $colors as $id => $args ) {
			$this->add_control(
				new Control(
					$id,
					array(
						'sanitize_callback' => 'neve_sanitize_colors',
						'transport'         => $this->selective_refresh,
						'default'           => $args['default'],
					),
					array(
						'label'                 => $args['label'],
						'section'               => 'neve_product_comparison_table',
						'priority'              => $args['priority'],
						'default'               => $args['default'],
						'active_callback'       => isset( $args['active_callback'] ) ? $args['active_callback'] : '__return_true',
						'live_refresh_selector' => true,
						'live_refresh_css_prop' => $args['live_refresh_css_prop'],
					),
					'Neve\Customizer\Controls\React\Color'
				)
			);
		}
	}

	/**
	 * Add 'Enable Related Products' toggle.
	 *
	 * @return void
	 */
	private function add_enable_alternating_row_bg_color() {
		$this->add_control(
			new Control(
				'neve_comparison_table_enable_alternating_row_bg_color',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'priority' => 85,
					'section'  => 'neve_product_comparison_table',
					'label'    => esc_html__( 'Enable alternating row color', 'neve' ),
					'type'     => 'neve_toggle_control',
				)
			)
		);
	}

	/**
	 * Add 'Enable Related Products' toggle.
	 *
	 * @return void
	 */
	private function add_enable_related_products_control() {
		$this->add_control(
			new Control(
				'neve_comparison_table_enable_related_products',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'priority'        => 111,
					'active_callback' => array( $this, 'is_popup_disabled' ),
					'section'         => 'neve_product_comparison_table',
					'label'           => esc_html__( 'Enable Related Products', 'neve' ),
					'type'            => 'neve_toggle_control',
				)
			)
		);
	}

	/**
	 * Adds bg color control for sticky bar of comparison table.
	 *
	 * @return void
	 */
	private function add_sticky_bar_bg_color_control() {
		$this->add_control(
			new Control(
				'neve_comparison_table_sticky_bar_background_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'transport'         => $this->selective_refresh,
					'default'           => 'var(--nv-light-bg)',
				),
				array(
					'label'                 => __( 'BG Color', 'neve' ),
					'section'               => 'neve_product_comparison_table',
					'priority'              => 151,
					'default'               => 'var(--nv-site-bg)',
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'template' =>
							'.nv-ct-sticky-bar {
							    background: {{value}};
						    }',
					],
				),
				'Neve\Customizer\Controls\React\Color'
			)
		);
	}

	/**
	 * Adds text color control for sticky bar of comparison table.
	 *
	 * @return void
	 */
	private function add_sticky_bar_text_color_control() {
		$this->add_control(
			new Control(
				'neve_comparison_table_sticky_bar_text_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'transport'         => $this->selective_refresh,
					'default'           => 'var(--nv-text-color)',
				),
				array(
					'label'                 => __( 'Text Color', 'neve' ),
					'section'               => 'neve_product_comparison_table',
					'priority'              => 152,
					'default'               => 'var(--nv-text-color)',
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'template' =>
							'.nv-ct-sticky-bar {
							    color: {{value}};
						    }
							
							.nv-ct-sticky-bar a {
							    color: {{value}};
						    }
							',
					],
				),
				'Neve\Customizer\Controls\React\Color'
			)
		);
	}

	/**
	 * Adds text color control for sticky bar of comparison table.
	 *
	 * @return void
	 */
	private function add_sticky_bar_button_type_control() {
		$this->add_control(
			new Control(
				'neve_comparison_table_sticky_bar_button_type',
				array(
					'default'           => 'primary',
					'sanitize_callback' => array( $this, 'sanitize_neve_comparison_table_sticky_bar_button_type' ),
				),
				array(
					'label'    => esc_html__( 'Button Type', 'neve' ),
					'section'  => 'neve_product_comparison_table',
					'priority' => 153,
					'type'     => 'select',
					'choices'  => array(
						'primary'   => esc_html__( 'Primary', 'neve' ),
						'secondary' => esc_html__( 'Secondary', 'neve' ),
					),
				)
			)
		);
	}

	/**
	 * Adds general accordion.
	 */
	private function add_general_accordion() {
		$this->add_control(
			new Control(
				'neve_comparison_table_general_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => __( 'General', 'neve' ),
					'section'          => 'neve_product_comparison_table',
					'priority'         => 10,
					'class'            => 'comparison-table-general-accordion',
					'accordion'        => true,
					'expanded'         => true,
					'controls_to_wrap' => 4,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Adds sticky bar accordion.
	 */
	private function add_sticky_bar_accordion() {
		$this->add_control(
			new Control(
				'neve_comparison_sticky_bar_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => __( 'Sticky Bar', 'neve' ),
					'section'          => 'neve_product_comparison_table',
					'priority'         => 150,
					'class'            => 'comparison-sticky-bar-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 3,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Adds table layout accordion.
	 */
	private function add_table_layout_accordion() {
		$this->add_control(
			new Control(
				'neve_comparison_table_layout_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => __( 'Table Layout', 'neve' ),
					'section'          => 'neve_product_comparison_table',
					'priority'         => 100,
					'class'            => 'comparison-table-layout-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 2,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Adds table style accordion.
	 */
	private function add_table_style_accordion() {
		$this->add_control(
			new Control(
				'neve_comparison_table_style_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => __( 'Table Style', 'neve' ),
					'section'          => 'neve_product_comparison_table',
					'priority'         => 80,
					'class'            => 'comparison-table-style-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 6,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Adds category restrict accordion.
	 */
	private function add_category_restrict_accordion() {
		$this->add_control(
			new Control(
				'neve_comparison_category_restrict_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => __( 'Category Restriction', 'neve' ),
					'section'          => 'neve_product_comparison_table',
					'priority'         => 90,
					'class'            => 'comparison-table-category-restriction-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 2,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Adds related products accordion for show related product feature options.
	 *
	 * @return void
	 */
	private function add_related_products_accordion() {
		$this->add_control(
			new Control(
				'neve_comparison_related_products_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => __( 'Related Products', 'neve' ),
					'section'          => 'neve_product_comparison_table',
					'priority'         => 110,
					'class'            => 'comparison-table-related-products-accordion',
					'active_callback'  => array( $this, 'is_popup_disabled' ),
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 1,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Sanitize Compare Checkbox Position
	 *
	 * @return string
	 */
	public function sanitize_neve_comparison_table_compare_checkbox_position( $value ) {
		$allowed_values = array( 'top', 'bottom' );

		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'top';
		}

		return sanitize_key( $value );
	}

	/**
	 * Active Callback for 'neve_comparison_table_alternate_row_bg_color' control.
	 *
	 * @return bool
	 */
	public function table_alternate_row_bg_color_active_callback() {
		return get_theme_mod( 'neve_comparison_table_enable_alternating_row_bg_color', false );
	}

	/**
	 * Sanitize the neve_comparison_table_product_listing_type control.
	 *
	 * @return string
	 */
	public function sanitize_neve_comparison_table_product_listing_type( $value ) {
		$allowed_values = array( 'column', 'row' );

		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'column';
		}

		return sanitize_key( $value );
	}

	/**
	 * Decide whetever to show 'open popup product limit control'
	 *
	 * @return bool
	 */
	public function open_popup_product_limit_control_callback() {
		return get_theme_mod( 'neve_comparison_table_view_type', 'page' ) === 'popup';
	}

	/**
	 * Sanitize the neve_comparison_table_view_type
	 *
	 * @param string $value that unsanitized value.
	 *
	 * @return string
	 */
	public function sanitize_neve_comparison_table_view_type( $value ) {
		$allowed_values = array( 'page', 'popup' );

		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'page';
		}

		return sanitize_key( $value );
	}

	/**
	 * Sanitize the neve_comparison_table_category_restrict_type
	 *
	 * @param string $value that unsanitized value.
	 *
	 * @return string
	 */
	public function sanitize_neve_comparison_table_category_restrict_type( $value ) {
		$accepted_values = array( 'none', 'exclude', 'include' );

		if ( ! in_array( $value, $accepted_values, true ) ) {
			return 'none';
		}

		return sanitize_key( $value );
	}

	/**
	 * Active Callback for neve_comparison_table_category_restrict_type control.
	 *
	 * @return bool
	 */
	public function neve_comparison_table_restricted_categories_active_callback() {
		return get_theme_mod( 'neve_comparison_table_category_restrict_type', 'none' ) !== 'none';
	}

	/**
	 * Sanitize the neve_comparison_table_sticky_bar_button_type control.
	 *
	 * @param string $value that unsanitized value.
	 *
	 * @return string
	 */
	public function sanitize_neve_comparison_table_sticky_bar_button_type( $value ) {
		$allowed_values = array( 'primary', 'secondary' );

		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'primary';
		}

		return sanitize_key( $value );
	}

	/**
	 * Check if popup is disabled.
	 */
	public function is_popup_disabled() {
		$mod = get_theme_mod( 'neve_comparison_table_view_type', 'page' );
		return $mod !== 'popup';
	}
}
