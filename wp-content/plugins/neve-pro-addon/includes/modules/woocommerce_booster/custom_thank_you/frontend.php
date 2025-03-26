<?php
/**
 * Show Custom Thank You page on Frontend
 * 
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The class responsible from the end user view (show custom thank you page after the checkout.)
 */
class Frontend {    
	/**
	 * Initialization
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'wc_get_template', array( $this, 'override_order_received_template' ), 10, 5 );
	}

	/**
	 * Override the default WC Order Received Template (if conditions are met)
	 *
	 * @param  string $template full file path.
	 * @param  string $template_name current template name.
	 * @param  array  $args the args array contains order instance.
	 * @param  string $template_path template path.
	 * @param  string $default_path default path.
	 * @return string the output of the custom thank you page or default WC Order Received page.
	 */
	public function override_order_received_template( $template, $template_name, $args, $template_path, $default_path ) {
		if ( $template_name !== 'checkout/thankyou.php' ) {
			return $template;
		}

		$order = array_key_exists( 'order', $args ) ? $args['order'] : false;
	
		if ( ! $order || ! is_a( $order, 'WC_Order' ) ) {
			return $template;
		}

		$router                 = new Router( $order );
		$prioritized_ty_page_id = $router->get_prioritized_page_id();

		$prioritized_ty_page = get_post( $prioritized_ty_page_id );

		// if prioritized thank you page post is not found, show the current template.
		if ( $prioritized_ty_page_id === false || ! is_a( $prioritized_ty_page, 'WP_Post' ) ) {
			return $template;
		}

		// TODO: get a code review about the global variable.
		global $neve_prioritized_thank_you_page;

		$neve_prioritized_thank_you_page = $prioritized_ty_page;

		return trailingslashit( NEVE_PRO_SPL_ROOT . 'modules/woocommerce_booster/custom_thank_you/templates/' ) . 'thank_you_page.php';
	}
}
