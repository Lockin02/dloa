<?php
/*
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/income/iincome.php');

class model_finance_income_strategy_refund extends model_base implements iincome{

	function getInfoAndDetail_d($obj){

	}

	public function businessDeal_d($obj,$incomeId){
		if(!empty($obj['incomeAllots'])){
			try{
				$incomeAllotDao = new model_finance_income_incomeAllot();
				$incomeAllotDao->createBatch($obj['incomeAllots'],array('incomeId' => $incomeId));
			}catch(exception $e){
				throw $e;
			}
		}
	}
}
?>
