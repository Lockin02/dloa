<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/purchase/external/purchaseplan_interface.php';

/**
 * @description: ���ۺ�ͬ�ɹ����model
 * @date 2010-12-17 ����04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_sale implements purchaseplan_interface {

	private $externalDao; //�ⲿ����dao�ӿ�
	private $externalEquDao; //�ⲿ�����豸dao�ӿ�

	function __construct() {
		$this->externalDao = new model_contract_sales_sales();
		$this->externalEquDao = new model_contract_equipment_equipment();

		//���ó�ʼ�����������
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/
/**
	 * @desription ��Ӳɹ��ƻ�-��ʾ�б�
	 * @param tags
	 * @date 2010-12-17 ����05:17:09
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
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
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
		$str="<tr align='center'><td colspan='50'>���޲ɹ��嵥��Ϣ</td></tr>";
	}
		return $str;
	}
	/**
	 * @desription ͳһ�ӿ���Ӳɹ��ƻ�-��ʾ�б�
	 * @param tags
	 * @date 2010-12-17 ����05:17:09
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
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
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
		$str="<tr align='center'><td colspan='50'>���޲ɹ��嵥��Ϣ</td></tr>";
	}
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------���½ӿڷ���,�ɹ�����ģ�����---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ͨ����ͬId��ȡ��Ʒ����
	 * @param tags
	 * @date 2010-12-17 ����05:05:57
	 */
	function getItemsByParentId($contid) {
		$equList = $this->externalEquDao->showEquipmentList($contid);
		foreach( $equList as $key => $val ){
			$equList[$key]['amountIssued'] = $equList[$key]['amount'] - $equList[$key]['canCarryAmount'] - $equList[$key]['amountIssued'];
		}
		return $equList;
	}

	/**
	 * ���ݲɹ����͵�ID����ȡ����Ϣ
	 *
	 * @param $id
	 * @return return_type
	 */
	function getInfoList ($id) {
		$mainRows=$this->externalDao->get_d($id);
		return $mainRows;
	}

	/**
	 * ���ݲ�ͬ�����Ͳɹ��ƻ�������ҵ����
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		return $this->updateAmountIssued($paramArr['itemId'],$paramArr['planAmount']);
	}

	/**
	 * @desription ���ú�ͬ�����豸�´������ӿ�
	 * @param tags
	 * @date 2011-1-11 ����02:26:21
	 */
	function updateAmountIssued ( $contOnlyId , $amountAll , $lastIssueNum=false ) {
		return $this->externalEquDao->updateAmountIssued( $contOnlyId , $amountAll , $lastIssueNum );
	}


}
?>
