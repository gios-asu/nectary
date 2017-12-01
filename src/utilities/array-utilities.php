<?php

if ( ! function_exists( 'array_peak' ) ) {
	function array_peak( $array ) {
		if ( is_array( $array ) ) {
			foreach ( $array as $value ) {
				return $value;
			}
		}

		return null;
	}
}


if ( ! function_exists( 'to_array' ) ) {
	/**
	 * Wraps whatever argument its passed in an array unless it is an array, always returns an array. always.
	 *
	 * @param mixed $something gets wrapped in an array
	 * @return array always returns an array
	 */
	function to_array( $something = array() ) {
		if ( is_array( $something ) ) {
			return $something;
		}

		if ( null === $something ) {
			return array();
		}

		return array( $something );
	}
}
