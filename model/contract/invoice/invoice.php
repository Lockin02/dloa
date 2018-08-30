<?php
/*
 * Created on 2010-6-24
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_invoice_invoice extends model_base{
	function __construct(){
		parent::__construct();
		$this->tbl_name = "oa_contract_invoice";
		$this->sql_map = "contract/invoice/invoiceSql.php";
	}

	/**
	 * �������뿪Ʊ�ƻ�-��ͬ
	 */
	function batchInsert($id,$contNumber,$contName,$rows){
		if($rows){
			$strdate = "";
			$str="insert into ".$this->tbl_name." (contractId,contNumber,contName,money,softM,iType,invDT,remark) values ";
			foreach($rows as $key => $val){
				if($val['money']!=""||$val['softM']!=""||$val['invDT']!=""||$val['remark']!=""){
					$strdate.=" ( '$id','$contNumber','$contName','$val[money]','$val[softM]','$val[iType]','$val[invDT]','$val[remark]' ) ,";
				}
				else{
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
	 * ���ݺ�ͬID�ͱ��ɾ����Ʊ�ƻ�
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
	 * ��Ʊ�ƻ�
	 * ���ݺ�ͬ��Ż�ȡ��Ʊ�ƻ�
	 */
	function showInvList($id){
		return $this->findAll(array( 'contractId' => $id ),'','id,money,softM,iType,invDT,remark');
	}

	/**
	 * ��ʾ��ͬ��Ʊ�ƻ��б�
	 */
	function showlist($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			$dataDictDao = new model_system_datadict_datadict();
			foreach ($rows as $val) {
				$i++;
				$iType = $dataDictDao->getDataNameByCode($val['iType']);
				$str .= '
					<tr align="center">
						<td>' . $i. '</td>
						<td width="10%" class="formatMoney">' . $val['money'] . '</td>
						<td width="10%" class="formatMoney">' . $val['softM'] . '</td>
						<td width="10%">' . $iType . '</td>
						<td width="12%">' . $val['invDT'] . '</td>
						<td>' . $val['remark'] . '</td>
					</tr>
					';
			}
		}else{
			return '<tr align="center"><td colspan="6">�����������</td></tr>';
		}
		return $str;
	}

	/**
	 * �����༭��ͬʱ��Ҫ�Ŀ�Ʊ�ƻ�
	 */
	function showlistInEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			$datadictArr = $this->getDatadicts ( "FPLX" );
			foreach ($rows as $val) {
				$i++;
				$productLineStr = $this->getDatadictsStr ( $datadictArr ['FPLX'], $val ['iType'] );
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td align="center">
					 		<input type="text" name="sales[invoice][$i][money]" id="InvMoney$i" value="$val[money]" class="txtshort formatMoney"/>
					 	</td>
					 	<td align="center">
					 		<input type="text" name="sales[invoice][$i][softM]" id="InvSoftM$i" value="$val[softM]" class="txtshort formatMoney"/>
					 	</td>
					 	<td>
						  <select class="txtmiddle" name="sales[invoice][$i][iType]">
						    $productLineStr
						  </select>
					 	</td>
					 	<td align="center">
					        <input type="text" name="sales[invoice][$i][invDT]" id="InvDT$i" class="txtshort" onfocus="WdatePicker()" value="$val[invDT]">
					    </td>
					 	<td align="center">
					 		<input type="text" name="sales[invoice][$i][remark]" id="InvRemark$i" value="$val[remark]" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'myinv')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
}
?>
