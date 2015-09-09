<?php

namespace Nectary;

/**
 * Just a common class for all daos to extend
 *
 * @package Dao
 */
abstract class Dao {
  public $db;

  /**
   * Set up the PDO
   *
   * @constructor
   */
  public function __construct( \PDO $db ) {
    $this->set_pdo( $db );
  }

  /**
   * Clean up
   *
   * @deconstructor
   */
  public function __destruct() {
    unset( $this->db );
  }

  public function set_pdo( \PDO $db ) {
    $this->db = $db;
  }

  /**
   * Child classes need to implement get_by_criterion
   *
   * @abstract
   */
  abstract public function get_by_criterion( $options );
}
