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
	 * ���������տ�ƻ�-��ͬ
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
	 * ���ݺ�ͬID�ͱ��ɾ���տ�ƻ�
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
	 * �տ�ƻ�
	 * ���ݺ�ͬ��Ż�ȡ�տ�ƻ�
	 */
	function showReceiptList($id){
		return $this->findAll(array( 'contractId' => $id),'','money,payDT,pType,collectionTerms');
	}

	/**
	 * ��ʾ��ͬ��Ʊ�ƻ��б�
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
			return '<tr align="center"><td colspan="5">�����������</td></tr>';
		}
		return $str;
	}

	/**
	 * �����༭��ͬʱ��Ҫ���տ�ƻ��б�
	 */
	function showlistInEdit($rows){
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$value1 = $value2 = $value3 = $value4 = $value5 = "";
				if($val['pType']=="���") {
					$value1 = "selected";
				}elseif($val['pType']=="�ֽ�"){
					$value2 = "selected";
				}elseif($val['pType']=="���л�Ʊ") {
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
								<option value="���" $value1>���</option>
								<option value="�ֽ�" $value2>�ֽ�</option>
								<option value="���л�Ʊ" $value3>���л�Ʊ</option>
								<option value="��ҵ��Ʊ" $value4>��ҵ��Ʊ</option>
							</select>
					    </td>
					 	<td>
					 		<input type="text" name="sales[receiptplan][$i][collectionTerms]" id="collectionTerms$i" value="$val[collectionTerms]" size="70" maxlength="70" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mypay')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
}
?>
