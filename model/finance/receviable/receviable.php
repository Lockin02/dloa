<?php
header("Content-type: text/html; charset=gb2312");
/**
 * 应收账款model层类
 */
class model_finance_receviable_receviable extends model_base {

	function __construct() {
		//$this->tbl_name = "oa_finance_receviable";
		$this->sql_map = "finance/receviable/receviableSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 获取明细 - 附带统计
	 */
	function getPayDetail_d($object){
		$rs = array(); //返回数组
		$countRow = array( 'id' => 'balance', 'deptName' => '期初余额:' ); //统计行数组
		$rsKey = null; //行月数
		$markKey = null;  //标记月数
		$periodAmount = 0; //本期合计
		$periodPayed = 0;  //本期实付
		$periodBalance = 0;  //本期余额
		$periodWord = '本期合计:';
		$yearAmount = 0;  //本年合计
		$yearPayed = 0;  //本年实付
		$yearWord = '本年累计:';
		$nowAmount = 0;

		//余额部分
		$balanceDao = new model_finance_balance_balance();

		$balanceRow = $balanceDao->rtBalance_d($object['year'],$object['beginMonth'],0,$object['customerId']);
		if($balanceRow){
			$countRow['amount'] = $balanceRow['needPay'];
			$countRow['trueReceive'] = $balanceRow['payed'];
			$countRow['balance'] = $balanceRow['balance'];

			$periodAmount = $balanceRow['needPay'];
			$periodPayed = $balanceRow['payed'];
			$periodBalance = $balanceRow['balance'];

			$isInPeriod = $balanceDao->isBigThanPeriod_d($object['year'],$object['beginMonth']);
//			print_r($isInPeriod);
			if($isInPeriod){
				$thisRs = $this->getFormCountByMonth_d($object,$isInPeriod);
//				print_r($thisRs);
				$countRow['amount'] += $thisRs['amount'];
				$countRow['trueReceive'] += $thisRs['trueReceive'];
				$countRow['balance'] += $thisRs['balance'];

				$periodAmount += $thisRs['amount'];
				$periodPayed += $thisRs['trueReceive'];
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
				$countRow['deptName'] = $periodWord;
				$countRow['amount'] = $periodAmount;
				$countRow['trueReceive'] = $periodPayed;
				$rs[] = $countRow;

				//统计本年,将本年金额加上
				$yearAmount += $periodAmount;
				$yearPayed += $periodPayed;
				$countRow['id'] = 'year'.$rsKey;
				$countRow['deptName'] = $yearWord;
				$countRow['amount'] = $yearAmount;
				$countRow['trueReceive'] = $yearPayed;
				$rs[] = $countRow;

				$markKey = $rsKey;
				$periodAmount = 0;
				$periodPayed = 0;
			}else if(empty($markKey)){//无初始记录值,进行初始赋值
				$markKey = $rsKey ;
			}
			$periodAmount += $val['amount'] ;
			$periodPayed += $val['trueReceive'] ;
			if(empty($val['amount'])){//判断，如果是应付，增加应付余额，否则减少应付余额
				$periodBalance -= $val['trueReceive'] ;
			}else{
				$periodBalance += $val['amount'] ;
			}
			$val['balance'] = $periodBalance;
			$rs[] = $val;
		}

		//结束后处理最后一期的金额
		$countRow['id'] = 'period'.$rsKey;
		$countRow['deptName'] = $periodWord;
		$countRow['amount'] = $periodAmount;
		$countRow['trueReceive'] = $periodPayed;
		$rs[] = $countRow;

		//结束后处理最后一起的金额
		$yearAmount += $periodAmount;
		$yearPayed += $periodPayed;
		$countRow['id'] = 'year'.$rsKey;
		$countRow['deptName'] = $yearWord;
		$countRow['amount'] = $yearAmount;
		$countRow['trueReceive'] = $yearPayed;
		$rs[] = $countRow;
		return $rs;
	}

	/**
	 * 获取期初余额数组
	 */
	function getFormCountByMonth_d($object,$isInPeriod){
		$this->searchArr['formDate'] = $isInPeriod['thisDate'];
		$this->searchArr['formDateMonth'] = $object['beginMonth'];
		if(!empty($object['customerId'])){
			$this->searchArr['customerId'] = $object['customerId'];
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
	 * 统计合同应收和到款,返回数组
	 */
	function getInvoiceAndIncome_d($id,$contractType){
		$sql = "select
				sum(moneyDetail.invoiceMoney) as invoiceMoney,sum(moneyDetail.incomeMoney) as incomeMoney ,
				sum(moneyDetail.softMoney) as softMoney,sum(moneyDetail.hardMoney) as hardMoney,
				sum(moneyDetail.serviceMoney) as serviceMoney,sum(moneyDetail.repairMoney) as repairMoney,
				sum(moneyDetail.applyedMoney) as applyedMoney
			from (
				select sum(if(c.isRed = 0 ,c.invoiceMoney,-c.invoiceMoney)) as invoiceMoney,0 as incomeMoney,sum(if( c.isRed = 0,c.softMoney,-c.softMoney)) as softMoney,sum(if( c.isRed = 0,c.hardMoney,-c.hardMoney)) as hardMoney,
					sum(if( c.isRed = 0,c.serviceMoney,-c.serviceMoney)) as serviceMoney,sum(if(c.isRed = 0 ,c.repairMoney,-c.repairMoney)) as repairMoney,0 as applyedMoney
				from financeView_invoice c where c.objId = $id and c.objType = '$contractType'
				union all
				select 0 as invoiceMoney,sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney,0 as softMoney,0 as hardMoney,0 as serviceMoney,0 as repairMoney,0 as applyedMoney
				from financeview_income_allot c left join oa_finance_income i on c.incomeId = i.id where c.objId = $id and c.objType = '$contractType'
				union all
				select 0 as invoiceMoney,0 as incomeMoney,0 as softMoney,0 as hardMoney,0 as serviceMoney,0 as repairMoney,sum(invoiceMoney) as applyedMoney
				from oa_finance_invoiceapply c inner join financeview_invoiceapply_relate r on c.id = r.applyId where c.ExaStatus <> '打回' and r.objId = $id  and r.objType = '$contractType'
			) moneyDetail limit 1";
		$rs = $this->_db->getArray($sql);
		if(!$rs){
			return array( "invoiceMoney" => 0 , "incomeMoney" => 0 );
		}
		return $rs[0];
	}

    /**
     * 到款明细表
     */
    function filterArr_d($rows){
        $rtArr = array();
        $incomeMoney = $allotAble = $money = 0;
        if($rows){
            $markId = null;
            foreach($rows as $key => $val){
                if($markId != $val['id']){
                    //标记值跟记录id不等时，直接将数组赋予到返回数组中
                    $rtArr[$key] = $val;
                    $markId = $val['id'];

                    $incomeMoney = bcadd($incomeMoney,$val['incomeMoney'],2);
                    $unIncomeMoney = bcadd($unIncomeMoney,$val['unIncomeMoney'],2);
                    $invoiceMoney = bcadd($invoiceMoney,$val['invoiceMoney'],2);
                    $unInvoiceMoney = bcadd($unInvoiceMoney,$val['unInvoiceMoney'],2);
                    $allotAble = bcadd($allotAble,$val['allotAble'],2);
                    $money = bcadd($money,$val['thisOrderMoney'],2);
                }else{
                	$rtArr[$key]['objId'] = $val['objId'];
                    $rtArr[$key]['objType'] = $val['objType'];
                    $rtArr[$key]['objCode'] = $val['objCode'];
                    $rtArr[$key]['money'] = $val['money'];
                    $rtArr[$key]['id'] = $val['id'];
                    $money = bcadd($money,$val['thisOrderMoney'],2);
                    $incomeMoney = bcadd($incomeMoney,$val['incomeMoney'],2);
                    $unIncomeMoney = bcadd($unIncomeMoney,$val['unIncomeMoney'],2);
                    $invoiceMoney = bcadd($invoiceMoney,$val['invoiceMoney'],2);
                    $unInvoiceMoney = bcadd($unInvoiceMoney,$val['unInvoiceMoney'],2);
                }
            }
        }
        array_push($rtArr,array('id' => 'noId' , 'areaPrincipal' => '合计：','thisOrderMoney' => $money  ,'incomeMoney' => $incomeMoney,'unIncomeMoney'=>$unIncomeMoney ,'invoiceMoney' => $invoiceMoney,'unInvoiceMoney'=>$unInvoiceMoney));
        return $rtArr;
    }

	/**
	 * 获取当前财务期
	 */
	function rtThisPeriod_d(){
		$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
	}


	/*************************** 新合同部分 **************************/
	/**
	 * 初始化
	 */
	function initIncomeAnalysis_d($object){
		$sql = "select
				i.incomeDate,year(i.incomeDate) as thisYear,month(i.incomeDate) as thisMonth,i.customerId,i.customerName,
				i.objId,i.objType,
				sum(i.incomeMoney) as incomeMoney,
				i.incomeDetail,
				if(f.invoiceMoney is null,0,f.invoiceMoney) as invoiceMoney
			from
			(
			select
				i.incomeDate,i.customerId,i.customerName,
				i.objId,i.objType,
				sum(i.incomeMoney) as incomeMoney,
				group_concat(concat(cast(i.incomeDate as char charset gbk),' , ',cast(i.incomeMoney as char charset gbk)) separator ' ; ') as incomeDetail,
				0 as invoiceMoney
			from
				(
				select
					i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,
					a.objId,a.objType,
					if(a.objId is null,i.incomeMoney ,if(i.formType <> 'YFLX-TKD' ,a.money,-a.money) ) as incomeMoney
				from
					oa_finance_income i
					left join
					oa_finance_income_allot a on i.id = a.incomeId
				where date_format(i.incomeDate,'%Y%m') between date_format('2012-01-01','%Y%m') and date_format('2012-04-30','%Y%m')
				union all
				select
					i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,
					null as objId,null as objType,
					if(i.formType <> 'YFLX-TKD' ,i.allotAble,-i.allotAble) as incomeMoney
				from
					oa_finance_income i
				where i.status = 'DKZT-BFFP' and date_format(i.incomeDate,'%Y%m') between date_format('2012-01-01','%Y%m') and date_format('2012-04-30','%Y%m')
				) i
			group by date_format(i.incomeDate,'%Y%m'),i.objType,i.objId,i.customerName

			) i left join (
				select
					i.objId,i.objType,sum(i.invoiceMoney) as invoiceMoney
				from
					oa_finance_invoice i
				where i.objId <> 0
				group by i.objType,i.objId
				) f on i.objId = f.objId and i.objType = f.objType
			group by date_format(i.incomeDate,'%Y%m'),i.objType,i.objId,i.customerName";
	}
}
?>