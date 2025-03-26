<?php
/**
 * Inside Layout class to insert custom layouts inside content.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin;

use Neve_Pro\Modules\Custom_Layouts\Admin\Builders\Abstract_Builders;
use Neve_Pro\Traits\Conditional_Display;
use Neve_Pro\Traits\Core;

/**
 * Class Inside_Layout
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */
class Inside_Layout {
	use Core;
	use Conditional_Display;

	const AFTER_HEADINGS = 'after_x_headings';
	const AFTER_BLOCKS   = 'after_x_blocks';

	/**
	 * Keep track of core headings count.
	 *
	 * @var integer
	 */
	private static $headings_count = 1;

	/**
	 * Keep track of main level blocks count.
	 *
	 * @var int
	 */
	private static $blocks_count = 0;

	/**
	 * No. of events to track.
	 * This is set on the custom layout.
	 *
	 * @var int
	 */
	private static $events_no = 0;

	/**
	 * Position of display.
	 * This is set on the custom layout.
	 *
	 * @var string
	 */
	private static $display = self::AFTER_HEADINGS;

	/**
	 * Holds an instance of this class.
	 *
	 * @var null|Inside_Layout
	 */
	private static $_instance = null;

	/**
	 * The content to be inserted.
	 *
	 * @var string
	 */
	private $content = '';

	/**
	 * Return an instance of the class.
	 *
	 * @return Inside_Layout;
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Set $events_no property.
	 *
	 * @param int $value Number of events to act on.
	 */
	private function set_events_no( $value = 0 ) {
		self::$events_no = (int) $value;
	}

	/**
	 * Set $display property.
	 *
	 * @param string $value Type of event to use for display.
	 */
	private function set_display( $value = '' ) {
		self::$display = (string) $value;
	}

	/**
	 * Set properties before render for the active custom layout.
	 *
	 * @param int $post_id The custom layout post ID.
	 */
	public function set_options( $post_id ) {
		$display   = get_post_meta( $post_id, 'custom-layout-options-inside-display', true );
		$events_no = get_post_meta( $post_id, 'custom-layout-options-events-no', true );
		$this->set_events_no( $events_no );
		$this->set_display( $display );
	}

	/**
	 * Init main hook.
	 */
	public function init() {
		add_action( 'template_redirect', [ $this, 'register_hooks' ] );
	}

	/**
	 * Trigger `neve_do_inside_content` hook, and store the content.
	 */
	public function register_hooks() {

		$post_id = null;
		global $post;
		if ( isset( $post->ID ) ) {
			$post_id = (string) $post->ID;
		}

		$editor = Abstract_Builders::get_post_builder( (int) $post_id );

		if ( is_singular( 'neve_custom_layouts' ) && is_preview() ) {
			/**
			 * This hook triggers the render of the inside content custom layout template.
			 * Here it is invoked for displaying the preview.
			 *
			 * @since 3.0.5
			 */
			do_action( 'neve_do_inside_content' );
			return;
		}

		$this->content = $this->get_inside_content();

		if ( $editor === 'default' ) {
			/**
			 * This method invoked here is shared from the Performance Module, it adds a new filter `render_block_top_level_only`
			 * that we can hook into for each block parsed. It is used to evaluate different blocks.
			 */
			add_filter( 'the_content', array( $this, 'process_content_blocks' ), -99 );
			add_filter( 'render_block_top_level_only', array( $this, 'filter_block' ), 10, 2 );
			return;
		}

		// For other editors than Gutenberg parse the HTML content
		add_filter( 'the_content', array( $this, 'filter_content' ), 10 );
	}

	/**
	 * Return the results of the render as string.
	 *
	 * @return string
	 */
	private function get_inside_content() {
		ob_start();
		/**
		 * This hook triggers the render of the inside content custom layout template.
		 * Here we use it to capture the rendered content and return it as a string so that we can add it based on the
		 * selected display options inside the qualified posts.
		 *
		 * @since 3.0.5
		 */
		do_action( 'neve_do_inside_content' );
		return ob_get_clean();
	}

	/**
	 * Method to count headings via HTML regex parse and insert content.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	private function count_headings_html( $content ) {
		if ( self::$events_no === 1 ) {
			return $this->content . $content;
		}

		if ( self::$events_no > 1 ) {
			preg_match_all( '/<h\d(?:.*)>.*<\/h\d>/iU', $content, $headings, PREG_OFFSET_CAPTURE, 0 );
			$match_position = self::$events_no - 2;
			if ( ! empty( $headings[0] ) && isset( $headings[0][ $match_position ] ) ) {
				$heading_content  = $headings[0][ $match_position ][0];
				$heading_position = $headings[0][ $match_position ][1];
				$inside_content   = $heading_content . $this->content;
				return substr_replace( $content, $inside_content, $heading_position, strlen( $heading_content ) );
			}
		}

		return $content;
	}

	/**
	 * Method to count blocks via HTML regex parse and insert content.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	private function count_blocks_html( $content ) {

		/**
		 * Holds the classes that other builders might use to define a main block.
		 *
		 * @var array $top_level_classes
		 */
		$top_level_classes = array(
			'elementor-top-section',
			'et_pb_section',
			'fl-row',
		);

		preg_match_all( '/<([A-Z][A-Z0-9]*)\b.*class=".*(' . implode( '|', $top_level_classes ) . ')/iU', $content, $blocks, PREG_OFFSET_CAPTURE, 0 );
		$match_position = self::$events_no + 1;

		if ( empty( $blocks[0] ) ) {
			return $content;
		}

		if ( $match_position === count( $blocks[0] ) ) {
			return $content . $this->content;
		}

		if ( isset( $blocks[0][ $match_position ] ) ) {
			$block_start_content  = $blocks[0][ $match_position ][0];
			$block_start_position = $blocks[0][ $match_position ][1];
			$inside_content       = $this->content . $block_start_content;
			return substr_replace( $content, $inside_content, $block_start_position, strlen( $block_start_content ) );
		}

		return $content;
	}

	/**
	 * Add inside custom content. HTML parse.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	final public function filter_content( $content ) {
		if ( is_admin() || wp_is_json_request() ) {
			return $content;
		}

		if ( self::$display === self::AFTER_HEADINGS ) {
			return $this->count_headings_html( $content );
		}

		if ( self::$display === self::AFTER_BLOCKS ) {
			return $this->count_blocks_html( $content );
		}

		return $content;
	}

	/**
	 * Method to count headings and insert content.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 *
	 * @return string
	 */
	private function count_headings( $block_content, $block ) {
		if ( self::$events_no === 1 && self::$headings_count === 1 ) {
			self::$headings_count++;
			return $this->content . $block_content;
		}

		if ( strpos( $block['blockName'], 'heading' ) !== false && self::$headings_count === self::$events_no - 1 ) {
			$heading_position = strpos( $block_content, $block['innerHTML'] );
			if ( $heading_position !== false ) {
				$heading_position += strlen( $block['innerHTML'] );
				$block_content     = substr_replace( $block_content, $this->content, $heading_position, 0 );
				self::$headings_count++;
			}

			return $block_content;
		}

		if ( strpos( $block['blockName'], 'heading' ) !== false ) {
			self::$headings_count++;
		}

		if ( ! empty( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $index => $inner_block ) {
				$block_content = $this->count_headings( $block_content, $inner_block );
			}
		}

		return $block_content;
	}

	/**
	 * Method to count main blocks and insert content.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 *
	 * @return string
	 */
	private function count_blocks( $block_content, $block ) {
		if ( $block['blockName'] ) {
			if ( ! empty( $block['innerBlocks'] ) ) {
				self::$blocks_count++;
			}
			if ( isset( $block['attrs'] ) && isset( $block['attrs']['height'] ) && $block['attrs']['height'] >= 250 ) {
				self::$blocks_count++;
			}
		}

		if ( ! empty( $block['innerBlocks'] ) && self::$blocks_count == self::$events_no ) {
			return $block_content . $this->content;
		}

		return $block_content;
	}

	/**
	 * Add inside custom content.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 *
	 * @return string
	 */
	final public function filter_block( $block_content, $block ) {
		if ( is_admin() || wp_is_json_request() ) {
			return $block_content;
		}

		if ( self::$display === self::AFTER_HEADINGS ) {
			$block_content = $this->count_headings( $block_content, $block );
		}

		if ( self::$display === self::AFTER_BLOCKS ) {
			$block_content = $this->count_blocks( $block_content, $block );
		}


		return $block_content;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @access public
	 * @since  3.0.5
	 */
	public function __clone() {}

	/**
	 * Un-serializing instances of this class is forbidden.
	 *
	 * @access public
	 * @since  3.0.5
	 */
	public function __wakeup() {}
}
