<?php
/**
 * Default values for controls from the single post.
 *
 * @package Neve_Pro\Modules\Blog_Pro\Customizer\Defaults
 */

namespace Neve_Pro\Modules\Blog_Pro\Customizer\Defaults;

/**
 * Trait Single_Post
 *
 * @package Neve_Pro\Modules\Blog_Pro\Customizer\Defaults
 */
trait Single_Post {

	/**
	 * Get social icons default value.
	 *
	 * @return array
	 */
	public function social_icons_default() {
		return [
			[
				'social_network'  => 'facebook',
				'title'           => 'Facebook',
				'visibility'      => 'yes',
				'display_desktop' => true,
				'display_mobile'  => true,
			],
			[
				'social_network'  => 'twitter',
				'title'           => 'Twitter',
				'visibility'      => 'yes',
				'display_desktop' => true,
				'display_mobile'  => true,
			],
			[
				'social_network'  => 'email',
				'title'           => 'Email',
				'visibility'      => 'yes',
				'display_desktop' => true,
				'display_mobile'  => true,
			],
		];
	}

	/**
	 * Return the default value for responsive padding control.
	 *
	 * @return array
	 */
	public function responsive_padding_default() {
		return [
			'mobile'       => [
				'top'    => 20,
				'right'  => 20,
				'bottom' => 20,
				'left'   => 20,
			],
			'tablet'       => [
				'top'    => 20,
				'right'  => 20,
				'bottom' => 20,
				'left'   => 20,
			],
			'desktop'      => [
				'top'    => 20,
				'right'  => 20,
				'bottom' => 20,
				'left'   => 20,
			],
			'mobile-unit'  => 'px',
			'tablet-unit'  => 'px',
			'desktop-unit' => 'px',
		];
	}
}
