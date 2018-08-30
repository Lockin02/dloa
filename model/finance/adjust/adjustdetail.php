<?php
/**
 * @author Show
 * @Date 2011��1��13�� ������ 17:22:31
 * @version 1.0
 * @description:�����Ŀ Model��
 */
 class model_finance_adjust_adjustdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_adjustment_detail";
		$this->sql_map = "finance/adjust/adjustdetailSql.php";
		parent::__construct ();
	}

	/****************ҳ����ʾ********************/
	/**
	 *  ����鿴��ϸ��Ϣ
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

	/****************ҳ����ʾ********************/

	 /**
	  * ��������ӱ�
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
	 * ���ݲ��id��ȡ��Ŀ
	 */
	function getRows_d($adjustId){
		return $this->findAll(array('adjustId' => $adjustId));
	}

	/**
	 * ���ݲ��ȡ����Ʒ���
	 */
	function getAjustRows_d(){
		$this->searchArr['astatus'] = 'CGFPZT-WHS';
		$this->groupBy = 'd.productId';
		return $this->listBySqlId('productadjust');
	}
 }
?>