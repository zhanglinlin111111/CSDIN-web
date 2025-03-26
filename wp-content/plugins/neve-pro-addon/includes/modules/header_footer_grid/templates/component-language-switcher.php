<?php
/**
 * Template used for component rendering wrapper.
 *
 * Name:    Header Footer Grid
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Templates;

$translation_plugins = array(
	'wpml'           => defined( 'ICL_SITEPRESS_VERSION' ),
	'translatepress' => defined( 'TRP_PLUGIN_VERSION' ),
	'polylang'       => defined( 'POLYLANG_VERSION' ),
	'weglot'         => defined( 'WEGLOT_VERSION' ),
);
$selected_plugin     = null;
foreach ( $translation_plugins as $key => $key_status ) {
	if ( $key_status !== true ) {
		continue;
	}
	$selected_plugin = $key;
	break;
}

?>
<div class="component-wrap">
	<?php
	if ( $selected_plugin === 'polylang' && function_exists( 'pll_the_languages' ) ) {
		echo '<ul class="nv--lang-switcher nv--pll">';
		pll_the_languages(
			array(
				'show_flags' => 1,
				'show_names' => 1,
				'dropdown'   => 0,
			)
		);
		echo '</ul>';
	}

	if ( $selected_plugin === 'translatepress' ) {
		echo '<div class="nv--lang-switcher nv--tlp">';
		echo preg_replace( '#<script(.*?)>(.*?)</script>#is', '', do_shortcode( '[language-switcher]' ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}

	if ( $selected_plugin === 'wpml' ) {
		echo '<div class="nv--lang-switcher nv--wpml">';
		do_action(
			'wpml_language_switcher',
			array(
				'flags'      => 1,
				'native'     => 0,
				'translated' => 0,
			)
		);
		echo '</div>';
	}

	if ( $selected_plugin === 'weglot' ) {
		echo '<div class="nv--lang-switcher nv--weglot">';
		echo do_shortcode( '[weglot_switcher]' );
		echo '</div>';
	}
	?>
</div>
