<?php
namespace TDD\Test\DataProviders;


trait ReceiptTestDataProvider {
   
	public function provideTotal() {
		return [
			'totaling 16' => [[1,2,5,8],16],
			'totaling 14' => [[-1,2,5,8],14],
			'totaling 11' => [[1,2,8],11]
		];
    }

    public function provideTax() {
		return [
			'totaling 1.00' => [10,0.10,1.00],
			'totaling 2.00' => [10,0.20,2.00],
			'totaling 3.00' => [10,0.30,3.00],
		];
	}
	
	
	public function providePostTaxTotal() {
		return [
			'totaling 11.0' => [[1,2,5,8],0.20,null,11.0],
		];
	}

	public function provideTotalAndCoupon() {
		return [
			'totaling 12' => [[0,2,5,8],0.20,12],
		];
	}
}
