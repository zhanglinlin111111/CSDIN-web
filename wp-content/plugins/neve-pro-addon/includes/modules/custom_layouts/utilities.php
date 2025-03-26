<?php
/**
 * Helper functions for Custom Layouts
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Custom_Layouts;

trait Utilities {
	/**
	 * Get priority value of the given custom layout post.
	 *
	 * @param  int     $post_id Custom layout post ID.
	 * @param  boolean $is_new Is that a new custom layout that haven't saved yet?.
	 * @return int
	 */
	private static function get_priority( $post_id, $is_new = false ) {
		$priority               = get_post_meta( $post_id, 'custom-layout-options-priority-v2', true );
		$backward_default_value = 1; // backward compatibility for old users.

		if ( empty( $priority ) && $priority !== 0 ) {
			if ( $is_new ) {
				return 10;
			}

			return $backward_default_value;
		}

		return $priority;
	}
}
