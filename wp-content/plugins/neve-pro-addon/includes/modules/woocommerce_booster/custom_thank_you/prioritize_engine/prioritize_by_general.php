<?php
/**
 * Prioritize_By_General
 * 
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Prioritize_Engine
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Prioritize_Engine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Query;
use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Main;

/**
 * Tries to find top prioritized general thank you page.
 */
class Prioritize_By_General extends Abstract_Prioritize {    
	/**
	 * Get thank you page posts
	 *
	 * @return array
	 */
	public function get_thank_you_page_posts() {
		return Query::get( false, array( Main::class, 'is_ty_page_general' ) );
	}
}
