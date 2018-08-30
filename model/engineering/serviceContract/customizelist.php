<?php
/**
 * @author LiuBo
 * @Date 2011年3月4日 14:32:55
 * @version 1.0
 * @description:订单自定义清单 Model层 产品清单

 */
 class model_engineering_serviceContract_customizelist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_service_customizelist";
		$this->sql_map = "engineering/serviceContract/customizelistSql.php";
		parent::__construct ();
	}


	/**
	 * 渲染查看页面内从表
	 */
	function initTableView($object){
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict();
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
			if (empty ($val['license'])) {
				$license = "";
			} else {
				$license = "<input type='button' class='txt_btn_a' value='配置' onclick='" .
				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=" . $val['license'] . "" .
				"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
			}

			   if(!empty($val['isSell'])){
					$checked = '是';
				}else{
					$checked = '否';
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
						<td>$checked </td>
					</tr>
EOT;
		}
		return $str;
	}

	/**
	 * 显示合同自定义清单-编辑时用
	 */
	function initTableEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
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
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][productCode]" id="PequID$i" size="10" value="$val[productCode]">
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="serviceContract[customizelist][$i][productName]" id="PequName$i" size="15" value="$val[productName]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][productModel]" id="PreModel$i" size="10" value="$val[productModel]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][number]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PreAmount$i" size="8" maxlength="10" value="$val[number]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort formatMoney" name="serviceContract[customizelist][$i][price]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PrePrice$i" size="8" maxlength="10"  value="$val[price]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort formatMoney" name="serviceContract[customizelist][$i][money]" id="CountMoney$i" size="8" maxlength="10"  value="$val[money]"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="serviceContract[customizelist][$i][projArraDT]" id="PreDeliveryDT$i" size="10" value="$val[projArraDT]" onfocus="WdatePicker()"/>
					    </td>
					 	<td>
					 		<input type="text" class="txt" name="serviceContract[customizelist][$i][remark]" id="PRemark$i" size="18" maxlength="100" value="$val[remark]"/>
					 	</td>
				 		 <td>
							<input type="hidden" id="cuslicenseId$i" name="serviceContract[customizelist][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('cuslicenseId$i');" />
		 			      </td>
				 		<td >
				        	<input type="checkbox" name="serviceContract[customizelist][$i][isSell]" $checked/>
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



	/**
	 * 商机转合同
	 */
	function changeCusOrder($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
				$i++;
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][productCode]" id="PequID$i" size="10" value="$val[productCode]">
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="serviceContract[customizelist][$i][productName]" id="PequName$i" size="15" value="$val[productName]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][productModel]" id="PreModel$i" size="10" value="$val[productModel]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][number]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PreAmount$i" size="8" maxlength="10" value="$val[number]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][price]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PrePrice$i" size="8" maxlength="10" class="formatMoney"  value="$val[price]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="serviceContract[customizelist][$i][money]" id="CountMoney$i" size="8" maxlength="10" class="formatMoney"  value="$val[money]"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="serviceContract[customizelist][$i][projArraDT]" id="PreDeliveryDT$i" size="10" value="$val[projArraDT]" onfocus="WdatePicker()"/>
					    </td>
					 	<td>
					 		<input type="text" class="txt" name="serviceContract[customizelist][$i][remark]" id="PRemark$i" size="18" maxlength="100" value="$val[remark]"/>
					 	</td>
					 	<td>
							<input type="hidden" id="cuslicenseId$i" name="serviceContract[customizelist][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('cuslicenseId$i');" />
	 			        </td>
				 		<td >
				        	<input type="checkbox" name="serviceContract[customizelist][$i][isSell]" checked="checked"/>
						</td>
					 	<td>
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mycustom')" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($str,$i);
	}
	/*******************************页面显示层*********************************/

	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($orderId){
		$this->searchArr['orderId'] = $orderId;
		return $this->list_d();
	}
/******************************临时合同处理******************************************/
     /**
	 * 根据主键获取对象
	 */
	function tempget_d($id) {
		$rows = parent::get_d($id);
        return $rows;

	}
  	/**
	 * 根据主键获取对象
	 */
	function get_d($id) {
		$rows = parent::get_d($id);

        //获取合同信息
        $orderInfoDao = new model_engineering_serviceContract_serviceContract();
        $rows['orderinfo'] = $orderInfoDao-> get_d($rows['orderId']);
        $rows['orderName'] = $rows['orderinfo']['orderName'];
        return $rows;

	}
 }
?>