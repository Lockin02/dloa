<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/purchase/external/purchaseplan_interface.php';

/**
 * @description: 销售合同采购入库model
 * @date 2010-12-17 下午04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_sale implements purchaseplan_interface {

	private $externalDao; //外部对象dao接口
	private $externalEquDao; //外部对象设备dao接口

	function __construct() {
		$this->externalDao = new model_contract_sales_sales();
		$this->externalEquDao = new model_contract_equipment_equipment();

		//调用初始化对象关联类
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/
/**
	 * @desription 添加采购计划-显示列表
	 * @param tags
	 * @date 2010-12-17 下午05:17:09
	 */
	function addPlan_s($contId,$equs) {
		$contract=$this->externalDao->get_d($contId);
       if( is_array( $equs ) ){
		$i = 0;
		foreach ($equs as $key => $val) {
			++ $i;
            $YMD = date("Y-m-d");
           	$val[amountIssued] = $val[amount]-$val[amountIssued];
			$str .=<<<EOT
						<tr height="28" align="center">
							<td>
								$i
							</td>
							<td>
								$val[productNumber] <br> $val[productName]
							</td>
							<td>
								$val[amount]
							</td>
							<td>
								$val[alreadyCarryAmount]
							</td>
							<td>
								$val[amountIssued]
							</td>
							<td>
								<input type="text" class="amount txtshort" name="basic[equipment][$key][amountAll]" value="$val[amountIssued]" onblur="addPlan(this);">
								<input type="hidden" name="amountAll" value="$val[amountIssued]" >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateIssued]" value="$YMD" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateHope]" value="$YMD" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<textarea rows="2" cols="23" name="basic[equipment][$key][remark]"></textarea>
								<input type="hidden" name="basic[equipment][$key][contOnlyId]" value="$val[contOnlyId]" />
								<input type="hidden" name="basic[equipment][$key][productNumb]" value="$val[productNumber]" />
								<input type="hidden" name="basic[equipment][$key][productName]" value="$val[productName]" />
								<input type="hidden" name="basic[equipment][$key][productId]" value="$val[productId]" />
								<input type="hidden" name="basic[equipment][$key][purchType]" value="contract_sales" />
							</td>
							<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="删除行" />
						    </td>
						</tr>

EOT;
		}
		$str .=<<<EOT
					<input type="hidden" name="basic[purchType]" value="contract_sales" />
					<input type="hidden" name="basic[objAssId]" value="$contract[id]" />
					<input type="hidden" name="basic[objAssName]" value="$contract[contName]" />
					<input type="hidden" name="basic[objAssType]" value="contract_sales" />
					<input type="hidden" name="basic[objAssCode]" value="$contract[contNumber]" />
					<input type="hidden" name="basic[equObjAssType]" value="contract_sales_equ" />
EOT;
	}else{
		$str="<tr align='center'><td colspan='50'>暂无采购清单信息</td></tr>";
	}
		return $str;
	}
	/**
	 * @desription 统一接口添加采购计划-显示列表
	 * @param tags
	 * @date 2010-12-17 下午05:17:09
	 */
	function showAddList($equs,$mianRows) {
       if(is_array($equs)){
		$i = 0;
		foreach ($equs as $key => $val) {
			++ $i;
            $YMD = date("Y-m-d");
           	$val[amountIssued] = $val[amount]-$val[amountIssued];
			$str .=<<<EOT
						<tr height="28" align="center">
							<td>
								$i
							</td>
							<td>
								 $val[productName]
							</td>
							<td>
								$val[productNumber]
							</td>
							<td>
								<input type="text" class="amount txtshort" name="basic[equipment][$key][amountAll]" value="$val[amountIssued]" onblur="addPlan(this);">
								<input type="hidden" name="amountAll" value="$val[amountIssued]" >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateIssued]" value="$YMD" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateHope]" value="$YMD" onfocus="WdatePicker()" readonly >
								<input type="hidden" name="basic[equipment][$key][equObjAssId]" value="$val[contOnlyId]" />
								<input type="hidden" name="basic[equipment][$key][productNumb]" value="$val[productNumber]" />
								<input type="hidden" name="basic[equipment][$key][productName]" value="$val[productName]" />
								<input type="hidden" name="basic[equipment][$key][productId]" value="$val[productId]" />
								<input type="hidden" name="basic[equipment][$key][purchType]" value="contract_sales" />
							</td>
							<td>
								<textarea  name="basic[equipment][$key][remark]"></textarea>
							</td>
							<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="删除行" />
						    </td>
						</tr>

EOT;
		}
		$str .=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="$mianRows[id]" />
					<input type="hidden" name="basic[objAssName]" value="$mianRows[contName]" />
					<input type="hidden" name="basic[objAssType]" value="contract_sales" />
					<input type="hidden" name="basic[objAssCode]" value="$mianRows[contNumber]" />
					<input type="hidden" name="basic[equObjAssType]" value="contract_sales_equ" />
EOT;
	}else{
		$str="<tr align='center'><td colspan='50'>暂无采购清单信息</td></tr>";
	}
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------以下接口方法,可供其他模块调用---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 通过合同Id获取产品数据
	 * @param tags
	 * @date 2010-12-17 下午05:05:57
	 */
	function getItemsByParentId($contid) {
		$equList = $this->externalEquDao->showEquipmentList($contid);
		foreach( $equList as $key => $val ){
			$equList[$key]['amountIssued'] = $equList[$key]['amount'] - $equList[$key]['canCarryAmount'] - $equList[$key]['amountIssued'];
		}
		return $equList;
	}

	/**
	 * 根据采购类型的ID，获取其信息
	 *
	 * @param $id
	 * @return return_type
	 */
	function getInfoList ($id) {
		$mainRows=$this->externalDao->get_d($id);
		return $mainRows;
	}

	/**
	 * 根据不同的类型采购计划，进行业务处理
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		return $this->updateAmountIssued($paramArr['itemId'],$paramArr['planAmount']);
	}

	/**
	 * @desription 调用合同更新设备下达数量接口
	 * @param tags
	 * @date 2011-1-11 下午02:26:21
	 */
	function updateAmountIssued ( $contOnlyId , $amountAll , $lastIssueNum=false ) {
		return $this->externalEquDao->updateAmountIssued( $contOnlyId , $amountAll , $lastIssueNum );
	}


}
?>
