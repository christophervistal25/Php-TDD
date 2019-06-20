<?php
namespace TDD\Test;
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
    // Following the Arrange-Act-Assert Pattern
    private $receipt;
    private $formatter;

    // Here's the section when we arrange all the class the we need
    public function setUp()
    {
        $this->formatter = $this->getMockBuilder('TDD\Formatter')
             ->setMethods(['currencyAmt'])
             ->getMock();

        $this->formatter->expects($this->any())
             ->method('currencyAmt')
             ->with($this->anything())
             ->will($this->returnArgument(0));

        $this->receipt = new Receipt($this->formatter);
    }

    public function tearDown()
    {
        unset($this->receipt);
    }
    // End of arrangement.
    /**
     * @dataProvider provideTotal
     */
    public function testSubtotal($items, $expected)
    {
        // Here's the section when we start to act for our test.
        $coupon = null;
        $output = $this->receipt->subTotal($items,$coupon);

        // Yeah you guest it right this section is the assert very self explanatory.
        $this->assertEquals(
            $expected,
            $output,
            "The total is not equal to {$expected}"
        );
    }

    public function provideTotal()
    {
        return [
            'ints totaling 16' => [ [1,2,5,8], 16],
            'ints totaling 14' => [ [-1,2,5,8], 14],
            'ints totaling 11' => [ [1,2,8], 11],
        ];
    }


    public function testTotalAndCoupon()
    {
        // Here's the section when we start to act for our test.
        $input = [0,2,5,8];
        $coupon = 0.20;
        $output = $this->receipt->subTotal($input,$coupon);

        // Yeah you guest it right this section is the assert very self explanatory.
        $this->assertEquals(
            12,
            $output,
            "The total is not equal to 12"
        );
    }

    public function testSubtotalException()
    {
        $input = [0,2,5,8];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $this->receipt->subTotal($input,$coupon);

    }

    public function testPostTaxTotal()
    {
        $items  = [1,2,5,8];
        $this->receipt->tax    = 0.20;
        $coupon = null;

        $receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['tax', 'subTotal'])
            ->setConstructorArgs([$this->formatter])
            ->getMock();

        $receipt->expects($this->once())
             ->method('subTotal')
             ->with($items, $coupon)
             ->will($this->returnValue(10.00));

        $receipt->expects($this->once())
            ->method('tax')
            ->with(10.00)
            ->will($this->returnValue(1.00));

        $result = $receipt->postTaxTotal([1,2,5,8], null);

        $this->assertEquals(11.00, $result);

    }

    public function testTax()
    {
        $amount = 10.0;
        $this->receipt->tax = 0.10;
        $output = $this->receipt->tax($amount);

        $this->assertEquals(
            1.00,
            $output,
            "The tax calculation should equal 1.00"
        );
    }



}
