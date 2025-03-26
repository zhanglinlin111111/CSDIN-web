<?php
/**
 * Payment Icons component class, Header Footer Grid Component.
 *
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Core\Settings\Manager as SettingsManager;
use Neve\Core\Settings\Config;
use Neve\Core\Styles\Dynamic_Selector;
use Neve_Pro\Core\Settings;

/**
 * Class Payment_Icons
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Payment_Icons extends Abstract_Component {
	const COMPONENT_ID     = 'payment_icons';
	const ITEM_ORDERING    = 'ordering_shortcut';
	const COLOR            = 'color';
	const BACKGROUND_COLOR = 'background_color';

	/**
	 * Check if component should be active.
	 *
	 * @return bool
	 */
	public function is_active() {
		if ( ! apply_filters( 'nv_pro_woocommerce_booster_status', false ) || ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Payment icons component Constructor
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Payment Icons', 'neve' ) );
		$this->set_property( 'id', $this->get_class_const( 'COMPONENT_ID' ) );
		$this->set_property( 'width', 3 );
		$this->set_property( 'section', 'hfg_payment_icons_component' );
		$this->set_property( 'icon', 'images-alt' );
		$this->set_property( 'default_selector', '.builder-item--' . $this->get_id() );
	}


	/**
	 * The customizer settings for this component are added in WooCommerce Booster module.
	 */
	public function add_settings() {
		$description = sprintf(
		/* translators: %s is link to section */
			esc_html__( 'Click %s to edit payment icons', 'neve' ),
			sprintf(
			/* translators: %s is link label */
				'<span class="quick-links"><a href="#" data-control-focus="neve_payment_icons">%s</a></span>',
				esc_html__( 'here', 'neve' )
			)
		);

		SettingsManager::get_instance()->add(
			[
				'id'                => self::ITEM_ORDERING,
				'group'             => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'               => SettingsManager::TAB_LAYOUT,
				'transport'         => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback' => 'sanitize_text_field',
				'label'             => esc_html__( 'Edit Payment Icons', 'neve' ),
				'description'       => $description,
				'type'              => 'hidden',
				'options'           => [
					'priority' => 70,
				],
				'section'           => $this->section,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                    => self::COLOR,
				'group'                 => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'neve_sanitize_colors',
				'label'                 => esc_html__( 'Color', 'neve' ),
				'type'                  => 'neve_color_control',
				'default'               => '#9b9b9b',
				'section'               => $this->section,
				'live_refresh_selector' => true,
				'live_refresh_css_prop' => [
					[
						'selector' => $this->default_selector . ' .nv-payment-icons-wrapper',
						'prop'     => 'fill',
						'fallback' => '#9b9b9b',
					],
				],
			]
		);
		SettingsManager::get_instance()->add(
			[
				'id'                    => self::BACKGROUND_COLOR,
				'group'                 => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'neve_sanitize_colors',
				'label'                 => esc_html__( 'Background Color', 'neve' ),
				'type'                  => 'neve_color_control',
				'section'               => $this->section,
				'default'               => '#e5e5e5',
				'live_refresh_selector' => true,
				'live_refresh_css_prop' => [
					[
						'selector' => $this->default_selector . ' .nv-payment-icon',
						'prop'     => 'background-color',
						'fallback' => '#e5e5e5',
					],
				],
			]
		);
	}

	/**
	 * Method to add Component css styles.
	 *
	 * @param array $css_array An array containing css rules.
	 *
	 * @return array
	 */
	public function add_style( array $css_array = array() ) {
		if ( neve_pro_is_new_skin() ) {
			$rules = [
				'--color'   => [
					'key'     => $this->id . '_' . self::COLOR,
					'default' => '#9b9b9b',
				],
				'--bgColor' => [
					'key'     => $this->id . '_' . self::BACKGROUND_COLOR,
					'default' => '#e5e5e5',
				],
			];

			$css_array[] = [
				'selectors' => '.builder-item--' . $this->get_id(),
				'rules'     => $rules,
			];

			return parent::add_style( $css_array );
		}

		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $this->default_selector . ' .nv-payment-icons-wrapper',
			Dynamic_Selector::KEY_RULES    => [
				Config::CSS_PROP_FILL_COLOR => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::COLOR,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::COLOR ),
				],
			],
		];
		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $this->default_selector . ' .nv-payment-icons-wrapper .nv-payment-icon',
			Dynamic_Selector::KEY_RULES    => [
				Config::CSS_PROP_BACKGROUND_COLOR => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::BACKGROUND_COLOR,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::BACKGROUND_COLOR ),
				],
			],
		];

		return parent::add_style( $css_array );
	}

	/**
	 * Render Payment Icons component.
	 *
	 * @return mixed|void
	 */
	public function render_component() {
		echo \Neve_Pro\Modules\Woocommerce_Booster\Views\Payment_Icons::render_payment_icons(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
