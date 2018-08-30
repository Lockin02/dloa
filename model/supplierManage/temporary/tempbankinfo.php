<?php


/**
 *供应商model层类
 */
class model_supplierManage_temporary_tempbankinfo extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_bankinfo_temp";
		$this->sql_map = "supplierManage/temporary/tempbankinfoSql.php";
		parent :: __construct();
	}

	/**
	 *新增供应商银行信息方法
	 */
	function addBankInfo_d($rows){
		if(is_array($rows)){
			if($rows['accountNum']!=""){
			   $this->add_d($rows);
			}
		}
	}
	//修改银行信息

	function addBankUpdate_d($rows){
		if(is_array($rows)){

			   $this->update(array("id"=>$rows['id']),$rows);

		}
	}

	function showOption($parentCodeArr, $keyCode = null) {
		if (is_array ( $parentCodeArr )) {
			$datadictArr = $this->getDatadicts ( $parentCodeArr );
			foreach ( $datadictArr as $key => $valueArr ) {
				$str = "";
				if (is_array ( $valueArr )) {
					foreach ( $valueArr as $k => $v ) {
						if ($v ['dataCode'] == $keyCode)
							$str .= '<option value="' . $v ['dataCode'] . '" selected>';
						else
							$str .= '<option value="' . $v ['dataCode'] . '">';
						$str .= $v ['dataName'];
						$str .= '</option>';
					}
				}
				//$k = array_search ( $key, $parentCodeArr );
				//$this->show->assign ( $k == false ? $key : $k, $str );
				return $str;
			}
		}
	}

	function showBankText($parentCodeArr,$keyCode=null){
		if (is_array ( $parentCodeArr )) {
			$datadictArr = $this->getDatadicts ( $parentCodeArr );
			foreach ( $datadictArr as $key => $valueArr ) {
				$str = "";
				if (is_array ( $valueArr )) {
					foreach ( $valueArr as $k => $v ) {
						if ($v ['dataCode'] == $keyCode)
						$str = $v ['dataName'];
					}
				}
				//$k = array_search ( $key, $parentCodeArr );
				//$this->show->assign ( $k == false ? $key : $k, $str );
				return $str;
	       }
		}
	}
	function showViewBank($contractId,$showoption){
		$condiction = array('suppId' => $contractId);
//		$trainCount = $this->findCount($condiction);
		$rows = $this->findAll($condiction);
		$i = 0;

		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$sk=$this->showBankText($showoption,$val[depositbank]);

				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td nowrap align="center">
					 	    $val[bankName]
					 	</td>
					 	<td nowrap align="center">
					 		$val[accountNum]
					 	</td>

					 	<td nowrap align="center">
					 		$val[remark]
					 	</td>




					 </tr>
EOT;
			}
		}
//		return $i."|".$str;
		return $str;

	}

	function showTrainEditList ($contractId,$showoption,$read=null) {

		$condiction = array('suppId' => $contractId);
//		$trainCount = $this->findCount($condiction);
		$rows = $this->findAll($condiction);
		$i = 0;

			$del="<td align='center'><img src='images/closeDiv.gif' onclick='mydel(this,mytra.id)' title='删除行'></td>";

		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$sk=$this->showOption($showoption,$val[depositbank]);

				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td>
					 		<input type="text" class="txt" name="temporary[Bank][$i][bankName]"  value="$val[bankName]" $read/>
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" name="temporary[Bank][$i][accountNum]"  value="$val[accountNum]" $read/>
					 	</td>

					 	<td nowrap align="center">
					 		<textarea class='txt_txtarea_input' name="temporary[Bank][$i][remark]" $read>$val[remark]</textarea>
					 	</td>

					 		$del
					 		<input type="hidden" name="temporary[Bank][$i][id]" value="$val[id]"/>
					    <input type="hidden" name="temporary[Bank][$i][busiCode]"/>
					    <input type="hidden" name="temporary[Bank][$i][suppName]"/>
                        <input type="hidden" name="temporary[Bank][$i][suppId]" value="$val[suppId]"/>

					 </tr>
EOT;
			}
		}
//		return $i."|".$str;
		return $str;

}
//查找数据库里数据
     function tempBankFind($condiction){
     	$record=$this->findAll($condiction);
     	return $record;
     }

}

?>