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
	 * 批量插入开票计划-合同
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
	 * 根据合同ID和编号删除开票计划
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
	 * 开票计划
	 * 根据合同编号获取开票计划
	 */
	function showInvList($id){
		return $this->findAll(array( 'contractId' => $id ),'','id,money,softM,iType,invDT,remark');
	}

	/**
	 * 显示合同开票计划列表
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
			return '<tr align="center"><td colspan="6">暂无相关内容</td></tr>';
		}
		return $str;
	}

	/**
	 * 构建编辑合同时需要的开票计划
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
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'myinv')" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
}
?>
