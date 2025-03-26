<?php
/**
 * Handle metabox controls that need to be added to already existing metabox in the theme.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2018-12-03
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Admin\Metabox;

use Neve_Pro\Traits\Core;

/**
 * Class Injector
 *
 * @package Neve Pro Addon
 */
class Injector {
	use Core;
	/**
	 * Initialize the injector and hook in the new classes.
	 */
	public function init() {
		add_filter( 'neve_filter_metabox_controls', array( $this, 'inject_control_classes' ) );
	}

	/**
	 * Inject front end style classes from this plugin.
	 *
	 * @see \Neve\Admin\Metabox\Manager
	 *
	 * @param array $control_classes control handlers from Neve.
	 *
	 * @return array
	 */
	public function inject_control_classes( $control_classes ) {
		if ( apply_filters( 'nv_pro_woocommerce_booster_status', false ) ) {
			$control_classes[] = '\\Neve_Pro\\Modules\\Woocommerce_Booster\\Admin\\Product_Metabox';
		}

		return $control_classes;
	}
}
