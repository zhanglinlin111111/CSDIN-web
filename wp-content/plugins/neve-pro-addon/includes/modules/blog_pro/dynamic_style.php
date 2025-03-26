<?php
/**
 * File that handle dynamic css for Blog pro integration.
 *
 * @package Neve_Pro\Modules\Blog_Pro
 */

namespace Neve_Pro\Modules\Blog_Pro;

use Neve\Core\Settings\Mods;
use Neve_Pro\Core\Generic_Style;
use Neve_Pro\Modules\Blog_Pro\Customizer\Defaults\Single_Post;

/**
 * Class Dynamic_Style
 *
 * @package Neve_Pro\Modules\Blog_Pro
 */
class Dynamic_Style extends Generic_Style {
	use Single_Post;

	const AVATAR_SIZE           = 'neve_author_avatar_size';
	const BLOG_LAYOUT           = 'neve_blog_archive_layout';
	const COVER_OVERLAY         = 'neve_blog_covers_overlay_color';
	const SHOW_CONTENT_ON_HOVER = 'neve_blog_show_on_hover';
	const CONTENT_PADDING       = 'neve_blog_content_padding';
	const COVER_MIN_HEIGHT      = 'neve_blog_covers_min_height';
	const CONTENT_ALIGNMENT     = 'neve_blog_content_alignment';
	const VERTICAL_ALIGNMENT    = 'neve_blog_content_vertical_alignment';
	const BORDER_RADIUS         = 'neve_blog_items_border_radius';
	const GRID_SPACING          = 'neve_blog_grid_spacing';
	const LIST_SPACING          = 'neve_blog_list_spacing';
	const IMAGE_POSITION        = 'neve_blog_list_image_position';
	const ALTERNATIVE_LAYOUT    = 'neve_blog_list_alternative_layout';
	const IMAGE_WIDTH           = 'neve_blog_list_image_width';
	const GRID_CARD_BG          = 'neve_blog_grid_card_bg_color';
	const GRID_TEXT_COLOR       = 'neve_blog_grid_text_color';
	const CARD_STYLE            = 'neve_enable_card_style';
	const SEPARATOR             = 'neve_blog_separator';
	const SEPARATOR_WIDTH       = 'neve_blog_separator_width';
	const SEPARATOR_COLOR       = 'neve_blog_separator_color';
	const CARD_SHADOW           = 'neve_blog_card_shadow';
	const SHARING_COLOR         = 'neve_sharing_custom_color';

	const AUTHOR_BOX_AVATAR_SIZE          = 'neve_author_box_avatar_size';
	const AUTHOR_BOX_AVATAR_BORDER_RADIUS = 'neve_author_box_avatar_border_radius';
	const AUTHOR_BOX_PADDING              = 'neve_author_box_boxed_padding';
	const AUTHOR_BOX_BACKGROUND_COLOR     = 'neve_author_box_boxed_background_color';
	const AUTHOR_BOX_TEXT_COLOR           = 'neve_author_box_boxed_text_color';

	const RELATED_POSTS_BOXED_STATUS     = 'neve_related_posts_boxed_layout';
	const RELATED_POSTS_COLUMNS          = 'neve_related_posts_columns';
	const RELATED_POSTS_PADDING          = 'neve_related_posts_boxed_padding';
	const RELATED_POSTS_BACKGROUND_COLOR = 'neve_related_posts_boxed_background_color';
	const RELATED_POSTS_TEXT_COLOR       = 'neve_related_posts_boxed_text_color';

	/**
	 * Register extra hooks.
	 */
	public function register_hooks() {
		parent::register_hooks();
		if ( version_compare( NEVE_VERSION, '3.0.0', '<' ) ) {
			add_filter(
				'neve_gravatar_args',
				[ $this, 'add_dynamic_gravatar' ]
			);
		}
		add_filter(
			'post_class',
			[ $this, 'add_hover_class' ]
		);
	}

	/**
	 * Add dynamic gravatar values.
	 *
	 * @param array $args_array Avatar args.
	 *
	 * @return mixed
	 */
	public function add_dynamic_gravatar( $args_array ) {

		$avatar_size = Mods::to_json( self::AVATAR_SIZE );

		if ( ! isset( $args_array['size'] ) ) {
			return $args_array;
		}
		if ( ! is_array( $avatar_size ) ) {
			return $args_array;
		}

		$args_array['size'] = max( $avatar_size );

		return $args_array;
	}


	/**
	 * Add dynamic style subscribers.
	 *
	 * @param array $subscribers Css subscribers.
	 *
	 * @return array|mixed
	 */
	public function add_subscribers( $subscribers = [] ) {
		if ( ! neve_pro_is_new_skin() ) {
			return $this->add_legacy_subscribers( $subscribers );
		}

		// Sharing icons
		if ( $this->section_is_enabled( 'sharing-icons' ) ) {
			$custom_sharing_icons_color = get_theme_mod( 'neve_sharing_enable_custom_color', false );
			if ( $custom_sharing_icons_color ) {
				$subscribers[] = [
					'selectors' => '.nv-post-share a',
					'rules'     => [
						'--hex' => [
							'key'     => self::SHARING_COLOR,
							'default' => 'var(--nv-primary-accent)',
						],
					],
				];
			}
		}

		// Author box
		if ( $this->section_is_enabled( 'author-biography' ) ) {
			$author_rules = [];
			$show_avatar  = get_theme_mod( 'neve_author_box_enable_avatar', true );
			if ( $show_avatar ) {
				$author_rules['--avatarSize']   = [
					'key'           => self::AUTHOR_BOX_AVATAR_SIZE,
					'default'       => '{ "mobile": 96, "tablet": 96, "desktop": 96 }',
					'suffix'        => 'px',
					'is_responsive' => true,
				];
				$author_rules['--borderRadius'] = [
					'key'     => self::AUTHOR_BOX_AVATAR_BORDER_RADIUS,
					'suffix'  => '%',
					'default' => 0,
				];
			}

			$is_boxed = get_theme_mod( 'neve_author_box_boxed_layout', false );

			if ( $is_boxed ) {
				$author_rules['--padding'] = [
					'key'              => self::AUTHOR_BOX_PADDING,
					'is_responsive'    => true,
					'directional-prop' => 'padding',
					'suffix'           => 'responsive_unit',
					'default'          => $this->responsive_padding_default(),
				];

				$author_rules['--bgColor'] = [
					'key'     => self::AUTHOR_BOX_BACKGROUND_COLOR,
					'default' => 'var(--nv-light-bg)',
				];

				$author_rules['--color'] = [
					'key'     => self::AUTHOR_BOX_TEXT_COLOR,
					'default' => 'var(--nv-text-color)',
				];
			}

			$subscribers[] = [
				'selectors' => '.nv-author-biography',
				'rules'     => $author_rules,
			];
		}

		if ( $this->section_is_enabled( 'related-posts' ) ) {
			// Related Posts
			$related_rules = [
				'--relatedColumns' => self::RELATED_POSTS_COLUMNS,
			];

			$is_related_boxed = Mods::get( self::RELATED_POSTS_BOXED_STATUS, false );
			if ( $is_related_boxed ) {
				$related_rules = array_merge(
					$related_rules,
					[
						'--bgColor' => [
							'key'     => self::RELATED_POSTS_BACKGROUND_COLOR,
							'default' => 'var(--nv-light-bg)',
						],
						'--color'   => [
							'key'     => self::RELATED_POSTS_TEXT_COLOR,
							'default' => 'var(--nv-text-color)',
						],
						'--padding' => [
							'key'              => self::RELATED_POSTS_PADDING,
							'is_responsive'    => true,
							'directional-prop' => 'padding',
							'suffix'           => 'responsive_unit',
							'default'          => $this->responsive_padding_default(),
						],
					]
				);
			}

			$subscribers[] = [
				'selectors' => '.nv-related-posts',
				'rules'     => $related_rules,
			];
		}

		// Blog archive
		$layout        = Mods::get( self::BLOG_LAYOUT, 'grid' );
		$image_pos     = Mods::get( self::IMAGE_POSITION, 'left' );
		$alt_layout    = Mods::get( self::ALTERNATIVE_LAYOUT, false );
		$has_separator = Mods::get( self::SEPARATOR, false ) === true;

		$rules                = [];
		$rules['--padding']   = [
			'key'              => self::CONTENT_PADDING,
			'is_responsive'    => true,
			'suffix'           => 'responsive_unit',
			'directional-prop' => 'padding',
		];
		$rules['--alignment'] = self::CONTENT_ALIGNMENT;

		if ( $layout === 'default' ) {
			$rules['--spacing'] = [
				'key'           => self::LIST_SPACING,
				'suffix'        => 'px',
				'is_responsive' => true,
			];

			$rules['--postColTemplate'] = [
				'key'         => self::IMAGE_WIDTH,
				'filter'      => function ( $css_prop, $value, $meta, $device ) use ( $image_pos ) {
					if ( $image_pos === 'no' ) {
						add_filter( 'neve_blog_post_thumbnail_markup', '__return_empty_string', 0 );

						return sprintf( '%s:1fr;', $css_prop );
					}

					$content = 100 - absint( $value );
					if ( $image_pos === 'left' ) {
						return sprintf( '%s:%sfr %sfr;', $css_prop, $value, $content );
					}

					return sprintf( '%s:%sfr %sfr;', $css_prop, $content, $value );
				},
				'default'     => 35,
				'device_only' => 'desktop',
			];

			if ( $image_pos === 'right' ) {
				$rules['--thumbGridColumn'] = [
					'key'         => self::IMAGE_POSITION,
					'override'    => 2,
					'default'     => 35,
					'device_only' => 'desktop',
				];
			}

			// Alt layout and image.
			if ( $alt_layout && $image_pos !== 'no' ) {
				$subscribers[] = [
					'selectors' => '.layout-alternative:nth-child(even)',
					'rules'     => [
						'--postColTemplate' => [
							'key'         => self::IMAGE_WIDTH,
							'filter'      => function ( $css_prop, $value, $meta, $device ) use ( $image_pos ) {
								$content = 100 - absint( $value );
								if ( $image_pos === 'right' ) {
									return sprintf( '%s:%sfr %sfr;', $css_prop, $value, $content );
								}

								return sprintf( '%s:%sfr %sfr;', $css_prop, $content, $value );
							},
							'default'     => 35,
							'device_only' => 'desktop',
						],
					],
				];
				if ( $image_pos === 'right' ) {
					$subscribers[] = [
						'selectors'   => '.posts-wrapper > article.has-post-thumbnail.layout-alternative:nth-child(even)',
						'rules'       => [
							'--thumbGridColumn' => [
								'key'      => self::IMAGE_WIDTH,
								'default'  => 35,
								'override' => 1,
							],
						],
						'device_only' => 'desktop',
					];
				}
			}
		}

		if ( $layout !== 'default' ) {
			$rules['--gridSpacing'] = [
				'key'           => self::GRID_SPACING,
				'is_responsive' => true,
				'suffix'        => 'px',
			];

			$rules['--borderRadius'] = [
				'key'    => self::BORDER_RADIUS,
				'suffix' => 'px',
			];
		}

		if ( $layout === 'covers' ) {
			$rules['--overlay'] = self::COVER_OVERLAY;

			$rules['--height'] = [
				'key'           => self::COVER_MIN_HEIGHT,
				'is_responsive' => true,
				'suffix'        => 'px',
			];

			$vertical_alignment = Mods::get( self::BLOG_LAYOUT );
			if ( ! empty( $vertical_alignment ) ) {
				$rules['--justify'] = self::VERTICAL_ALIGNMENT;
			}
		}

		if ( $has_separator && $layout !== 'covers' ) {
			$rules['--borderColor'] = [
				'key'     => self::SEPARATOR_COLOR,
				'default' => 'var(--nv-light-bg)',
			];
			$rules['--borderWidth'] = [
				'key'           => self::SEPARATOR_WIDTH,
				'default'       => '{ "mobile": 1, "tablet": 1, "desktop": 1 }',
				'suffix'        => 'px',
				'is_responsive' => true,
			];
		}

		if ( $layout === 'grid' ) {
			// Make sure image goes to edges
			$subscribers[] = [
				'selectors'     => '.layout-grid .nv-post-thumbnail-wrap',
				'rules'         => [
					'margin' => [
						'key'           => self::CONTENT_PADDING,
						'is_responsive' => true,
						'filter'        => function ( $css_prop, $value, $meta, $device ) {
							$output = '';
							if ( isset( $value['right'] ) && ! empty( $value['right'] ) ) {
								$output .= sprintf( 'margin-right:-%spx;', $value['right'] );
							}
							if ( isset( $value['left'] ) && ! empty( $value['left'] ) ) {
								$output .= sprintf( 'margin-left:-%spx;', $value['left'] );
							}

							return $output;
						},
					],
				],
				'is_responsive' => true,
			];

			$has_card_style = Mods::get( self::CARD_STYLE, false ) === true;

			if ( $has_card_style ) {
				$rules['--bgColor']   = self::GRID_CARD_BG;
				$rules['--color']     = self::GRID_TEXT_COLOR;
				$rules['--boxShadow'] = [
					'key'    => self::CARD_SHADOW,
					'filter' => function ( $css_prop, $value, $meta, $device ) {
						$blur    = $value * 4;
						$opacity = 0.1 + $value / 10;

						return sprintf( '%s:0 0 %spx 0 rgba(0,0,0,%s);', $css_prop, $blur, $opacity );
					},
				];
			}
		}

		$subscribers[] = [
			'selectors' => '.posts-wrapper',
			'rules'     => $rules,
		];

		return $subscribers;
	}

	/**
	 * Add dynamic style subscribers.
	 *
	 * @param array $subscribers Css subscribers.
	 *
	 * @return array|mixed
	 */
	public function add_legacy_subscribers( $subscribers = [] ) {
		$layout         = Mods::get( self::BLOG_LAYOUT, 'grid' );
		$image_pos      = Mods::get( self::IMAGE_POSITION, 'left' );
		$alt_layout     = Mods::get( self::ALTERNATIVE_LAYOUT, false );
		$has_separator  = Mods::get( self::SEPARATOR, true ) === true;
		$has_card_style = Mods::get( self::CARD_STYLE, false ) === true;

		if ( version_compare( NEVE_VERSION, '3.0.0', '<' ) ) {
			$subscribers['.nv-meta-list .meta.author .photo'] = [
				'height' => [
					'key'           => self::AVATAR_SIZE,
					'is_responsive' => true,
				],
				'width'  => [
					'key'           => self::AVATAR_SIZE,
					'is_responsive' => true,
				],
			];
		}

		$subscribers[] = [
			'selectors' => '.layout-grid .article-content-col .content ',
			'rules'     => [
				'border-radius' => [
					'key'    => self::BORDER_RADIUS,
					'filter' => function ( $css_prop, $value, $meta, $device ) {
						if ( absint( $value ) === 0 ) {
							return '';
						}

						return 'overflow:hidden;';
					},
				],
			],
		];

		if ( $layout === 'covers' ) {
			$subscribers['.cover-post:after'] = [
				'background-color' => [
					'key' => self::COVER_OVERLAY,
				],
			];

			$subscribers[] = [
				'selectors' => '.cover-post .inner',
				'rules'     => [
					'padding'    => [
						'key'           => self::CONTENT_PADDING,
						'is_responsive' => true,
						'suffix'        => 'responsive_unit',
					],
					'min-height' => [
						'is_responsive' => true,
						'key'           => self::COVER_MIN_HEIGHT,
					],
					'text-align' => [
						'key'    => self::CONTENT_ALIGNMENT,
						'filter' => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%s;', $css_prop, $value );
						},
					],
				],
			];

			$subscribers[] = [
				'selectors' => '.cover-post',
				'rules'     => [
					'border-radius' => [
						'key' => self::BORDER_RADIUS,
					],
				],
			];

			$subscribers[] = [
				'selectors' => '.posts-wrapper > article.layout-covers',
				'rules'     => [
					'margin-bottom' => [
						'key'           => self::GRID_SPACING,
						'is_responsive' => true,
						'filter'        => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%spx;', $css_prop, $value );
						},
					],
					'padding'       => [
						'key'           => self::GRID_SPACING,
						'is_responsive' => true,
						'filter'        => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:0 %spx;', $css_prop, floor( $value / 2 ) );
						},
					],
				],
			];

			$vertical_alignment = Mods::get( self::BLOG_LAYOUT );
			if ( ! empty( $vertical_alignment ) ) {
				$subscribers['.cover-post .inner'] = [
					'justify-content' => [
						'key'    => self::VERTICAL_ALIGNMENT,
						'filter' => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%s;', $css_prop, $value );
						},
					],
				];
			}
		}
		if ( $layout === 'covers' || ! $has_separator ) {
			$subscribers[] = [
				'selectors' => '.article-content-col .content',
				'rules'     => [
					'border' => [
						'default' => 0,
						'key'     => self::SEPARATOR,
						'filter'  => function ( $css_prop, $value, $meta, $device ) {
							return 'border:0;';
						},
					],
				],
			];
		} else {
			$subscribers[] = [
				'selectors' => '.article-content-col .content',
				'rules'     => [
					'border-color' => [
						'key' => self::SEPARATOR_COLOR,
					],
					'border-width' => [
						'key'           => self::SEPARATOR_WIDTH,
						'is_responsive' => true,
					],
				],
			];
		}
		if ( $layout === 'default' ) {
			if ( $image_pos === 'no' ) {
				add_filter( 'neve_blog_post_thumbnail_markup', '__return_empty_string', 0 );
			}
			if ( $image_pos !== 'no' ) {
				$subscribers[] = [
					'selectors' => '.nv-non-grid-article.has-post-thumbnail .non-grid-content',
					'rules'     => [
						'width' => [
							'key'         => self::IMAGE_WIDTH,
							'suffix'      => '%',
							'filter'      => 'minus_100',
							'device_only' => 'desktop',
						],
					],
				];
				$subscribers[] = [
					'selectors' => '.layout-default .nv-post-thumbnail-wrap, .layout-alternative .nv-post-thumbnail-wrap',
					'rules'     => [
						'width'     => [
							'key'         => self::IMAGE_WIDTH,
							'suffix'      => '%',
							'device_only' => 'desktop',
						],
						'max-width' => [
							'key'         => self::IMAGE_WIDTH,
							'suffix'      => '%',
							'device_only' => 'desktop',
						],
					],
				];
			}
			if ( $image_pos === 'right' ) {
				$subscribers['.layout-default .article-content-col .content']     = [
					'flex-direction' => [
						'key'         => self::IMAGE_POSITION,
						'device_only' => 'desktop',
						'filter'      => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%s;', $css_prop, 'row-reverse' );
						},
					],
				];
				$subscribers['.nv-post-thumbnail-wrap ~ .default-layout-content'] = [
					'padding' => [
						'key'         => self::IMAGE_POSITION,
						'device_only' => 'desktop',
						'filter'      => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%s;', $css_prop, '0 20px 0 0' );
						},
					],
				];
			}
			if ( $image_pos === 'left' ) {
				if ( $alt_layout === true ) {
					$subscribers['article.layout-alternative:nth-child(even) .article-content-col .content'] = [
						'flex-direction' => [
							'key'         => self::IMAGE_POSITION,
							'device_only' => 'desktop',
							'filter'      => function ( $css_prop, $value, $meta, $device ) {
								return sprintf( '%s:%s;', $css_prop, 'row-reverse' );
							},
						],
					];
					$subscribers['article.layout-alternative:nth-child(odd) .article-content-col .content']  = [
						'flex-direction' => [
							'key'         => self::IMAGE_POSITION,
							'device_only' => 'desktop',
							'filter'      => function ( $css_prop, $value, $meta, $device ) {
								return sprintf( '%s:%s;', $css_prop, 'row' );
							},
						],
					];
					$subscribers['.nv-post-thumbnail-wrap ~ .alternative-layout-content']                    = [
						'padding' => [
							'key'    => self::IMAGE_POSITION,
							'filter' => function ( $css_prop, $value, $meta, $device ) {
								return sprintf( '%s:%s;', $css_prop, '0 0 0 20px' );
							},
						],
					];

					$subscribers['.layout-alternative:nth-child(even) .nv-post-thumbnail-wrap ~ .alternative-layout-content'] = [
						'padding' => [
							'key'         => self::IMAGE_POSITION,
							'device_only' => 'desktop',
							'filter'      => function ( $css_prop, $value, $meta, $device ) {
								return sprintf( '%s:%s;', $css_prop, '0 20px 0 0' );
							},
						],
					];
				}
			}

			$subscribers['.nv-non-grid-article .content .non-grid-content, .nv-non-grid-article .content .non-grid-content.alternative-layout-content'] = [
				'text-align' => [
					'key'    => self::CONTENT_ALIGNMENT,
					'filter' => function ( $css_prop, $value, $meta, $device ) {
						return sprintf( '%s:%s;', $css_prop, $value );
					},
				],
				'padding'    => [
					'key'           => self::CONTENT_PADDING,
					'is_responsive' => true,
					'suffix'        => 'responsive_unit',
				],
			];

			$subscribers['.posts-wrapper > article'] = [
				'margin' => [
					'key'           => self::LIST_SPACING,
					'filter'        => function ( $css_prop, $value, $meta, $device ) {
						return sprintf( 'margin-bottom:%spx;', $value );
					},
					'is_responsive' => true,
				],
			];

		}
		if ( $layout !== 'default' ) {
			$subscribers[] = [
				'selectors' => '.posts-wrapper > article',
				'rules'     => [
					'margin-bottom' => [
						'key'           => self::GRID_SPACING,
						'is_responsive' => true,
						'filter'        => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%spx;', $css_prop, $value );
						},
					],
					'padding'       => [
						'key'           => self::GRID_SPACING,
						'is_responsive' => true,
						'filter'        => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:0 %spx;', $css_prop, floor( $value / 2 ) );
						},
					],
				],
			];
		}
		if ( $layout === 'grid' ) {
			$subscribers[] = [
				'selectors' => '.article-content-col .content',
				'rules'     => [
					'border-radius' => [
						'key'    => self::BORDER_RADIUS,
						'filter' => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%spx;', $css_prop, $value );
						},
					],
					'padding'       => [
						'key'           => self::CONTENT_PADDING,
						'is_responsive' => true,
						'suffix'        => 'responsive_unit',
					],
				],
			];
			$subscribers[] = [
				'selectors' => '.article-content-col .nv-post-thumbnail-wrap',
				'rules'     => [
					'margin' => [
						'key'           => self::CONTENT_PADDING,
						'is_responsive' => true,
						'filter'        => function ( $css_prop, $value, $meta, $device ) {
							$output = '';
							if ( isset( $value['right'] ) && ! empty( $value['right'] ) ) {
								$output .= sprintf( 'margin-right:-%spx;', $value['right'] );
							}
							if ( isset( $value['left'] ) && ! empty( $value['left'] ) ) {
								$output .= sprintf( 'margin-left:-%spx;', $value['left'] );
							}

							return $output;
						},
					],
				],
			];
			$subscribers[] = [
				'selectors' => '.article-content-col .content',
				'rules'     => [
					'text-align' => [
						'key'    => self::CONTENT_ALIGNMENT,
						'filter' => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%s;', $css_prop, $value );
						},
					],
				],
			];

			$subscribers[] = [
				'selectors' => '.nv-post-thumbnail-wrap a',
				'rules'     => [
					'justify-content' => [
						'key'    => self::CONTENT_ALIGNMENT,
						'filter' => function ( $css_prop, $value, $meta, $device ) {
							return sprintf( '%s:%s;display:inline-block;', $css_prop, $value );
						},
					],
				],
			];

			if ( $has_card_style ) {
				$subscribers[] = [
					'selectors' => '.article-content-col .content',
					'rules'     => [
						'background-color' => [
							'key'     => self::GRID_CARD_BG,
							'default' => '#333333',
						],
						'color'            => [
							'key'    => self::CARD_SHADOW,
							'filter' => function ( $css_prop, $value, $meta, $device ) {
								return sprintf( 'box-shadow:0 0 %spx 0 rgba(0,0,0,%s);', $value * 4, ( 0.1 + $value / 10 ) );
							},
						],
					],
				];
				$subscribers[] = [
					'selectors' => '.article-content-col .content, .article-content-col .content a:not(.button), .article-content-col .content li',
					'rules'     => [
						'color' => [
							'key'     => self::GRID_TEXT_COLOR,
							'default' => '#ffffff',
						],
					],
				];
			}
		}

		$subscribers[] = [
			'selectors' => '.nv-post-share.custom-color .nv-social-icon svg, .nv-post-share.custom-color .nv-social-icon a svg',
			'rules'     => [
				'fill' => [
					'key'     => self::SHARING_COLOR,
					'default' => 'var(--nv-primary-accent)',
				],
			],
		];

		$subscribers['.nv-post-share.nv-is-boxed.custom-color .social-share, .nv-post-share.nv-is-boxed.custom-color .nv-social-icon a'] = [
			'background-color' => [
				'key'     => self::SHARING_COLOR,
				'default' => 'var(--nv-primary-accent)',
			],
		];

		return $subscribers;
	}

	/**
	 * Add class to posts to only show on hover.
	 *
	 * @param string[] $classes post classes.
	 *
	 * @return array
	 */
	public function add_hover_class( $classes ) {
		if ( Mods::get( self::SHOW_CONTENT_ON_HOVER, false ) === false ) {
			return $classes;
		}
		$classes[] = 'show-hover';

		return $classes;
	}

	/**
	 * Is single post section enabled.
	 *
	 * @param string $element Post page section.
	 *
	 * @return bool
	 */
	private function section_is_enabled( $element ) {
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
}
