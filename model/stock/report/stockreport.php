<?php

class model_stock_report_stockreport extends model_base {

	public $db;

	function __construct() {
		//$this->tbl_name = "oa_stock_baseinfo";
		//$this->sql_map = "stock/stockinfo/stockinfoSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * 物料明细列表模板
	 */
	function showProItemDetail($rows) {
		if (is_array ( $rows )) {
			$str = "";
			foreach ( $rows as $key => $val ) {
				$str .= <<<EOT
					<tr align="center">
							<td>
								$val[docDate]
							</td>
							<td>
								$val[productCode]
							</td>
							<td>
								$val[productName]
							</td>														
							<td>
								$val[stockName]
							</td>
							<td>
								$val[docCode]
							</td>
							<td>
								$val[actType]
							</td>
							<td>
								$val[inNum]
							</td>
							<td class="formatMoney">
								$val[inPrice]
							</td>
							<td class="formatMoney">
								$val[inAmount]
							</td>
							<td>
								$val[outNum]
							</td>
							<td class="formatMoney">
								$val[outPrice]
							</td>
							<td class="formatMoney">
								$val[outAmount]
							</td>
							<td>
								$val[balNum]
							</td>
							<td class="formatMoney">
								$val[balPrice]
							</td>
							<td class="formatMoney">
								$val[balAmount]
							</td>
							<td>
								
							</td>
				 		</tr>
EOT;
			}
			return $str;
		} else {
			return "";
		}
	}

	/**
	 *
	 * 获取物料明细信息
	 */
	function findProItemDetail($productId, $thisYear, $thisMonth) {
		$thisYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $thisMonth, $thisYear ); //这个月有多少天
		$monthStartDate = $thisYear . "-" . $thisMonth . "-1";//月开始日期
		$monthEndDate = $thisYear . "-" . $thisMonth . "-" . $thisYearMonthNum;//月结束日期

		$datadictDao = new model_system_datadict_datadict ();
		$resultArr = array (); //返回结果
		$qcObj = array (); //期初信息
		$selectSql = "";
		$inSubNum = 0; //收入小计
		$outSubNum = 0; //发出小计


		/*s:---------------期初信息处理----------------------*/
		$selectSql = "select   s.thisYear,
						       s.thisMonth,
						       s.productId,s.productNo as productCode,s.productName,
						       sum(s.balanceAmount) as balAmount,
						       s.price as balPrice,
						       sum(s.clearingNum) as balNum
						from oa_finance_stockbalance s
						group by s.thisYear,
						         s.thisMonth,
						         s.productId
						having s.productId = $productId and s.thisYear = $thisYear and s.thisMonth = $thisMonth ";

		$qcObj = $this->_db->getArray ( $selectSql );
		$qcObj [0] ['docDate'] = $thisYear ."-".$thisMonth;
		$qcObj [0] ['docCode'] = null;
		$qcObj [0] ['actType'] = "期初结存";
		$qcObj [0] ['inNum'] = null;
		$qcObj [0] ['inPrice'] = null;
		$qcObj [0] ['inAmount'] = null;
		$qcObj [0] ['outNum'] = null;
		$qcObj [0] ['outPrice'] = null;
		$qcObj [0] ['outAmount'] = null;
		$qcObj [0] ['remark'] = null;
		$qcObj [0] ['stockName'] = null;
		if (empty($qcObj [0]['balAmount'])) { //期初信息
			$productinfoDao=new model_stock_productinfo_productinfo();
			$proObj=$productinfoDao->get_d($productId);
			
			$qcObj [0] ['productCode'] = $proObj['productCode'];
			$qcObj [0] ['productName'] = $proObj['productName'];
			$qcObj [0] ['balAmount'] = 0;
			$qcObj [0] ['balPrice'] = 0;
			$qcObj [0] ['balNum'] = 0;
		}
//		echo "<pre>";
//		print_r($qcObj [0]);
		array_push ( $resultArr, $qcObj [0] );
//		print_r($resultArr);
		/*e:---------------期初信息处理----------------------*/

		/*s:---------------收发台账处理----------------------*/
		$QuerySQL = <<<EOT
		select `docDate`,docCode,docType,inNum,productCode,productName,inPrice,inAmount,outNum,outPrice,outAmount,remark,stockName from (
		select `ik`.`auditDate` as docDate,
		       `ik`.`docCode`,
		       ik.`docType`,
		       case ik.isRed when 0 then iik.`actNum` else -iik.`actNum` end  as inNum,
		       iik.`productCode` as productCode,
		       iik.`productName` as productName,
		       iik.`price` as inPrice,
		       iik.`subPrice` as inAmount,
		       null as outNum,
		       null as outPrice,
		       null as outAmount,
		       ik.remark as remark,
		       iik.inStockName as stockName
		from `oa_stock_instock` ik inner join oa_stock_instock_item iik on(iik.`mainId`=ik.`id`) where ik.docStatus='YSH' and iik.productId=$productId and ik.auditDate between '$monthStartDate' and '$monthEndDate'
		union ALL
		select ok.`auditDate` as docDate,
		       ok.`docCode` as docCode,
		       ok.`docType`,
		       null as inNum,
		       ook.`productCode` as productCode,
		       ook.`productName` as productName,		       
		       null as inPrice,
		       null as inAmount,
		       case ok.isRed when 0 then ook.`actOutNum` else -ook.`actOutNum` end  as outNum,
		       ook.`cost` as outPrice,
		       ook.`subCost` as outAmount,
		       ok.remark,
		       ook.stockName as stockName
		
		from `oa_stock_outstock` `ok` inner join `oa_stock_outstock_item` ook on(ook.mainId=ok.id) where ok.docStatus='YSH' and ook.productId=$productId and ok.auditDate between '$monthStartDate' and '$monthEndDate'
       union all
       select al.auditDate as docDate,
              al.docCode as docCode,
              al.`docType` as docType,
              ali.`allocatNum` as inNum,
		      ali.`productCode` as productCode,
		      ali.`productName` as productName,              
              ali.`cost` as inPrice,
              `ali`.`subCost` as inAmount,
              null as outNum,
              null as outPrice,
              null as outAmount,
              al.`remark`,
              al.importStockName as stockName
       from `oa_stock_allocation` `al`
            INNER join `oa_stock_allocation_item` ali on (ali.mainId = al.id) where   al.docStatus='YSH' and  ali.productId=$productId and al.auditDate between '$monthStartDate' and '$monthEndDate'
       union all
       select an.auditDate as docDate,
              an.docCode as docCode,
              an.`docType` as docType,
              null as inNum,
              ani.`productCode` as productCode,
		      ani.`productName` as productName,
              null as inPrice,
              null as inAmount,
              ani.`allocatNum` as outNum,
              ani.`cost` as outPrice,
              ani.`subCost` as outAmount,
              an.`remark`,
              an.exportStockName as stockName
       from `oa_stock_allocation` `an`
            INNER join `oa_stock_allocation_item` ani on (ani.mainId = an.id) where an.docStatus='YSH' and ani.productId=$productId and an.auditDate between '$monthStartDate' and '$monthEndDate'		
		) sub
		ORDER by sub.docDate asc     
EOT;
//		echo $QuerySQL;
		$itemArr = $this->_db->getArray ( $QuerySQL );
	//		echo  "<pre>";
	//		print_r($itemArr);

		if (is_array ( $itemArr )) {

			for($i = 0; $i < count ( $itemArr ); $i ++) {
				$tempObj = $itemArr [$i];
				$actType=$datadictDao->getDataNameByCode ( $tempObj ['docType'] );
				if(empty($actType)||$actType=="NULL")
					$tempObj ['actType'] ="调拨单";
				else
					$tempObj ['actType'] =$actType;
				$preResult = $resultArr [$i];
				if (! empty ( $tempObj ['inNum'] )) {
					$tempObj ['balNum'] = $preResult ['balNum'] + $tempObj ['inNum'];
					$tempObj ['balAmount'] = $preResult ['balAmount'] + $tempObj ['inAmount'];
					$inSubNum += $tempObj ['inNum'];

				} else {
//					echo "====balNum:".$preResult ['balNum'];
//					echo "----outNum:".$tempObj ['outNum'];
					$tempObj ['balNum'] = $preResult ['balNum'] - $tempObj ['outNum'];
					$tempObj ['balAmount'] = $preResult ['balAmount'] - $tempObj ['inAmount'];
					$outSubNum += $tempObj ['outNum'];
				}
				$tempObj ['balPrice'] = round ( $tempObj ['balAmount'] / $tempObj ['balNum'], 4 );
				array_push ( $resultArr, $tempObj );
			}
			/*e:---------------收发台账处理----------------------*/
			$tempPrice = $resultArr [(count ( $resultArr ) - 1)] ['balPrice']; //移动平均价
			$bqObj = array ("docDate" => $thisYear . "-" . $thisMonth . "-" . $thisYearMonthNum, "actType" => "本期合计", "inNum" => $inSubNum, "outNum" => $outSubNum, "inPrice" => $tempPrice, "outPrice" => $tempPrice, "inAmount" => $tempPrice * $inSubNum, "outAmount" => $tempPrice * $outSubNum, "balPrice" => "", "balNum" => "", "balAmount" => "", "docCode" => "", "remark" => "" ,"productCode"=>"","productName"=>"","stockName"=>""	);
			array_push ( $resultArr, $bqObj );
			$qmObj = array ("docDate" => $thisYear . "-" . $thisMonth . "-" . $thisYearMonthNum, "actType" => "期末结存", "inNum" => "", "outNum" => "", "inPrice" => "", "outPrice" => "", "inAmount" => "", "outAmount" => "", "balPrice" => $resultArr [(count ( $resultArr ) - 2)] ['balPrice'], "balNum" => $resultArr [(count ( $resultArr ) - 2)] ['balNum'], "balAmount" => $resultArr [(count ( $resultArr ) - 2)] ['balAmount'], "docCode" => "", "remark" => "" ,"productCode"=>"","productName"=>"","stockName"=>"");
			array_push ( $resultArr, $qmObj );
		}else{
			$bqObj = array ("docDate" => $thisYear . "-" . $thisMonth . "-" . $thisYearMonthNum, "actType" => "本期合计", "inNum" => 0, "outNum" => 0, "inPrice" => 0, "outPrice" => 0, "inAmount" => 0, "outAmount" => 0, "balPrice" => "", "balNum" => "", "balAmount" => "", "docCode" => "", "remark" => "" ,"productCode"=>"","productName"=>"","stockName"=>""	);
			array_push ( $resultArr, $bqObj );
			$qmObj = array ("docDate" => $thisYear . "-" . $thisMonth . "-" . $thisYearMonthNum, "actType" => "期末结存", "inNum" => "", "outNum" => "", "inPrice" => "", "outPrice" => "", "inAmount" => "", "outAmount" => "", "balPrice" =>$qcObj [0]['balPrice'], "balNum" => $qcObj [0]['balNum'], "balAmount" => $qcObj [0]['balAmount'], "docCode" => "", "remark" => "" ,"productCode"=>"","productName"=>"","stockName"=>"");
			array_push ( $resultArr, $qmObj );
				
		}

		return $resultArr;

	}
}
?>