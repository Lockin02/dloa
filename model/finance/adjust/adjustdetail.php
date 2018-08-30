<?php
/**
 * @author Show
 * @Date 2011年1月13日 星期四 17:22:31
 * @version 1.0
 * @description:补差单条目 Model层
 */
 class model_finance_adjust_adjustdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_adjustment_detail";
		$this->sql_map = "finance/adjust/adjustdetailSql.php";
		parent::__construct ();
	}

	/****************页面显示********************/
	/**
	 *  补差单查看详细信息
	 */
	function showDrtail($rows){
		$str = null;
		$i = 0;
		if($rows){
			foreach($rows as $val){
				$i ++ ;
				$str .=<<<EOT
					<tr>
						<td width="5%">$i
						</td>
						<td width="12%">$val[productNo]
						</td>
						<td width="12%">$val[productName]
						</td>
						<td width="8%">$val[number]
						</td>
						<td width="12%" class="formatMoney6">$val[cost]
						</td>
						<td width="12%" class="formatMoney6">$val[price]
						</td>
						<td width="13%" class="formatMoney6">$val[differ]
						</td>
						<td width="13%" class="formatMoney">$val[allDiffer]
						</td>
						<td width="13%">$val[stockName]
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/****************页面显示********************/

	 /**
	  * 批量插入从表
	  * @param $object
	  * @param $adjustId
	  * @return int|string
	  * @throws Exception
	  */
	 function batchAdd_d($object, $adjustId) {
		 $str = "insert into " . $this->tbl_name . " (adjustId,productId,productNo,productName,cost,price,differ," .
			 "allDiffer,number,stockId,stockName) values ";
		 $formDiffer = 0;
		 foreach ($object as $key => $val) {
			 $formDiffer = bcadd($formDiffer, $val['allDiffer'], 2);
			 if ($key) {
				 $str .= ",";
			 }
			 $str .= "('$adjustId','" . $val['productId'] . "','" . $val['productNo'] . "','" . $val['productName'] .
				 "','" . $val['cost'] . "','" . $val['price'] . "','" . $val['differ'] . "','" . $val['allDiffer'] .
				 "','" . $val['number'] . "','" . $val['stockId'] . "','" . $val['stockName'] . "')";
		 }
		 try {
			 $this->query($str);
			 return $formDiffer;
		 } catch (exception $e) {
			 throw $e;
		 }
	 }

	/**
	 * 根据补差单id获取条目
	 */
	function getRows_d($adjustId){
		return $this->findAll(array('adjustId' => $adjustId));
	}

	/**
	 * 根据补差单取出产品差价
	 */
	function getAjustRows_d(){
		$this->searchArr['astatus'] = 'CGFPZT-WHS';
		$this->groupBy = 'd.productId';
		return $this->listBySqlId('productadjust');
	}
 }
?>