<?php

namespace Nectary\Tests;

use Nectary\Daos\Select_SQL_Query_Builder;
use PHPUnit\Framework\TestCase;

/**
 * SQL Query Builder Test
 *
 * @group daos
 */
class Sql_Query_Builder_Test extends TestCase {
	public function test_constructor() {
		$builder = new Select_SQL_Query_Builder();
		$this->assertInstanceOf( 'Nectary\Daos\Select_SQL_Query_Builder', $builder );
	}

	public function test_that_a_simple_query_is_executeable() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( 'people.person_id' );
		$builder->from( 'people' );
		$statement = $builder->get_sql();

		$this->assertContains( 'SELECT people.person_id FROM people WHERE 1=1 LIMIT 100', $statement );
	}

	public function test_that_a_bare_query_is_executeable() {
		$builder = new Select_SQL_Query_Builder();
		$builder->from( 'people' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 LIMIT 100', $statement );
	}

	public function test_where_clauses_incorrectly() {
		// this should fail because we are using the where clause incorrectly, we are giving it a statement not a clause
		$builder = new Select_SQL_Query_Builder();
		$builder->from( 'people' );
		$builder->where( 'AND 1<>1' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 AND 1<>1 LIMIT 100', $statement );
	}

	public function test_get_columns() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( 'people.person_id' );
		$columns = $builder->get_columns();

		$this->assertEquals( ['people.person_id'], $columns );
	}

	public function test_bind_value() {
		$builder = new Select_SQL_Query_Builder();
		$builder->bind_value( ':name', 'value' );

		$statement_stub = $this->getMockBuilder('MockStatement')
								 ->setMethods(array('bindValue'))
								 ->getMock();

		$statement_stub->expects($this->once())
								 ->method('bindValue')
								 ->with( ':name', 'value' )
								 ->willReturn(true);

		$pdo_stub = $this->getMockBuilder('MockPDO')
								 ->setMethods(array('prepare'))
								 ->getMock();

		$pdo_stub->expects($this->once())
								 ->method('prepare')
								 ->willReturn($statement_stub);


		$statement = $builder->get_statement( $pdo_stub );
	}

	public function test_bind_value_with_type() {
		$builder = new Select_SQL_Query_Builder();
		$builder->bind_value( ':name', 'value', \PDO::PARAM_STR );

		$statement_stub = $this->getMockBuilder('MockStatement')
								 ->setMethods(array('bindValue'))
								 ->getMock();

		$statement_stub->expects($this->once())
								 ->method('bindValue')
								 ->with( ':name', 'value', \PDO::PARAM_STR)
								 ->willReturn(true);

		$pdo_stub = $this->getMockBuilder('MockPDO')
								 ->setMethods(array('prepare'))
								 ->getMock();

		$pdo_stub->expects($this->once())
								 ->method('prepare')
								 ->willReturn($statement_stub);


		$statement = $builder->get_statement( $pdo_stub );
	}


	public function test_and_where_clauses_correctly() {
		$builder = new Select_SQL_Query_Builder();
		$builder->from( 'people' );
		$builder->and_where( '1=1' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 AND 1=1 LIMIT 100', $statement );
	}

	public function test_or_where_clauses_correctly() {
		$builder = new Select_SQL_Query_Builder();
		$builder->from( 'people' );
		$builder->or_where( '1=1' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 OR 1=1 LIMIT 100', $statement );
	}

	public function test_add_columns_can_take_multiple_types_and_maintain_array() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( 'people.person_id' );
		$builder->add_columns( array( 'people.first_name' ) );
		$builder->add_columns( 'people.last_name, people.middle_name' );
		$builder->from( 'people' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT people.person_id, people.first_name, people.last_name, people.middle_name FROM people WHERE 1=1 LIMIT 100', $statement );
	}

	public function test_that_a_more_complicated_query_is_executeable() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( 'people.*' );
		$builder->from( 'people' );
		$builder->limit( 1 );
		$builder->order_by( 'people.person_id DESC' );
		$builder->joins( 'LEFT JOIN groups_people ON (groups_people.person_id = people.person_id)' );
		$builder->add_columns( 'groups_people.*' );
		$builder->where( 'AND people.person_id <> 1' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT people.*, groups_people.* FROM people LEFT JOIN groups_people ON (groups_people.person_id = people.person_id) WHERE 1=1 AND people.person_id <> 1 ORDER BY people.person_id DESC LIMIT 1', $statement );
	}

	public function test_that_a_limit_integer_can_be_set() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( '*' );
		$builder->from( 'people' );
		$builder->limit( 1 );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 LIMIT 1', $statement );
	}

	public function test_that_a_limit_can_be_set_as_bind_value() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( '*' );
		$builder->from( 'people' );
		$builder->limit( ':limit_to' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 LIMIT :limit_to', $statement );
	}

	public function test_that_a_limit_defaults_to_zero_if_input_is_not_integer() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( '*' );
		$builder->from( 'people' );
		$builder->limit( 'limit' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 LIMIT 0', $statement );
	}

	public function test_group_by() {
		$builder = new Select_SQL_Query_Builder();
		$builder->group_by( 'column-to-group-by' );
		$sql = $builder->get_sql();

		$this->assertContains( ' GROUP BY column-to-group-by ', $sql );
	}

	public function test_group_by_multiple() {
		$builder = new Select_SQL_Query_Builder();
		$builder->group_by( 'first-column-to-group-by' );
		$builder->group_by( 'second-column-to-group-by' );
		$sql = $builder->get_sql();

		$this->assertContains( ' GROUP BY first-column-to-group-by, second-column-to-group-by', $sql );
	}

	 public function test_or_where_clauses_has_proper_white_spaces() {
		$builder = new Select_SQL_Query_Builder();
		$builder->from( 'people' );
		$builder->and_where( '1=1' );
		$builder->or_where( '1=1' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 AND 1=1 OR 1=1 LIMIT 100', $statement );
	}

	public function test_or_multiple_joins_has_proper_white_spaces() {
		$builder = new Select_SQL_Query_Builder();
		$builder->from( 'people' );
		$builder->and_where( '1=1' );
		$builder->or_where( '1=1' );
		$builder->joins( 'LEFT JOIN groups_people ON (groups_people.person_id = people.person_id)' );
		$builder->joins( 'LEFT JOIN groups_people ON (groups_people.person_id = people.person_id)' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people LEFT JOIN groups_people ON (groups_people.person_id = people.person_id) LEFT JOIN groups_people ON (groups_people.person_id = people.person_id) WHERE 1=1 AND 1=1 OR 1=1 LIMIT 100', $statement );
	}

	public function test_that_a_offest_can_be_set_as_bind_value() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( '*' );
		$builder->from( 'people' );
		$builder->offset( ':offset' );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 LIMIT 100 OFFSET :offset', $statement );
	}

	public function test_that_a_offest_can_be_set_with_an_integer_value() {
		$builder = new Select_SQL_Query_Builder();
		$builder->add_columns( '*' );
		$builder->from( 'people' );
		$builder->offset( 33 );
		$statement = $builder->get_sql();

		$this->assertEquals( 'SELECT * FROM people WHERE 1=1 LIMIT 100 OFFSET 33', $statement );
	}
}
