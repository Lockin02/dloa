<?php
/*
 * Created on 2011-3-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_supplierManage_formal_bankinfo extends model_base{

 	function __construct() {
		$this->tbl_name = "oa_supp_bankinfo";
		$this->sql_map = "supplierManage/formal/bankinfoSql.php";
		parent :: __construct();
	}


	/******************************************************外部接口类调用方法******************************************************/
	//将注册库的银行帐户信息存入到正式库里
	function bankAddToFormal_d($constion){
		if(is_array($constion)){
			$this->add_d($constion);
		}
	}

	/**
	 *新增供应商银行信息方法
	 */
	function addBankInfo_d($rows){
		if(is_array($rows)){
			if($rows['accountNum']!=""){
			   $this->add_d($rows,true);
			}
		}
	}

	/**根据供应商ID，获取银行信息
	 * @author suxc
	 *
	 */
	 function getBankInfoBySuppId($suppID){
		$condiction = array('suppId' => $suppID);
		$rows = $this->findAll($condiction);
		return $rows;
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
	function showViewBank($contractId){
		$condiction = array('suppId' => $contractId);
//		$trainCount = $this->findCount($condiction);
		$rows = $this->findAll($condiction);
		$i = 0;

		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;

				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td  nowrap align="center">
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
		}else{
			$str .="<tr><td colspan='4' align='center'>无相关银行信息<td></tr>";
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

					 		$del
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txt" name="flibrary[Bank][$i][bankName]"  value="$val[bankName]" $read/>
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text"  class="txt" name="flibrary[Bank][$i][accountNum]"  value="$val[accountNum]" $read/>
					 	</td>

					 	<td nowrap align="center">
					 		<input type='text' class='txt' name="flibrary[Bank][$i][remark]" value="$val[remark]"/>
					 	</td>
					 		<input type="hidden" name="flibrary[Bank][$i][id]" value="$val[id]"/>
					    <input type="hidden" name="flibrary[Bank][$i][busiCode]"/>
					    <input type="hidden" name="flibrary[Bank][$i][suppName]"/>
                        <input type="hidden" name="flibrary[Bank][$i][suppId]" value="$val[suppId]"/>

					 </tr>
EOT;
			}
		}
//		return $i."|".$str;
		return $str;

}

 }
?>
