<?php
/**
 * @author LiuBo
 * @Date 2011��3��4�� 14:32:55
 * @version 1.0
 * @description:�����Զ����嵥 Model�� ��Ʒ�嵥

 */
 class model_projectmanagent_chance_customizelist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_customizelist";
		$this->sql_map = "projectmanagent/chance/customizelistSql.php";
		parent::__construct ();
	}


	/**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function initTableView($object){
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
               if(empty($val['license'] )){
               		$license = "";
               }else{
               		$license = "<input type='button' class='txt_btn_a' value='����' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['license']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }
				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productCode]</td>
						<td>$val[productName]</td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[projArraDT]</td>
						<td>$val[remark]</td>
                        <td>$license</td>
					</tr>
EOT;
		}
		return $str;
	}

	/**
	 * ��ʾ�Զ����嵥-�༭ʱ��
	 */
	function initTableEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
				$i++;
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="chance[customizelist][$i][productCode]" id="PequID$i" size="10" value="$val[productCode]">
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="chance[customizelist][$i][productName]" id="PequName$i" size="15" value="$val[productName]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="chance[customizelist][$i][productModel]" id="PreModel$i" size="10" value="$val[productModel]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="chance[customizelist][$i][number]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PreAmount$i" size="8" maxlength="10" value="$val[number]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="chance[customizelist][$i][price]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PrePrice$i" size="8" maxlength="10" class="formatMoney"  value="$val[price]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="chance[customizelist][$i][money]" id="CountMoney$i" size="8" maxlength="10" class="formatMoney"  value="$val[money]"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="chance[customizelist][$i][projArraDT]" id="PreDeliveryDT$i" size="10" value="$val[projArraDT]" onfocus="WdatePicker()"/>
					    </td>
					 	<td>
					 		<input type="text" class="txt" name="chance[customizelist][$i][remark]" id="PRemark$i" size="18" maxlength="100" value="$val[remark]"/>
					 	</td>
					 	<td>
							<input type="hidden" id="chanCuslicenseId$i" name="chance[customizelist][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('chanCuslicenseId$i');" />
	 			        </td>
					 	<td>
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mycustom')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($str,$i);
	}
	/*******************************ҳ����ʾ��*********************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($chanceId){
		$this->searchArr['chanceId'] = $chanceId;
		return $this->list_d();
	}
/******************************��ʱ��ͬ����******************************************/
//     /**
//	 * ����������ȡ����
//	 */
//	function tempget_d($id) {
//		$rows = parent::get_d($id);
//        return $rows;
//
//	}
//  	/**
//	 * ����������ȡ����
//	 */
//	function get_d($id) {
//		$rows = parent::get_d($id);
//
//        //��ȡ��ͬ��Ϣ
//        $orderInfoDao = new model_projectmanagent_order_order();
//        $rows['orderinfo'] = $orderInfoDao-> get_d($rows['orderId']);
//        $rows['orderName'] = $rows['orderinfo']['orderName'];
//        return $rows;
//
//	}
 }
?>