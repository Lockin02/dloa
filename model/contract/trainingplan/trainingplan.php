<?php
/*
 * Created on 2010-6-24
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_trainingplan_trainingplan extends model_base{
	function __construct(){
		parent::__construct();
		$this->tbl_name = "oa_contract_trainingplan";
	}

	/**
	 * 批量插入培训计划-合同
	 */
	function batchInsert($id,$contNumb,$contName,$rows){
		if($rows){
			$strdate = "";
			$str="insert into ".$this->tbl_name." (contractId,contNumber,contName,beginDT,endDT,traNum,adress,content,trainerDemand) values ";
			foreach($rows as $key => $val){
				if($val['beginDT']!=""||$val['endDT']!=""||$val['traNum']!=""||$val['adress']!=""||$val['content']!=""||$val['trainerDemand']!=""){
					$strdate.=" ( '$id','$contNumb','$contName','$val[beginDT]','$val[endDT]','$val[traNum]','$val[adress]','$val[content]','$val[trainerDemand]') ,";
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
	 * 收款计划
	 * 根据合同编号获取收款计划
	 */
	function showTrainList($id){
		return $this->findAll(array( 'contractId' => $id),null,'beginDT,endDT,traNum,adress,content,trainerDemand');
	}

	/**
	 * 显示合同培训计划
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
						<td width="8%">' . $val['beginDT'] . '</td>
						<td width="8%">' . $val['endDT'] . '</td>
						<td width="8%">' . $val['traNum'] . '</td>
						<td width="18%">' . $val['adress'] . '</td>
						<td>' . $val['content'] . '</td>
						<td width="20%">' . $val['trainerDemand'] . '</td>
					</tr>
					';
			}
		}else{
			return '<tr height="28px" class="TableData" align="center"><td colspan="7">暂无相关内容</td></tr>';
		}
		return $str;
	}

	/**
	 * 显示合同培训计划-编辑时用
	 */
	function showlistInEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="sales[trainingplan][$i][beginDT]" id="TraDT$i" size="10" onfocus="WdatePicker()" value="$val[beginDT]"/>
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="sales[trainingplan][$i][endDT]" id="TraEndDT$i" size="10" onfocus="WdatePicker()" value="$val[endDT]" />
					 	</td>
					 	<td align="center">
					 		<input type="text" class="txtshort" name="sales[trainingplan][$i][traNum]" value="$val[traNum]" size="8" maxlength="40"/>
					    </td>
					 	<td nowrap align="center">
					 		<textarea name="sales[trainingplan][$i][adress]" rows="3" style="width: 100%">$val[adress]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="sales[trainingplan][$i][content]" rows="3" style="width: 100%">$val[content]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="sales[trainingplan][$i][trainerDemand]" rows="3" style="width: 100%">$val[trainerDemand]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<img src="images/closeDiv.gif" onclick="mydel(this,mytra.id)" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
}
?>
