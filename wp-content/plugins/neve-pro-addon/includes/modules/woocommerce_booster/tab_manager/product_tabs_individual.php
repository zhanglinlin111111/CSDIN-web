<?php
/**
 * Handles the individual tabs configuration in single product admin page.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager\Admin
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Tab_Manager;

/**
 * Class Single_Tabs
 */
class Product_Tabs_Individual {
	use Utilities;

	/**
	 * Initialize tabs in product edit.
	 */
	public function init() {
		add_action( 'woocommerce_product_data_tabs', [ $this, 'add_product_tabs_panel' ] );
		add_action( 'woocommerce_product_data_panels', [ $this, 'render_product_tabs' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'product_tabs_enqueue_scripts' ] );
		add_action( 'init', [ $this, 'register_tabs_data_meta' ] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_tabs_data' ] );
	}

	/**
	 * Add Product tabs tab in Product data panel.
	 *
	 * @return array
	 */
	public function add_product_tabs_panel( $tabs ) {
		$tabs['neve_product_tabs'] = [
			'label'  => esc_html__( 'Product tabs', 'neve' ),
			'target' => 'nv-product-tabs',
			'class'  => [ 'show_if_simple', 'show_if_variable' ],
		];
		return $tabs;
	}

	/**
	 * Get the global tabs defined in Product tabs cpt.
	 *
	 * @param string $type Type of tabs to retreive.
	 *
	 * @return array
	 */
	private function get_global_tabs_data( $type = '' ) {
		$global_tabs_data = [];
		$args             = [
			'post_type'   => 'neve_product_tabs',
			'post_status' => 'publish',
			'orderby'     => 'menu_order title',
			'order'       => 'ASC',
			'numberposts' => - 1,
		];

		$tabs = get_posts( $args );
		if ( empty( $tabs ) ) {
			return $global_tabs_data;
		}

		if ( $type === 'core' ) {
			return $this->get_core_tabs_data( $tabs );
		}

		foreach ( $tabs as $tab ) {
			$global_tabs_data[] = [
				'id'      => $tab->ID,
				'title'   => ! empty( $tab->post_title ) ? $tab->post_title : esc_html__( 'Custom tab', 'neve' ),
				'type'    => $this->is_core_tab( $tab->ID ) ? 'core' : 'global',
				'editUrl' => get_edit_post_link( $tab->ID, '' ),
				'slug'    => $tab->post_name,
			];
		}
		return $global_tabs_data;
	}

	/**
	 * Get core tabs data in the same order as in CPT
	 *
	 * @param array $tabs CPT tabs.
	 */
	private function get_core_tabs_data( $tabs ) {
		$global_tabs_data = [];
		foreach ( $tabs as $tab ) {
			if ( ! $this->is_core_tab( $tab->ID ) ) {
				continue;
			}
			$is_visible = get_post_meta( $tab->ID, 'nv_tab_visibility', true );
			if ( $is_visible === 'no' ) {
				continue;
			}
			$global_tabs_data[] = [
				'id'      => $tab->ID,
				'title'   => $tab->post_title,
				'type'    => 'core',
				'editUrl' => get_edit_post_link( $tab->ID, '' ),
				'slug'    => $tab->post_name,
			];
		}
		return $global_tabs_data;
	}

	/**
	 * Get the template for a tab item in the tab panel.
	 *
	 * @param string $type Tab type.
	 *
	 * @return string
	 */
	private function get_tab_template_by_type( $type ) {
		if ( ! in_array( $type, [ 'core', 'global', 'custom', 'general' ] ) ) {
			return '';
		}

		$is_general = $type === 'general';
		$is_core    = $type === 'core';
		$is_global  = $type === 'global';
		$is_custom  = $type === 'custom';

		$result = '<div class="woocommerce_attribute nv-product-tab wc-metabox" data-slug="%s">';

		$result .= '<h3>';
		$result .= '<button type="button" class="nv-remove-tab button">';
		$result .= esc_html__( 'Remove', 'neve' );
		$result .= '</button>';
		if ( ! $is_core || $is_general ) {
			$result .= '<div class="nv-tab-toggle" title="' . esc_attr__( 'Click to toggle', 'neve' ) . '" aria-expanded="true"></div>';
		}
		$result .= '<div class="nv-tab-handle"></div>';
		$result .= '<strong class="nv-tab-name">%s</strong>';
		$result .= '</h3>';

		if ( ! $is_core || $is_general ) {
			$result .= '<table class="nv-product-tab-data wc-metabox-content hidden">';
			if ( $is_global || $is_general ) {
				$result .= '<tr class="nv-global-tab-data">';
				$result .= '<td>';
				$result .= esc_html__( 'Content', 'neve' );
				$result .= '</td>';
				$result .= '<td>';
				$result .= '<a class="nv-edit-tab-content" href="%s" target="_blank">' . esc_html__( 'Edit tab content', 'neve' ) . '</a>';
				$result .= '</td>';
				$result .= '</tr>';
			}

			if ( $is_custom || $is_general ) {
				$result .= '<tr class="nv-custom-tab-data"><td><table>';

				$result .= '<tr>';
				$result .= '<td>';
				$result .= esc_html__( 'Tab title', 'neve' );
				$result .= '</td>';
				$result .= '<td>';
				$result .= '<input class="nv-custom-tab-title" type="text" value="%s" />';
				$result .= '</td>';
				$result .= '</tr>';

				$result .= '<tr>';
				$result .= '<td>';
				$result .= esc_html__( 'Custom content', 'neve' );
				$result .= '</td>';
				$result .= '<td>';
				$result .= '%s';
				$result .= '</td>';
				$result .= '</tr>';

				$result .= '</table></td></tr>';
			}
			$result .= '</table>';
		}
		$result .= '</div>';

		return $result;
	}

	/**
	 * Get wp_editor markup based on content.
	 *
	 * @param string $content Editor content.
	 * @param string $slug Field slug.
	 *
	 * @return false|string
	 */
	private function get_wc_editor_markup( $content, $slug ) {
		ob_start();
		wp_editor(
			$content,
			$slug,
			[
				'textarea_name' => $slug,
				'tinymce'       => false,
				'media_buttons' => false,
				'textarea_rows' => 10,
			]
		);
		return ob_get_clean();
	}

	/**
	 * Get the template for the general tab that will be cloned in js.
	 *
	 * @return string
	 */
	private function get_general_tab_template() {
		$general_template = $this->get_tab_template_by_type( 'general' );
		$content_field    = '<textarea type="text" class="nv-custom-tab-content" name="nv-custom-tab-content" rows="10"></textarea>';
		return sprintf( $general_template, '', esc_html__( 'New tab', 'neve' ), '', '', $content_field );
	}

	/**
	 * Escape function.
	 *
	 * @param string $markup Markup to escape.
	 *
	 * @return string
	 */
	private function escape_html( $markup ) {
		return neve_custom_kses_escape(
			$markup,
			[
				'input' => [
					'class' => [],
					'type'  => [],
					'value' => [],
				],
			]
		);
	}

	/**
	 * Render the content in product tabs.
	 */
	public function render_product_tabs() {
		global $post;
		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		$product_tabs_specific_data = get_post_meta( $post->ID, 'neve_tabs_data', true );
		$product_tabs_data          = ! empty( $product_tabs_specific_data ) ? json_decode( $product_tabs_specific_data, true ) : $this->get_global_tabs_data( 'core' );

		echo '
			<style type="text/css">
				#nv-product-tabs .toolbar { display: flex; justify-content: end; }
				.nv-tab-handle {
					margin-top: .25em;
				    cursor: move;
				    float: right;
				    margin-right: .5em;
				    vertical-align: middle;
				}
				.nv-tab-handle:before {
				    content: "\f333";
				    font-family: Dashicons;
				    text-align: center;
				    line-height: 28px;
				    color: #999;
				}
				.nv-tab-toggle {
				    margin-top: .25em;
			        width: 27px;
			        float: right;
				}
				.nv-tab-toggle:before {
				    content: "\f140";
				    cursor: pointer;
				    display: inline-block;
				    font: 400 20px/1 Dashicons;
				    line-height: .5!important;
				    padding: 8px 10px;
				    position: relative;
				    right: 12px;
				    top: 0;
				}
				#nv-product-tabs .wc-metabox table td input[type="button"] {
					width: auto;
					min-width: auto;
				}
				.nv-tab-block {
				    background-color: white;
				    height: 100%;
				    left: 0;
				    opacity: 0.6;
				    position: absolute;
				    top: 0;
				    width: 100%;
				}
			</style>';
		echo '<div id="nv-product-tabs" class="panel wc-metaboxes-wrapper">';
		echo '<p class="toolbar">';
		$override_tab_layout = get_post_meta( $post->ID, 'neve_override_tab_layout', true );
		echo '<label for="_override_tab_layout">';
		esc_html_e( 'Override default tab layout:', 'neve' );
		echo '</label>';
		echo '<input type="checkbox" name="neve_override_tab_layout" id="neve_override_tab_layout" ';
		checked( $override_tab_layout, 'on' );
		echo ' />';
		echo '</p>';

		echo '<div style="position:relative;">';
		echo '<div class="wc-metaboxes">';

		foreach ( $product_tabs_data as $tab_data ) {
			if ( ! array_key_exists( 'type', $tab_data ) ) {
				continue;
			}

			$template = $this->escape_html( $this->get_tab_template_by_type( $tab_data['type'] ) );
			$title    = ! empty( $tab_data['title'] ) ? $tab_data['title'] : esc_html__( 'Custom tab', 'neve' );
			switch ( $tab_data['type'] ) {
				case 'core':
					echo sprintf( $template, esc_attr( $tab_data['slug'] ), esc_html( $title ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
				case 'global':
					echo sprintf( $template, esc_attr( $tab_data['slug'] ), esc_html( $title ), esc_url( $tab_data['editUrl'] ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
				case 'custom':
					$content       = array_key_exists( 'content', $tab_data ) ? base64_decode( $tab_data['content'] ) : '';
					$content_field = $this->get_wc_editor_markup( $content, $tab_data['slug'] );
					echo sprintf( $template, esc_attr( $tab_data['slug'] ), esc_html( $title ), esc_html( $tab_data['title'] ), wp_kses_post( $content_field ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
			}
		}

		echo '</div>';

		echo '<p class="toolbar">';
		echo '<button type="button" class="button button-primary nv-add-tab">';
		esc_html_e( 'Add', 'neve' );
		echo '</button>';
		echo '<select name="nv-insert-new-tab" class="nv-insert-new-tab">';
		echo '<option value="">';
		esc_html_e( 'Custom Tab', 'neve' );
		echo '</option>';
		$global_tabs_data = $this->get_global_tabs_data();
		foreach ( $global_tabs_data as $tab ) {
			echo '<option value="' . esc_attr( $tab['slug'] ) . '">' . esc_html( $tab['title'] ) . '</option>';
		}
		echo '</select>';
		echo '</p>';
		echo '<div class="nv-tab-block" ';
		if ( $override_tab_layout === 'on' ) {
			echo 'style="display:none"';
		}
		echo '></div>';
		echo '<div class="nv-tab-template" style="display: none;">' . $this->escape_html( $this->get_general_tab_template() ) . '</div>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input class="neve_tab_data_collector" name="neve_tab_data_collector" type="hidden" value=\'' . esc_attr( wp_json_encode( $product_tabs_data ) ) . '\'/>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Enqueue scripts for product tabs.
	 */
	public function product_tabs_enqueue_scripts() {
		global $post;

		$screen_id = get_current_screen()->id;
		if ( 'product' !== $screen_id ) {
			return;
		}

		wp_enqueue_editor();
		wp_register_script(
			'nv-tm-specific-tabs',
			NEVE_PRO_INCLUDES_URL . 'modules/woocommerce_booster/tab_manager/assets/js/build/tab-manager-product.js',
			[ 'wp-api' ],
			NEVE_PRO_VERSION,
			true
		);

		wp_localize_script(
			'nv-tm-specific-tabs',
			'tmData',
			[
				'postid'         => $post->ID,
				'nonce'          => wp_create_nonce( 'neve-tm-specific-nonce' ),
				'globalTabsData' => $this->get_global_tabs_data(),
			]
		);

		wp_enqueue_script( 'nv-tm-specific-tabs' );
	}

	/**
	 * Register the meta where tabs are stored.
	 */
	public function register_tabs_data_meta() {
		register_post_meta(
			'product',
			'neve_tabs_data',
			[
				'show_in_rest' => true,
				'single'       => true,
			]
		);
		register_post_meta(
			'product',
			'neve_override_tab_layout',
			[
				'show_in_rest' => true,
				'single'       => true,
			]
		);
	}

	/**
	 * Save tabs data.
	 *
	 * @param  int $post_id the Product post ID.
	 * @return void
	 */
	public function save_tabs_data( $post_id ) {
		$override_tab_layout = array_key_exists( 'neve_override_tab_layout', $_POST ) ? sanitize_text_field( $_POST['neve_override_tab_layout'] ) : 'off';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $override_tab_layout !== 'on' ) {
			delete_post_meta( $post_id, 'neve_override_tab_layout' );
			delete_post_meta( $post_id, 'neve_tabs_data' );
			return;
		}

		update_post_meta( $post_id, 'neve_override_tab_layout', $override_tab_layout );

		$collector_data = array_key_exists( 'neve_tab_data_collector', $_POST ) ? sanitize_text_field( $_POST['neve_tab_data_collector'] ) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( empty( $collector_data ) ) {
			delete_post_meta( $post_id, 'neve_tabs_data' );
			return;
		}

		$data = json_decode( stripslashes( $collector_data ) );
		if ( is_null( $data ) ) {
			delete_post_meta( $post_id, 'neve_tabs_data' );
			return;
		}

		foreach ( $data as $key => $tab ) {
			if ( property_exists( $tab, 'content' ) ) {
				$decoded_input         = base64_decode( $tab->content );
				$sanitized_input       = wp_kses( $decoded_input, $this->get_allowed_html() );
				$sanitized_input       = force_balance_tags( $sanitized_input );
				$data[ $key ]->content = base64_encode( $sanitized_input );
			}
			if ( property_exists( $tab, 'title' ) ) {
				$data[ $key ]->title = esc_html( $data[ $key ]->title );
			}
		}

		update_post_meta( $post_id, 'neve_tabs_data', wp_json_encode( $data ) );
	}
}
