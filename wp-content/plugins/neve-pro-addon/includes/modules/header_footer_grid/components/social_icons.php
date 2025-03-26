<?php
/**
 * Button Component class for Header Footer Grid.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Main;
use Neve_Pro\Core\Loader;
use Neve_Pro\Traits\Core;

/**
 * Class Social_Icons
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Social_Icons extends Abstract_Component {

	use Core;

	/**
	 * Holds the instance count.
	 * Starts at 1 since the base component is not altered.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected static $instance_count = 0;
	/**
	 * Holds the current instance count.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $instance_number;
	/**
	 * The maximum allowed instances of this class.
	 * This refers to the global scope, across all builders.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $max_instance = 2;

	const COMPONENT_ID  = 'social_icons';
	const REPEATER_ID   = 'content_setting';
	const NEW_TAB       = 'new_tab';
	const ICON_SIZE     = 'icon_size';
	const ICON_SPACING  = 'icon_spacing';
	const ICON_PADDING  = 'icon_padding';
	const BORDER_RADIUS = 'border_radius';
	/**
	 * Repeater defaults
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var array
	 */
	private $repeater_default = array(
		array(
			'title'            => 'Facebook',
			'url'              => '#',
			'icon'             => 'facebook',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#3b5998',
		),
		array(
			'title'            => 'Twitter',
			'url'              => '#',
			'icon'             => 'twitter',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#1da1f2',
		),
		array(
			'title'            => 'Youtube',
			'url'              => '#',
			'icon'             => 'youtube-play',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#cd201f',
		),
		array(
			'title'            => 'Instagram',
			'url'              => '#',
			'icon'             => 'instagram',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#e1306c',
		),
	);

	/**
	 * Social_Icons constructor.
	 *
	 * @param string $panel Builder panel.
	 */
	public function __construct( $panel ) {
		self::$instance_count ++;
		$this->instance_number = self::$instance_count;
		parent::__construct( $panel );
		$this->set_property( 'section', $this->get_class_const( 'COMPONENT_ID' ) );
	}


	/**
	 * Initialize.
	 *
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Social Icons', 'neve' ) );
		$this->set_property( 'id', $this->get_class_const( 'COMPONENT_ID' ) );
		$this->set_property( 'width', 4 );
		$this->set_property( 'section', 'social_icons_' . $this->instance_number );
		$this->set_property( 'icon', 'share' );
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
		SettingsManager::get_instance()->add(
			array(
				'id'                => self::REPEATER_ID,
				'group'             => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback' => array( $this, 'sanitize_social_icons_repeater' ),
				'default'           => wp_json_encode( $this->repeater_default ),
				'label'             => __( 'Social Icons', 'neve' ),
				'type'              => Loader::has_compatibility( 'repeater_control' ) ? '\Neve\Customizer\Controls\React\Repeater' : 'Neve_Pro\Customizer\Controls\Repeater',
				'options'           => array(
					'fields' => array(
						'title'            => array(
							'type'  => 'text',
							'label' => 'Title',
						),
						'icon'             => array(
							'type'  => 'icon',
							'label' => 'Icon',
						),
						'url'              => array(
							'type'  => 'text',
							'label' => 'Link',
						),
						'icon_color'       => array(
							'type'  => 'color',
							'label' => 'Icon Color',
						),
						'background_color' => array(
							'type'  => 'color',
							'label' => 'Background Color',
						),
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::NEW_TAB,
				'group'             => $this->get_id(),
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback' => 'absint',
				'default'           => 0,
				'label'             => __( 'Open in new tab', 'neve' ),
				'type'              => 'neve_toggle_control',
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::ICON_SIZE,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'absint',
				'default'            => 18,
				'label'              => __( 'Icon Size', 'neve' ),
				'type'               => 'Neve\Customizer\Controls\React\Range',
				'options'            => array(
					'input_attrs' => array(
						'step'       => 1,
						'min'        => 10,
						'max'        => 40,
						'defaultVal' => 18,
					),
				),
				'section'            => $this->section,
				'conditional_header' => $this->get_builder_id() === 'header',
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::ICON_SPACING,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'absint',
				'default'            => 10,
				'label'              => __( 'Icon Spacing', 'neve' ),
				'type'               => 'Neve\Customizer\Controls\React\Range',
				'options'            => array(
					'input_attrs' => array(
						'step'       => 1,
						'min'        => 0,
						'max'        => 100,
						'defaultVal' => 10,
					),
				),
				'section'            => $this->section,
				'conditional_header' => $this->get_builder_id() === 'header',
			)
		);
		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::BORDER_RADIUS,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'absint',
				'default'            => 5,
				'label'              => __( 'Border Radius (px)', 'neve' ),
				'type'               => 'Neve\Customizer\Controls\React\Range',
				'options'            => array(
					'input_attrs' => array(
						'step'       => 1,
						'min'        => 0,
						'max'        => 50,
						'defaultVal' => 5,
					),
				),
				'section'            => $this->section,
				'conditional_header' => $this->get_builder_id() === 'header',
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::ICON_PADDING,
				'group'              => $this->get_id(),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => array( $this, 'sanitize_spacing_array' ),
				'conditional_header' => $this->get_builder_id() === 'header',
				'default'            => array(
					'desktop'      => array(
						'top'    => 5,
						'right'  => 5,
						'bottom' => 5,
						'left'   => 5,
					),
					'tablet'       => array(
						'top'    => 5,
						'right'  => 5,
						'bottom' => 5,
						'left'   => 5,
					),
					'mobile'       => array(
						'top'    => 5,
						'right'  => 5,
						'bottom' => 5,
						'left'   => 5,
					),
					'desktop-unit' => 'px',
					'tablet-unit'  => 'px',
					'mobile-unit'  => 'px',
				),
				'options'            => [
					'input_attrs' => array(
						'hideResponsiveButtons' => true,
					),
				],
				'label'              => __( 'Icon Padding', 'neve' ),
				'type'               => '\Neve\Customizer\Controls\React\Spacing',
				'section'            => $this->section,
			)
		);
	}

	/**
	 * Method to add Component css styles.
	 *
	 * @param array $css_array An array containing css rules.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_style( array $css_array = array() ) {
		if ( neve_pro_is_new_skin() ) {
			$rules = [
				'--spacing'      => [
					'key'    => $this->id . '_' . self::ICON_SPACING,
					'suffix' => 'px',
				],
				'--borderRadius' => [
					'key'    => $this->get_id() . '_' . self::BORDER_RADIUS,
					'suffix' => 'px',
				],
				'--iconPadding'  => [
					'key'              => $this->get_id() . '_' . self::ICON_PADDING,
					'directional-prop' => 'padding',
					'is_responsive'    => true,
				],
			];

			$css_array[] = [
				'selectors' => '.builder-item--' . $this->get_id(),
				'rules'     => $rules,
			];

			return parent::add_style( $css_array );
		}

		if ( ! defined( 'NEVE_NEW_DYNAMIC_STYLE' ) ) {
			return $this->legacy_style( $css_array );
		}

		$this->default_selector = '.builder-item--' . $this->get_id() . ' ul.nv-social-icons-list';
		$icon_selector          = $this->default_selector . ' li a';
		$css_array[]            = [
			'selectors' => $this->default_selector . ' > li:not(:first-child)',
			'rules'     => [
				'margin-left' => [
					'key'     => $this->get_id() . '_' . self::ICON_SPACING,
					'default' => 10,
				],
			],
		];
		$css_array []           = [
			'selectors' => $icon_selector,
			'rules'     => [
				'border-radius' => [
					'key'     => $this->get_id() . '_' . self::BORDER_RADIUS,
					'default' => 5,
				],
				'padding'       => [
					'is_responsive' => true,
					'key'           => $this->get_id() . '_' . self::ICON_PADDING,
					'suffix'        => 'responsive_unit',
				],
			],
		];

		return parent::add_style( $css_array );
	}

	/**
	 * Method to add Component css styles.
	 *
	 * @param array $css_array An array containing css rules.
	 *
	 * @return array
	 * @deprecated In favor of new dynamic CSS.
	 * @since   1.0.0
	 * @access  public
	 */
	public function legacy_style( array $css_array = array() ) {
		$this->default_selector = '.builder-item--' . $this->get_id() . ' ul.nv-social-icons-list';
		$icon_spacing           = get_theme_mod( $this->get_id() . '_' . self::ICON_SPACING, 10 );
		$border_radius          = get_theme_mod( $this->get_id() . '_' . self::BORDER_RADIUS, 5 );
		$icon_padding           = get_theme_mod( $this->get_id() . '_' . self::ICON_PADDING, '' );
		$icon_selector          = $this->default_selector . ' li a';

		$css_array[ $this->default_selector . ' > li:not(:first-child)' ] = array( 'margin-left' => $icon_spacing . 'px' );
		$css_array[ $icon_selector ]                                      = array( 'border-radius' => $border_radius . 'px' );

		if ( isset( $icon_padding['mobile'] ) ) {
			$css_array[' @media (max-width: 576px)'][ $icon_selector ]['padding'] =
				$icon_padding['mobile']['top'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['right'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['bottom'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['left'] . $icon_padding['mobile-unit'];
		}
		if ( isset( $icon_padding['tablet'] ) ) {
			$css_array[' @media (min-width: 576px)'][ $icon_selector ]['padding'] =
				$icon_padding['tablet']['top'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['right'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['bottom'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['left'] . $icon_padding['tablet-unit'];
		}
		if ( isset( $icon_padding['desktop'] ) ) {
			$css_array[' @media (min-width: 961px)'][ $icon_selector ]['padding'] =
				$icon_padding['desktop']['top'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['right'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['bottom'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['left'] . $icon_padding['desktop-unit'];
		}

		return parent::add_style( $css_array );
	}

	/**
	 * Sanitize repeater values.
	 *
	 * @param string $value repeater json value.
	 *
	 * @return string
	 */
	public function sanitize_social_icons_repeater( $value ) {
		$fields = array(
			'title',
			'url',
			'icon',
			'visibility',
			'icon_color',
			'background_color',
		);
		$valid  = $this->sanitize_repeater_json( $value, $fields );

		if ( $valid === false ) {
			return wp_json_encode( $this->repeater_default );
		}

		return $value;
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-social-icons' );
	}

	/**
	 * Allow for constant changes in pro.
	 *
	 * @param string $const Name of the constant.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  protected
	 */
	protected function get_class_const( $const ) {
		return $this->instance_number > 1 ? constant( 'static::' . $const ) . '_' . $this->instance_number : constant( 'static::' . $const );
	}

	/**
	 * Method to filter component loading if needed.
	 *
	 * @return bool
	 * @since   1.0.1
	 * @access  public
	 */
	public function is_active() {
		if ( $this->max_instance < $this->instance_number ) {
			return false;
		}

		return parent::is_active();
	}
}
