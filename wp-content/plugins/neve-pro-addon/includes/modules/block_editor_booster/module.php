<?php
/**
 * Block Editor Booster Module main file.
 *
 * @package Neve_Pro\Modules\Block_Editor_Booster
 */

namespace Neve_Pro\Modules\Block_Editor_Booster;

use Neve_Pro\Core\Abstract_Module;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Block_Editor_Booster
 */
class Module extends Abstract_Module {

	/**
	 * Holds the base module namespace
	 * Used to load submodules.
	 *
	 * @var string $module_namespace
	 */
	private $module_namespace = 'Neve_Pro\Modules\Block_Editor_Booster';

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug              = 'block_editor_booster';
		$this->name              = __( 'Block Editor Booster', 'neve' );
		$this->description       = __( 'Do more with the Block Editor with Otter\'s additional blocks made specifically for Neve Pro.', 'neve' );
		$this->order             = 5;
		$this->dependent_plugins = array(
			'otter-blocks' => array(
				'path' => 'otter-blocks/otter-blocks.php',
				'name' => 'Gutenberg Blocks and Template Library by Otter',
			),
		);
		$this->documentation     = array(
			'url'   => 'https://bit.ly/nv-gb-bl',
			'label' => __( 'Learn more', 'neve' ),
		);
	}

	/**
	 * Check if module should be loaded.
	 *
	 * @return bool
	 */
	function should_load() {
		return ( $this->is_active() && defined( 'OTTER_BLOCKS_VERSION' ) );
	}

	/**
	 * Run Block Editor Booster Module
	 */
	function run_module() {
		add_filter( 'neve_has_block_editor_module', '__return_true' );
	}
}

