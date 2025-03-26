<?php
/**
 * Output of the Product Tab to match thank you page in Admin
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Templates
 *
 * @var array $vars
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Main;
?>

<div id="nv_custom_thank_you" class="panel woocommerce_options_panel hidden">
	<div class="options_group">
		<p class="form-field">
			<label for="nv_custom_thank_you_page_id"><?php esc_html_e( 'Choose thank you page', 'neve' ); ?></label>
			<?php Main::get_template( 'select_element.php', $vars ); ?>
		</p>
	</div>
</div>
