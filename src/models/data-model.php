<?php

namespace Nectary;

/**
 * Data model
 */
abstract class Data_Model {
	public static $primary_key;
	public static $table_name;

	/**
	 * This is a helpful method for constructing objects from an
	 * associated array of values
	 *
	 * @param array|Object $array_of_values
	 */
	public function __construct( $array_of_values = array() ) {
		foreach ( $array_of_values as $fieldname => $value ) {
			if ( property_exists( $this, $fieldname ) ) {
				$this->$fieldname = $value;
			}
		}
	}

	/**
	 * Getter
	 *
	 * @param $property
	 * @return mixed
	 */
	public function __get( $property ) {
		if ( property_exists( $this, $property ) ) {
			return $this->$property;
		}
	}

	/**
	 * Setter - only allow properties that exist to be set
	 *
	 * @param  string $property
	 * @param  mixed  $value
	 * @return Data_Model
	 */
	public function __set( $property, $value ) {
		if ( property_exists( $this, $property ) ) {
			$this->$property = $value;
		}
		return $this;
	}

	/**
	 * Models should implement get_minimal_columns
	 *
	 * @return string
	 */
	public static function get_minimal_columns() : string {
		return '';
	}

	/**
	 * Models should implement get_all_columns
	 *
	 * @return string
	 */
	public static function get_all_columns() : string {
		return '';
	}
}
