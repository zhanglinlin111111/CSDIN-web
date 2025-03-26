<?php
/**
 * Class that handle the show/hide hooks.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin;

use WP_Admin_Bar;

/**
 * Class View_Hooks
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */
class View_Hooks {

	/**
	 * Initialize function.
	 */
	public function init() {
		if ( ! $this->should_load() ) {
			return;
		}
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 99 );
		add_action( 'wp', array( $this, 'render_hook_placeholder' ) );
	}

	/**
	 * Check user role before allowing the class to run
	 *
	 * @return bool
	 */
	private function should_load() {
		return current_user_can( 'administrator' );
	}

	/**
	 * Admin Bar Menu
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Admin bar menus.
	 */
	function admin_bar_menu( $wp_admin_bar ) {
		if ( is_admin() ) {
			return;
		}
		$title = __( 'Show Hooks', 'neve' );

		$href = add_query_arg( 'neve_preview_hook', 'show' );
		if ( isset( $_GET['neve_preview_hook'] ) && 'show' === $_GET['neve_preview_hook'] ) {
			$title = __( 'Hide Hooks', 'neve' );
			$href  = remove_query_arg( 'neve_preview_hook' );
		}

		$wp_admin_bar->add_menu(
			array(
				'title'  => $title,
				'id'     => 'neve_preview_hook',
				'parent' => false,
				'href'   => $href,
			)
		);
	}

	/**
	 * Beautify hook names.
	 *
	 * @param string $hook Hook name.
	 *
	 * @return string
	 */
	public static function beautify_hook( $hook ) {
		$hook_label = str_replace( '_', ' ', $hook );
		$hook_label = str_replace( 'neve', ' ', $hook_label );
		$hook_label = str_replace( 'nv', ' ', $hook_label );
		$hook_label = str_replace( 'woocommerce', ' ', $hook_label );
		$hook_label = ucwords( $hook_label );
		return $hook_label;
	}

	/**
	 * Render hook placeholder.
	 */
	public function render_hook_placeholder() {
		if ( ! isset( $_GET['neve_preview_hook'] ) || 'show' !== $_GET['neve_preview_hook'] ) {
			return;
		}
		$hooks         = neve_hooks();
		$hide_hookname = apply_filters( 'neve_is_theme_whitelabeled', false ) || apply_filters( 'neve_is_plugin_whitelabeled', false );
		foreach ( $hooks as $hook_category => $hooks_in_category ) {
			foreach ( $hooks_in_category as $hook_value ) {
				$hook_label = self::beautify_hook( $hook_value );
				add_action(
					$hook_value,
					function () use ( $hook_label, $hide_hookname, $hook_value ) {
						$css_style = 'width: 98%; margin: 10px auto; border: 2px dashed #e22222; font-size: 14px; padding: 6px 10px; display: inline-block; color: #404248; text-align: left; word-break: break-word;';
						echo '<div class="nv-hook-wrapper" style="text-align: center; width: 100%;">';
						echo '<div class="nv-hook-placeholder" style="' . esc_attr( $css_style ) . '">';
						echo esc_html( $hook_label );
						if ( $hide_hookname === false ) {
							echo ' (' . esc_html( $hook_value ) . ')';
						}
						echo '</div>';
						echo '</div>';
					}
				);
			}
		}
	}


}
