<?php 
namespace TDD;

class Receipt {

	public function total(array $items = [] , $coupon) {
		$sum = array_sum($items);
		if(!is_null($coupon)) {
			return $sum - ($sum * $coupon);
		}
		return $sum;
	}

	public function tax(float $amount,float $tax) {
		return $amount * $tax;
	}

	public function postTaxTotal(array $items , $tax , $coupon) {
		$sub_total = $this->total($items,$coupon);
		return $sub_total + $this->tax($sub_total,$tax);
	}

}
