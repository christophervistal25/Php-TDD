<?php 
namespace TDD\Test;
require dirname(dirname(__FILE__))  . '\vendor' .  '\autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase {

	/**
	* All test data is encapsulated on this trait
	*/
	use DataProviders\ReceiptTestDataProvider;

	public function setUp()	{
		$this->Receipt = new Receipt();
	}

	public function tearDown() {
		unset($this->Receipt);
	}

	/**
	 * @dataProvider provideTotal
	 * @test
	 */
	public function Total($items , $expected)	{
		$coupon = null;
		$output = $this->Receipt
					   ->total($items,$coupon);
		$this->assertEquals($expected,$output , "When summing the total should equal {$expected}");
	}


	/**
	 * @dataProvider provideTotalAndCoupon
	 * @test
	 */
	public function totalAndCoupon($items , $coupon , $expected) {
		$output = $this->Receipt
					   ->total($items,$coupon);
		$this->assertEquals($expected,$output);
	}


	/**
	 * @dataProvider providePostTaxTotal
	 * @test
	 */
	public function postTaxTotal($items , $tax , $coupon , $expected) {

		
		$Receipt = $this->getMockBuilder('TDD\Receipt')
			 ->setMethods(['tax','total'])
			 ->getMock();

		$Receipt->expects($this->once())
				->method('total')
				->with($items,$coupon)
				->will($this->returnValue(10.00));

		$Receipt->expects($this->once())
				->method('tax')
				->with(10.00,$tax)
				->will($this->returnValue(1.00));
		
		$result = $Receipt->postTaxTotal($items,$tax,$coupon);

		$this->assertEquals($expected,$result);
		

	}

	/**
	 * @dataProvider provideTax
	 * @test
	 */
	public function tax($amount , $tax , $expected) {
		$output = $this->Receipt
					   ->tax($amount,$tax);
		$this->assertEquals($expected,$output);
	}

	
	
}

