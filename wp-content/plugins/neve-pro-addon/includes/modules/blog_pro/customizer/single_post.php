<?php
/**
 * Single post customizer controls.
 *
 * @package Neve_Pro\Modules\Blog_Pro\Customizer
 */

namespace Neve_Pro\Modules\Blog_Pro\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve_Pro\Core\Loader;
use Neve_Pro\Traits\Core;


/**
 * Class Single_Post
 *
 * @package Neve_Pro\Modules\Blog_Pro\Customizer
 */
class Single_Post extends Base_Customizer {
	use Defaults\Single_Post;
	use Core;

	/**
	 * New Skin 3.0 flag.
	 *
	 * @var bool
	 */
	private $is_new_skin = false;

	/**
	 * Add customizer controls.
	 */
	public function add_controls() {
		$this->is_new_skin = neve_pro_is_new_skin();
		$this->headings();
		$this->comments();
		$this->sharing();
		$this->related_posts();
		$this->post_navigation();

		if ( $this->is_new_skin ) {
			$this->author_box();
		}
	}

	/**
	 * Add headings controls.
	 */
	private function headings() {
		$related_no_controls = $this->is_new_skin ? ( method_exists( $this, 'add_boxed_layout_controls' ) ? 10 : 6 ) : 4;
		$sharing_no_controls = $this->is_new_skin ? 4 : 1;

		$headings = [
			'sharing'       => [
				'label'            => esc_html__( 'Sharing icons', 'neve' ),
				'priority'         => 205,
				'expanded'         => false,
				'controls_to_wrap' => $sharing_no_controls,
				'active_callback'  => function () {
					return $this->element_is_enabled( 'sharing-icons' );
				},
			],
			'related_posts' => [
				'label'            => esc_html__( 'Related Posts', 'neve' ),
				'priority'         => 285,
				'expanded'         => false,
				'controls_to_wrap' => $related_no_controls,
				'active_callback'  => function () {
					return $this->element_is_enabled( 'related-posts' );
				},
			],
			'post_nav'      => [
				'label'            => esc_html__( 'Post Navigation', 'neve' ),
				'priority'         => 350,
				'expanded'         => false,
				'controls_to_wrap' => 1,
				'active_callback'  => '__return_true',
			],
		];

		if ( $this->is_new_skin ) {
			$headings['author_box'] = [
				'label'            => esc_html__( 'Author Box', 'neve' ),
				'priority'         => 230,
				'expanded'         => false,
				'controls_to_wrap' => method_exists( $this, 'add_boxed_layout_controls' ) ? 10 : 6,
				'active_callback'  => function () {
					return $this->element_is_enabled( 'author-biography' );
				},
			];
		}

		foreach ( $headings as $heading_id => $heading_data ) {
			$this->add_control(
				new Control(
					'neve_post_' . $heading_id . '_heading',
					[
						'sanitize_callback' => 'sanitize_text_field',
					],
					[
						'label'            => $heading_data['label'],
						'section'          => 'neve_single_post_layout',
						'priority'         => $heading_data['priority'],
						'class'            => $heading_id . '-accordion',
						'expanded'         => $heading_data['expanded'],
						'accordion'        => true,
						'controls_to_wrap' => $heading_data['controls_to_wrap'],
						'active_callback'  => $heading_data['active_callback'],
					],
					'Neve\Customizer\Controls\Heading'
				)
			);
		}
	}

	/**
	 * Add comments customizer controls.
	 */
	private function comments() {
		/**
		 * Heading for Related posts options
		 */
		$this->add_control(
			new Control(
				'neve_comments_heading',
				[
					'sanitize_callback' => 'sanitize_text_field',
				],
				[
					'label'            => esc_html__( 'Comments', 'neve' ),
					'section'          => 'neve_single_post_layout',
					'priority'         => 140,
					'class'            => 'comments-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 1,
					'active_callback'  => function () {
						return $this->element_is_enabled( 'comments' );
					},
				],
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_comment_section_style',
				[
					'default'           => 'always',
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				],
				[
					'label'           => esc_html__( 'Comment Section Style', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'priority'        => 145,
					'type'            => 'select',
					'choices'         => [
						'always' => esc_html__( 'Always Show', 'neve' ),
						'toggle' => esc_html__( 'Show/Hide mechanism', 'neve' ),
					],
					'active_callback' => function () {
						return $this->element_is_enabled( 'comments' );
					},
				]
			)
		);
	}

	/**
	 * Add single post sharing controls.
	 */
	public function sharing() {
		// Content
		$this->add_control(
			new Control(
				'neve_sharing_icons',
				[
					'sanitize_callback' => [ $this, 'sanitize_sharing_icons_repeater' ],
					'default'           => wp_json_encode( $this->social_icons_default() ),
				],
				[
					'label'           => esc_html__( 'Choose your social icons', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'fields'          => [
						'title'           => [
							'type'  => 'text',
							'label' => esc_html__( 'Title', 'neve' ),
						],
						'social_network'  => [
							'type'    => 'select',
							'label'   => __( 'Social Network', 'neve' ),
							'choices' => [
								'facebook'  => 'Facebook',
								'twitter'   => 'Twitter',
								'email'     => 'Email',
								'pinterest' => 'Pinterest',
								'linkedin'  => 'LinkedIn',
								'tumblr'    => 'Tumblr',
								'reddit'    => 'Reddit',
								'whatsapp'  => 'WhatsApp',
								'sms'       => 'SMS',
								'vk'        => 'VKontakte',
							],
						],
						'display_desktop' => [
							'type'  => 'checkbox',
							'label' => esc_html__( 'Show on Desktop', 'neve' ),
						],
						'display_mobile'  => [
							'type'  => 'checkbox',
							'label' => esc_html__( 'Show on Mobile', 'neve' ),
						],
					],
					'priority'        => 225,
					'active_callback' => function () {
						return $this->element_is_enabled( 'sharing-icons' );
					},
				],
				Loader::has_compatibility( 'repeater_control' ) ? '\Neve\Customizer\Controls\React\Repeater' : 'Neve_Pro\Customizer\Controls\Repeater'
			)
		);

		if ( ! $this->is_new_skin ) {
			return;
		}

		$this->add_control(
			new Control(
				'neve_sharing_icon_style',
				[
					'default'           => 'round',
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				],
				[
					'label'           => esc_html__( 'Icon style', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'priority'        => 210,
					'type'            => 'select',
					'choices'         => [
						'plain' => esc_html__( 'Plain', 'neve' ),
						'round' => esc_html__( 'Round', 'neve' ),
					],
					'active_callback' => function () {
						return $this->element_is_enabled( 'sharing-icons' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_sharing_enable_custom_color',
				[
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				],
				[
					'label'           => esc_html__( 'Use custom icon color', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'type'            => 'neve_toggle_control',
					'priority'        => 215,
					'active_callback' => function () {
						return $this->element_is_enabled( 'sharing-icons' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_sharing_custom_color',
				[
					'sanitize_callback' => 'neve_sanitize_colors',
					'transport'         => $this->selective_refresh,
					'default'           => 'var(--nv-primary-accent)',
				],
				[
					'label'                 => esc_html__( 'Custom icon color', 'neve' ),
					'section'               => 'neve_single_post_layout',
					'priority'              => 220,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--hex',
							'selector' => '.nv-social-icon a',
						],
						'template' => '
							.nv-post-share:not(.nv-is-boxed).custom-color .nv-social-icon svg,
							.nv-post-share:not(.nv-is-boxed).custom-color .nv-social-icon a svg  {
								fill: {{value}};
							}
							.nv-post-share.nv-is-boxed.custom-color .social-share,
							.nv-post-share.nv-is-boxed.custom-color .nv-social-icon a  {
								background-color: {{value}};
							}',
					],
					'active_callback'       => function () {
						return $this->element_is_enabled( 'sharing-icons' ) && get_theme_mod( 'neve_sharing_enable_custom_color', false );
					},
				],
				'Neve\Customizer\Controls\React\Color'
			)
		);
	}

	/**
	 * Add author box settings.
	 */
	public function author_box() {

		$this->add_control(
			new Control(
				'neve_author_box_enable_avatar',
				[
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => true,
				],
				[
					'label'           => esc_html__( 'Show author image', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'type'            => 'neve_toggle_control',
					'priority'        => 235,
					'active_callback' => function () {
						return $this->element_is_enabled( 'author-biography' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_author_box_avatar_size',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => $this->selective_refresh,
					'default'           => '{ "mobile": 100, "tablet": 100, "desktop": 100 }',
				],
				[
					'label'                 => esc_html__( 'Image Size', 'neve' ),
					'section'               => 'neve_single_post_layout',
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 100,
						'defaultVal' => [
							'mobile'  => 100,
							'tablet'  => 100,
							'desktop' => 100,
						],
						'units'      => [ 'px' ],
					],
					'priority'              => 240,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'selector'   => '.nv-author-biography',
							'vars'       => '--avatarSize',
							'suffix'     => 'px',
							'responsive' => true,
						],
						'responsive' => true,
						'prop'       => 'width',
						'template'   => '.nv-author-bio-image {
							width: {{value}}px;
						}',
					],
					'active_callback'       => function () {
						return $this->element_is_enabled( 'author-biography' ) && get_theme_mod( 'neve_author_box_enable_avatar', true );
					},
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_author_box_avatar_position',
				[
					'sanitize_callback' => 'wp_filter_nohtml_kses',
					'transport'         => $this->is_new_skin ? 'refresh' : $this->selective_refresh,
					'default'           => 'left',
				],
				[
					'label'                 => esc_html__( 'Image position', 'neve' ),
					'section'               => 'neve_single_post_layout',
					'priority'              => 245,
					'choices'               => [
						'left'   => [
							'tooltip' => esc_html__( 'Left', 'neve' ),
							'icon'    => 'align-left',
						],
						'center' => [
							'tooltip' => esc_html__( 'Center', 'neve' ),
							'icon'    => 'align-center',
						],
						'right'  => [
							'tooltip' => esc_html__( 'Right', 'neve' ),
							'icon'    => 'align-right',
						],
					],
					'show_labels'           => true,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'template' =>
							'.nv-author-biography .nv-author-elements-wrapper {
							    flex-direction: {{value}}!important;
					    	}',
					],
					'active_callback'       => function () {
						return $this->element_is_enabled( 'author-biography' ) && get_theme_mod( 'neve_author_box_enable_avatar' ) === true;
					},
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);

		$this->add_control(
			new Control(
				'neve_author_box_avatar_border_radius',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => 0,
				],
				[
					'label'                 => esc_html__( 'Border Radius', 'neve' ),
					'section'               => 'neve_single_post_layout',
					'type'                  => 'neve_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 50,
						'defaultVal' => 0,
					],
					'priority'              => 250,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--borderRadius',
							'suffix'   => '%',
							'selector' => '.nv-author-biography',
						],
						'fallback' => 0,
						'template' =>
							'.nv-author-bio-image {
							    border-radius: {{value}}px;
					    	}',
					],
					'active_callback'       => function () {
						return $this->element_is_enabled( 'author-biography' ) && get_theme_mod( 'neve_author_box_enable_avatar' ) === true;
					},
				],
				'\Neve\Customizer\Controls\React\Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_author_box_enable_archive_link',
				[
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				],
				[
					'label'           => esc_html__( 'Show archive link', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'type'            => 'neve_toggle_control',
					'priority'        => 255,
					'active_callback' => function () {
						return $this->element_is_enabled( 'author-biography' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_author_box_content_alignment',
				[
					'sanitize_callback' => 'wp_filter_nohtml_kses',
					'transport'         => $this->selective_refresh,
					'default'           => 'left',
				],
				[
					'label'                 => esc_html__( 'Content alignment', 'neve' ),
					'section'               => 'neve_single_post_layout',
					'priority'              => 260,
					'choices'               => [
						'left'   => [
							'tooltip' => esc_html__( 'Left', 'neve' ),
							'icon'    => 'editor-alignleft',
						],
						'center' => [
							'tooltip' => esc_html__( 'Center', 'neve' ),
							'icon'    => 'editor-aligncenter',
						],
						'right'  => [
							'tooltip' => esc_html__( 'Right', 'neve' ),
							'icon'    => 'editor-alignright',
						],
					],
					'show_labels'           => true,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'template' =>
							'.nv-author-bio-text-wrapper {
							    text-align: {{value}}!important;
					    	}',
					],
					'active_callback'       => function () {
						return $this->element_is_enabled( 'author-biography' );
					},
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);

		if ( method_exists( $this, 'add_boxed_layout_controls' ) ) {
			$this->add_boxed_layout_controls(
				'author_box',
				[
					'priority'                => 265,
					'section'                 => 'neve_single_post_layout',
					'padding_default'         => $this->responsive_padding_default(),
					'background_default'      => 'var(--nv-light-bg)',
					'color_default'           => 'var(--nv-text-color)',
					'boxed_selector'          => '.nv-author-biography.nv-is-boxed',
					'text_color_css_selector' => '.nv-author-biography.nv-is-boxed, .nv-author-biography.nv-is-boxed a',
					'toggle_active_callback'  => function () {
						return $this->element_is_enabled( 'author-biography' );
					},
					'active_callback'         => function () {
						return $this->element_is_enabled( 'author-biography' ) && get_theme_mod( 'neve_author_box_boxed_layout', false );
					},
				]
			);
		}
	}

	/**
	 * Add post navigation related controls.
	 */
	public function post_navigation() {
		$this->add_control(
			new Control(
				'neve_post_nav_infinite',
				[
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				],
				[
					'label'       => esc_html__( 'Enable infinite scroll', 'neve' ),
					'description' => apply_filters( 'neve_external_link', 'https://bit.ly/nv-sp-inf', __( 'View more details about this', 'neve' ) ),
					'section'     => 'neve_single_post_layout',
					'type'        => 'neve_toggle_control',
					'priority'    => 360,
				]
			)
		);
	}

	/**
	 * Related Posts customizer controls
	 */
	public function related_posts() {

		$this->add_control(
			new Control(
				'neve_related_posts_title',
				[
					'sanitize_callback' => 'wp_kses_post',
					'default'           => esc_html__( 'Related Posts', 'neve' ),
				],
				[
					'priority'        => 290,
					'section'         => 'neve_single_post_layout',
					'label'           => esc_html__( 'Title', 'neve' ),
					'type'            => 'text',
					'active_callback' => function () {
						return $this->element_is_enabled( 'related-posts' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_related_posts_taxonomy',
				[
					'default'           => 'category',
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				],
				[
					'label'           => esc_html__( 'Related Posts By', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'priority'        => 295,
					'type'            => 'select',
					'choices'         => [
						'category' => esc_html__( 'Categories', 'neve' ),
						'post_tag' => esc_html__( 'Tags', 'neve' ),
					],
					'active_callback' => function () {
						return $this->element_is_enabled( 'related-posts' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_related_posts_number',
				[
					'sanitize_callback' => 'absint',
					'default'           => 3,
				],
				[
					'label'           => esc_html__( 'Number of Related Posts', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'input_attrs'     => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
					'priority'        => 305,
					'type'            => 'number',
					'active_callback' => function () {
						return $this->element_is_enabled( 'related-posts' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_related_posts_excerpt_length',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => 25,
				],
				[
					'label'           => esc_html__( 'Excerpt Length', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'step'            => 5,
					'input_attr'      => [
						'min'     => 5,
						'max'     => 300,
						'default' => 25,
					],
					'input_attrs'     => [
						'min'     => 5,
						'max'     => 300,
						'default' => 25,
					],
					'priority'        => 310,
					'active_callback' => function () {
						return $this->element_is_enabled( 'related-posts' );
					},
				],
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);

		if ( ! $this->is_new_skin ) {
			return;
		}

		$this->add_control(
			new Control(
				'neve_related_posts_columns',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => 3,
				],
				[
					'label'           => esc_html__( 'Columns', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'input_attrs'     => [
						'min' => 1,
						'max' => 6,
					],
					'priority'        => 300,
					'type'            => 'number',
					'active_callback' => function () {
						return $this->element_is_enabled( 'related-posts' );
					},
				]
			)
		);

		$this->add_control(
			new Control(
				'neve_related_posts_enable_featured_image',
				[
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => true,
				],
				[
					'label'           => esc_html__( 'Show featured image', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'type'            => 'neve_toggle_control',
					'priority'        => 315,
					'active_callback' => function () {
						return $this->element_is_enabled( 'related-posts' );
					},
				]
			)
		);

		if ( method_exists( $this, 'add_boxed_layout_controls' ) ) {
			$this->add_boxed_layout_controls(
				'related_posts',
				[
					'priority'                  => 320,
					'section'                   => 'neve_single_post_layout',
					'padding_default'           => $this->responsive_padding_default(),
					'background_default'        => 'var(--nv-light-bg)',
					'color_default'             => 'var(--nv-text-color)',
					'boxed_selector'            => '.nv-related-posts.nv-is-boxed',
					'border_color_css_selector' => '.nv-related-posts.nv-is-boxed .posts-wrapper .related-post .content',
					'text_color_css_selector'   => '.nv-related-posts.nv-is-boxed, .nv-related-posts.nv-is-boxed a',
					'toggle_active_callback'    => function () {
						return $this->element_is_enabled( 'related-posts' );
					},
					'active_callback'           => function () {
						return $this->element_is_enabled( 'related-posts' ) && get_theme_mod( 'neve_related_posts_boxed_layout', false );
					},
				]
			);
		}
	}

	/**
	 * Active callback for sharing controls.
	 *
	 * @param string $element Post page element.
	 *
	 * @return bool
	 */
	public function element_is_enabled( $element ) {
		$default_order = apply_filters(
			'neve_single_post_elements_default_order',
			array(
				'title-meta',
				'thumbnail',
				'content',
				'tags',
				'comments',
			)
		);

		$content_order = get_theme_mod( 'neve_layout_single_post_elements_order', wp_json_encode( $default_order ) );
		$content_order = json_decode( $content_order, true );
		if ( ! in_array( $element, $content_order, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sanitize sharing order.
	 *
	 * @param string $value Value from the control.
	 *
	 * @return string
	 */
	public function sanitize_sharing_icons_repeater( $value ) {
		$default_value = apply_filters( 'neve_sharing_icons_default_value', $this->social_icons_default() );
		$fields        = array(
			'title',
			'social_network',
			'visibility',
		);
		$valid         = $this->sanitize_repeater_json( $value, $fields );

		if ( $valid === false ) {
			return wp_json_encode( $default_value );
		}

		return $value;
	}
}
