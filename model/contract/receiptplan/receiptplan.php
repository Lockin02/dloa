<?php
/*
 * Created on 2010-6-24
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_receiptplan_receiptplan extends model_base{
	function __construct(){
		parent::__construct();
		$this->tbl_name = "oa_contract_receiptplan";
	}

	/**
	 * 批量插入收款计划-合同
	 */
	function batchInsert($id,$contNumber,$contName,$rows){
		if($rows){
			$strdate = "";
			$str="insert into ".$this->tbl_name." (contractId,contNumber,contName,money,payDT,pType,collectionTerms) values ";
			foreach($rows as $key => $val){
				if($val['money']!=""||$val['payDT']!=""||$val['collectionTerms']!=""){
					$strdate.=" ( '$id','$contNumber','$contName','$val[money]','$val[payDT]','$val[pType]','$val[collectionTerms]') ,";
				}else{
					continue;
				}
			}
		}
		if($strdate!=""){
			$str.=$strdate;
			$str = substr($str,0,-1);
			return $this->query($str);
		}else{
			return true;
		}
	}

	/**
	 * 根据合同ID和编号删除收款计划
	 */
	function delectByIdAndNumber($id,$contNumber){
		try{
			$rows = $this->delete(array( 'contractId' => $id, 'contNumber' => $contNumber ));
			return $rows;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/**
	 * 收款计划
	 * 根据合同编号获取收款计划
	 */
	function showReceiptList($id){
		return $this->findAll(array( 'contractId' => $id),'','money,payDT,pType,collectionTerms');
	}

	/**
	 * 显示合同开票计划列表
	 */
	function showlist($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $val) {
				$i++;
				$str .= '
					<tr align="center">
						<td width="5%">' . $i. '</td>
						<td class="formatMoney" width="10%">' . $val['money'] . '</td>
						<td width="10%">' . $val['payDT'] . '</td>
						<td width="10%">' . $val['pType'] . '</td>
						<td>' . $val['collectionTerms'] . '</td>
					</tr>
					';
			}
		}else{
			return '<tr align="center"><td colspan="5">暂无相关内容</td></tr>';
		}
		return $str;
	}

	/**
	 * 构建编辑合同时需要的收款计划列表
	 */
	function showlistInEdit($rows){
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$value1 = $value2 = $value3 = $value4 = $value5 = "";
				if($val['pType']=="电汇") {
					$value1 = "selected";
				}elseif($val['pType']=="现金"){
					$value2 = "selected";
				}elseif($val['pType']=="银行汇票") {
					$value3 = "selected";
				}else{
					$value4 = "selected";
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" name="sales[receiptplan][$i][money]" id="PayMoney$i" value="$val[money]" size="10" class="txtshort formatMoney" maxlength="40"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="sales[receiptplan][$i][payDT]" id="PayDT$i"  size="12" onfocus="WdatePicker()" value="$val[payDT]">
					    </td>
					 	<td>
							<select name="sales[receiptplan][$i][pType]" id="PayStyle$i" class="txtshort">
								<option value="电汇" $value1>电汇</option>
								<option value="现金" $value2>现金</option>
								<option value="银行汇票" $value3>银行汇票</option>
								<option value="商业汇票" $value4>商业汇票</option>
							</select>
					    </td>
					 	<td>
					 		<input type="text" name="sales[receiptplan][$i][collectionTerms]" id="collectionTerms$i" value="$val[collectionTerms]" size="70" maxlength="70" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mypay')" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
}
?>
