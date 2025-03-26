<?php
/**
 * Shared functions for tab manager classes.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager
 */
namespace Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager;

/**
 * Trait Tab_Manager_Utilities
 */
trait Utilities {

	/**
	 * Get the default WooCommerce core tabs.
	 *
	 * @return array The core tabs
	 */
	private function get_core_tabs() {
		return [
			'description'            => esc_html__( 'Description', 'neve' ),
			'additional_information' => esc_html__( 'Additional Information', 'neve' ),
			'reviews'                => esc_html__( 'Reviews', 'neve' ),
		];
	}

	/**
	 * Decide if a post is part of the core tabs.
	 *
	 * @param int $post_id Post id.
	 *
	 * @return bool
	 */
	public function is_core_tab( $post_id ) {
		if ( empty( $post_id ) ) {
			return false;
		}

		$post_object = get_post( $post_id );
		if ( is_null( $post_object ) ) {
			return false;
		}

		$slug      = $post_object->post_name;
		$core_tabs = $this->get_core_tabs();

		return array_key_exists( $slug, $core_tabs );
	}

	/**
	 * Get allowed html tags for custom tabs.
	 */
	public function get_allowed_html() {
		return [
			'a'          => [
				'class'  => [],
				'href'   => [],
				'rel'    => [],
				'title'  => [],
				'target' => [],
			],
			'abbr'       => [
				'title' => [],
			],
			'b'          => [],
			'blockquote' => [
				'cite' => [],
			],
			'cite'       => [
				'title' => [],
			],
			'code'       => [],
			'del'        => [
				'datetime' => [],
				'title'    => [],
			],
			'dd'         => [],
			'div'        => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'dl'         => [],
			'dt'         => [],
			'em'         => [],
			'h1'         => [],
			'h2'         => [],
			'h3'         => [],
			'h4'         => [],
			'h5'         => [],
			'h6'         => [],
			'i'          => [],
			'img'        => [
				'alt'    => [],
				'class'  => [],
				'height' => [],
				'src'    => [],
				'width'  => [],
			],
			'ins'        => [
				'datetime' => [],
			],
			'li'         => [
				'class' => [],
			],
			'ol'         => [
				'class' => [],
			],
			'p'          => [
				'class' => [],
			],
			'q'          => [
				'cite'  => [],
				'title' => [],
			],
			'span'       => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'strike'     => [],
			'strong'     => [],
			'ul'         => [
				'class' => [],
			],
		];
	}

}
