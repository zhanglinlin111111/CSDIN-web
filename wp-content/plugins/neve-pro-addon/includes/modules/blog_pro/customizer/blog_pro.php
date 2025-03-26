<?php
/**
 * Author:          Stefan Cotitosu <stefan@themeisle.com>
 * Created on:      2019-02-27
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Modules\Blog_Pro\Customizer;

use HFG\Traits\Core;
use Neve\Core\Settings\Mods;
use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve_Pro\Modules\Blog_Pro\Module;

/**
 * Class Blog_Pro
 *
 * @package Neve_Pro\Modules\Blog_Pro\Customizer
 */
class Blog_Pro extends Base_Customizer {
	use Core;

	/**
	 * Base initialization
	 */
	public function init() {

		parent::init();
		add_filter( 'neve_single_post_elements', array( $this, 'filter_single_post_elements' ) );
	}

	/**
	 * Add customizer section and controls
	 */
	public function add_controls() {

		$this->add_blog_layout_controls();
		$this->add_ordering_content_controls();
		$this->add_read_more_controls();

		if ( ! Module::has_single_compatibility() ) {
			$this->add_post_meta_controls();
		}

		add_action( 'customize_register', [ $this, 'adjust_headings' ], PHP_INT_MAX );
		add_action( 'customize_register', [ $this, 'adapt_old_lite' ], PHP_INT_MAX );
	}

	/**
	 * Adjust Headings.
	 */
	public function adjust_headings() {
		$this->change_customizer_object( 'control', 'neve_blog_layout_heading', 'controls_to_wrap', 20 );
		$this->change_customizer_object( 'control', 'neve_blog_ordering_content_heading', 'controls_to_wrap', 7 );
		if ( ! Module::has_single_compatibility() ) {
			$this->change_customizer_object( 'control', 'neve_blog_post_meta_heading', 'controls_to_wrap', 4 );
		}
	}

	/**
	 * Adapt to old version of lite.
	 */
	public function adapt_old_lite() {
		if ( version_compare( NEVE_VERSION, '2.8.0', '>' ) ) {
			return;
		}
		$changes = [
			'neve_blog_archive_layout'      => [ 'priority' => 11 ],
			'neve_blog_list_image_position' => [
				'choices' => [
					'left'  => [
						'tooltip' => __( 'Left', 'neve' ),
						'icon'    => 'align-pull-left',
					],
					'right' => [
						'tooltip' => __( 'Right', 'neve' ),
						'icon'    => 'align-pull-right',
					],
				],
			],
		];

		foreach ( $changes as $control_slug => $props ) {
			foreach ( $props as $prop => $new_value ) {
				$this->change_customizer_object( 'control', $control_slug, $prop, $new_value );
			}
		}
	}

	/**
	 * Add blog layout controls.
	 */
	private function add_blog_layout_controls() {
		$new_skin = neve_pro_is_new_skin();

		$this->add_control(
			new Control(
				'neve_blog_grid_spacing',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => '{ "mobile": 30, "tablet": 30, "desktop": 30 }',
				],
				[
					'label'                 => esc_html__( 'Grid Spacing', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 300,
						'units'      => [ 'px' ],
						'defaultVal' => [
							'mobile'  => 30,
							'tablet'  => 30,
							'desktop' => 30,
						],
					],
					'priority'              => 12,
					'active_callback'       => function () {
						return ! $this->is_list_layout();
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--gridSpacing',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.posts-wrapper',
						],
						'responsive' => true,
						'template'   =>
							'body .posts-wrapper > article.layout-covers,
                             body .posts-wrapper > article.layout-grid {
							    margin-bottom: {{value}}px;
							    padding-right: calc({{value}}px/2);
							    padding-left: calc({{value}}px/2);
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_list_spacing',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => $new_skin ? '{ "mobile": 60, "tablet": 60, "desktop": 60 }' : '{ "mobile": 30, "tablet": 30, "desktop": 30 }',
				],
				[
					'label'                 => esc_html__( 'List Spacing', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 300,
						'units'      => [ 'px' ],
						'defaultVal' => [
							'mobile'  => $new_skin ? 60 : 30,
							'tablet'  => $new_skin ? 60 : 30,
							'desktop' => $new_skin ? 60 : 30,
						],
					],
					'priority'              => 12,
					'active_callback'       => [ $this, 'is_list_layout' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--spacing',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.posts-wrapper',
						],
						'responsive' => true,
						'template'   =>
							'body .posts-wrapper .nv-non-grid-article {
							    margin-bottom: {{value}}px;
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_covers_min_height',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => '{ "mobile": 350, "tablet": 350, "desktop": 350 }',
				],
				[
					'label'                 => esc_html__( 'Card Min Height', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 1000,
						'units'      => [ 'px' ],
						'defaultVal' => [
							'mobile'  => 350,
							'tablet'  => 350,
							'desktop' => 350,
						],
					],
					'priority'              => 13,
					'active_callback'       => [ $this, 'is_covers_layout' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--height',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.posts-wrapper',
						],
						'responsive' => true,
						'template'   =>
							'body .cover-post .inner {
							    min-height: {{value}}px;
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_items_border_radius',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => 0,
				],
				[
					'label'                 => esc_html__( 'Border Radius', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'type'                  => 'neve_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 100,
						'defaultVal' => 0,
					],
					'priority'              => 13,
					'active_callback'       => function () {
						return ! $this->is_list_layout();
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--borderRadius',
							'suffix'   => 'px',
							'fallback' => '0',
							'selector' => '.posts-wrapper',
						],
						'fallback' => 0,
						'template' =>
							'body .cover-post, body .layout-grid .article-content-col .content {
							    border-radius: {{value}}px;
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_covers_overlay_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'default'           => 'rgba(0,0,0,0.75)',
					'transport'         => 'postMessage',
				),
				array(
					'label'                 => esc_html__( 'Overlay Color', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'priority'              => 14,
					'active_callback'       => [ $this, 'is_covers_layout' ],
					'default'               => 'rgba(0,0,0,0.75)',
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--overlay',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'body .cover-post:after {
							background-color: {{value}};
						}',
					],
				),
				'Neve\Customizer\Controls\React\Color'
			)
		);

		$content_padding_default = [
			'mobile'       => [
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			],
			'tablet'       => [
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			],
			'desktop'      => [
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			],
			'mobile-unit'  => 'px',
			'tablet-unit'  => 'px',
			'desktop-unit' => 'px',
		];

		$this->add_control(
			new Control(
				'neve_blog_content_padding',
				array(
					'sanitize_callback' => array( $this, 'sanitize_spacing_array' ),
					'transport'         => $new_skin ? 'refresh' : 'postMessage',
					'default'           => $content_padding_default,
				),
				array(
					'label'                 => esc_html__( 'Content Padding', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'priority'              => 15,
					'input_attrs'           => array(
						'units' => [ 'px', 'em' ],
						'min'   => 0,
					),
					'default'               => $content_padding_default,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => array(
						'responsive'  => true,
						'directional' => true,
						'template'    =>
						'body .cover-post .inner, 
						 body .nv-non-grid-article .content .non-grid-content,
						 body .nv-non-grid-article .content .non-grid-content.alternative-layout-content {
							padding-top: {{value.top}};
							padding-right: {{value.right}};
							padding-bottom: {{value.bottom}};
							padding-left: {{value.left}};
						}
						body .layout-grid .article-content-col .content {
						    padding-top: {{value.top}};
							padding-right: {{value.right}};
							padding-bottom: {{value.bottom}};
							padding-left: {{value.left}};
						}
						body .layout-grid .article-content-col .nv-post-thumbnail-wrap{
							margin-right: -{{value.right}};
							margin-left: -{{value.left}};
						}
						',
					),
				),
				'\Neve\Customizer\Controls\React\Spacing'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_show_on_hover',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'type'            => 'neve_toggle_control',
					'priority'        => 36,
					'section'         => 'neve_blog_archive_layout',
					'label'           => esc_html__( 'Show Content Only On Hover', 'neve' ),
					'active_callback' => [ $this, 'is_covers_layout' ],
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_list_image_position',
				[
					'sanitize_callback' => function ( $value ) {
						if ( ! in_array( $value, [ 'left', 'no', 'right' ], true ) ) {
							return 'left';
						}

						return $value;
					},
					'default'           => 'left',
				],
				[
					'label'           => esc_html__( 'Image Position', 'neve' ),
					'section'         => 'neve_blog_archive_layout',
					'choices'         => [
						'left'  => [
							'tooltip' => __( 'Left', 'neve' ),
							'icon'    => 'align-pull-left',
						],
						'no'    => [
							'tooltip' => __( 'No image', 'neve' ),
							'icon'    => 'menu-alt',
						],
						'right' => [
							'tooltip' => __( 'Right', 'neve' ),
							'icon'    => 'align-pull-right',
						],
					],
					'show_labels'     => true,
					'priority'        => 14,
					'active_callback' => [ $this, 'is_list_layout' ],
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);
		$this->add_control(
			new Control(
				'neve_blog_list_image_width',
				[
					'sanitize_callback' => 'absint',
					'transport'         => $new_skin ? 'refresh' : $this->selective_refresh,
					'default'           => 35,
				],
				[
					'label'                 => esc_html__( 'Image Width', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'type'                  => 'neve_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 100,
						'units'      => [ '%' ],
						'defaultVal' => 35,
					],
					'priority'              => 15,
					'active_callback'       => function () {
						return $this->is_list_layout() && Mods::get( 'neve_blog_list_image_position', 'left' ) !== 'no';
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'template' =>
							'body .nv-non-grid-article.has-post-thumbnail .non-grid-content {
							    width: calc(100% - {{value}}%);
					    	}
					    	body .layout-default .nv-post-thumbnail-wrap, body .layout-alternative .nv-post-thumbnail-wrap {
							    width: {{value}}%;
							    max-width: {{value}}%;
					    	}',
					],

				],
				'\Neve\Customizer\Controls\React\Range'
			)
		);
		$this->add_control(
			new Control(
				'neve_blog_separator',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => ! neve_pro_is_new_skin(),
				),
				array(
					'type'            => 'neve_toggle_control',
					'priority'        => 19,
					'section'         => 'neve_blog_archive_layout',
					'label'           => esc_html__( 'Add Separator between posts', 'neve' ),
					'active_callback' => function () {
						return get_theme_mod( 'neve_blog_archive_layout' ) !== 'covers';
					},
				)
			)
		);
		$this->add_control(
			new Control(
				'neve_blog_separator_width',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => $this->selective_refresh,
					'default'           => '{ "mobile": 1, "tablet": 1, "desktop": 1 }',
				],
				[
					'label'                 => esc_html__( 'Separator Weight', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 100,
						'step'       => 1,
						'units'      => [ 'px' ],
						'defaultVal' => [
							'mobile'  => 1,
							'tablet'  => 1,
							'desktop' => 1,
						],
					],
					'priority'              => 21,
					'active_callback'       => function () {
						return get_theme_mod( 'neve_blog_archive_layout' ) !== 'covers' && get_theme_mod( 'neve_blog_separator' ) === true;
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--borderWidth',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.posts-wrapper',
						],
						'responsive' => true,
						'template'   =>
							'body .article-content-col .content {
							    border-width: {{value}}px;
						    }',
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);
		$this->add_control(
			new Control(
				'neve_blog_separator_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'transport'         => $this->selective_refresh,
					'default'           => version_compare( NEVE_VERSION, '2.9.0', '<' ) ? '#f0f0f0' : 'var(--nv-light-bg)',
				),
				array(
					'label'                 => esc_html__( 'Separator Color', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'priority'              => 22,
					'active_callback'       => function () {
						return get_theme_mod( 'neve_blog_archive_layout' ) !== 'covers' && get_theme_mod( 'neve_blog_separator' ) === true;
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--borderColor',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'body .article-content-col .content {
							    border-color: {{value}};
						    }',
					],
				),
				'Neve\Customizer\Controls\React\Color'
			)
		);
		$this->add_control(
			new Control(
				'neve_enable_card_style',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'type'            => 'neve_toggle_control',
					'priority'        => 36,
					'section'         => 'neve_blog_archive_layout',
					'label'           => esc_html__( 'Enable Card Style', 'neve' ),
					'active_callback' => [ $this, 'is_grid_layout' ],
				)
			)
		);
		$this->add_control(
			new Control(
				'neve_blog_grid_card_bg_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'default'           => '#333333',
					'transport'         => 'postMessage',
				),
				array(
					'label'                 => esc_html__( 'Card Background Color', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'priority'              => 37,
					'default'               => '#333333',
					'active_callback'       => function () {
						return $this->is_grid_layout() && Mods::get( 'neve_enable_card_style', false ) === true;
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--bgColor',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'.layout-grid .article-content-col .content {
							background-color: {{value}};
						}',
					],
				),
				'Neve\Customizer\Controls\React\Color'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_grid_text_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'default'           => '#ffffff',
					'transport'         => 'postMessage',
				),
				array(
					'label'                 => esc_html__( 'Text Color', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'priority'              => 38,
					'default'               => '#ffffff',
					'active_callback'       => function () {
						return $this->is_grid_layout() && Mods::get( 'neve_enable_card_style', false ) === true;
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--color',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'.layout-grid .article-content-col .content, .layout-grid .article-content-col .content a:not(.button), .layout-grid .article-content-col .content li {
							color: {{value}};
						}',
					],
				),
				'Neve\Customizer\Controls\React\Color'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_card_shadow',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => $new_skin ? 'refresh' : 'postMessage',
					'default'           => 0,
				],
				[
					'label'                 => esc_html__( 'Card Box Shadow', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'type'                  => 'neve_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 5,
						'defaultVal' => 0,
					],
					'priority'              => 39,
					'active_callback'       => function () {
						return $this->is_grid_layout() && Mods::get( 'neve_enable_card_style', false ) === true;
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'fallback' => 0,
						'template' =>
							'body .layout-grid .article-content-col .content {
							    box-shadow: 0 0 calc({{value}}px * 4) 0 rgba(0,0,0,calc(0.1 + 0.{{value}}));
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Range'
			)
		);

	}

	/**
	 * Add ordering controls.
	 */
	private function add_ordering_content_controls() {
		$this->add_control(
			new Control(
				'neve_posts_order',
				array(
					'default'           => 'date_posted_desc',
					'sanitize_callback' => array( $this, 'sanitize_posts_sorting' ),
				),
				array(
					'label'    => esc_html__( 'Order posts by', 'neve' ),
					'section'  => 'neve_blog_archive_layout',
					'priority' => 51,
					'type'     => 'select',
					'choices'  => array(
						'date_posted_desc' => esc_html__( 'Date posted descending', 'neve' ),
						'date_posted_asc'  => esc_html__( 'Date posted ascending', 'neve' ),
						'date_updated'     => esc_html__( 'Date updated', 'neve' ),
					),
				)
			)
		);
		// content alignment
		$align_choices = [
			'left'   => [
				'tooltip' => __( 'Left', 'neve' ),
				'icon'    => 'editor-alignleft',
			],
			'center' => [
				'tooltip' => __( 'Center', 'neve' ),
				'icon'    => 'editor-aligncenter',
			],
			'right'  => [
				'tooltip' => __( 'Right', 'neve' ),
				'icon'    => 'editor-alignright',
			],
		];
		$this->add_control(
			new Control(
				'neve_blog_content_alignment',
				[
					'sanitize_callback' => function ( $value ) {
						if ( ! in_array( $value, [ 'left', 'center', 'right' ], true ) ) {
							return 'left';
						}

						return $value;
					},
					'default'           => 'left',
					'transport'         => 'postMessage',
				],
				[
					'label'                 => esc_html__( 'Content Alignment', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'choices'               => $align_choices,
					'show_labels'           => true,
					'priority'              => 56,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--alignment',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'body .cover-post .inner, 
                            body .nv-non-grid-article .content  .non-grid-content, 
							body .nv-non-grid-article .content .non-grid-content.alternative-layout-content,
                            body .article-content-col .content, 
                            body .article-content-col .content a, 
                            body .article-content-col .content li {
							    text-align: {{value}};
					    	}
					    	.layout-grid .nv-post-thumbnail-wrap a {
					    	    display: inline-block;
					    	}
					    	',
					],
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);
		// vertical alignment
		$align_choices = [
			'flex-start' => [
				'tooltip' => __( 'Top', 'neve' ),
				'icon'    => 'verticalTop',
			],
			'center'     => [
				'tooltip' => __( 'Middle', 'neve' ),
				'icon'    => 'verticalMiddle',
			],
			'flex-end'   => [
				'tooltip' => __( 'Bottom', 'neve' ),
				'icon'    => 'verticalBottom',
			],
		];
		$this->add_control(
			new Control(
				'neve_blog_content_vertical_alignment',
				[
					'sanitize_callback' => function ( $value ) {
						if ( ! in_array( $value, [ 'flex-start', 'center', 'flex-end' ], true ) ) {
							return 'flex-end';
						}

						return $value;
					},
					'transport'         => 'postMessage',
					'default'           => 'bottom',
				],
				[
					'label'                 => esc_html__( 'Content Alignment', 'neve' ),
					'section'               => 'neve_blog_archive_layout',
					'show_labels'           => true,
					'choices'               => $align_choices,
					'priority'              => 57,
					'active_callback'       => [ $this, 'is_covers_layout' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--justify',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'body .cover-post .inner {
							    justify-content: {{value}};
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);
	}

	/**
	 * Add post meta controls.
	 */
	private function add_post_meta_controls() {
		$this->add_control(
			new Control(
				'neve_metadata_separator',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html( '/' ),
				),
				array(
					'priority'    => 77,
					'section'     => 'neve_blog_archive_layout',
					'label'       => esc_html__( 'Separator', 'neve' ),
					'description' => esc_html__( 'For special characters make sure to use Unicode. For example > can be displayed using \003E.', 'neve' ),
					'type'        => 'text',
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_author_avatar_size',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => wp_json_encode(
						array(
							'desktop' => 20,
							'tablet'  => 20,
							'mobile'  => 20,
						)
					),
				),
				array(
					'label'           => esc_html__( 'Avatar Size', 'neve' ),
					'section'         => 'neve_blog_archive_layout',
					'units'           => array(
						'px',
					),
					'input_attr'      => array(
						'mobile'  => array(
							'min'          => 20,
							'max'          => 50,
							'default'      => 20,
							'default_unit' => 'px',
						),
						'tablet'  => array(
							'min'          => 20,
							'max'          => 50,
							'default'      => 20,
							'default_unit' => 'px',
						),
						'desktop' => array(
							'min'          => 20,
							'max'          => 50,
							'default'      => 20,
							'default_unit' => 'px',
						),
					),
					'input_attrs'     => [
						'step'       => 1,
						'min'        => 20,
						'max'        => 50,
						'defaultVal' => [
							'mobile'  => 20,
							'tablet'  => 20,
							'desktop' => 20,
						],
						'units'      => [ 'px' ],
					],
					'priority'        => 81,
					'active_callback' => function () {
						return get_theme_mod( 'neve_author_avatar', false );
					},
					'responsive'      => true,
				),
				version_compare( NEVE_VERSION, '2.6.3', '>=' ) ? 'Neve\Customizer\Controls\React\Responsive_Range' : 'Neve\Customizer\Controls\Responsive_Number'
			)
		);
	}

	/**
	 * Read More Options
	 */
	public function add_read_more_controls() {
		/*
		 * Heading for Read More options
		 */
		$this->add_control(
			new Control(
				'neve_read_more_options',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Read More', 'neve' ),
					'section'          => 'neve_blog_archive_layout',
					'priority'         => 85,
					'class'            => 'blog-layout-read-more-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 2,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		/*
		 * Read More Text
		 */
		$this->add_control(
			new Control(
				'neve_read_more_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__( 'Read More', 'neve' ) . ' &raquo;',
				),
				array(
					'priority' => 90,
					'section'  => 'neve_blog_archive_layout',
					'label'    => esc_html__( 'Text', 'neve' ),
					'type'     => 'text',
				)
			)
		);

		/*
		 * Read More Style
		 */
		$this->add_control(
			new Control(
				'neve_read_more_style',
				array(
					'default'           => 'text',
					'sanitize_callback' => array( $this, 'sanitize_read_more_style' ),
				),
				array(
					'label'    => esc_html__( 'Style', 'neve' ),
					'section'  => 'neve_blog_archive_layout',
					'priority' => 95,
					'type'     => 'select',
					'choices'  => array(
						'text'             => esc_html__( 'Text', 'neve' ),
						'primary_button'   => esc_html__( 'Primary Button', 'neve' ),
						'secondary_button' => esc_html__( 'Secondary Button', 'neve' ),
					),
				)
			)
		);
	}

	/**
	 * Sanitize read more button style
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_read_more_style( $value ) {
		$allowed_values = array( 'text', 'primary_button', 'secondary_button' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'number';
		}

		return esc_html( $value );
	}

	/**
	 * Filter single post elements
	 *
	 * @param array $input - controls registered by the theme.
	 *
	 * @return array
	 */
	public function filter_single_post_elements( $input ) {

		$new_controls = array(
			'author-biography' => __( 'Author Biography', 'neve' ),
			'related-posts'    => __( 'Related Posts', 'neve' ),
			'sharing-icons'    => __( 'Sharing Icons', 'neve' ),
		);

		$single_post_elements = array_merge( $input, $new_controls );

		return $single_post_elements;
	}

	/**
	 * Sanitize posts sorting
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_posts_sorting( $value ) {
		$allowed_values = array( 'date_posted_asc', 'date_posted_desc', 'date_updated' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'date_posted_desc';
		}

		return esc_html( $value );
	}

	/**
	 * Checks if is list layout blog
	 *
	 * @return bool
	 */
	public function is_list_layout() {
		return get_theme_mod( 'neve_blog_archive_layout', 'grid' ) === 'default';
	}

	/**
	 * Checks if is covers layout blog
	 *
	 * @return bool
	 */
	public function is_covers_layout() {
		return get_theme_mod( 'neve_blog_archive_layout', 'grid' ) === 'covers';
	}

	/**
	 * Checks if is grid layout blog
	 *
	 * @return bool
	 */
	public function is_grid_layout() {
		return get_theme_mod( 'neve_blog_archive_layout', 'grid' ) === 'grid';
	}
}
