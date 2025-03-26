<?php
/**
 * SVG markup for Scroll to top icons.
 *
 * Author:          Uriahs Victor
 * Created on:      07/01/2022 (d/m/y)
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Scroll_To_Top;

/**
 * Class Icons
 * 
 * @package Neve_Pro\Modules\Scroll_To_Top
 */
class Icons {

	/**
	 * Get the icon based on the style
	 *
	 * @param string $style The icon style to retrieve.
	 * @return string The SVG markup for the icon.
	 */
	public function get_icon_svg( $style ) {
		
		$icons = array(
			'stt-icon-style-1' => '<svg class="scroll-to-top-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><rect width="15" height="15" fill="none"/><path fill="currentColor" d="M2,8.48l-.65-.65a.71.71,0,0,1,0-1L7,1.14a.72.72,0,0,1,1,0l5.69,5.7a.71.71,0,0,1,0,1L13,8.48a.71.71,0,0,1-1,0L8.67,4.94v8.42a.7.7,0,0,1-.7.7H7a.7.7,0,0,1-.7-.7V4.94L3,8.47a.7.7,0,0,1-1,0Z"/></svg>',
			'stt-icon-style-2' => '<svg class="scroll-to-top-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><rect width="15" height="15" fill="none"/><path fill="currentColor" d="M14,12a1,1,0,0,1-.73-.32L7.5,5.47,1.76,11.65a1,1,0,0,1-1.4,0A1,1,0,0,1,.3,10.3l6.47-7a1,1,0,0,1,1.46,0l6.47,7a1,1,0,0,1-.06,1.4A1,1,0,0,1,14,12Z"/></svg>',
			'stt-icon-style-3' => '<svg class="scroll-to-top-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><rect width="15" height="15" fill="none"/><path fill="currentColor" d="M14.71,10.3l-6.48-7a1,1,0,0,0-1.46,0l-6.48,7A1,1,0,0,0,1,12H14a1,1,0,0,0,.73-1.68Z"/></svg>',
			'stt-icon-style-4' => '<svg class="scroll-to-top-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><rect width="15" height="15" fill="none"/><path fill="currentColor" d="M13,15a1,1,0,0,1-.74-.32L7.5,9.46l-4.76,5.2A1,1,0,1,1,1.26,13.3l5.5-6a1,1,0,0,1,1.48,0l5.5,6a1,1,0,0,1-.06,1.42A1.05,1.05,0,0,1,13,15Z"/><path fill="currentColor" d="M13,8a1,1,0,0,1-.74-.33L7.5,2.49,2.74,7.68A1,1,0,0,1,1.26,6.33l5.5-6a1,1,0,0,1,1.48,0l5.5,6A1,1,0,0,1,13,8Z"/></svg>',
			'stt-icon-style-5' => '<svg class="scroll-to-top-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><rect width="15" height="15" fill="none"/><path fill="currentColor" d="M2,10.91l-.65-.65a.69.69,0,0,1,0-1L7,3.57a.72.72,0,0,1,1,0l5.69,5.7a.71.71,0,0,1,0,1l-.65.65a.71.71,0,0,1-1,0L8.67,7.37v6.56a.7.7,0,0,1-.7.7H7a.7.7,0,0,1-.7-.7V7.37L3,10.9A.69.69,0,0,1,2,10.91Z"/><rect fill="currentColor" x="1" y="0.37" width="13" height="2" rx="0.4"/></svg>',
			'stt-icon-style-6' => '<svg class="scroll-to-top-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><rect width="15" height="15" fill="none"/><path fill="currentColor" d="M7.86,1.93l5.83,10.2a.8.8,0,0,1-1.08,1.1L8,10.65a.83.83,0,0,0-.78,0L2.39,13.36a.79.79,0,0,1-1.1-1L6.45,2A.8.8,0,0,1,7.86,1.93Z"/></svg>',
		);

		return $icons[ $style ] ?? '';
		
	}
}
