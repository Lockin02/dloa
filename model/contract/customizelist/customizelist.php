<?php
/*
 * Created on 2010-6-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_customizelist_customizelist extends model_base{
	function __construct(){
		parent::__construct();
		$this->tbl_name = "oa_contract_customizelist";
	}

	/**
	 * 批量插入自定义清单-合同
	 */
	function batchInsert($id,$contNumb,$contName,$rows){
		if($rows){
//			print_r($rows);
			$strdate = "";
			$str="insert into ".$this->tbl_name.
				 " (contractId,contNumber,contName,productnumber,name,prodectmodel,amount,price,countMoney,projArraDT,remark,productLine,isSell) values ";
			foreach($rows as $key => $val){
				if($val['productnumber']!=""||$val['name']!=""){
					$strdate.=" ( '$id','$contNumb','$contName','$val[productnumber]','$val[name]','$val[prodectmodel]','$val[amount]','$val[price]','$val[countMoney]','$val[projArraDT]','$val[remark]','$val[productLine]','$val[isSell]') ,";
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
	 * 根据合同ID和合同编号删除培训计划
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
	 * 自定义清单
	 * 根据合同编号获取收款计划
	 */
	function showCustomizeList($id){
		return $this->findAll(array( 'contractId' => $id),null,'productnumber,name,prodectmodel,productLine,amount,price,countMoney,projArraDT,remark,isSell');
	}

	/**
	 * 显示合同自定义清单列表
	 */
	function showlist($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $val) {
				$i++;
				if(!empty($val['isSell'])){
					$checked = '是';
				}else{
					$checked = '否';
				}
				$str .=<<<EOT
					<tr align="center">
						<td>$i</td>
						<td>$val[productLine]</td>
						<td>$val[productnumber]</td>
						<td>$val[name]</td>
						<td>$val[prodectmodel]</td>
						<td>$val[amount]</td>
						<td class="formatMoney" >$val[price]</td>
						<td class="formatMoney" >$val[countMoney]</td>
						<td>$val[projArraDT]</td>
						<td>$val[remark]</td>
						<td>$checked</td>
					</tr>
EOT;
			}
		}else{
			return '<tr align="center"><td colspan="11">暂无相关内容</td></tr>';
		}
		return $str;
	}

	/**
	 * 显示合同自定义清单-编辑时用
	 */
	function showlistInEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="sales[customizelist][$i][productLine]" id="cproductLine$i" size="9" value="$val[productLine]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="sales[customizelist][$i][productnumber]" id="PequID$i" size="10" value="$val[productnumber]">
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="sales[customizelist][$i][name]" id="PequName$i" size="15" value="$val[name]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="sales[customizelist][$i][prodectmodel]" id="PreModel$i" size="10" value="$val[prodectmodel]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="sales[customizelist][$i][amount]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PreAmount$i" size="8" maxlength="10" value="$val[amount]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="sales[customizelist][$i][price]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PrePrice$i" size="8" maxlength="10" class="formatMoney"  value="$val[price]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="sales[customizelist][$i][countMoney]" id="CountMoney$i" size="8" maxlength="10" class="formatMoney"  value="$val[countMoney]"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="sales[customizelist][$i][projArraDT]" id="PreDeliveryDT$i" size="10" value="$val[projArraDT]" onfocus="WdatePicker()"/>
					    </td>
					 	<td>
					 		<input class="txt" type="text" name="sales[customizelist][$i][remark]" id="PRemark$i" size="18" maxlength="100" value="$val[remark]"/>
					 	</td>
				 		<td width="4%">
				        	<input type="checkbox" name="sales[customizelist][$i][isSell]" $checked/>
						</td>
					 	<td>
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mycustom')" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
}
?>
