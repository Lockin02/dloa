<?php

/**
 * 应付账款model层类
 */
class model_finance_payable_payable extends model_base {

	function __construct() {
//		$this->tbl_name = "oa_finance_payable";
		$this->sql_map = "finance/payable/payableSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 获取明细 - 附带统计
	 */
	function getPayDetail_d($object){
		//清除searchArr
//		unset($this->searchArr);

		$rs = array(); //返回数组
		$countRow = array( 'id' => 'balance', 'departments' => '期初余额:' ); //统计行数组
		$rsKey = null; //行月数
		$markKey = null;  //标记月数
		$periodNeedPay = 0; //本期合计
		$periodPayed = 0;  //本期实付
		$periodBalance = 0;  //本期余额
		$periodWord = '本期合计:';
		$yearNeedPay = 0;  //本年合计
		$yearPayed = 0;  //本年实付
		$yearWord = '本年累计:';
		$nowAmount = 0;
		//余额部分
		$balanceDao = new model_finance_balance_balance();

		$balanceRow = $balanceDao->rtBalance_d($object['year'],$object['beginMonth'],1,$object['supplierId']);
		if($balanceRow){
			$countRow['amount'] = $balanceRow['needPay'];
			$countRow['needPay'] = $balanceRow['payed'];
			$countRow['balance'] = $balanceRow['balance'];

			$periodNeedPay = $balanceRow['needPay'];
			$periodPayed = $balanceRow['payed'];
			$periodBalance = $balanceRow['balance'];

			$isInPeriod = $balanceDao->isBigThanPeriod_d($object['year'],$object['beginMonth'],1);
			if($isInPeriod){
				$thisRs = $this->getFormCountByMonth_d($object,$isInPeriod);
//				print_r($thisRs);
				$countRow['amount'] += $thisRs['amount'];
				$countRow['needPay'] += $thisRs['needPay'];
				$countRow['balance'] += $thisRs['balance'];

				$periodNeedPay += $thisRs['amount'];
				$periodPayed += $thisRs['needPay'];
				$periodBalance += $thisRs['balance'];
			}
		}
		$rs[] = $countRow;
		unset($countRow['balance']);

		//明细部分
		$this->getParam($object);
		$row = $this->listBySqlId('detail_union');
		foreach($row as $key => $val){
			$rsKey = date( 'm' ,strtotime($val['formDate'] ));
			if($rsKey == $markKey){//月份相同,不需要特殊处理

			}else if(!empty($markKey) && $rsKey != $markKey){//月份不同,需要做统计处理

				//统计本期,将本期金额加上
				$countRow['id'] = 'period'.$rsKey;
				$countRow['departments'] = $periodWord;
				$countRow['amount'] = $periodNeedPay;
				$countRow['needPay'] = $periodPayed;
				$rs[] = $countRow;

				//统计本年,将本年金额加上
				$yearNeedPay += $periodNeedPay;
				$yearPayed += $periodPayed;
				$countRow['id'] = 'year'.$rsKey;
				$countRow['departments'] = $yearWord;
				$countRow['amount'] = $yearNeedPay;
				$countRow['needPay'] = $yearPayed;
				$rs[] = $countRow;

				$markKey = $rsKey;
				$periodNeedPay = 0;
				$periodPayed = 0;
			}else if(empty($markKey)){//无初始记录值,进行初始赋值
				$markKey = $rsKey ;
			}
			$periodNeedPay += $val['amount'] ;
			$periodPayed += $val['needPay'] ;
			if(empty($val['amount'])){//判断，如果是应付，增加应付余额，否则减少应付余额
				$periodBalance -= $val['needPay'] ;
			}else{
				$periodBalance += $val['amount'] ;
			}
			$val['balance'] = $periodBalance ;
			$rs[] = $val;
		}

		//结束后处理最后一期的金额
		$countRow['id'] = 'period'.$rsKey;
		$countRow['departments'] = $periodWord;
		$countRow['amount'] = $periodNeedPay;
		$countRow['needPay'] = $periodPayed;
		$rs[] = $countRow;

		//结束后处理最后一起的金额
		$yearNeedPay += $periodNeedPay;
		$yearPayed += $periodPayed;
		$countRow['id'] = 'year'.$rsKey;
		$countRow['departments'] = $yearWord;
		$countRow['amount'] = $yearNeedPay;
		$countRow['needPay'] = $yearPayed;
		$rs[] = $countRow;
		return $rs;
	}

	/**
	 * 获取期初余额数组
	 */
	function getFormCountByMonth_d($object,$isInPeriod){
		$this->searchArr['formDate'] = $isInPeriod['thisDate'];
		$this->searchArr['formDateMonth'] = $object['beginMonth'];
		if(!empty($object['supplierId'])){
			$this->searchArr['supplierIds'] = $object['supplierId'];
		}
		if(!empty($object['deptId'])){
			$this->searchArr['deptIds'] = $object['deptId'];
		}
		if(!empty($object['salesmanId'])){
			$this->searchArr['salesmanId'] = $object['salesmanId'];
			$this->groupBy = 'salesmanId';
		}
		$rs = $this->listBySqlId('sum_union');
		return $rs[0];
	}


	/**
	 * 应付款汇总数据获取方法
	 */
	function getCount_d($object){
		$addRow = array();      //数据附加数组
		$rtRows = array();      //返回数组
		$countRows = array();   //统计数组

		$periodBeginBalance = 0; //期初余额
		$periodEndBalance = 0;   //期末余额
		$periodNeedPay = 0;      //本期应付
		$periodPayed = 0;        //本期实付

		$yearNeedPay = 0;        //本年累计应付
		$yearPayed = 0;          //本年累计实付

		$thisSpuulierId = 0;     //当前供应商id

		$beginDeal = 0;          //前部数据是否已处理
		$endDeal = 0;            //后部数据是否已处理

		/********统计数据生成部分***************/
		$this->getParam($object);
		$this->groupBy = 'supplierId,month(formDate)';//根据供应商id获取分组
		$this->sort = 'supplierId';
		$this->asc = false;
		$rs = $this->listBySqlId('count_union');

		//汇总数据处理
		foreach($rs as $key => $val){
			$rtRows[] = $val;
		}

		$rtRows = array_merge( $rtRows , $countRows);
		return $rtRows;
	}
}
?>