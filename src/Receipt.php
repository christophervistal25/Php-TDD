<?php
namespace TDD;

use BadMethodCallException;
use TDD\Formatter;

class Receipt {

	public $tax;

	public function __construct(Formatter $formatter)
	{
		$this->formatter = $formatter;
	}

	public function subTotal(array $items = [], $coupon) :int
	{
		if ( $coupon > 1.00 ) {
			throw new BadMethodCallException('Coupon must be less than or equal to 1.00');
		}

		$sum = array_sum($items);

		if ( !is_null($coupon) ) {
			return $sum - ($sum * $coupon);
		}

		return $sum;
	}

	public function tax(float $amount) :float
	{
		return $this->formatter->currencyAmt($amount * $this->tax);
	}

	public function postTaxTotal(array $items = []  , $coupon)
	{
		$subtotal = $this->subTotal($items, $coupon);
		return $subtotal + $this->tax($subtotal, $this->tax);
	}
}
