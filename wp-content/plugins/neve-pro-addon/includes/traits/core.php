<?php
/**
 * Core traits, shared with other classes.
 *
 * Name:    Neve Pro Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve_Pro
 */

namespace Neve_Pro\Traits;

/**
 * Trait Core
 *
 * @package Neve_Pro\Traits
 */
trait Core {

	/**
	 * License tier map.
	 *
	 * @var array
	 */
	public $tier_map = [
		1 => 1,
		2 => 1,
		3 => 2,
		4 => 2,
		5 => 3,
		6 => 3,
		7 => 1,
		8 => 2,
		9 => 3,
	];

	/**
	 * Recursive wp_parse_args.
	 * Extends parse args for nested arrays.
	 *
	 * @param array $target The target array.
	 * @param array $default The defaults array.
	 *
	 * @return array
	 */
	public function rec_wp_parse_args( &$target, $default ) {
		$target  = (array) $target;
		$default = (array) $default;
		$result  = $default;
		foreach ( $target as $key => &$value ) {
			if ( is_array( $value ) && isset( $result[ $key ] ) ) {
				$result[ $key ] = $this->rec_wp_parse_args( $value, $result[ $key ] );
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * Sanitize the repeater control.
	 *
	 * @param string $value json value.
	 * @param array  $must_have_fields array of must have fields for repeater.
	 *
	 * @return bool
	 */
	public function sanitize_repeater_json( $value, $must_have_fields = array( 'visibility' ) ) {
		$decoded = json_decode( $value, true );

		if ( ! is_array( $decoded ) ) {
			return false;
		}
		foreach ( $decoded as $item ) {
			if ( ! is_array( $item ) ) {
				return false;
			}

			foreach ( $must_have_fields as $field_key ) {
				if ( ! array_key_exists( $field_key, $item ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * License type.
	 *
	 * @return int
	 */
	public function get_license_type() {
		$status = $this->get_license_data();
		if ( $status === false ) {
			return -1;
		}

		if ( ! isset( $status->price_id ) ) {
			return -1;
		}

		if ( isset( $status->license ) && ( $status->license !== 'valid' && $status->license !== 'active_expired' ) ) {
			return - 1;
		}

		if ( ! array_key_exists( $status->price_id, $this->tier_map ) ) {
			return -1;
		}

		return (int) $this->tier_map[ $status->price_id ];
	}

	/**
	 * Get the license data.
	 *
	 * @return bool|\stdClass
	 */
	public function get_license_data() {
		$option_name = basename( dirname( NEVE_PRO_BASEFILE ) );
		$option_name = str_replace( '-', '_', strtolower( trim( $option_name ) ) );
		return get_option( $option_name . '_license_data' );
	}

	/**
	 * Enqueue with RTL support.
	 *
	 * @param string $handle style handle.
	 * @param string $src style src.
	 * @param array  $dependencies dependencies.
	 * @param string $version version.
	 */
	public function rtl_enqueue_style( $handle, $src, $dependencies, $version ) {
		wp_register_style( $handle, $src, $dependencies, $version );
		wp_style_add_data( $handle, 'rtl', 'replace' );
		wp_style_add_data( $handle, 'suffix', '.min' );
		wp_enqueue_style( $handle );
	}

	/**
	 * Wrapper for wp_remote_get.
	 */
	public function remote_get( $url ) {
		return function_exists( 'vip_safe_wp_remote_get' )
			? vip_safe_wp_remote_get( $url )
			: wp_remote_get( $url ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
	}

	/**
	 * Wrapper for attachment_url_to_postid.
	 */
	public function attachment_url_to_postid( $url ) {
		return function_exists( 'wpcom_vip_attachment_url_to_postid' )
			? wpcom_vip_attachment_url_to_postid( $url )
			: attachment_url_to_postid( $url ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.attachment_url_to_postid_attachment_url_to_postid
	}

	/**
	 * Get the license expiration date.
	 *
	 * @param string $format format of the date.
	 * @return false|string
	 */
	private function get_license_expiration_date( $format = 'F Y' ) {
		$data = $this->get_license_data();
		if ( isset( $data->expires ) ) {
			$parsed = date_parse( $data->expires );
			$time   = mktime( $parsed['hour'], $parsed['minute'], $parsed['second'], $parsed['month'], $parsed['day'], $parsed['year'] );
			return gmdate( $format, $time );
		}
		return false;
	}

	/**
	 * Helper method to flush rules on particular actions.
	 *
	 * @param string $key Key action.
	 */
	private function maybe_flush_rules( $key ) {
		$option = 'nv_' . $key . '_rules_flushed';
		if ( get_option( $option ) === 'yes' ) {
			return;
		}
		update_option( $option, 'yes' );
		flush_rewrite_rules(); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
	}

	/**
	 * Wrapper to add additional filter for block processing
	 * Reported and suggested here: https://github.com/WordPress/gutenberg/issues/17358
	 * Based on https://github.com/WordPress/WordPress/blob/965fcddcf68cf4fd122ae24b992e242dfea1d773/wp-includes/blocks.php#L755
	 *
	 * This method is invoked from the inside Inside_Layout class for custom layouts and from the Performance\Module.
	 *
	 * @param string $content The content string.
	 * @retun string
	 */
	public function process_content_blocks( $content ) {
		$output = '';
		$blocks = parse_blocks( $content );
		foreach ( $blocks as $block ) {
			$block_output = render_block( $block );
			$block_output = apply_filters( 'render_block_top_level_only', $block_output, $block );
			$output       = $output . $block_output;
		}

		// If there are blocks in this content, we shouldn't run wpautop() on it later.
		$priority = has_filter( 'the_content', 'wpautop' );
		if ( $priority !== false && doing_filter( 'the_content' ) && has_blocks( $content ) ) {
			remove_filter( 'the_content', 'wpautop', $priority );
			add_filter( 'the_content', '_restore_wpautop_hook', $priority + 1 );
		}

		return $output;
	}
}
