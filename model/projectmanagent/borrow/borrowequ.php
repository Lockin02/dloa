<?php

/**
 * @author Administrator
 * @Date 2011年5月9日 16:02:12
 * @version 1.0
 * @description:借试用申请产品清单 Model层
 */
class model_projectmanagent_borrow_borrowequ extends model_base
{

	function __construct() {
		include(WEB_TOR . "model/common/mailConfig.php");
		$this->tbl_name = "oa_borrow_equ";
		$this->sql_map = "projectmanagent/borrow/borrowequSql.php";
		$this->mailArr = $mailUser['personnelBorrow'];
		parent::__construct();
	}


	/**
	 * 渲染查看页面内从表
	 */
	function initTableView($object, $objId) {
		//获取最近一次变更审批的明细记录
		$dao = new model_common_changeLog();
		$changeInfo = $dao->getLastDetails("borrow", $objId);

		$detailId = array();
		foreach ($changeInfo as $k => $v) {
			if (!empty($v['detailId'])) {
				$detailId[$v['detailId']] = $k;
			}
		}
		if (!empty($detailId)) {
			foreach ($detailId as $k => $v) {
				$sql = "select * from oa_borrow_equ where id = '" . $k . "' ";
				$chIn = $this->_db->getArray($sql);
				foreach ($chIn as $k => $v) {
					if ($v['isDel'] == '1') {
						$object = array_merge($object, $chIn);
					}
				}
			}
		}
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach ($object as $key => $val) {
			$i++;
			if (empty($val['license'])) {
				$license = "";
			} else {
				$license = "<input type='button' class='txt_btn_a' value='配置' onclick='" .
					"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=" . $val['license'] . "" .
					"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
			}
			//k3 编号、名称
			if (!empty($val['productNoKS'])) {
				$KsNo = "K3:" . $val['productNoKS'];
			} else {
				$KsNo = "";
			}
			if (!empty($val['productNameKS'])) {
				$KsName = "K3:" . $val['productNameKS'];
			} else {
				$KsName = "";
			}
			if ($val['changeTips'] != '0') {
				if ($val['isDel'] == '1') {
					$str .= <<<EOT
					<tr style="background:#C8C8C8">
						<td width="5%">$i</td>
						<td>$val[productNo]$KsNo</td>
						<td title="K3系统内物料名称: $val[productNameKS]"><span class="red" >$val[productName]$KsName</span></td>
						<td>$val[productModel]</td>
						<td title="双击查看序列号" style="background:#efefef;" ondblclick="serialNo($val[borrowId],$val[id]);"><span class="red" title="双击查看序列号" >$val[number]</span></td>
						<td>$val[executedNum]</td>
						<td>$val[backNum]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>

					</tr>
EOT;
				} else {
					$str .= <<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]$KsNo</td>
						<td title="K3系统内物料名称: $val[productNameKS]"><span class="red" >$val[productName]$KsName</span></td>
						<td>$val[productModel]</td>
						<td title="双击查看序列号" style="background:#efefef;" ondblclick="serialNo($val[borrowId],$val[id]);"><span class="red" title="双击查看序列号" >$val[number]</span></td>
						<td>$val[executedNum]</td>
						<td>$val[backNum]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>

					</tr>
EOT;
				}
			} else {
				$str .= <<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]$KsNo</td>
						<td title="K3系统内物料名称: $val[productNameKS]">$val[productName]$KsName</td>
						<td>$val[productModel]</td>
						<td title="双击查看序列号" style="background:#efefef;" ondblclick="serialNo($val[borrowId],$val[id]);"><span class="red" title="双击查看序列号" >$val[number]</span></td>
						<td>$val[executedNum]</td>
						<td>$val[backNum]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>

					</tr>
EOT;
			}

		}
		return $str;
	}


	/**
	 * 物料配置 渲染
	 */
	function configTable($object, $Num) {
		$str = "";
		$i = $Num;

		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" class="readOnlyTxtShort" readonly="readonly"  name="borrow[borrowequ][$i][productNo]" id="productNo$i"  value="$val[productCode]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[id]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle" readonly="readonly"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="查看配置信息"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]"  id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[pattern]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]" id="number$i" class="txtshort" value="$val[configNum]" onblur="FloatMul('number$i','price$i','money$i')"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName1" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[money]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warranty]" /></td>
				        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[License]"/>
 			                <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
 			                <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="$val[isConfig]" value="$val[isCon]">
 			            </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行" id="Del$i"/> </td>
					</tr>
EOT;
		}
		return array($str, $i);
	}


	/**
	 * 单独物料修改  配置 渲染
	 */
	function configTableEdit($object, $Num) {
		$str = "";
		$i = $Num;

		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" class="readOnlyTxtShort" readonly="readonly"  name="borrow[borrowequ][$i][productNo]" id="productNo$i"  value="$val[productCode]"/></td>
			            <td><input type="hidden" id="isAdd$i" name="borrow[borrowequ][$i][isAdd]" value="1" />
			                <input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[id]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle" readonly="readonly"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="查看配置信息"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]"  id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[pattern]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]" id="number$i" class="txtshort" value="$val[configNum]" onblur="FloatMul('number$i','price$i','money$i')"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName1" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[money]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warranty]" /></td>
				        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[License]"/>
 			                <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
 			                <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="$val[isConfig]" value="$val[isCon]">
 			            </td>
			            <td><img src="images/closeDiv.gif" onclick="mydelT(this,'invbody')" title="删除行" id="Del$i"/> </td>
					</tr>
EOT;
		}
		return array($str, $i);
	}

	/**
	 * 渲染编辑页面从表
	 */
	function initTableEdit($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			if (!empty($val[isConfig])) {
				$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName" class="readOnlyTxtMiddle"  value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" readonly="readonly" class="readOnlyTxtShort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
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
			} else {
				$str .= <<<EOT
					<tr id="equTab_$i" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo$i" class="txtmiddle" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/><input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="txt"  value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel$i" class="txtmiddle" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" readonly="readonly" class="readOnlyTxtShort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
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
		}
		return array($i, $str);
	}

	/**
	 * 单独物料修改
	 */
	function proTableEdit($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			if (!empty($val[isConfig])) {
				$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo$i" class="readOnlyTxtShort" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="isAdd$i" name="borrow[borrowequ][$i][proId]" value="$val[id]">
			                <input type="hidden" id="isDel$i" value="$val[productId]" />
			                <input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle"  value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i" class="txtshort" value="$val[number]"/></td>
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
			} else {
				$str .= <<<EOT
					<tr id="equTab_$i" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo$i" class="txtmiddle" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="isAdd$i" name="borrow[borrowequ][$i][proId]" value="$val[id]">
			                <input type="hidden" id="isDel$i" value="$val[productId]" />
			                <input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="txt"  value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel$i" class="txtmiddle" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i" class="txtshort" value="$val[number]"/></td>
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
		}
		return array($i, $str);
	}

	/**
	 * 渲染借试用从表
	 */
	function chanceTableEdit($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .= <<<EOT
					<tr><td width="5%">$i
						</td>
						<td>
			                <input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo$i" class="txtmiddle" value="$val[productNumber]"/>
			            </td>
			            <td>
			            	<input type="hidden" id="productId1" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName1" class="txt" value="$val[productName]"/>
			            </td>
			            <td>
			                <input type="text" name="borrow[borrowequ][$i][productModel]"
			                id="productModel1" class="txtmiddle" readonly="readonly" value="$val[productModel]"/>
			            </td>
			            <td>
			                <input type="text" name="borrow[borrowequ][$i][number]"  id="number1" class="txtshort" value="$val[amount]"/>
			            </td>
			            <td>
			                <input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName1" class="txtshort" value="$val[unitName]" />
			            </td>
			            <td>
                            <input type="text" name="borrow[borrowequ][$i][price]" id="price1" class="txtshort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/>
                        </td>
                        <td>
                            <input type="text" name="borrow[borrowequ][$i][money]" id="money1" class="txtshort formatMoney" value="$val[money]"/>
                        </td>

				        <td nowrap width="8%">
					       <input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod1" value="$val[warrantyPeriod]" />

						</td>
                        <td>
							<input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[License]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
		 			      </td>
			            <td>
			                <img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行" id="Del$i"/>
			            </td>
					</tr>
EOT;
		}
		return array($i, $str);
	}


	/*******************************页面显示层*********************************/

	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($borrowId) {
		$this->searchArr['borrowId'] = $borrowId;
		$this->searchArr['isDel'] = '0';
		$this->asc = false;
		return $this->list_d();
	}


	/**
	 * 根据合同ID 获取从表数据
	 */
	function getByProId_d($contractId) {
		$this->searchArr['conProductId'] = $contractId;
		$this->searchArr['isDel'] = 0;
		// $this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}


	/**更新已下达采购数量
	 *author zengzx
	 *2011年9月16日 14:47:11
	 */
	function updateAmountIssued($id, $issuedNum, $lastIssueNum = false) {
		if (isset ($lastIssueNum) && $issuedNum == $lastIssueNum) {
			return true;
		} else {
			if ($lastIssueNum) {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=(ifnull(issuedPurNum,0)  + $issuedNum - $lastIssueNum) where id='$id'  ";
			} else {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=ifnull(issuedPurNum,0) + $issuedNum where id='$id' ";
			}
			return $this->query($sql);
		}
	}

	/**
	 *
	 * 设备列表-处理订单
	 * 根据订单编号获取设备列表
	 */
	function showEquListInByOrder($borrowId, $docType) {
		$sql = 'select e.id,e.borrowId,e.borrowCode,e.productLine,e.productName,e.isDel,e.productId,e.productNo,e.productModel,e.productType,e.number,e.price,e.money,e.warrantyPeriod,e.executedNum,e.onWayNum,e.purchasedNum,e.issuedPurNum,e.uniqueCode from ' . $this->tbl_name . ' e  where e.borrowId = ' . $borrowId;

		$equs = $this->_db->getArray($sql);
		//print_r($equs);
		//获取设备已锁定数量
		//		echo "<pre>";
		//		print_R( $equs );
		$lockDao = new model_stock_lock_lock();
		foreach ($equs as $key => $val) {
			$lockNum = $lockDao->getEquStockLockNum($val['id'], null, $docType);
			$equs[$key]['lockNum'] = $lockNum;
		}

		return $equs;
	}

	/**
	 * 处理订单时显示设备
	 */
	function showDetailByOrder($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $key => $val) {
				$val['lockNum'] = isset($val['lockNum']) ? $val['lockNum'] : 0;
				$proId = $val['productId'];
				$equId = $val['id'];
				if ($val['isDel'] == 1) {
					$productNo = "<font color=red>" . $val[productNo] . "</font>";
					$productName = "<font color=red>" . $val[productName] . "</font>";
				} else {
					$productNo = $val[productNo];
					$productName = $val[productName];
				}
				$canUseNum = $val['exeNum'] + $val['lockNum'];

				$lockNum = $val['number'] - $val['lockNum'];
				$str .= <<<EOT
							<tr align="center">
							<td>
						<input type="hidden" id="productId$i" value="$val[productId]" />
								$productNo/<br/>
								$productName

							<input type="hidden" equId="$equId" proId="$proId" value="$val[borrowId]" name="lock[$i][objId]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[id]" name="lock[$i][objEquId]" id="equId$i"/>
							<input type="hidden" equId="$equId" proId="$proId" value="oa_borrow_borrow" name="lock[$i][objType]"/></td>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productId]" name="lock[$i][productId]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productName]" name="lock[$i][productName]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productNo]" name="lock[$i][productNo]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="" id="inventoryId$i" name="lock[$i][inventoryId]"/>
								</td>

							<td width="8%"><div equId="$equId" proId="$proId" id="amount$i">$val[number]</div></td>
							<td width="8%">$val[executedNum]</td>

							<td width="8%"><font color="red"><div equId="$equId" proId="$proId" id="actNum$i">0</div></td>
							<td width="8%"><font color="red"><div equId="$equId" proId="$proId" id="exeNum$i">0</div></font></td>
							<td width="8%" title="当前仓库的锁定数量">
								<font color="red">
							     	<a  href="javascript:toLockRecordsPage('$val[id]',true)" >
							     		<div equId="$equId" proId="$proId" id="stockLockNum$i"></div>
							     	</a>
							     </font>
							</td>
							<td width="8%" title="所有仓库的锁定数量总和">
								<font color="red">
							     	<a href="javascript:toLockRecordsPage('$val[id]',false)">
							     		<div equId="$equId" proId="$proId" id="lockNum$i"> $val[lockNum]</div>
							     	</a>
							     </font>
							</td>
							<td width="8%">0</td>
							<td width="8%">$val[issuedPurNum]</td>
							<td width="8%">$val[purchasedNum]</td>
							<td width="8%"><input type="text" equId="$equId" proId="$proId"  value="$lockNum" id="lockNumber$i" name="lock[$i][lockNum]" class="txtshort" onclick="$(this).css({'color':'black'})" onblur="checkLockNum(this,$i)"/></td>
							</tr>
EOT;
				$i++;

			}
			$str .= "<input type='hidden' id='rowNum' value='$i'/>";
		}

		return $str;
	}

	/**
	 * 用于员工借试用 调拨单下推
	 */
	function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$productCodeClass = "readOnlyTxtItem";
				$productNameClass = "readOnlyTxtNormal";
				$deexecutedNum = $val['number'] - $val['executedNum'];
				if ($deexecutedNum != 0) {
					$seNum = $i + 1;
					$str .= <<<EOT
				<tr align="center" >
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
					</td>
                    <td>
                        $seNum
                       </td>
                    <td>
                        <input type="text" name="allocation[items][$i][productCode]" id="productCode$i" class="$productCodeClass" value="{$val['productNo']}" readonly="readonly"/>
                        <input type="hidden" name="allocation[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />

                    </td>
                    <td>
						<input type="text" name="allocation[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtItem" value="{$val['k3Code']}" readonly="readonly"/>
					</td>
                    <td>
                        <input type="text" name="allocation[items][$i][productName]" id="productName$i" class="$productNameClass" value="{$val['productName']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['productModel']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" value="{$val['unitName']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][allocatNum]"  id="allocatNum$i" class="txtshort" ondblclick="chooseSerialNo($i)" onblur="FloatMul('allocatNum$i','cost$i','subCost$i')" value="{$deexecutedNum}" />
	                 </td>
                    <td>
                        <input type="text" name="allocation[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','allocatNum$i','subCost$i')" value="{$val['price']}" />
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="{$val['money']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][exportStockName]" id="exportStockName$i" class="txtshort" value="" />
                        <input type="hidden" name="allocation[items][$i][exportStockId]" id="exportStockId$i" value="" />
                       	<input type="hidden" name="allocation[items][$i][exportStockCode]"id="exportStockCode$i" value="" />
                       	<input type="hidden" name="allocation[items][$i][relDocId]" id="relDocId$i"   value="{$val['id']}" />
                        <input type="hidden" name="allocation[items][$i][relDocName]" id="relDocName$i"  value=""  />
                       	<input type="hidden" name="allocation[items][$i][relCodeCode]" id="relCodeCode$i"  value=""  />
                       	<input type="hidden" name="allocation[items][$i][borrowItemId]" id="borrowItemId$i" value="{$val['borrowItemId']}" />
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][importStockName]" id="importStockName$i" class="txtshort" value="" />
                        <input type="hidden" name="allocation[items][$i][importStockId]" id="importStockId$i" value="" />
                       	<input type="hidden" name="allocation[items][$i][importStockCode]"id="importStockCode$i" value="" />
                    </td>
                    <td>
                     	<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo($i);" title="选择序列号">
                     	<input type="hidden" name="allocation[items][$i][serialnoId]" id="serialnoId$i" value="" />
						<input type="text" name="allocation[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i" value=""   />
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][remark]" id="remark$i" class="txtshort" value="{$val['remark']}"  />
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][validDate]" id="subPrice$i" onfocus="WdatePicker()" class="txtshort" value="{$val['warrantyPeriod']}" />
                    </td>
               		 </tr>
EOT;
					$i++;
				}

			}
			return $str;
		}
	}

	/**
	 * 借试用转销售-----销售合同从表渲染
	 */
	function toOrderBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//查找借试用单据 物料的未归还数量
			//		    $dao = new model_stock_allocation_allocation();
			//		    $proNum = $dao->getApplyDocNotBackNum($val['borrowId'],$val['productId'],"DBDYDLXFH");
			$exeDao = new model_projectmanagent_borrow_toorder();
			$exeNum = $exeDao->getBorrowOrderequNum($val['borrowId'], $val['productId']);
			$Num = $val['executedNum'] - $exeNum;
			$price = $val['price'];
			$mony = $Num * $price;
			if (!empty ($val ['isSell'])) {
				$checked = 'checked="checked"';
			} else {
				$checked = 'checked="checked"';
			}
			$i++;

			$str .= <<<EOT
					<tr id="borrowequTab_$i" parentRowId="$val[isCon]">
					    <td><img src="images/removeline.png" onclick="mydel(this,'borrowequ')" title="删除行" id="Del$i"/></td>
					    <td>$i</td>
						<td><input type="text" class="readOnlyTxtShort" name="order[borrowequ][$i][productNo]" id="borproductNo$i"  value="$val[productNo]"/>
			                <input type="hidden" name="order[borrowequ][$i][unitName]" id="borunitName$i" value="$val[unitName]"></td>
			            <td><input type="hidden" id="borproductId$i" name="order[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="order[borrowequ][$i][productName]" id="borproductName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="查看配置信息"/></td>
			            <td><input type="text" name="order[borrowequ][$i][productModel]" id="borproductModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="order[borrowequ][$i][number]" id="bornumber$i" class="txtshort" ondblclick="serialNum($val[borrowId],$val[productId],$i);" onchange="num();" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$Num"/>
			                <input type="hidden" id="num$i" value="$Num">
			                <input type="hidden" id="serialName$i" name="order[borrowequ][$i][serialName]" />
			                <input type="hidden" id="serialId$i" name="order[borrowequ][$i][serialId]" /></td>
			            <td><input type="text" name="order[borrowequ][$i][price]" id="borprice$i" class="txtshort formatMoney" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$price"/></td>
                        <td><input type="text" name="order[borrowequ][$i][money]" id="bormoney$i" class="txtshort formatMoney"  onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$mony"/></td>
                        <td><input class="txtshort" type="text" name="order[borrowequ][$i][projArraDate]" id="borprojArraDate$i"  onfocus="WdatePicker()" value="$val[projArraDate]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="order[borrowequ][$i][warrantyPeriod]" id="borwarrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
			        	<td><input type="hidden" id="borlicenseId$i" name="order[borrowequ][$i][license]" value="$val[license]"/>
 			                <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i','edit');" /></td>
				        <td width="4%"><input type="checkbox" name="order[borrowequ][$i][isSell]" id="isSell$i" $checked/>
					        <input type="hidden" name="order[borrowequ][$i][remark]" id="borremark$i" value="$val[remark]">
					        <input type="hidden" name="order[borrowequ][$i][businessId]" value="$val[borrowId]" />
					        <input type="hidden" name="order[borrowequ][$i][businessEquId]" value="$val[id]" />
					        </td>
					</tr>
EOT;


		}
		return array($str, $i);
	}

	/**
	 * 借试用转销售-----服务合同从表渲染
	 */
	function toServiceBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//查找借试用单据 物料的未归还数量
			//		    $dao = new model_stock_allocation_allocation();
			//		    $proNum = $dao->getApplyDocNotBackNum($val['borrowId'],$val['productId'],"DBDYDLXFH");
			$exeDao = new model_projectmanagent_borrow_toorder();
			$exeNum = $exeDao->getBorrowOrderequNum($val['borrowId'], $val['productId']);
			$Num = $val['executedNum'] - $exeNum;
			$price = $val['price'];
			$mony = $Num * $price;
			if (!empty ($val ['isSell'])) {
				$checked = 'checked="checked"';
			} else {
				$checked = 'checked="checked"';
			}
			$i++;

			$str .= <<<EOT
					<tr id="borrowequTab_$i" parentRowId="$val[isCon]"><td>$i</td>
						<td><input type="text" class="readOnlyTxtShort" name="serviceContract[borrowequ][$i][productNo]" id="borborproductNo"  value="$val[productNo]"/>
			                <input type="hidden" name="serviceContract[borrowequ][$i][unitName]" id="borunitName$i" value="$val[unitName]"></td>
			            <td><input type="hidden" id="borproductId$i" name="serviceContract[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="serviceContract[borrowequ][$i][productName]" id="borproductName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="查看配置信息"/></td>
			            <td><input type="text" name="serviceContract[borrowequ][$i][productModel]" id="borproductModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="serviceContract[borrowequ][$i][number]" id="bornumber$i" class="txtshort" ondblclick="serialNum($val[borrowId],$val[productId],$i);" onchange="num();" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$Num"/>
			                <input type="hidden" id="num$i" value="$Num">
			                <input type="hidden" id="serialName$i" name="serviceContract[borrowequ][$i][serialName]" />
			                <input type="hidden" id="serialId$i" name="serviceContract[borrowequ][$i][serialId]" /></td>
			            <td><input type="text" name="serviceContract[borrowequ][$i][price]" id="borprice$i" class="txtshort formatMoney" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$price"/></td>
                        <td><input type="text" name="serviceContract[borrowequ][$i][money]" id="bormoney$i" class="txtshort formatMoney"  onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$mony"/></td>
                        <td><input class="txtshort" type="text" name="serviceContract[borrowequ][$i][projArraDate]" id="borprojArraDate$i"  onfocus="WdatePicker()" value="$val[projArraDate]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="serviceContract[borrowequ][$i][warrantyPeriod]" id="borwarrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
			        	<td><input type="hidden" id="licenseId$i" name="serviceContract[borrowequ][$i][license]" value="$val[license]"/>
 			                <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i','edit');" /></td>
				        <td width="4%"><input type="checkbox" name="serviceContract[borrowequ][$i][isSell]" id="borisSell$i" $checked/>
					        <input type="hidden" name="serviceContract[borrowequ][$i][remark]" id="borremark$i" value="$val[remark]">
					        <input type="hidden" name="serviceContract[borrowequ][$i][businessId]" value="$val[borrowId]" />
					        <input type="hidden" name="serviceContract[borrowequ][$i][businessEquId]" value="$val[id]" />
					        </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'borrowequ')" title="删除行" id="borDel$i"/></td>
					</tr>
EOT;

		}
		return array($str, $i);
	}

	/**
	 * 借试用转销售-----租赁合同从表渲染
	 */
	function toLeaseBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//查找借试用单据 物料的未归还数量
			//		    $dao = new model_stock_allocation_allocation();
			//		    $proNum = $dao->getApplyDocNotBackNum($val['borrowId'],$val['productId'],"DBDYDLXFH");
			$exeDao = new model_projectmanagent_borrow_toorder();
			$exeNum = $exeDao->getBorrowOrderequNum($val['borrowId'], $val['productId']);
			$Num = $val['executedNum'] - $exeNum;
			$price = $val['price'];
			$mony = $Num * $price;
			if (!empty ($val ['isSell'])) {
				$checked = 'checked="checked"';
			} else {
				$checked = 'checked="checked"';
			}
			$i++;

			$str .= <<<EOT
					<tr id="borrowequTab_$i" parentRowId="$val[isCon]"><td>$i</td>
						<td><input type="text" class="readOnlyTxtShort" name="rentalcontract[borrowequ][$i][productNo]" id="borproductNo"  value="$val[productNo]"/>
			                <input type="hidden" name="rentalcontract[borrowequ][$i][unitName]" id="borunitName$i" value="$val[unitName]"></td>
			            <td><input type="hidden" id="borproductId$i" name="rentalcontract[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[borrowequ][$i][productName]" id="borproductName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="查看配置信息"/></td>
			            <td><input type="text" name="rentalcontract[borrowequ][$i][productModel]" id="borproductModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[borrowequ][$i][number]" id="bornumber$i" class="txtshort" ondblclick="serialNum($val[borrowId],$val[productId],$i);" onchange="num();" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$Num"/>
			                <input type="hidden" id="num$i" value="$Num">
			                <input type="hidden" id="serialName$i" name="rentalcontract[borrowequ][$i][serialName]" />
			                <input type="hidden" id="serialId$i" name="rentalcontract[borrowequ][$i][serialId]" /></td>
			            <td><input type="text" name="rentalcontract[borrowequ][$i][price]" id="borprice$i" class="txtshort formatMoney" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$price"/></td>
                        <td><input type="text" name="rentalcontract[borrowequ][$i][money]" id="bormoney$i" class="txtshort formatMoney"  onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$mony"/></td>
                        <td><input class="txtshort" type="text" name="rentalcontract[borrowequ][$i][projArraDate]" id="borprojArraDate$i"  onfocus="WdatePicker()" value="$val[projArraDate]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[borrowequ][$i][warrantyPeriod]" id="borwarrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
			        	<td><input type="hidden" id="licenseId$i" name="rentalcontract[borrowequ][$i][license]" value="$val[license]"/>
 			                <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i','edit');" /></td>
				        <td width="4%"><input type="checkbox" name="rentalcontract[borrowequ][$i][isSell]" id="borisSell$i" $checked/>
					        <input type="hidden" name="rentalcontract[borrowequ][$i][remark]" id="borremark$i" value="$val[remark]">
					        <input type="hidden" name="rentalcontract[borrowequ][$i][businessId]" value="$val[borrowId]" />
					        <input type="hidden" name="rentalcontract[borrowequ][$i][businessEquId]" value="$val[id]" />
					        </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'borrowequ')" title="删除行" id="borborDel$i"/></td>
					</tr>
EOT;

		}
		return array($str, $i);
	}

	/**
	 * 借试用转销售---- 研发合同从表渲染
	 */
	function toRdprojectBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//查找借试用单据 物料的未归还数量
			//		    $dao = new model_stock_allocation_allocation();
			//		    $proNum = $dao->getApplyDocNotBackNum($val['borrowId'],$val['productId'],"DBDYDLXFH");
			$exeDao = new model_projectmanagent_borrow_toorder();
			$exeNum = $exeDao->getBorrowOrderequNum($val['borrowId'], $val['productId']);
			$Num = $val['executedNum'] - $exeNum;
			$price = $val['price'];
			$mony = $Num * $price;
			if (!empty ($val ['isSell'])) {
				$checked = 'checked="checked"';
			} else {
				$checked = 'checked="checked"';
			}
			$i++;

			$str .= <<<EOT
					<tr id="borrowequTab_$i" parentRowId="$val[isCon]"><td>$i</td>
						<td><input type="text" class="readOnlyTxtShort" name="rdproject[borrowequ][$i][productNo]" id="borproductNo"  value="$val[productNo]"/>
			                <input type="hidden" name="rdproject[borrowequ][$i][unitName]" id="borunitName$i" value="$val[unitName]"></td>
			            <td><input type="hidden" id="borproductId$i" name="rdproject[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rdproject[borrowequ][$i][productName]" id="borproductName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="查看配置信息"/></td>
			            <td><input type="text" name="rdproject[borrowequ][$i][productModel]" id="borproductModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rdproject[borrowequ][$i][number]" id="bornumber$i" class="txtshort" ondblclick="serialNum($val[borrowId],$val[productId],$i);" onchange="num();" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$Num"/>
			                <input type="hidden" id="num$i" value="$Num">
                            <input type="hidden" id="serialName$i" name="rdproject[borrowequ][$i][serialName]" />
			                <input type="hidden" id="serialId$i" name="rdproject[borrowequ][$i][serialId]" /></td>
			            <td><input type="text" name="rdproject[borrowequ][$i][price]" id="borprice$i" class="txtshort formatMoney" onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$price"/></td>
                        <td><input type="text" name="rdproject[borrowequ][$i][money]" id="bormoney$i" class="txtshort formatMoney"  onblur="FloatMul('bornumber$i','borprice$i','bormoney$i')" value="$mony"/></td>
                        <td><input class="txtshort" type="text" name="rdproject[borrowequ][$i][projArraDate]" id="borprojArraDate$i"  onfocus="WdatePicker()" value="$val[projArraDate]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="rdproject[borrowequ][$i][warrantyPeriod]" id="borwarrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
			        	<td><input type="hidden" id="licenseId$i" name="rdproject[borrowequ][$i][license]" value="$val[license]"/>
 			                <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i','edit');" /></td>
				        <td width="4%"><input type="checkbox" name="rdproject[borrowequ][$i][isSell]" id="borisSell$i" $checked/>
					        <input type="hidden" name="rdproject[borrowequ][$i][remark]" id="borremark$i" value="$val[remark]">
					        <input type="hidden" name="rdproject[borrowequ][$i][businessId]" value="$val[borrowId]" />
					        <input type="hidden" name="rdproject[borrowequ][$i][businessEquId]" value="$val[id]" />
					        </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'borrowequ')" title="删除行" id="Del$i"/></td>
					</tr>
EOT;


		}
		return array($str, $i);
	}

	/************外部接口*********************/
	//续借渲染从表
	function renewTableEdit($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			$i++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" name="renew[renewequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" readonly="readonly" value="$val[productNo]"/>
						    <input type="hidden" name="renew[renewequ][$i][equId]" value="$val[id]"></td>
			            <td><input type="hidden" id="productId$i" name="renew[renewequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="renew[renewequ][$i][productName]" id="productName" class="readOnlyTxtMiddle"  readonly="readonly" value="$val[productName]"/></td>
			            <td><input type="text" name="renew[renewequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="renew[renewequ][$i][number]"  id="number$i" ondblclick="serialNo($val[borrowId],$val[id],$i,'renew',$val[productId]);" onchange="num();" class="txtshort" value="$val[number]" onblur="FloatMul('number1','price1','money1')"/>
			                <input type="hidden" id="num$i" value="$val[number]">
			                <input type="hidden" id="serialName$i" name="renew[renewequ][$i][serialName]" />
			                <input type="hidden" id="serialId$i"   name="renew[renewequ][$i][serialId]" /></td>
			            <td><input type="text" name="renew[renewequ][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" readonly="readonly" value="$val[unitName]" /></td>
			            <td><input type="text" name="renew[renewequ][$i][price]" id="price$i" readonly="readonly" class="readOnlyTxtShort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
                        <td><input type="text" name="renew[renewequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
                        <td nowrap width="8%"><input type="text" class="txtshort" name="renew[renewequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
                        <td><input type="hidden" id="licenseId$i" name="renew[renewequ][$i][License]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
		 			    </td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行" id="Del$i"/></td>
					</tr>
EOT;
		}
		return array($i, $str);
	}

	//转借渲染从表
	function subtenancyTable($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			$i++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" readonly="readonly" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName" class="readOnlyTxtMiddle"  readonly="readonly" value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i" ondblclick="serialNo($val[borrowId],$val[id],$i,'renew',$val[productId]);"  class="txtshort" value="$val[number]" onblur="FloatMul('number1','price1','money1')"/>
			                <input type="hidden" id="num$i" value="$val[number]">
			                <input type="hidden" id="serialName$i" name="borrow[borrowequ][$i][serialName]" value="$val[serialName]"/>
			                <input type="hidden" id="serialId$i"   name="borrow[borrowequ][$i][serialId]" value="$val[serialId]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" readonly="readonly" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" readonly="readonly" class="readOnlyTxtShort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
                        <td nowrap width="8%"><input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
                        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
		 			    </td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行" id="Del$i"/></td>
					</tr>
EOT;
		}
		return array($i, $str);
	}

	/************借试用 变更********************************/
	function changeTable($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			$i++;
			if (empty ($val ['originalId'])) {
				$str .= '<input type="hidden" name="borrow[borrowequ][' . $i . '][oldId]" value="' . $val ['id'] . '" />';
			} else {
				$str .= '<input type="hidden" name="borrow[borrowequ][' . $i . '][oldId]" value="' . $val ['originalId'] . '" />';
			}
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" readonly="readonly" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName" class="readOnlyTxtMiddle"  readonly="readonly" value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i"  class="txtshort" value="$val[number]" onblur="FloatMul('number1','price1','money1')"/>
			                <input type="hidden" id="serialName$i" name="borrow[borrowequ][$i][serialName]" value="$val[serialName]"/>
			                <input type="hidden" id="serialId$i"   name="borrow[borrowequ][$i][serialId]" value="$val[serialId]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" readonly="readonly" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" readonly="readonly" class="readOnlyTxtShort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
                        <td nowrap width="8%"><input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
                        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="配置" onclick="License('licenseId$i');" />
		 			    </td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody','borrowequ')" title="删除行" id="Del$i"/></td>
					</tr>
EOT;
		}
		return array($i, $str);
	}

	/**
	 * 判断借试用的发货状态
	 */
	function borrowShipmentsStatus($borrowId) {
		$equinfo = $this->getDetail_d($borrowId);
		$flagArr = array();//判断发货状态的数组
		foreach ($equinfo as $key => $val) {
			if ($val['executedNum'] == '0') {
				$flagArr[] = '未发货';
			} else if ($val['number'] > $val['executedNum']) {
				$flagArr[] = '部分发货';
			} else if ($val['number'] <= $val['executedNum']) {
				$flagArr[] = '完成发货';
			}
		}
		if (!in_array("未发货", $flagArr) && !in_array("部分发货", $flagArr)) {
			return "0";
		} else if ((in_array("部分发货", $flagArr)) or (in_array("未发货", $flagArr) && in_array("完成发货", $flagArr))) {
			return "1";
		} else {
			return "2";
		}
	}

	/**
	 * 根据源单、从表ID 获取未执行数量
	 */
	function getDocNotExeNum($docId, $docItemId) {
		$sql = "select (number - executedNum) as nonexecutionNum from oa_borrow_equ where id=" . $docItemId . "";
		$numarr = $this->_db->getArray();
		return $numarr[0]['nonexecutionNum'];
	}

	/***************************************物料确认 start*****************************************/
	/**
	 * 获取产品下的物料信息
	 */
	function getProductEqu_d($id) {
		$productDao = new model_projectmanagent_borrow_product();
		$goodsArr = $productDao->resolve_d($id);
		$equArr = array();
		if ($goodsArr != 0) {
			$equItemDao = new model_goods_goods_propertiesitem();
			$productInfoDao = new model_stock_productinfo_productinfo();
			$goodsInfoIdArr = array();
			foreach ($goodsArr as $key => $val) {
				$goodsInfoIdArr[] = $key;
			}
			$goodsInfoIdStr = implode(',', $goodsInfoIdArr);
			$equItemDao->searchArr['ids'] = $goodsInfoIdStr;
			$productArr = $equItemDao->list_d();
			$productIdArr = array();
			foreach ($productArr as $key => $val) {
				$productIdArr[] = $val['productId'];
			}
			$productIdStr = implode(',', $productIdArr);
			$productInfoDao->searchArr['idArr'] = $productIdStr;
			$equArr = $productInfoDao->list_d();
			foreach ($productArr as $row => $index) {
				foreach ($equArr as $key => $val) {
					if ($index['productId'] == $val['id']) {
						$equArr[$key]['productId'] = $val['id'];
						$equArr[$key]['productNo'] = $val['productCode'];
						$equArr[$key]['productModel'] = $val['pattern'];
						$equArr[$key]['number'] = $index['defaultNum'];
						unset ($equArr[$key]['id']);
					}
				}
			}
		}
		return $equArr;
	}

	/**
	 * @description 新增物料处理页面显示产品信息
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$i = 0; //列表记录序号
			foreach ($rows as $key => $val) {
				$deployShow = '';
				$deployShow = $goodDao->showDeploy($val['deploy']);
				//合同设备是否赠送
				$j = $i + 1;
				if ($val['license'] != 0 || $val['license'] != '') {
					$licenseHtml = "<input type='button'  value='加密配置'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				} else {
					$licenseHtml = '无';
				}
				if ($val['isDel'] == "1") {
					$style .= '<img title="变更删除的产品" src="images/box_remove.png" />';
				}
				if ($val['changeTips'] == "2") {
					$style .= '<img title="变更新增的产品" src="images/new.gif" />';
				}
				if ($val['changeTips'] == "1") {
					$style .= '<img title="变更编辑的产品" src="images/changeedit.gif" />';
				}
				$str .= <<<EOT
						<tr height="30px" bgcolor='#ECFFFF'>
							<td width="35px">$j</td>
<!--							<td>$val[conProductCode]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>-->
							<td>$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]<input type="hidden" id="number$j" value="$val[number]"/></td>
<!--							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							<input type="button"  value="加密配置"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>-->
							<td width="8%"><input type="button"  value="产品配置"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">物料清单</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="展开" alt="新增选项" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:35px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">物料清单</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="隐藏" alt="新增选项" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * @description 变更/编辑 物料处理页面显示产品信息
	 * @param $rows
	 */
	function showItemChange($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$equDao = new model_projectmanagent_borrow_borrowequ();
			$i = 0; //列表记录序号
			foreach ($rows as $key => $val) {
				$deployShow = '';
				$deployShow = $goodDao->showDeploy($val['deploy']);
				$equArr = $equDao->getByProId_d($val['id']);
				if ($val['isDel'] == 1) {
					if (!(is_array($equArr) && count($equArr) > 0)) {
						continue;
					}
				}
				if ($val['isDel'] == "1") {
					$style .= '<img title="变更删除的产品" src="images/box_remove.png" />';
				}
				if ($val['changeTips'] == "2") {
					$style .= '<img title="变更新增的产品" src="images/new.gif" />';
				}
				if ($val['changeTips'] == "1") {
					$style .= '<img title="变更编辑的产品" src="images/changeedit.gif" />';
				}
				$img = '';
				if ($val['license'] != 0 || $val['license'] != '') {
					$licenseHtml = "<input type='button'  value='加密配置'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				} else {
					$licenseHtml = '无';
				}
				if ($val['isDel'] == '1') {
					$trStyle = " bgcolor='#efefef'";
				} else {
					$trStyle = " bgcolor='#ECFFFF'";
				}
				$img = '';
				//合同设备是否赠送
				$j = $i + 1;
				$str .= <<<EOT
						<tr height="30px" $trStyle>
							<td width="8%">$style &nbsp; $j</td>
<!--<img src="images/changeAdd2.png"/>&nbsp;							<td>$val[conProductCode]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>-->
							<td>$img &nbsp;$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]<input type="hidden" id="number$j" value="$val[number]"/></td>
							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							<input type="button"  value="加密配置"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>
							<td width="8%"><input type="button"  value="产品配置"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">物料清单</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="展开" alt="新增选项" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:20px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">物料清单</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="隐藏" alt="新增选项" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
				$style = '';
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * 渲染产品配置
	 */
	function showProductInfo($object, $id, $rowNum, $itemNum) {
		$name = 'borrow[detail][' . $itemNum . ']';
		$trId = $id . "_" . $rowNum;
		$str = "<tr id='goodsDetail_$trId'><td class='innerTd' colspan='20'><table class='form_in_table' id='contractequ_$id'>";
		if (is_array($object)) {
			$titleStr = "<tr class='main_tr_header1'>";
			$infoStr = '';
			$titleStr .= <<<EOT
				<th width="30px"></th>
				<th width="35px">序号</th>
				<th>物料编码</th>
				<th>物料名称</th>
				<th>版本型号</th>
				<th>数量</th>
				<th>交货期
EOT;
			foreach ($object as $key => $val) {
				$j = $key + 1;
				$trName = $name . '[' . $rowNum . '0' . $j . ']';
				{
					$productId_n = $trName . '[productId]';
					$productCode_n = $trName . '[productCode]';
					$productName_n = $trName . '[productName]';
					$productpattern_n = $trName . '[pattern]';
					$productnumber_n = $trName . '[number]';
					$productarrivalPeriod_n = $trName . '[arrivalPeriod]';
					$infoStr .= "<tr class='tr_inner'>";
				}

				$infoStr .= <<<EOT
					<td><img src='images/removeline.png' onclick='mydel(this,"contractequ_$id")' title='删除行'></td>
					<td>$j</td>
					<td>$val[productCode]
						<input type="hidden" class="txtshort" id="inner_productCode$id-$key" name='$productCode_n' value="$val[productCode]"/>
						<input type="hidden" class="txtshort" id="inner_productId$id-$key" name='$productId_n' value="$val[id]"/>
						</td>
					<td>$val[productName]<input type="hidden" class="txtshort" id="inner_productName$id-$key" name='$productName_n' value="$val[productName]"/></td>
					<td>$val[pattern]<input type="hidden" class="txtshort" id="inner_pattern$id-$key" name='$productpattern_n' value="$val[pattern]"/></td>
					<td><input type="text" class="txtshort" id="inner_number$id-$key" name='$productnumber_n' value="$val[configNum]"/></td>
					<td>$val[arrivalPeriod]<input type="hidden" class="txtshort" id="inner_arrivalPeriod$id-$key" name='$productarrivalPeriod_n' value="$val[arrivalPeriod]"/>
EOT;
				$infoStr .= "</td></tr>";
			}
			$titleStr .= "</th></tr>";
			$infoStr .= "</td></tr>";
			$str .= $titleStr . $infoStr . "</table></td></tr>";
			return $str;
		} else {
			return '';
		}
	}
	/**
	 * 更新特殊物料 （RCU服务） 配件的已执行数量
	 */
	function updateRCU($bid){
          $findIdSql = "select id from oa_borrow_equ where borrowId='".$bid."' and productId='".specialProId."'";
          $idArr = $this->_db->getArray($findIdSql);
          if(!empty($idArr[0]['id'])){
            $updateSqo = "update oa_borrow_equ set executedNum=number,issuedShipNum=number,backNum=number where borrowId='".$bid."' and parentEquId='".$idArr[0]['id']."'";
	        $this->query($updateSqo);
          }
	}

    //获取合同已经确认了的物料
    function getConMat_d($cid,$pid){
        if(!empty($cid)){
            $sql = "select * from ".$this->tbl_name." where borrowId = '".$cid."' and conProductId = '".$pid."' and isDel = 0 and isTemp = 0";
            $resultArr = $this->_db->getArray($sql);
            return $resultArr;
        }else{
            return 0;
        }
    }

	/**
	 * 物料分配 新增
	 */
    function equAddNew_d($object,$act = 'save') {
//         echo"<pre>";print_r($equs);exit();
        try {
            $this->start_d();

            if ($object['id']) {
                $contDao = new model_projectmanagent_borrow_borrow();
                $linkDao = new model_projectmanagent_borrow_borrowequlink();
                $linkDao->searchArr['borrowId'] = $object['id'];
                $contObj = $contDao->get_d($object['id']);
                $linkInfo = $linkDao->list_d();
                if ($contObj['ExaStatus'] != '物料确认') {
                    echo "<script>alert('该需求已经确认，请刷新原列表页，查看该单据处理状态!')</script>";
                    throw new Exception("该需求已经确认，请刷新原列表页，查看该单据处理状态!");
                }

                // 更新临时记录成本概算
                $borrowCostDao = new model_projectmanagent_borrow_cost();
                $costId = $borrowCostDao->addCostConfirm($object,null,1);

                $equs = array();
                foreach ($object['detail'] as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $key => $value) {
                            if (!empty($value['productId'])) {
                                $equs[] = $value;
                            }
                        }
                    }
                }

                //加入关联表
                $linkArr = array(
                    "borrowId" => $contObj['id'],
                    "borrowCode" => $contObj['Code'],
                    "borrowType" => 'oa_borrow_borrow',
                );
                if($act != "audit"){
                    $linkArr['ExaStatus'] = "未提交";
                }else{
                    $linkArr['ExaStatus'] = "完成";
                }
                $relativeLinkArr = $linkDao->find(array("borrowId" => $contObj['id']));
                if(!$relativeLinkArr && !isset($relativeLinkArr['id'])){
                    $linkId = $linkDao->add_d($linkArr, true);
                }else{
                    $linkId = $relativeLinkArr['id'];
                }
                $borrowCostDao->updateById(array("id"=>$costId,"linkId" => $linkId));

                // 物料处理相同属性
                $equs = $this->processEquCommonInfo($equs, $linkArr);
                $lastEquId = 0;
                foreach ($equs as $key => $val) {
                    $val['linkId'] = $linkId;
                    if (empty($val['isDel'])) {
                        $val['isDel'] = '0';
                    }
                    if (isset($val['isDelTag']) && $val['isDelTag'] == 1) {
                        $val['isDel'] = '1';
                    }
                    if (empty ($val['configCode'])) {
                        $val['parentEquId'] = $lastEquId;
                    }
                    //					$val['productNo'] = $val['productCode'];
                    if (isset($val['id']) && $val['id'] != '') {
                        if($val['isDel'] == 1){
                            $this->delete(array("id"=>$val['id']));
                            $this->delete(array("parentEquId"=>$val['id']));
                        }else{
                            if(!empty ($val['configCode'])){
                                $lastEquId = $val['id'];
                            }
                            $this->edit_d($val);
                        }
                    } else {
                        $id = $this->add_d($val);
                        if (!empty ($val['configCode'])) {
                            $lastEquId = $id;
                        }
                    }
                }

            }else{
                throw new Exception("单据信息不完整，请确认!");
            }

            $this->commit_d();
            return $object['id'];
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

	function equAdd_d($object, $audti = false) {
		try {
			$this->start_d();
			if ($object['id']) {
				$contDao = new model_projectmanagent_borrow_borrow();
				$linkDao = new model_projectmanagent_borrow_borrowequlink();
				$linkDao->searchArr['borrowId'] = $object['id'];
				$contObj = $contDao->get_d($object['id']);
				$linkInfo = $linkDao->list_d();
				if ($contObj['dealStatus'] == '1' || !empty($linkInfo)) {
					echo "<script>alert('该需求已经确认，请刷新原列表页，查看该单据处理状态!')</script>";
					throw new Exception("该需求已经确认，请刷新原列表页，查看该单据处理状态!");
				}

				$equs = array();
				foreach ($object['detail'] as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $key => $value) {
							if (!empty($value['productId'])) {
								$equs[] = $value;
							}
						}
					}
				}
				//加入关联表
				$linkArr = array(
					"borrowId" => $contObj['id'],
					"borrowCode" => $contObj['Code'],
					"borrowType" => 'oa_borrow_borrow',
				);

                // 放到审批完成后处理
				$dateObj = array(
					'id' => $object['id'],
					'standardDate' => $object['standardDate'],
				);
				if ($audti) {
					$dateObj['dealStatus'] = 1;
					$linkArr['ExaStatus'] = '完成';
					$linkArr['ExaDT'] = day_date;
					$linkArr['ExaDTOne'] = day_date;
				} else {
					$linkArr['ExaStatus'] = '未提交';
				}
                $contDao->updateById($dateObj);
                // 放到审批完成后处理

				$linkId = $linkDao->add_d($linkArr, true);
				if ($linkId) {
					$linkDao->confirmAudit($linkId);
				} else {
					throw new Exception("单据信息不完整，请确认!");
				}
				$linkArr['linkId'] = $linkId;
				//处理相同属性
				$equs = $this->processEquCommonInfo($equs, $linkArr);
				$lastEquId = 0;
				foreach ($equs as $key => $val) {
					if (empty($val['isDel'])) {
						$val['isDel'] = '0';
					}
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
					}
					//					$val['productNo'] = $val['productCode'];
					if (isset($val['id']) && $val['id'] != '') {
						$this->edit_d($val);
					} else {
						$id = $this->add_d($val);
						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
						}
					}
				}

				if ($audti) {
                    // 放到审批完成后处理
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
					$this->sendMailAtAudit($object, '提交');
					$this->updateRCU($object['id']);
                    // 放到审批完成后处理
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			$this->commit_d();
			return $linkId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 获取某个物料清单的配件信息
	 * add by chengl
	 */
	function getEquByParentEquId_d($parentEquId, $isDel) {
		if ($isDel === 0) {
			$this->searchArr['isDel'] = 0;
		}
		$this->searchArr['parentEquId'] = $parentEquId;
		return $this->list_d();
	}

	/**
	 * 物料分配 编辑
	 */
    function equEditNew_d($object,$act = 'save') {
        try {
            $this->start_d();

            if ($object['id']) {
                $contDao = new model_projectmanagent_borrow_borrow();
                $linkDao = new model_projectmanagent_borrow_borrowequlink();
                $linkObj = $linkDao->findBy('borrowId', $object['id']);
                $contObj = $contDao->get_d($object['id']);
                if ($contObj['ExaStatus'] != '物料确认') {
                    echo "<script>alert('该需求已经确认，请刷新原列表页，查看该单据处理状态!')</script>";
                    throw new Exception("该需求已经确认，请刷新原列表页，查看该单据处理状态!");
                }

                // 更新临时记录成本概算
                $object['linkId'] = $linkObj['id'];
                $borrowCostDao = new model_projectmanagent_borrow_cost();
                $borrowCostDao->addCostConfirm($object,null,1);

                if($act == "audit"){
                    $linkObj['ExaStatus'] = '完成';
                    $linkDao->edit_d($linkObj);
                }

                // 物料信息处理
                $this->delete(array(
                    'borrowId' => $object['id']
                ));
                $equs = array();
                foreach ($object['detail'] as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $key => $value) {
                            unset($value['id']);
                            $equs[] = $value;
                        }
                    }
                }
                $contObj['linkId'] = $linkObj['id'];

                //处理相同属性
                $contObj['borrowId'] = $contObj['id'];
                $equs = $this->processEquCommonInfo($equs, $contObj);
                $lastEquId = 0;
                foreach ($equs as $key => $val) {
                    if (empty($val['isDel'])) {
                        $val['isDel'] = '0';
                    }
                    if (empty ($val['configCode'])) {
                        $val['parentEquId'] = $lastEquId;
                    }
                    if ($val['isDelTag'] != 1) {
                        $id = $this->add_d($val);
                        if (!empty ($val['configCode'])) {
                            $lastEquId = $id;
                        }
                    }
                }

            }else{
                throw new Exception("单据信息不完整，请确认!");
            }

            $this->commit_d();
            return $object['id'];
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }
	function equEdit_d($object, $audti = false) {
		try {
			$this->start_d();
			if ($object['id']) {
				$contDao = new model_projectmanagent_borrow_borrow();
				$linkDao = new model_projectmanagent_borrow_borrowequlink();
				$linkObj = $linkDao->findBy('borrowId', $object['id']);
				$contObj = $contDao->get_d($object['id']);
				if ($contObj['dealStatus'] == '1') {
					echo "<script>alert('该需求已经确认，请刷新原列表页，查看该单据处理状态!')</script>";
					throw new Exception("该需求已经确认，请刷新原列表页，查看该单据处理状态!");
				}

				// 放到审批完成后处理
				$dateObj = array(
					'id' => $object['id'],
					'standardDate' => $object['standardDate'],
				);
				if ($audti) {
					$dateObj['dealStatus'] = 1;
					$linkObj['ExaStatus'] = '完成';
					$linkObj['ExaDT'] = day_date;
					$linkObj['ExaDTOne'] = day_date;
					$linkDao->edit_d($linkObj);
				}
				$contDao->updateById($dateObj);
                // 放到审批完成后处理

				$this->delete(array(
					'borrowId' => $object['id']
				));
				$equs = array();
				foreach ($object['detail'] as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $key => $value) {
							unset($value['id']);
							$equs[] = $value;
						}
					}
				}
				$contObj['linkId'] = $linkObj['id'];
				//处理相同属性
				//				echo "<pre>";
				//				print_R($equs);
				$contObj['borrowId'] = $contObj['id'];
				$equs = $this->processEquCommonInfo($equs, $contObj);
				$lastEquId = 0;
				foreach ($equs as $key => $val) {
					if (empty($val['isDel'])) {
						$val['isDel'] = '0';
					}
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
					}
					if ($val['isDelTag'] != 1) {
						$id = $this->add_d($val);
						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
						}
					}
				}
				if ($audti) {
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
					$this->sendMailAtAudit($object, '提交');
					$this->updateRCU($object['id']);
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			$this->commit_d();
			return $linkObj['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 物料分配 变更
	 */
	function equChange_d($object) {
		try {
			$this->start_d();
			$contDao = new model_projectmanagent_borrow_borrow();
			$linkDao = new model_projectmanagent_borrow_borrowequlink();
			$contract = $contDao->get_d($object['id']);
			$linkObj = $linkDao->findBy('borrowId', $object['id']);
			$updateTipsSql = " update oa_borrow_equ set changeTips='0' where borrowId='" . $object['id'] . "'";
			$this->_db->query($updateTipsSql);


			$linkObj['ExaStatus'] = '完成';
			$linkObj['ExaDT'] = day_date;
			$linkDao->edit_d($linkObj);

			$dateObj = array(
				'id' => $object['id'],
				'standardDate' => $object['standardDate'],
				'dealStatus' => '1'
			);
			$contDao->updateById($dateObj);
			$equs = array();
			//echo "<pre>";print_r($object['detail']);
			foreach ($object['detail'] as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $key => $value) {
						//						if (!empty ($value['configCode'])) { //把配置转物料的更新下
						//							$value['parentRowNum'] = 0;
						//							$value['parentEquId'] = 0;
						//						}
						if (isset ($value['rowNum_'])) { //物料
							$value['isCon'] = $value['productId'] . "_" . $value['rowNum_'];
						}
						$value['isConfig'] = $value['parentRowNum'];
						if (!empty ($value['id'])) {
							$value['oldId'] = $value['id'];
							unset ($value['id']);
						}
						if (empty($value['isDel'])) {
							if (empty($value['isDelTag'])) {
								$value['isDel'] = 0;
							} else {
								$value['isDel'] = $value['isDelTag'];
							}
						}
						$equs[] = $value;
					}
				}
			}
			$linkObj['equs'] = $equs;
			$linkObj['oldId'] = $linkObj['id'];
			$changeLogDao = new model_common_changeLog('borrowequ', false);
			$tempObjId = $changeLogDao->addLog($linkObj);
			$contract['borrowId'] = $contract['id'];
			$equs = $this->processEquCommonInfo($equs, $contract);
			$equArr = $equs;
			foreach ($equs as $key => $val) {
				if ($val['isCon'] != '' && $val['isDel'] == 1) {
					foreach ($equArr as $index => $value) {
						if ($val['isCon'] == $value['isConfig']) {
							$equArr[$index]['isConfig'] = '';
							$equArr[$index]['parentEquId'] = 0;
						}
					}
				}
			}
			//			echo "<pre>";
			//			print_R($equArr);
			$lastEquId = 0;
			foreach ($equArr as $key => $val) {
				if ($val['productId'] == '') {
					unset($equArr[$key]);
					continue;
				}
				//如果变更数量是0，删除计划设备清单
				if ($val ['isDel'] == 1) {
					$val['id'] = $val ['oldId'];
					$val['isDel'] = 1;
					$val['changeTips'] = 3;
					$this->edit_d($val);
				} elseif ($val ['oldId']) {
					$val['id'] = $val ['oldId'];
					$this->edit_d($val);
				} else {
					$val['linkId'] = $linkObj['id'];
					$val['changeTips'] = 2;
					if (empty ($val['configCode']) || $val['configCode'] === 0) {
						$val['parentEquId'] = $lastEquId;
					}
					$id = $this->add_d($val);
					if (!empty ($val['configCode'])) {
						$lastEquId = $id;
					}

                    //接装海外新增数组

				}
			}

			$contDao->updateShipStatus_d($object['id']);
			$contDao->updateOutStatus_d($object['id']);
			$this->updateRCU($object['id']);

            /**更新海外数据（更新海外物料）*/
            //查询海外单据物料信息
//            $materialSql="select c.id,c.borrowProductId,c.borrowId,c.borrowCode,c.materialId,c.materialName,c.materialCode,c.materialModel,c.materialType,
//	c.number,c.remark,c.price,c.unitName,c.amount,c.warrantyPeriod,c.license,c.deploy,c.executedNum,c.backNum,c.allocatBackNum,c.purchasedNum,c.changeTips,
//	c.isTemp,c.originalId,c.isDel,c.isNeedDelivery,c.outStockDate,c.isDefault,c.parentEquId,c.arrivalPeriod,c.serialName,
//	c.serialId,c.firmId,c.firmName from overseas_borrow_material c where c.borrowCode='".$contract['Code']."'";
//            $osMaterialRows = $this->connectSql($materialSql);
//            if(is_array($osMaterialRows)&&!empty($osMaterialRows)){
//                foreach($osMaterialRows as $osKey=>$osVal){
//                    $updateSql="";
//                    foreach ($equArr as $key => $val) {
//                        if($osVal['materialCode']==$val['productNo']){
//                            if ($val ['isDel'] == 1) {
//                                $updateSql="update overseas_borrow_material set isDel=0,number=".$val['number']." where  id='".$osVal['id']."'";
//                            }else{
//                                $updateSql="update overseas_borrow_material set number=".$val['number']." where  id='".$osVal['id']."'";
//                            }
//                        }
//                        continue;
//                    }
//                    $this->connectSql($updateSql);
//                }
//            }
			//			$linkDao->confirmChange($linkObj['id']);
//			$this->sendMailAtAudit($object, '变更');



			$this->commit_d();
			return $linkObj['id'];
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->rollBack();
			return null;
		}
	}

    /**
     * 物料分配 变更(2017-11-16)
     */
    function equChangeNew_d($object){
        try{
            $this->start_d();
            $contDao = new model_projectmanagent_borrow_borrow();
            $contract = $contDao->get_d($object['id']);
            $linkDao = new model_projectmanagent_borrow_borrowequlink();
            $linkObj = $linkDao->findBy('borrowId', $object['id']);
            if(empty($linkObj) && isset($object['oldId']) && $object['oldId'] > 0){
                $linkObj = $linkDao->findBy('borrowId', $object['oldId']);
            }

            // 更新临时记录成本概算
            $object['linkId'] = $linkObj['id'];
            $borrowCostDao = new model_projectmanagent_borrow_cost();
            $borrowCostDao->addCostConfirm($object,null,1);

            $equs = array();
            //echo "<pre>";print_r($object['detail']);
            foreach ($object['detail'] as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        if (isset ($value['rowNum_'])) { //物料
                            $value['isCon'] = $value['productId'] . "_" . $value['rowNum_'];
                        }
                        $value['isConfig'] = $value['parentRowNum'];
                        if (!empty ($value['id'])) {
                            $value['oldId'] = $value['id'];
                            unset ($value['id']);
                        }
                        if (empty($value['isDel'])) {
                            if (empty($value['isDelTag'])) {
                                $value['isDel'] = 0;
                            } else {
                                $value['isDel'] = $value['isDelTag'];
                            }
                        }
                        $equs[] = $value;
                    }
                }
            }
            $contract['borrowId'] = $contract['id'];
            $equs = $this->processEquCommonInfo($equs, $contract);
            $equArr = $equs;
            foreach ($equs as $key => $val) {
                if ($val['isCon'] != '' && $val['isDel'] == 1) {
                    foreach ($equArr as $index => $value) {
                        if ($val['isCon'] == $value['isConfig']) {
                            $equArr[$index]['isConfig'] = '';
                            $equArr[$index]['parentEquId'] = 0;
                        }
                    }
                }
            }

            $lastEquId = 0;
            foreach ($equArr as $key => $val) {
                $val['isTemp'] = 1;
                $val['linkId'] = empty($val['linkId'])? $object['linkId'] : $val['linkId'];
                if ($val['productId'] == '') {
                    unset($equArr[$key]);
                    continue;
                }
                //如果变更数量是0，删除计划设备清单
                if ($val ['isDel'] == 1) {
                    $val['id'] = $val ['oldId'];
                    $val['isDel'] = 1;
                    $val['changeTips'] = 3;
                    $this->edit_d($val);
                } elseif ($val ['oldId']) {
                    $val['id'] = $val ['oldId'];
                    $this->edit_d($val);
                } else {
                    $val['changeTips'] = 2;
                    if (empty ($val['configCode']) || $val['configCode'] === 0) {
                        $val['parentEquId'] = $lastEquId;
                    }
                    $id = $this->add_d($val);
                    if (!empty ($val['configCode'])) {
                        $lastEquId = $id;
                    }

                }
            }

            $this->updateRCU($object['id']);
//            $this->sendMailAtAudit($object, '变更');

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->rollBack();
            return null;
        }
    }

	/**
	 * 管理员变更
	 * @param $object
	 * @return bool
	 */
	function equChangeFromManager_d($object) {
//		echo "<pre>";print_r($object['detail']);die;
		//实例化变更类
		$changeLogDao = new model_common_changeLog('borrowequ');
		$linkDao = new model_projectmanagent_borrow_borrowequlink();
        $linkObj = $linkDao->findBy('borrowId', $object['id']);

		// 用于变更的数据
		$equs = array();

		foreach ($object['detail'] as $v) {
            if (is_array($v)) {
                $delParentEquRow = array();
                foreach ($v as $vi) {
                    if (isset ($vi['rowNum_'])) { //物料
                        $vi['isCon'] = $vi['productId'] . "_" . $vi['rowNum_'];
                    }
                    $vi['isConfig'] = $vi['parentRowNum'];

                    if (isset($vi['id']) && $vi['id']) {
                        $vi['oldId'] = $vi['id'];
						unset($vi['id']);
                    }
                    if (isset($vi['isDel']) && $vi['isDel'] === "") {
                        $vi['isDel'] = 0;
                    }

                    // PMS 2962 借试用审批通过之后，变更删除的物料没有删除, 发现直接删除带配件物料的时候,配件的isDel是1,可是关联的物料的isDel不会转换为1,导致变更后物料的删除标识isDel没变
                    if(isset($vi['isDelTag']) && !empty($vi['isDelTag']) && $vi['isDelTag'] > 0){
                        $vi['isDel'] = 1;
                        $delParentEquRow[] = $vi['isCon'];
                    }

                    if(in_array($vi['parentRowNum'],$delParentEquRow)){
                        $vi['parentEquId'] = 0;
                        $vi['parentRowNum'] = '';
                        $vi['isConfig'] = '';
                    }

                    $vi['borrowId'] = $linkObj['borrowId'];
                    $equs[] = $vi;
                }
            }
		}

        // 更新成本概算
        $borrowCostDao = new model_projectmanagent_borrow_cost();
        // 清除掉原来未提交的记录
        $tempLinkObj = $linkDao->findAll(array("borrowId" => $object['id'],"ExaStatus" => "待提交"));
        // 清除掉原来未提交的记录
        foreach ($tempLinkObj as $tmpLink){
            $linkDao->delete(array("id" => $tmpLink['id']));
            $deleteEquSql = "delete from oa_borrow_equ where borrowId = '{$object['id']}' and linkid = '{$tmpLink['id']}';";
            $this->query($deleteEquSql);
            $borrowCostDao->delete(array("linkId" => $tmpLink['id'],"isTemp" => 1));
        }
//		echo "<pre>";print_r($equs);die;

		try {
			$this->start_d();

            // 获取关联表ID
            $linkObj['isTemp'] = '1';
            $linkObj['equs'] = $equs;
            $linkObj['originalId'] = $linkObj['oldId'] = $linkObj['id'];
            $linkObj['ExaStatus'] = WAITAUDIT;
            $tempObjId = $changeLogDao->addLog($linkObj);

            // 新增临时概算记录
            $borrowCostDao->addCostConfirm(array(
                "id" => ($object['oldId'] == 0)? $object['id'] : $object['oldId'],
                "linkId" => $tempObjId,
                "isTemp" => 1,
                "equEstimate" => $object['equEstimate'],
                "equEstimateTax" => $object['equEstimateTax']
            ));

            // 更新临时记录物料的父级物料关联ID
            $updateParentEquSql = "update oa_borrow_equ e2 
                left join (
                    select id,parentEquId,borrowId from oa_borrow_equ e1 where e1.isTemp = 0 and e1.parentEquId <> 0
                )et on (e2.originalId = et.id and et.borrowId = e2.borrowId)
                left join oa_borrow_equ e3 on (e3.originalId = et.parentEquId and et.borrowId = e3.borrowId)
                set e2.parentEquId = IF(e3.isDel > 0,0,e3.id)
                where e2.borrowId = '{$object['id']}'
                and e3.id is not null
                and e3.isTemp != 0
                and e2.isTemp != 0
                and e2.linkId = '{$tempObjId}'
                and e3.linkId = '{$tempObjId}'";
            $this->query($updateParentEquSql);

			$this->commit_d();
			return $tempObjId;
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->rollBack();
			return false;
		}
	}

    /**
     * @param $spid
     */
    function dealAfterChangeAudit_d($spid) {
        $otherDatas = new model_common_otherdatas();
        $flowInfo = $otherDatas->getStepInfo($spid);
        $objId = $flowInfo['objId'];
        $linkDao = new model_projectmanagent_borrow_borrowequlink();
        $obj = $linkDao->get_d($objId);

        if ($obj['ExaStatus'] == AUDITED) {
            // 正常通过
            $changeLogDao = new model_common_changeLog('borrowequ');
            $changeLogDao->confirmChange_d($obj, null);

            // 将原记录的审批状态更新为完成。
            $orgObj = $linkDao->get_d($obj['originalId']);
            if (!empty($orgObj)) {
                $orgObj['ExaStatus'] = AUDITED;
                $linkDao->edit_d($orgObj);
            }

            $borrowCostDao = new model_projectmanagent_borrow_cost();
            $contDao = new model_projectmanagent_borrow_borrow();
            $costInfo = $borrowCostDao->find(array("borrowId" => $obj['borrowId'],"linkId" => $obj['id']));
            if($costInfo && !empty($costInfo['confirmMoney'])) {
                $borrowCostDao->updateById(array("id"=>$costInfo['id'],"state" => "1","ExaStatus" => "完成"));
                $contDao->updateById(array("id" => $obj['borrowId'], "equEstimate" => $costInfo['confirmMoney'], "equEstimateTax" => $costInfo['confirmMoneyTax']));
            }

            $updateParentEquSql = "update oa_borrow_equ e1 left join oa_borrow_equ e2 on (e1.isCon = e2.isConfig and e1.borrowId = e2.borrowId and e1.linkId = e2.linkId) set e2.parentEquId = e1.id where e1.isTemp = 0 and (e1.isDel = 0 && e2.isDel = 0) and  (e1.isCon <> '' and e1.isCon <> 'NULL') and (e2.isConfig <> '' and e2.isConfig <> 'NULL') and e2.parentEquId <> e1.id and e1.borrowId = '{$obj['borrowId']}' and e1.linkId > 0;";
            $this->query($updateParentEquSql);

            // 如有重启物料的时候
            $tempEqu = $this->findAll(array("borrowId" => $obj['borrowId'],"linkId" => $obj['id']));
            foreach ($tempEqu as $k => $v){
                if(!empty($v['originalId']) && $v['originalId'] > 0){
                    $this->updateById(array("id"=>$v['originalId'],"linkId"=>$obj['originalId'],"isDel" => $v['isDel']));
                }
            }

            $contDao->updateShipStatus_d($obj['borrowId']);
        } else if ($obj['ExaStatus'] == BACK){
            // 打回处理
            $borrowCostDao = new model_projectmanagent_borrow_cost();
            $costInfo = $borrowCostDao->find(array("borrowId" => $obj['borrowId'],"linkId" => $obj['id']));
            if($costInfo && !empty($costInfo['confirmMoney'])) {
                $borrowCostDao->updateById(array("id"=>$costInfo['id'],"state" => "2","ExaStatus" => "打回"));
            }
            // 将原记录的审批状态更新为完成。
            $orgObj = $linkDao->get_d($obj['originalId']);
            if (!empty($orgObj)) {
                $orgObj['ExaStatus'] = AUDITED;
                $linkDao->edit_d($orgObj);
            }
        }
    }

    //处理并返回insertSQL并创建数据库连接并执行sql语句
    function handleCreateSql($row, $tableName) {
        if (!is_array($row))
            return FALSE;
        if (empty($row))
            return FALSE;
        $uuid = $this->uuid();
        $row['id'] = "$uuid";
        foreach ($row as $key => $value) {
            $cols[] = $key;
            $vals[] = "'" . $this->__val_escape($value) . "'";
        }
        $col = join(',', $cols);
        $val = join(',', $vals);

        $sql = "INSERT INTO $tableName ({$col}) VALUES ({$val})";

        $con = mysql_connect(localhostOA, dbuserOA, dbpwOA);
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db(dbnameOA, $con);
        mysql_query("SET NAMES 'GBK'");

        if (!mysql_query($sql, $con)) {
            echo $sql;
            die('Error: ' . mysql_error());
        }
        $id = mysql_insert_id();
        mysql_close($con);
        return $uuid;
    }

    /**
     * 创建数据库连接并执行sql语句
     * @param string sql语句
     * @return string id
     */
    function  connectSql($sql) {
        $con = mysql_connect(localhostOA, dbuserOA, dbpwOA);
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db(dbnameOA, $con);
        mysql_query("SET NAMES 'GBK'");

        if (!$result = mysql_query($sql, $con)) {
            echo $sql;
            die('Error: ' . mysql_error());
        }
        $id = mysql_insert_id();
        $rows = array();
        while ($rows[] = mysql_fetch_array($result, MYSQL_ASSOC)) {
        }
        mysql_free_result($result);
        array_pop($rows);
        mysql_close($con);
        return $rows;
    }

    function uuid($prefix = '') {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }

	/**
	 * 处理物料清单相同属性
	 */
	function processEquCommonInfo($equs, $contract) {
		$mainArr = array(
			"borrowId" => $contract['borrowId'],
			"borrowCode" => $contract['borrowCode'],
			"borrowType" => 'oa_borrow_borrow',
		);
		if (!empty ($contract['linkId'])) {
			$mainArr["linkId"] = $contract['linkId'];
		}
		$equs = util_arrayUtil:: setArrayFn($mainArr, $equs);
		return $equs;
	}
	/****************************************物料确认 end*********************************************************/
	/**
	 * 更新在途数量
	 * $temId 合同从表ID
	 * $num 数量
	 * $type +/-  （add为 + /subtraction为减）
	 */
	function updateOnWayNum($temId, $num, $type) {
		if ($type == "add") {
			$onWayNum = $num;
		} else {
			$onWayNum = $num * (-1);
		}
		$sql = "update " . $this->tbl_name . " set onWayNum = onWayNum + $onWayNum where id=" . $temId . "";
		$this->query($sql);
	}

	/**
	 * 物料确认 独立新增物料
	 */
	function getNoProductEqu_d($contractId) {
		$this->searchArr['borrowId'] = $contractId;
		$this->searchArr['noContProductId'] = 0;
		//		print_R($this->searchArr);
		$rows = $this->list_d();
		return $rows;
	}

	/**
	 * 物料确认邮件通知
	 */
	function sendMailAtAudit($object, $title) {
		$mainDao = new model_projectmanagent_borrow_borrow();
		$otherdatas = new model_common_otherdatas ();
		$mainObj = $mainDao->get_d($object['id']);
		$deptName = $otherdatas->getUserDatas($mainObj['salesNameId'], 'DEPT_NAME');
		if ($deptName != '海外业务部') {
			$outmailArr = array(
				$mainObj['salesNameId'],
				$mainObj['createId']
			);
		} else {
			$outmailArr = array(
				$mainObj['createId']
			);
		}
		if ($mainObj['limits'] == '员工') {
			if (is_array($this->mailArr)) {
				$mailArr = $this->mailArr;
			} else {
				$mailArr = array();
			}
			$mailConIdArr = explode(',', $mailArr['sendUserId']);
		}
		foreach ($mailConIdArr as $key => $val) {
			array_push($outmailArr, $val);
		}
		$outmailStr = implode(',', $outmailArr);
		$outmailStr .= "," . EQUCONFIRMUSER;
		$addMsg = $this->sendMesAsAdd($mainObj);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $title, $mainObj['limits'] . '借试用' . $mainObj['Code'], $outmailStr, $addMsg, '1');
	}


	/**
	 * 邮件中附加物料信息
	 */
	function sendMesAsAdd($object) {
		if ($object['limits'] != '员工') {
			$addmsg = '<br/>客户名称：' . $object['customerName'] . "<br/>";
		}
		if (is_array($object ['borrowequ'])) {
			$j = 0;
            $addmsg .= "物料明细：";
            $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>序号</td><td>物料编号</td><td>物料名称</td><td>规格型号</td><td>单位</td><td>数量</td></tr>";
			foreach ($object ['borrowequ'] as $key => $equ) {
				$j++;
				$productCode = $equ['productNo'];
				$productName = $equ['productName'];
				$productModel = $equ ['productModel'];
				$unitName = $equ ['unitName'];
				$number = $equ ['number'];
				$addmsg .= <<<EOT
						<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$productCode</td><td>$productName</td><td>$productModel</td><td>$unitName</td><td>$number</td></tr>
EOT;
			}
			//					$addmsg.="</table>" .
			//							"<br><span color='red'>以上列表若有背景色为绿色的物料，说明该物料是借试用转销售的。</span></br>";
		}
		return $addmsg;
	}

	/**
	 * 更新申请数量
	 * $id
	 * $num 数量
	 */
	function updateApplyBackNum($id, $num) {
		$sql = "update $this->tbl_name set applyBackNum = applyBackNum + $num where id=$id";
		$this->query($sql);
	}

	/**
	 * 重计算借用归还数量
	 */
	function updateApplyBackNumEqu($id, $num) {
		$sql = "update $this->tbl_name set applyBackNum = $num where id=$id";
		$this->query($sql);
	}

	/**
	 * 数组过滤 - 同表单的表单数据不予显示
	 */
	function filterRows_d($object) {
		if ($object) {
			$markId = null;
			foreach ($object as $key => $val) {
				if ($markId == $val['conProductId']) {
					unset ($object[$key]['conProductName']);
				} else {
					$markId = $val['conProductId'];
				}
			}
		}
		return $object;
	}

	/**
	 * 附加物料配置
	 * @param $rows
	 * @param $productIdArr
	 * @return array
	 */
	function settingDeal_d($rows, $productIdArr) {
		// 系统只默认带出物料分类为手机类物料的配置
		// 获取物料类型为手机的顶级分类id
		$otherDatasDao = new model_common_otherdatas();
		$cellphoneIdArr = $otherDatasDao->getConfig('product_type_cellphone_id', null, 'arr');
		if(!empty($cellphoneIdArr)){
			$lrNode = array();// 树左右节点数组，键为树的左节点，值为树的右节点
			$proTypeDao = new model_stock_productinfo_producttype();
			foreach ($cellphoneIdArr as $v){
				$rs = $proTypeDao->find(array('id' => $v),null,'lft,rgt');
				if(!empty($rs)){
					$lrNode[$rs['lft']] = $rs['rgt'];
				}
			}
			$sql = 'SELECT t.lft,t.rgt FROM oa_stock_product_info i LEFT JOIN oa_stock_product_type t ON i.proTypeId = t.id WHERE i.id =';
			foreach ($productIdArr as $key => $val){
				$rs = $this->_db->getArray($sql.$val);
				if(empty($rs)){
					unset($productIdArr[$key]);
				}else{
					$isCellphoneType = false;
					foreach ($lrNode as $k => $v){
						if($rs[0]['lft'] > $k && $rs[0]['rgt'] < $v){
							$isCellphoneType = true;
							break;
						}
					}
					if(!$isCellphoneType){
						unset($productIdArr[$key]);
					}
				}
			}
		}
		// 如果手机类的物料，则进行配置附加
		if (!empty($productIdArr)) {
			// 实例化物料配置
			$configurationDao = new model_stock_productinfo_configuration();
			$productConfig = $configurationDao->findProductConfig($productIdArr);
			$productConfigMap = array();

			// 转换
			foreach ($productConfig as $v) {
				if (!isset($productConfigMap[$v['hardWareId']])) {
					$productConfigMap[$v['hardWareId']] = array();
				}
				// 如果是一电一充一线，分开显示两列（一电 ，一充一线）
				if($v['configName'] == '一电一充一数据线'){
					$productConfigMap[$v['hardWareId']][] = array(
						'productId' => -1, // 物料ID默认-1，系统遇到时不做仓库处理
						'productNo' => $v['configCode'],
						'productName' => '一电',
						'productModel' => $v['configPattern'],
						'number' => $v['configNum'],
						'maxNum' => 99
					);
					$productConfigMap[$v['hardWareId']][] = array(
						'productId' => -1, // 物料ID默认-1，系统遇到时不做仓库处理
						'productNo' => $v['configCode'],
						'productName' => '一充一线',
						'productModel' => $v['configPattern'],
						'number' => $v['configNum'],
						'maxNum' => 99
					);
				}else{
					$productConfigMap[$v['hardWareId']][] = array(
						'productId' => -1, // 物料ID默认-1，系统遇到时不做仓库处理
						'productNo' => $v['configCode'],
						'productName' => $v['configName'],
						'productModel' => $v['configPattern'],
						'number' => $v['configNum'],
						'maxNum' => 99
					);
				}
			}

			$result = array();

			// 查询物料相关清单
			foreach ($rows as $v) {
				$result[] = $v;

				// 配置添加
				if (isset($productConfigMap[$v['productId']])) {
					foreach ($productConfigMap[$v['productId']] as $vi) {
						$vi['number'] = $vi['number'] * $v['number'];
						$result[] = $vi;
					}
				}
			}
			return $result;
		}
		return $rows;
	}

    /**
     * 根据传入的物料信息统计成本概算
     * @param $equArr
     * @return array|bool
     */
	function curEquEstimates($equArr){
        $sql = "";
        if($equArr){
            $conEqu = array();
            foreach ($equArr as $k => $v){
                if(isset($v['equConId']) && $v['equConId'] != ''){
                    $equObj = $this->get_d($v['equConId']);
                    if($equObj['number'] <> $v['equNum']){// 修改了数量就取新的成本,否则取上次确认的
                        $number = ($v['equNum'] > 0)? $v['equNum'] : 0;
                        $sql .= ($sql == "")? "select {$number} as number, priCost as costVal,((1 * priCost) * 1.17) as costValTax from oa_stock_product_info where id='" . $v['equId'] . "'" :
                            " UNION ALL select {$number} as number, priCost as costVal,((1 * priCost) * 1.17) as costValTax from oa_stock_product_info where id='" . $v['equId'] . "'";
                    }else{
                        $conEqu[] = array("equConId" => $v['equConId'],"equNum" => $v['equNum']);
                    }
                }else{
                    $number = ($v['equNum'] > 0)? $v['equNum'] : 0;
                    $sql .= ($sql == "")? "select {$number} as number, priCost as costVal,((1 * priCost) * 1.17) as costValTax from oa_stock_product_info where id='" . $v['equId'] . "'" :
                        " UNION ALL select {$number} as number, priCost as costVal,((1 * priCost) * 1.17) as costValTax from oa_stock_product_info where id='" . $v['equId'] . "'";
                }
            }

            $equCostArr = array();
            if($sql != ''){
                $sql = "select * from ({$sql})t";
                $equCost = $this->_db->getArray($sql);
                if($equCost){
                    foreach ($equCost as $equ){
                        $arr['Estimate'] = bcmul($equ['costVal'],$equ['number'],2);
                        $arr['EstimateTax'] = bcmul($equ['costValTax'],'1',2);
                        $arr['EstimateTax'] = bcmul($arr['EstimateTax'],$equ['number'],5);
                        $equCostArr[] = $arr;
                    }
                }
            }

            if(!empty($conEqu)){// 如果有保存记录的话,用上次保存的单价
                foreach ($conEqu as $k => $v){
                    $thisEquArr = $this->get_d($v['equConId']);
                    $arr['Estimate'] = bcmul($thisEquArr['price'],$v['equNum'],3);
                    $EstimateTax = bcmul(bcmul($thisEquArr['price'],'1',4),'1.17',2);
                    $arr['EstimateTax'] = bcmul($EstimateTax,$v['equNum'],5);
                    $equCostArr[] = $arr;
                }
            }
            $equCost = array("totalEstimate" => 0,"totalEstimateTax" => 0);
            foreach ($equCostArr as $equC){
                $equCost['totalEstimate'] = bcadd($equC['Estimate'],$equCost['totalEstimate'],2);
                $equCost['totalEstimateTax'] = bcadd($equC['EstimateTax'],$equCost['totalEstimateTax'],2);
            }
        }else{
            $equCost = array("totalEstimate" => 0,"totalEstimateTax" => 0);
        }
        return $equCost;
    }

    /**
     * 物料确认邮件通知
     */
    function sendMailAfterEquConfirm($object, $title, $isChange = 0) {
        $object['borrowequ'] = isset($object['borrowequ'])? $object['borrowequ'] : (isset($object['detail'])? $object['detail'] : array());
        if(isset($object['detail']) && !empty($object['detail'])){
            $object['borrowequ'] = array();
            foreach ($object['detail'] as $equRow){
                foreach ($equRow as $equ){
                    $object['borrowequ'][] = $equ;
                }
            }
        }
        $mainDao = new model_projectmanagent_borrow_borrow();
        $otherdatas = new model_common_otherdatas ();
        $mainObj = $mainDao->get_d($object['id']);
        $deptName = $otherdatas->getUserDatas($mainObj['salesNameId'], 'DEPT_NAME');
        if ($deptName != '海外业务部') {
            $outmailArr = array(
                $mainObj['salesNameId'],
                $mainObj['createId']
            );
        } else {
            $outmailArr = array(
                $mainObj['createId']
            );
        }
        $object['customerName'] = isset($mainObj['customerName'])? $mainObj['customerName'] : '';
        $outmailStr = implode(',', $outmailArr);
        $addmsg = "<br/><br/><b>为避免耽误借试用交付时间，请借试用销售负责人抽空核对，无异议请点击“确认”，有疑问请与罗权洲联系。</b><br/>
				            <b>OA确认地址：进入【销售管理】->【我的借试用】->右键进行【发货物料确认】</b><br/><br/>";
        $addSubMsg = $this->sendMesAsAdd($object);
        $addmsg .= $addSubMsg;
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $title, $mainObj['limits'] . '借试用 ' . $mainObj['Code'], $outmailStr, $addmsg, '1');
    }
}