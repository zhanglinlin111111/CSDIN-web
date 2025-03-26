<?php
/**
 * Class provides option for comparison table module.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fields Class for Comparison Table functions.
 */
class Product_Fields extends Fields {
		
	/**
	 * Data_Store istance
	 *
	 * @var Data_Store
	 */
	private $data_store;
	
	/**
	 * __construct
	 *
	 * @param  Data_Store $data_store that instance of Data_Store class.
	 * @return void
	 */
	public function __construct( $data_store ) {
		parent::__construct();
		
		$this->data_store = $data_store;
	}

	/**
	 * Returns all available (only active ones) fields.
	 * Return array contains objects which instance of Field classes.
	 *
	 * @return array that contains Field objecct instances.
	 */
	public function get_available_fields( $attrs = array() ) {
		$available_fields = parent::get_available_fields( $attrs );
		
		$new_fields = array();
		
		foreach ( $available_fields as $field ) {
			/**
			* Create multiple classes for all product attributes. (Product attributes generally has multiple column.)
			*/
			if ( $field->get_key() === 'attributes' ) {
				$attributes_of_all_products = $this->get_attributes_of_all_products();

				// inject the attributes of products
				foreach ( $attributes_of_all_products as $attribute_key => $attribute_label ) {
					$new_attribute_field = clone $field;
					$new_attribute_field->set_attribute_key( $attribute_key );
					$new_attribute_field->set_attribute_label( $attribute_label );

					$new_fields[] = $new_attribute_field;
				}
			} else {
				$new_fields[] = $field;
			}
		}

		return $new_fields;
	}

	/**
	 * Function that returns all possible attributes of the products.
	 * $all_attributes property contains all possible attribute_key, attribte label pairs for comparison table products.
	 *
	 * @return array
	 */
	protected function get_attributes_of_all_products() {
		$products       = $this->data_store->get_products();
		$all_attributes = array();

		foreach ( $products as $product ) {
			$product_attribute = new \Neve_Pro\Modules\Woocommerce_Booster\Comparison_Table\Product_Attribute( $product );

			foreach ( $product_attribute->get_attributes() as $attribute_key => $attribute ) {
				$all_attributes[ $attribute_key ] = $attribute['label'];
			}
		}

		return $all_attributes;
	}
}
