<?php

/**
 * @author LiuBo
 * @Date 2011年3月16日 9:34:46
 * @version 1.0
 * @description:商机产品清单 Model层
 */
class model_projectmanagent_chance_chanceequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_equ";
		$this->sql_map = "projectmanagent/chance/chanceequSql.php";
		parent :: __construct();
	}

	/**
	 * 渲染查看页面内从表
	 */
	function initTableView($object) {
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach ($object as $key => $val) {
			$i++;
               if(empty($val['license'] )){
               		$license = "";
               }else{
               		$license = "<input type='button' class='txt_btn_a' value='配置' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['license']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }
			$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNumber]</td>
						<td>$val[productName]&nbsp<img src="images/icon/icon105.gif" onclick="conInfo($val[productId]);" title="查看配置信息"/></td>
						<td>$val[productModel]</td>
						<td>$val[amount]</td>
						<td>$val[unitName]</td>
                        <td class="formatMoney">$val[price]</td>
                        <td class="formatMoney">$val[money]</td>
                        <td>$val[warrantyPeriod]</td>
						<td>$val[projArraDate]</td>
                        <td>$license</td>

					</tr>
EOT;
		}
		return $str;
	}

	/**
	 * 渲染编辑页面从表
	 */
	function initTableEdit($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
			$i++;
           if(!empty( $val ['isConfig'] )){
           	  			$str .=<<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]"><td width="5%">$i</td>
						<td><input type="text" name="chance[chanceequ][$i][productNumber]" id="productNumber" class="readOnlyTxtShort " value="$val[productNumber]"/>
			            </td>
			            <td><input type="hidden" id="productId$i" name="chance[chanceequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="chance[chanceequ][$i][productName]"  id="productName" class="readOnlyTxtNormal" readonly="readonly" value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="查看配置信息"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][productModel]"  id="productModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][amount]" id="amount$i" class="txtshort" onblur="FloatMul('amount$i','price$i','money$i')"   value="$val[amount]"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]" /></td>
                        <td><input type="text" name="chance[chanceequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('amount$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="chance[chanceequ][$i][money]" id="money$i" class="txtshort formatMoney"  onblur="FloatMul('amount$i','price$i','money$i')" value="$val[money]"/></td>
                        <td><input type="text" name="chance[chanceequ][$i][warrantyPeriod]" id="warrantyPeriod$i" class="txtshort" value="$val[warrantyPeriod]"/></td>
                        <td><input class="txtshort" type="text" name="chance[chanceequ][$i][projArraDate]" id="projArraDate$i"  onfocus="WdatePicker()" value="$val[projArraDate]"/></td>
                       <td><input type="hidden" id="chanEqulicenseId$i" name="chance[chanceequ][$i][license]" value="$val[license]"/>
		 			       <input type="button" class="txt_btn_a" value="配置" onclick="License('chanEqulicenseId$i');" />
		 			       <input type="hidden" name="chance[chanceequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
						   <input type="hidden" name="chance[chanceequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]"> </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="删除行" id="Del$i"/></td>
					</tr>
EOT;
           }else{
           	     $str .=<<<EOT
					<tr id="equTab_$i"><td width="5%">$i</td>
						<td><input type="text" name="chance[chanceequ][$i][productNumber]" id="productNumber$i" class="txtmiddle " value="$val[productNumber]"/>
			            </td>
			            <td><input type="hidden" id="productId$i" name="chance[chanceequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="chance[chanceequ][$i][productName]"  id="productName$i" class="txt" readonly="readonly" value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="查看配置信息"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][productModel]"  id="productModel$i" class="txtmiddle" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][amount]" id="amount$i" class="txtshort" onblur="FloatMul('amount$i','price$i','money$i')"   value="$val[amount]"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]" /></td>
                        <td><input type="text" name="chance[chanceequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('amount$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="chance[chanceequ][$i][money]" id="money$i" class="txtshort formatMoney"  onblur="FloatMul('amount$i','price$i','money$i')" value="$val[money]"/></td>
                        <td><input type="text" name="chance[chanceequ][$i][warrantyPeriod]" id="warrantyPeriod$i" class="txtshort" value="$val[warrantyPeriod]"/></td>
                        <td><input class="txtshort" type="text" name="chance[chanceequ][$i][projArraDate]" id="projArraDate$i"  onfocus="WdatePicker()" value="$val[projArraDate]"/></td>
                       <td><input type="hidden" id="chanEqulicenseId$i" name="chance[chanceequ][$i][license]" value="$val[license]"/>
		 			       <input type="button" class="txt_btn_a" value="配置" onclick="License('chanEqulicenseId$i');" />
		 			       <input type="hidden" name="chance[chanceequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
						   <input type="hidden" name="chance[chanceequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]"> </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="删除行" id="Del$i"/></td>
					</tr>
EOT;
           }
		}
		return array ( $str,$i );
	}
/**
 * 物料配置 渲染
 */
function configTable($object,$Num) {
		$str = "";
		$i = $Num;

		foreach ( $object as $key => $val ) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts ( "CPX" );
			$i ++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i
						</td>
						<td><input type="text" class="readOnlyTxtShort" readonly="readonly"  name="chance[chanceequ][$i][productNumber]" id="productNo$i"  value="$val[productCode]"/></td>
			            <td><input type="hidden" id="productId$i" name="chance[chanceequ][$i][productId]" value="$val[id]"/>
			                <input type="text" name="chance[chanceequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle" readonly="readonly"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="查看配置信息"/>
			            </td>
			            <td><input type="text" name="chance[chanceequ][$i][productModel]"  id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[pattern]"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][amount]" id="number$i" class="txtshort" value="$val[configNum]" onblur="FloatMul('number$i','price$i','money$i')"/></td>
			            <td><input type="text" name="chance[chanceequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]"></td>
			            <td><input type="text" name="chance[chanceequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="chance[chanceequ][$i][money]" id="money$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[money]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="chance[chanceequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warranty]" /></td>
                        <td><input class="txtshort" type="text" name="chance[chanceequ][$i][projArraDate]" id="projArraDate$i"  onfocus="WdatePicker()" value="$val[projArraDate]"/></td>
				        <td><input type="hidden" id="licenseId$i" name="chance[chanceequ][$i][license]" value="$val[license]"/>
 			                    <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
 			                    <input type="hidden" name="chance[chanceequ][$i][isCon]" id="isCon" value="$val[isCon]">
							    <input type="hidden" name="chance[chanceequ][$i][isConfig]" id="$val[isConfig]" value="$val[isCon]">
 			            </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="删除行" id="Del$i"/> </td>
					</tr>
EOT;
		}
		             return array ($str, $i );
	}


/**
 * 渲染借试用物料从表
 */
    function borrowTableEdit($object){
		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
				$str .=<<<EOT
					<tr id="equTab_$i" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo$i" class="readOnlyTxtShort" value="$val[productNumber]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/><input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="readOnlyTxt"  value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i" class="txtshort" value="$val[amount]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/>
                        </td><td nowrap width="8%"> <input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" />
						</td>
                        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
		 			        <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
		 			      </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行" id="Del$i"/> </td>
					</tr>
EOT;
		}
		return array($i,$str);
	}

	/*******************************页面显示层*********************************/

	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($chanceId) {
		$this->searchArr['chanceId'] = $chanceId;
		$this->asc = false;
		return $this->list_d();
	}
}
?>