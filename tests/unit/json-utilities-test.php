<?php

namespace Nectary\Tests;

use Nectary\Utilities\Json_Utilities;

/**
 * Test the json utilities in the framework
 *
 * @group utility
 */
class Json_Utilities_Test extends \PHPUnit_Framework_TestCase {
  function test_will_provide_value() {
    $raw = "
      {
        \"key\" : \"value\"
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key' );

    $this->assertEquals( 'value', $value );
  }

  function test_will_walk_along_json() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.key3' );
    $this->assertEquals( 'value', $value );
  }

  function test_will_return_null_on_fail() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.keyNope' );
    $this->assertEquals( null, $value );
  }

  function test_will_return_null_value() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"null\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.key3' );
    $this->assertEquals( 'null', $value );
  }

  function test_will_return_real_value_when_providing_a_default() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get_or_default( $json, 'key1.key2.key3', '???' );
    $this->assertEquals( 'value', $value );
  }

  function test_will_provide_default_when_key_does_not_exist() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get_or_default( $json, 'key1.key2.keyNope', '???' );
    $this->assertEquals( '???', $value );
  }

  function test_will_access_arrays() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : [
              \"value1\",
              \"value2\"
            ]
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.key3.1' );
    $this->assertEquals( 'value2', $value );
  }

  function test_will_return_null_when_accessing_non_existant_array_index() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : [
              \"value1\",
              \"value2\"
            ]
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.key3.100' );
    $this->assertEquals( null, $value );
  }

  function test_will_return_array() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : [
              \"value1\",
              \"value2\"
            ]
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.key3' );
    $this->assertEquals( array( 'value1', 'value2' ), $value );
  }
}
