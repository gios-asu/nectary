<?php

namespace Nectary\Tests;

use Nectary\Factories\Html_Factory;
use PHPUnit\Framework\TestCase;

/**
 * Test the html factory
 *
 * @group html
 * @group factory
 */
class Html_Factory_Test extends TestCase {
	public function test_no_html() {
		$factory = new Html_Factory();
		$html = $factory->build();

		$this->assertEquals( '', $html );
	}

	public function test_heading() {
		$factory = new Html_Factory();
		$factory->add_heading( 'Test' );
		$html = $factory->build();

		$this->assertEquals( "<h2 class=''>Test</h2>", $html );
	}

	public function test_heading_with_classes() {
		$factory = new Html_Factory();
		$factory->add_heading( 'Test', [ 'class' => 'red' ] );
		$html = $factory->build();

		$this->assertEquals( "<h2 class='red'>Test</h2>", $html );
	}

	public function test_with_heading_returns_html() {
		$factory = new Html_Factory();
		$html = $factory->with_heading( 'Test', [ 'class' => 'red' ] );

		$this->assertEquals( "<h2 class='red'>Test</h2>", $html );
	}

	public function test_text() {
		$factory = new Html_Factory();
		$factory->add_text( 'Test' );
		$html = $factory->build();

		$this->assertEquals( "<p class=''>Test</p>", $html );
	}

	public function test_text_with_classes() {
		$factory = new Html_Factory();
		$factory->add_text( 'Test', [ 'class' => 'red' ] );
		$html = $factory->build();

		$this->assertEquals( "<p class='red'>Test</p>", $html );
	}

	public function test_with_text_returns_html() {
		$factory = new Html_Factory();
		$html = $factory->with_text( 'Test', [ 'class' => 'red' ] );

		$this->assertEquals( "<p class='red'>Test</p>", $html );
	}

	public function test_link() {
		$factory = new Html_Factory();
		$factory->add_link( 'Test' );
		$html = $factory->build();

		$this->assertEquals( "<a class='' href='#'>Test</a>", $html );
	}

	public function test_link_with_classes() {
		$factory = new Html_Factory();
		$factory->add_link( 'Test', [ 'class' => 'red' ] );
		$html = $factory->build();

		$this->assertEquals( "<a class='red' href='#'>Test</a>", $html );
	}

	public function test_with_link_returns_html() {
		$factory = new Html_Factory();
		$html = $factory->with_link( 'Test', [ 'class' => 'red' ] );

		$this->assertEquals( "<a class='red' href='#'>Test</a>", $html );
	}

	public function test_link_with_href() {
		$factory = new Html_Factory();
		$factory->add_link( 'Test', [ 'href' => 'red' ] );
		$html = $factory->build();

		$this->assertEquals( "<a class='' href='red'>Test</a>", $html );
	}

	public function test_image_with_classes() {
		$factory = new Html_Factory();
		$factory->add_image( 'red', [ 'class' => 'red' ] );
		$html = $factory->build();

		$this->assertEquals( "<img src='red' class='red' />", $html );
	}

	public function test_with_image_returns_html() {
		$factory = new Html_Factory();
		$html = $factory->with_image( 'red', [ 'class' => 'red' ] );

		$this->assertEquals( "<img src='red' class='red' />", $html );
	}

	public function test_image_with_src() {
		$factory = new Html_Factory();
		$factory->add_image( 'red' );
		$html = $factory->build();

		$this->assertEquals( "<img src='red' class='' />", $html );
	}

	public function test_div() {
		$factory = new Html_Factory();
		$factory->add_div( 'Test' );
		$html = $factory->build();

		$this->assertEquals( "<div class=''>Test</div>", $html );
	}

	public function test_div_with_classes() {
		$factory = new Html_Factory();
		$factory->add_div( 'Test', [ 'class' => 'red' ] );
		$html = $factory->build();

		$this->assertEquals( "<div class='red'>Test</div>", $html );
	}

	public function test_with_div_returns_html() {
		$factory = new Html_Factory();
		$html = $factory->with_div( 'Test', [ 'class' => 'red' ] );

		$this->assertEquals( "<div class='red'>Test</div>", $html );
	}
}
