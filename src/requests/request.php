<?php

namespace Nectary;

/**
 * A request will validate based on a set of rules
 *
 * A request can also be used to take in options and
 * pull in the data required to fulfill a request.
 */
abstract class Request {
	public function validate( $error_callback ) {
		$rules = $this->validation_rules();

		$results = [];

		foreach ( $rules as $name => $check ) {
			if ( true !== $check ) {
				if ( \is_array( $error_callback ) ) {
					$results[] = \call_user_func_array( $error_callback, [ $check, $this ] );
				} else {
					$results[] = $error_callback( $check, $this );
				}
			}
		}

		if ( \count( $results ) > 0 ) {
			return $results;
		}
	}

	abstract public function validation_rules() : array;
}
