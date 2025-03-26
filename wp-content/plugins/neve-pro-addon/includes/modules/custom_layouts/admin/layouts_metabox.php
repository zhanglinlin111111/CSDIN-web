<?php
/**
 * Class that adds the metabox for Custom Layouts custom post type.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin;

use Neve_Pro\Admin\Conditional_Display;
use Neve_Pro\Modules\Custom_Layouts\Utilities;

/**
 * Class Layouts_Metabox
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */
class Layouts_Metabox {
	use Utilities;

	/**
	 * Custom layouts location.
	 *
	 * @var array
	 */
	private $layouts;

	/**
	 * Root rules.
	 *
	 * @var array
	 */
	private $root_ruleset;

	/**
	 * End rules.
	 *
	 * @var array
	 */
	private $end_ruleset;

	/**
	 * Ruleset map.
	 *
	 * @var array
	 */
	private $ruleset_map;

	/**
	 * Conditional display instance.
	 *
	 * @var Conditional_Display
	 */
	private $conditional_display = null;

	/**
	 * Conditional logic value.
	 *
	 * @var string
	 */
	private $conditional_logic_value;

	/**
	 * Available dynamic tags ma[.
	 *
	 * @var array
	 */
	public static $magic_tags = array(
		'post_type'        => array(
			'general' => array( '{current_single_title}', '{current_single_excerpt}', '{current_single_url}', '{current_post_meta}', '{meta_author}', '{meta_date}', '{meta_category}', '{meta_comments}', '{meta_time_to_read}' ),
		),
		'archive_taxonomy' => array(
			'general' => array( '{archive_description}', '{archive_title}' ),
		),
		'archive_type'     => array(
			'general' => array( '{archive_description}', '{archive_title}', '{archive_url}' ),
			'author'  => array( '{author_avatar}', '{author_bio}', '{author_name}', '{author_url}' ),
			'date'    => array( '{date}' ),
		),
		'user_status'      => array(
			'general' => array( '{user_nicename}', '{display_name}', '{user_email}' ),
		),
		'user_role'        => array(
			'general' => array( '{user_nicename}', '{display_name}', '{user_email}' ),
		),
		'user'             => array(
			'general' => array( '{user_nicename}', '{display_name}', '{user_email}' ),
		),
	);

	/**
	 * Layouts_Metabox constructor.
	 */
	public function __construct() {
		$this->add_shop_available_tags();
		require_once get_template_directory() . '/globals/utilities.php';
	}

	/**
	 * Add shop available tags.
	 */
	private function add_shop_available_tags() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		self::$magic_tags['post_type']['product'] = array( '{product_price}', '{product_title}', '{cart_link}', '{checkout_link}' );

		$cart_page_id = get_option( 'woocommerce_cart_page_id' );
		if ( ! empty( $cart_page_id ) ) {
			self::$magic_tags['page'][ $cart_page_id ] = array( '{cart_total_currency_symbol}', '{cart_total}', '{currency_name}', '{currency_symbol}' );
		}

		return true;
	}

	/**
	 * Setup class properties.
	 */
	public function setup_props() {
		$this->conditional_display = new Conditional_Display();
		$this->layouts             = array(
			'individual' => __( 'Individual', 'neve' ),
			'header'     => __( 'Header', 'neve' ),
			'inside'     => __( 'Inside content', 'neve' ),
			'footer'     => __( 'Footer', 'neve' ),
			'hooks'      => __( 'Hooks', 'neve' ),
			'not_found'  => __( '404 Page', 'neve' ),
		);

		if ( defined( 'PWA_VERSION' ) ) {
			$this->layouts['offline']      = __( 'Offline Page', 'neve' );
			$this->layouts['server_error'] = __( 'Internal Server Error Page', 'neve' );
		}

		$this->root_ruleset = $this->conditional_display->get_root_ruleset();
		$this->end_ruleset  = $this->conditional_display->get_end_ruleset();
		$this->ruleset_map  = $this->conditional_display->get_ruleset_map();
	}

	/**
	 * Initialize function.
	 */
	public function init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'init', array( $this, 'setup_props' ), 999 );
		add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_post_data' ) );
	}

	/**
	 * Create meta box.
	 */
	public function create_meta_box() {
		$post_type = get_post_type();
		if ( $post_type !== 'neve_custom_layouts' ) {
			return;
		}
		add_meta_box(
			'custom-layouts-settings',
			__( 'Custom Layout Settings', 'neve' ),
			array( $this, 'meta_box_markup' ),
			$post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Save meta fields.
	 *
	 * @param int $post_id Post id.
	 */
	public function save_post_data( $post_id ) {
		$this->save_layout( $post_id, $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$this->save_hook( $post_id, $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$this->save_custom_hook( $post_id, $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$this->save_priority( $post_id, $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$this->save_conditional_rules( $post_id, $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$this->save_expiration_rules( $post_id, $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Save layout meta option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 *
	 * @return bool
	 */
	private function save_layout( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-custom-layout', $post ) ) {
			return false;
		}

		if ( (bool) $post['nv-custom-layout'] === false ) {
			delete_post_meta( $post_id, 'custom-layout-options-layout' );
		}

		$choices = array( 'header', 'footer', 'hooks', 'not_found', 'offline', 'server_error', 'individual', 'inside' );
		if ( ! in_array( $post['nv-custom-layout'], $choices, true ) ) {
			return false;
		}

		if ( $post['nv-custom-layout'] === 'inside' ) {
			$this->save_inside_options( $post_id, $post );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		update_post_meta(
			$post_id,
			'custom-layout-options-layout',
			$post['nv-custom-layout']
		);

		return true;
	}

	/**
	 * Save inside layout meta option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 *
	 * @return bool
	 */
	private function save_inside_options( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-custom-inside', $post ) || ! array_key_exists( 'nv-custom-events-no', $post ) ) {
			return false;
		}

		if ( ! in_array( $post['nv-custom-inside'], array( Inside_Layout::AFTER_HEADINGS, Inside_Layout::AFTER_BLOCKS ) ) ) {
			$post['nv-custom-inside'] = Inside_Layout::AFTER_HEADINGS;
		}

		update_post_meta(
			$post_id,
			'custom-layout-options-inside-display',
			sanitize_text_field( $post['nv-custom-inside'] )
		);

		update_post_meta(
			$post_id,
			'custom-layout-options-events-no',
			absint( $post['nv-custom-events-no'] )
		);

		return true;
	}

	/**
	 * Save hook meta option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 *
	 * @return bool
	 */
	private function save_hook( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-custom-hook', $post ) ) {
			return false;
		}

		$hooks           = neve_hooks();
		$available_hooks = array( 'custom' );
		foreach ( $hooks as $list_of_hooks ) {
			$available_hooks = array_merge( $available_hooks, $list_of_hooks );
		}

		if ( ! in_array( $post['nv-custom-hook'], $available_hooks, true ) ) {
			return false;
		}

		update_post_meta(
			$post_id,
			'custom-layout-options-hook',
			$post['nv-custom-hook']
		);

		return true;
	}

	/**
	 * Save custom hook option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 */
	private function save_custom_hook( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-specific-hook', $post ) ) {
			return;
		}

		$sanitized_input = sanitize_text_field( $post['nv-specific-hook'] );
		$sanitized_input = filter_var( $sanitized_input, FILTER_SANITIZE_STRING );
		update_post_meta(
			$post_id,
			'custom-layout-specific-hook',
			$sanitized_input
		);
	}

	/**
	 * Save priority meta option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 *
	 * @return bool
	 */
	private function save_priority( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-custom-priority', $post ) ) {
			return false;
		}
		update_post_meta(
			$post_id,
			'custom-layout-options-priority-v2',
			(int) $post['nv-custom-priority']
		);

		return true;
	}

	/**
	 * Save the conditional rules.
	 *
	 * @param int   $post_id post ID.
	 * @param array $post    $_POST variables.
	 */
	private function save_conditional_rules( $post_id, $post ) {
		if ( empty( $post['custom-layout-conditional-logic'] ) ) {
			return;
		}
		update_post_meta(
			$post_id,
			'custom-layout-conditional-logic',
			$post['custom-layout-conditional-logic']
		);
	}

	/**
	 * Save the expiration rules.
	 *
	 * @param int   $post_id post ID.
	 * @param array $post    $_POST variables.
	 */
	private function save_expiration_rules( $post_id, $post ) {
		update_post_meta(
			$post_id,
			'custom-layout-options-should-expire',
			isset( $post['nv-template-should-expire'] )
		);

		if ( ! array_key_exists( 'nv-expiration-date', $post ) ) {
			return;
		}

		update_post_meta(
			$post_id,
			'custom-layout-expiration-date',
			$this->sanitize_date_field( $post['nv-expiration-date'] )
		);
	}

	/**
	 * Sanitize the date field
	 *
	 * @param  string $input Input value.
	 *
	 * @return int|mixed|string
	 */
	private function sanitize_date_field( $input ) {
		$input = sanitize_text_field( $input );
		$input = filter_var( $input, FILTER_SANITIZE_STRING );

		$date = \DateTime::createFromFormat( 'Y-m-d\TH:i', $input );

		if ( $date && $date->format( 'Y-m-d\TH:i' ) === $input ) {
			return $input;
		}

		return current_time( 'Y-m-d\TH:i' );
	}

	/**
	 * Meta box HTML.
	 *
	 * @param \WP_Post $post Post.
	 */
	public function meta_box_markup( $post ) {
		// @var boolean $is_new if the current custom layout post is new (haven't saved yet)?
		$is_new = $post->post_status === 'auto-draft';

		$this->conditional_logic_value = $this->get_conditional_logic_value( $post );
		$is_header_layout              = get_post_meta( $post->ID, 'header-layout', true );
		$layout                        = get_post_meta( $post->ID, 'custom-layout-options-layout', true );
		echo '<table class="nv-custom-layouts-settings ' . ( $is_header_layout ? 'hidden' : '' ) . ' ">';
		echo '<tr>';
		echo '<td>';
		echo '<label>' . esc_html__( 'Layout', 'neve' ) . '</label>';
		echo '</td>';
		echo '<td>';
		echo '<select id="nv-custom-layout" name="nv-custom-layout">';
		echo '<option value="0">' . esc_html__( 'Select', 'neve' ) . '</option>';
		foreach ( $this->layouts as $layout_value => $layout_name ) {
			echo '<option ' . selected( $layout_value, $layout ) . ' value="' . esc_attr( $layout_value ) . '">' . esc_html( $layout_name ) . '</option>';
		}
		echo '</select>';
		if ( 'individual' === $layout ) {
			echo self::get_shortcode_info( $post->ID ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</td>';
		echo '</tr>';

		$hooks         = neve_hooks();
		$hook          = get_post_meta( $post->ID, 'custom-layout-options-hook', true );
		$class         = ( $layout !== 'hooks' ? 'hidden' : '' );
		$hide_hookname = apply_filters( 'neve_is_theme_whitelabeled', false ) || apply_filters( 'neve_is_plugin_whitelabeled', false );
		if ( ! empty( $hooks ) ) {
			echo '<tr class="' . esc_attr( $class ) . '">';
			echo '<td>';
			echo '<label>' . esc_html__( 'Hooks', 'neve' ) . '</label>';
			echo '</td>';
			echo '<td>';
			echo '<select id="nv-custom-hook" name="nv-custom-hook">';
			echo '<option disabled selected value>' . esc_html__( 'Select a hook', 'neve' ) . '</option>';
			echo '<option ' . selected( 'custom', $hook ) . ' value="custom">' . esc_html__( 'Custom', 'neve' ) . '</option>';
			foreach ( $hooks as $hook_cat_slug => $hook_cat ) {
				echo '<optgroup label="' . esc_attr( ucwords( $hook_cat_slug ) ) . '">';
				foreach ( $hook_cat as $hook_value ) {
					$hook_label = View_Hooks::beautify_hook( $hook_value );
					echo '<option ' . selected( $hook_value, $hook ) . ' value="' . esc_attr( $hook_value ) . '">';
					echo esc_html( $hook_label );
					if ( $hide_hookname === false ) {
						echo ' (' . esc_html( $hook_value ) . ')';
					}
					echo '</option>';
				}
				echo '</optgroup>';
			}
			echo '</select>';
			echo '</td>';
			echo '</tr>';

			$custom_hook       = get_post_meta( $post->ID, 'custom-layout-specific-hook', true );
			$custom_hook_class = $hook === 'custom' && $layout === 'hooks' ? '' : 'hidden';
			echo '<tr id="nv-specific-hook-wrapper" class="' . esc_attr( $custom_hook_class ) . '">';
			echo '<td>';
			echo esc_html__( 'Custom hook name', 'neve' );
			echo '</td>';
			echo '<td>';
			echo '<input type="text" name="nv-specific-hook" value="' . esc_attr( $custom_hook ) . '"/>';
			echo '</td>';
			echo '</tr>';

			$priority = $this->get_priority( $post->ID, $is_new );
			echo '<tr class="' . esc_attr( $class ) . '">';
			echo '<td>';
			echo '<label>' . esc_html__( 'Priority', 'neve' ) . '</label>';
			echo '</td>';
			echo '<td>';
			echo '<input value="' . (int) $priority . '" type="number" id="nv-custom-priority" name="nv-custom-priority" min="1" max="150" step="1"/>';
			echo '</td>';
			echo '</tr>';

			$should_expire = get_post_meta( $post->ID, 'custom-layout-options-should-expire', true );
			echo '<tr>';
			echo '<td>';
			echo '<label>' . esc_html__( 'Enable expiration date', 'neve' ) . '</label>';
			echo '</td>';
			echo '<td>';
			echo '<input value="' . esc_attr( $should_expire ) . '" type="checkbox" id="nv-template-should-expire" name="nv-template-should-expire" ' . checked( $should_expire, true, false ) . ' />';
			echo '</td>';
			echo '</tr>';

			$expiration_date = get_post_meta( $post->ID, 'custom-layout-expiration-date', true );
			$time            = current_time( 'Y-m-d\TH:i' );
			echo '<tr id="nv-expiration-row" ' . ( $should_expire ? '' : 'class="hidden"' ) . '>';
			echo '<td>';
			echo '<label>' . esc_html__( 'Date and time', 'neve' ) . '</label>';
			echo '</td>';
			echo '<td>';
			echo '<input value="' . esc_attr( $expiration_date ) . '" min="' . esc_attr( date_i18n( 'Y-m-d\TH:i', $time ) ) . '" type="datetime-local" name="nv-expiration-date" />';
			echo '</td>';
			echo '</tr>';
		}

		$class             = ( $layout !== 'inside' ? 'hidden' : '' );
		$selected_position = get_post_meta( $post->ID, 'custom-layout-options-inside-display', true );
		$events_number     = get_post_meta( $post->ID, 'custom-layout-options-events-no', true );
		if ( empty( $events_number ) && $events_number !== 0 ) {
			$events_number = 1;
		}
		$inside_position = array(
			'after' => array(
				Inside_Layout::AFTER_HEADINGS => __( 'After certain number of headings', 'neve' ),
				Inside_Layout::AFTER_BLOCKS   => __( 'After certain number of blocks', 'neve' ),
			),
		);
		echo '<tr class="' . esc_attr( $class ) . '">';
		echo '<td>';
		echo '<label>' . esc_html__( 'Display', 'neve' ) . '</label>';
		echo '</td>';
		echo '<td>';
		echo '<select id="nv-custom-inside" name="nv-custom-inside">';
		foreach ( $inside_position as $slug => $position ) {
			echo '<optgroup label="' . esc_attr( ucwords( $slug ) ) . '">';
			foreach ( $position as $value => $label ) {
				echo '<option ' . selected( $value, $selected_position ) . ' value="' . esc_attr( $value ) . '">';
				echo esc_html( $label );
				echo '</option>';
			}
			echo '</optgroup>';
		}
		echo '</select>';
		echo '</td>';
		echo '</tr>';

		echo '<tr class="' . esc_attr( $class ) . '">';
		echo '<td>';
		echo '<label>' . esc_html__( 'Number', 'neve' ) . '</label>';
		echo '</td>';
		echo '<td>';
		echo '<input value="' . esc_attr( $events_number ) . '" type="number" id="nv-custom-events-no" name="nv-custom-events-no" min="1" max="150" step="1"/>';
		echo '</td>';
		echo '</tr>';

		echo '</table>';

		$this->render_conditional_logic_setup( $post );
		$this->render_rule_group_template();
		?>
		<input type="hidden" class="nv-conditional-meta-collector" name="custom-layout-conditional-logic"
				id="custom-layout-conditional-logic" value="<?php echo esc_attr( $this->conditional_logic_value ); ?>"/>
		<?php
	}

	/**
	 * Shortcode info for individual custom layouts.
	 *
	 * @param int $post_id Post id.
	 *
	 * @return string
	 */
	public static function get_shortcode_info( $post_id = 0 ) {
		$markup  = '<div class="nv-info nv-cl-shortcode">';
		$markup .= '<span class="dashicons dashicons-info-outline"></span>';
		$markup .= esc_html__( 'Available shortcode:', 'neve' );
		$markup .= '<span class="custom-layout-shortcode">';
		$markup .= '<span class="custom-layout-shortcode-text"> [nv-custom-layout id="' . esc_attr( (string) $post_id ) . '"]</span>';
		$markup .= '<span class="dashicons dashicons-edit-page"></span>';
		$markup .= '</span>';
		$markup .= '</div>';
		return $markup;
	}

	/**
	 * Get the conditional logic meta value.
	 *
	 * @param \WP_Post $post the post object.
	 *
	 * @return mixed|string
	 */
	private function get_conditional_logic_value( $post ) {
		$value = get_post_meta( $post->ID, 'custom-layout-conditional-logic', true );

		if ( empty( $value ) ) {
			$value = '{}';
		}

		return $value;
	}

	/**
	 * Render the conditional logic.
	 */
	private function render_conditional_logic_setup( $post ) {
		$value            = json_decode( $this->conditional_logic_value, true );
		$layout           = get_post_meta( $post->ID, 'custom-layout-options-layout', true );
		$class            = ( empty( $layout ) || in_array(
			$layout,
			[
				'not_found',
				'offline',
				'server_error',
				'individual',
			],
			true
		) ) ? 'hidden' : '';
		$is_header_layout = get_post_meta( $post->ID, 'header-layout', true );
		if ( $is_header_layout ) {
			$class = '';
		}
		?>
		<div id="nv-conditional" class="<?php echo esc_attr( $class ); ?>">
			<div>
				<label><?php echo esc_html__( 'Conditional Logic', 'neve' ); ?></label>
				<p class="<?php echo $is_header_layout ? 'hidden' : ''; ?>">
					<span class="dashicons dashicons-info"></span>
					<i>
						<?php echo esc_html__( 'If no conditional logic is selected, the Custom Layout will be applied site-wide.', 'neve' ); ?>
					</i>
				</p>
			</div>
			<div class="nv-rules-wrapper">
				<div class="nv-rule-groups">
					<?php
					if ( ! is_array( $value ) || empty( $value ) ) {
						$this->display_magic_tags();
						$this->render_rule_group();
					} else {
						$index = 0;
						foreach ( $value as $rule_group ) {
							$magic_tags = $this->get_magic_tags( $rule_group );
							$this->display_magic_tags( $magic_tags, $index );
							$this->render_rule_group( $rule_group );
							$index ++;
						}
					}
					?>
				</div>
				<div class="rule-group-actions">
					<button class="button button-primary nv-add-rule-group"><?php esc_html_e( 'Add Rule Group', 'neve' ); ?></button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get magic tags based on current rules.
	 *
	 * @param array $rule_group Set of rules.
	 *
	 * @return array
	 */
	private function get_magic_tags( $rule_group ) {
		$all_magic_tags = array();
		foreach ( $rule_group as $rule ) {
			if ( $rule['condition'] !== '===' ) {
				return array();
			}

			if ( empty( $rule['root'] ) || empty( $rule['end'] ) ) {
				return array();
			}

			if ( ! array_key_exists( $rule['root'], self::$magic_tags ) ) {
				return array();
			}

			$end_array = self::$magic_tags[ $rule['root'] ];
			if ( ! array_key_exists( $rule['end'], $end_array ) ) {
				return array();
			}

			$all_magic_tags = array_merge( $all_magic_tags, $end_array[ $rule['end'] ] );
		}

		return $all_magic_tags;
	}

	/**
	 * Render rule group.
	 *
	 * @param array $rules The rules.
	 */
	private function render_rule_group( $rules = array() ) {
		if ( empty( $rules ) ) {
			$rules[] = array(
				'root'      => '',
				'condition' => '===',
				'end'       => '',
			);
		}
		?>
		<div class="nv-rule-group-wrap">
			<div class="nv-rule-group">
				<div class="nv-group-inner">
					<?php foreach ( $rules as $rule ) { ?>
						<div class="individual-rule">
							<div class="rule-wrap root_rule">
								<select class="nv-slim-select root-rule">
									<option value="" <?php echo $rule['root'] === '' ? 'selected' : ''; ?>><?php echo esc_html__( 'Select', 'neve' ); ?></option>
									<?php
									foreach ( $this->root_ruleset as $option_group_slug => $option_group ) {
										echo '<optgroup label="' . esc_attr( $option_group['label'] ) . '">';
										foreach ( $option_group['choices'] as $slug => $label ) {
											echo '<option value="' . esc_attr( $slug ) . '" ' . ( $slug === $rule['root'] ? 'selected' : '' ) . ' >' . esc_html( $label ) . '</option>';
										}
										echo '</optgroup>';
									}
									?>
								</select>
							</div>
							<div class="rule-wrap condition">
								<select class="nv-slim-select condition-rule">
									<option value="==="
										<?php echo esc_attr( $rule['condition'] === '===' ? 'selected' : '' ); ?>>
										<?php echo esc_html__( 'is equal to', 'neve' ); ?></option>
									<option value="!=="
										<?php echo esc_attr( $rule['condition'] === '!==' ? 'selected' : '' ); ?>>
										<?php echo esc_html__( 'is not equal to', 'neve' ); ?></option>
								</select>
							</div>
							<div class="rule-wrap end_rule">
								<?php
								foreach ( $this->end_ruleset as $ruleset_slug => $options ) {
									$this->render_end_option( $ruleset_slug, $options, $rule['end'], $rule['root'] );
								}
								?>
							</div>
							<div class="actions-wrap">
								<button class="remove action button button-secondary">
									<i class="dashicons dashicons-no"></i>
								</button>
								<button class="duplicate action button button-primary">
									<i class="dashicons dashicons-plus"></i>
								</button>
							</div>
							<span class="operator and"><?php esc_html_e( 'AND', 'neve' ); ?></span>
						</div>
					<?php } ?>
				</div>
				<div class="rule-group-actions">
					<button class="button button-secondary nv-remove-rule-group"><?php esc_html_e( 'Remove Rule Group', 'neve' ); ?></button>
				</div>
			</div>
			<span class="operator or"><?php esc_html_e( 'OR', 'neve' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Display magic tags/
	 *
	 * @param array $magic_tags Array of magic tags.
	 *
	 * @return bool
	 */
	private function display_magic_tags( $magic_tags = array(), $index = 0 ) {
		echo '<div class="nv-magic-tags" id="nv-magic-tags-group-' . esc_attr( $index ) . '">';
		if ( ! empty( $magic_tags ) ) {
			echo '<p>' . esc_html__( 'You can add the following tags in your template:', 'neve' ) . '</p>';
			echo '<ul class="nv-available-tags-list">';
			foreach ( $magic_tags as $magic_tag ) {
				echo '<li>' . esc_html( $magic_tag ) . '</li>';
			}
			echo '</ul>';
		}
		echo '</div>';

		return true;
	}

	/**
	 * Render the end option.
	 *
	 * @param string $slug     the ruleset slug.
	 * @param array  $args     the ruleset options.
	 * @param string $end_val  the ruleset end value.
	 * @param string $root_val the ruleset root value.
	 */
	private function render_end_option( $slug, $args, $end_val, $root_val ) {
		?>
		<div class="single-end-rule <?php echo esc_attr( join( ' ', $this->ruleset_map[ $slug ] ) ); ?>">
			<select name="<?php echo esc_attr( $slug ); ?>"
					class="nv-slim-select end-rule">
				<option value="" <?php echo esc_attr( $end_val === '' ? 'selected' : '' ); ?>><?php echo esc_html__( 'Select', 'neve' ); ?></option>
				<?php
				switch ( $slug ) {
					case 'terms':
						foreach ( $args as $post_type_slug => $taxonomies ) {
							foreach ( $taxonomies as $taxonomy ) {
								if ( ! is_array( $taxonomy['terms'] ) || empty( $taxonomy['terms'] ) ) {
									continue;
								}
								echo '<optgroup label="' . esc_attr( $taxonomy['nicename'] ) . ' (' . esc_attr( $post_type_slug ) . ' - ' . esc_attr( $taxonomy['name'] ) . ')">';
								foreach ( $taxonomy['terms'] as $term ) {
									if ( ! $term instanceof \WP_Term ) {
										continue;
									}
									echo '<option value="' . esc_attr( $taxonomy['name'] ) . '|' . esc_attr( $term->slug ) . '" ' . esc_attr( ( $taxonomy['name'] ) . '|' . esc_attr( $term->slug ) === $end_val ? 'selected' : '' ) . '>' . esc_html( $term->name ) . '</option>';
								}
							}
							echo '</optgroup>';
						}
						break;
					case 'taxonomies':
						foreach ( $args as $post_type_slug => $taxonomies ) {
							foreach ( $taxonomies as $taxonomy ) {
								if ( ! is_array( $taxonomy['terms'] ) || empty( $taxonomy['terms'] ) ) {
									continue;
								}
								echo '<option value="' . esc_attr( $taxonomy['name'] ) . '" ' . esc_attr( (string) $taxonomy['name'] === $end_val ? 'selected' : '' ) . '>' . esc_html( $taxonomy['nicename'] . ' (' . $post_type_slug . ' - ' . $taxonomy['name'] ) . ')</option>';
							}
						}
						break;
					default:
						foreach ( $args as $value => $label ) {
							echo '<option value="' . esc_attr( $value ) . '" ' . esc_attr( (string) $value === $end_val ? 'selected' : '' ) . '>' . esc_html( $label ) . '</option>';
						}
						break;
				}
				?>
			</select>
		</div>
		<?php
	}

	/**
	 * Render the rule group template.
	 */
	private function render_rule_group_template() {
		?>
		<div class="nv-rule-group-template">
			<?php $this->render_rule_group(); ?>
		</div>
		<?php
	}
}
