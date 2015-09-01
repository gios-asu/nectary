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

    if ( is_array( $error_callback ) ) {
      return call_user_func_array( $error_callback, [ $rules, $this ] );
    }

    return $error_callback( $rules, $this );
  }

  abstract public function validation_rules();
}
