<?php
/**
 * Thank You page content.
 * The template is shown as order received page.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Templates
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// TODO: review: should we add a check to here to be sure the global variable is exist?
$prioritized_ty_page = $GLOBALS['neve_prioritized_thank_you_page'];

echo wp_kses_post( html_entity_decode( apply_filters( 'the_content', $prioritized_ty_page->post_content, 1 ), ENT_QUOTES, get_option( 'blog_charset' ) ) );

unset( $GLOBALS['neve_prioritized_thank_you_page'] );
