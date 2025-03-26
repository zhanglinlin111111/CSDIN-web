<?php
/**
 * Page Header class for Header Footer Grid.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Builder;

use HFG\Core\Builder\Abstract_Builder;
use HFG\Main;
use WP_Customize_Manager;

/**
 * Class Page_Header
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Builder
 */
class Page_Header extends Abstract_Builder {
	/**
	 * Builder name.
	 */
	const BUILDER_NAME            = 'page_header';
	const DISPLAY_LOCATIONS       = 'neve_pro_page_header_display_locations';
	const GLOBAL_SETTINGS_SECTION = 'neve_pro_global_page_header_settings';

	/**
	 * Header init.compo
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		if ( ! self::is_module_activated() ) {
			return;
		}

		$this->set_property( 'title', __( 'Page Header', 'neve' ) );
		$this->set_property(
			'description',
			sprintf(
				/* translators: %s link to documentation */
				esc_html__( 'Design your %1$s by dragging, dropping and resizing all the elements in real-time. %2$s.', 'neve' ),
				/* translators: %s builder type */
				$this->get_property( 'title' ),
				/* translators: %s link text */
				sprintf(
					'<br/><a target="_blank" href="https://docs.themeisle.com/article/1057-header-booster-documentation">%s</a>',
					esc_html__( 'Read full documentation', 'neve' )
				)
			)
		);
		$this->set_property(
			'instructions_array',
			array(
				'description' => sprintf(
					/* translators: 1: builder, 2: builder symbol */
					esc_attr__( 'Welcome to the %1$s builder! Click the %2$s button to add a new component or follow the Quick Links.', 'neve' ),
					$this->get_property( 'title' ),
					'+'
				),
				'quickLinks'  => array(
					'hfg_page_header_layout_top_background' => array(
						'label' => esc_html__( 'Change Top Row Color', 'neve' ),
						'icon'  => 'dashicons-admin-appearance',
					),
				),
			)
		);
		$this->devices = array(
			'desktop' => __( 'Desktop', 'neve' ),
		);
		if ( version_compare( NEVE_VERSION, '2.5.4', '>=' ) ) {
			$this->devices['mobile'] = __( 'Mobile', 'neve' );
		}
		add_filter( 'hfg_template_locations', array( $this, 'register_template_location' ) );
		add_action( 'neve_after_header_hook', array( $this, 'render_on_neve_page_header' ), 1, 1 );
	}

	/**
	 * Check that the module is enabled.
	 *
	 * @return boolean
	 */
	public static function is_module_activated() {
		return get_option( 'nv_pro_enable_page_header', true );
	}

	/**
	 * Invoke page header render on neve hook.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_on_neve_page_header() {
		if ( is_page_template() ) {
			return;
		}
		$will_render = false;
		$display     = get_theme_mod( self::DISPLAY_LOCATIONS, [ 'post', 'page' ] );

		if ( in_array( 'post', $display, true ) && is_home() ) {
			$will_render = true;
		}

		$current_post_type = get_post_type();
		if ( in_array( $current_post_type, $display, true ) && ( is_archive() || is_singular( $current_post_type ) ) ) {
			$will_render = true;
		}

		if ( $will_render ) {
			do_action( 'hfg_' . self::BUILDER_NAME . '_render' );
		}
	}

	/**
	 * Register a new template location for pro.
	 *
	 * @param array $template_locations An array with places to look for templates.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  public
	 */
	public function register_template_location( $template_locations ) {
		array_push( $template_locations, NEVE_PRO_SPL_ROOT . 'modules/header_footer_grid/templates/' );

		return $template_locations;
	}

	/**
	 * Method called via hook.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load_template() {
		Main::get_instance()->load( 'page-header-wrapper' );
	}

	/**
	 * Get builder id.
	 *
	 * @return string Builder id.
	 */
	public function get_id() {
		return self::BUILDER_NAME;
	}

	/**
	 * Render builder row.
	 *
	 * @param string $device_id The device id.
	 * @param string $row_id The row id.
	 * @param array  $row_details Row data.
	 */
	public function render_row( $device_id, $row_id, $row_details ) {
		Main::get_instance()->load( 'row-page-wrapper', $row_id );
	}

	/**
	 * Return  the builder rows.
	 *
	 * @return array
	 * @since   1.0.0
	 * @updated 1.0.1
	 * @access  protected
	 */
	protected function get_rows() {
		return array(
			'top'    => array(
				'title'       => esc_html__( 'Page Header Top', 'neve' ),
				'description' => $this->get_property( 'description' ),
			),
			'bottom' => array(
				'title'       => esc_html__( 'Page Header Bottom', 'neve' ),
				'description' => $this->get_property( 'description' ),
			),
		);
	}

	/**
	 * Add section.
	 *
	 * @param WP_Customize_Manager $wp_customize wp customize manager instance.
	 * @return WP_Customize_Manager
	 */
	public function customize_register( WP_Customize_Manager $wp_customize ) {
		if ( version_compare( NEVE_VERSION, '2.7.3', '<' ) ) {
			return parent::customize_register( $wp_customize );
		}
		$wp_customize->add_section(
			self::GLOBAL_SETTINGS_SECTION,
			[
				'priority' => 100,
				'title'    => esc_html__( 'Global Page Header Settings', 'neve' ),
				'panel'    => $this->panel,
			]
		);

		$wp_customize->add_setting(
			self::DISPLAY_LOCATIONS,
			[
				'transport'         => 'refresh',
				'sanitize_callback' => [ $this, 'sanitize_display_post_types' ],
				'default'           => [ 'post', 'page' ],
			]
		);

		$wp_customize->add_control(
			new \Neve\Customizer\Controls\React\Multiselect(
				$wp_customize,
				self::DISPLAY_LOCATIONS,
				[
					'label'   => esc_html__( 'Display on single and archives for', 'neve' ),
					'section' => self::GLOBAL_SETTINGS_SECTION,
					'choices' => $this->get_post_types(),
				]
			)
		);

		return parent::customize_register( $wp_customize );
	}

	/**
	 * Sanitize the display option.
	 *
	 * @param array $array post types array.
	 * @return array
	 */
	public function sanitize_display_post_types( $array ) {
		$available = $this->get_post_types();

		if ( ! is_array( $array ) ) {
			return [ 'post', 'page' ];
		}

		foreach ( $array as $post_type_slug ) {
			if ( ! array_key_exists( $post_type_slug, $available ) ) {
				unset( $array[ $post_type_slug ] );
			}
		}
		return $array;
	}

	/**
	 * Get the available post types.
	 *
	 * @return array
	 */
	private function get_post_types() {
		$post_types = [];
		$types      = get_post_types( [ 'public' => true ], 'objects' );

		foreach ( $types as $post_type => $args ) {
			$post_types[ $post_type ] = $args->label;
		}
		return $post_types;
	}
}
