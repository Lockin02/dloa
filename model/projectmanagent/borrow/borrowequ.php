<?php

/**
 * @author Administrator
 * @Date 2011��5��9�� 16:02:12
 * @version 1.0
 * @description:�����������Ʒ�嵥 Model��
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
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function initTableView($object, $objId) {
		//��ȡ���һ�α����������ϸ��¼
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
				$license = "<input type='button' class='txt_btn_a' value='����' onclick='" .
					"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=" . $val['license'] . "" .
					"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
			}
			//k3 ��š�����
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
						<td title="K3ϵͳ����������: $val[productNameKS]"><span class="red" >$val[productName]$KsName</span></td>
						<td>$val[productModel]</td>
						<td title="˫���鿴���к�" style="background:#efefef;" ondblclick="serialNo($val[borrowId],$val[id]);"><span class="red" title="˫���鿴���к�" >$val[number]</span></td>
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
						<td title="K3ϵͳ����������: $val[productNameKS]"><span class="red" >$val[productName]$KsName</span></td>
						<td>$val[productModel]</td>
						<td title="˫���鿴���к�" style="background:#efefef;" ondblclick="serialNo($val[borrowId],$val[id]);"><span class="red" title="˫���鿴���к�" >$val[number]</span></td>
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
						<td title="K3ϵͳ����������: $val[productNameKS]">$val[productName]$KsName</td>
						<td>$val[productModel]</td>
						<td title="˫���鿴���к�" style="background:#efefef;" ondblclick="serialNo($val[borrowId],$val[id]);"><span class="red" title="˫���鿴���к�" >$val[number]</span></td>
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
	 * �������� ��Ⱦ
	 */
	function configTable($object, $Num) {
		$str = "";
		$i = $Num;

		foreach ($object as $key => $val) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" class="readOnlyTxtShort" readonly="readonly"  name="borrow[borrowequ][$i][productNo]" id="productNo$i"  value="$val[productCode]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[id]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle" readonly="readonly"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]"  id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[pattern]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]" id="number$i" class="txtshort" value="$val[configNum]" onblur="FloatMul('number$i','price$i','money$i')"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName1" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[money]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warranty]" /></td>
				        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[License]"/>
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
 			                <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="$val[isConfig]" value="$val[isCon]">
 			            </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
					</tr>
EOT;
		}
		return array($str, $i);
	}


	/**
	 * ���������޸�  ���� ��Ⱦ
	 */
	function configTableEdit($object, $Num) {
		$str = "";
		$i = $Num;

		foreach ($object as $key => $val) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i</td>
						<td><input type="text" class="readOnlyTxtShort" readonly="readonly"  name="borrow[borrowequ][$i][productNo]" id="productNo$i"  value="$val[productCode]"/></td>
			            <td><input type="hidden" id="isAdd$i" name="borrow[borrowequ][$i][isAdd]" value="1" />
			                <input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[id]"/>
			                <input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle" readonly="readonly"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]"  id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[pattern]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]" id="number$i" class="txtshort" value="$val[configNum]" onblur="FloatMul('number$i','price$i','money$i')"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName1" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[money]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warranty]" /></td>
				        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[License]"/>
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
 			                <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="$val[isConfig]" value="$val[isCon]">
 			            </td>
			            <td><img src="images/closeDiv.gif" onclick="mydelT(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
					</tr>
EOT;
		}
		return array($str, $i);
	}

	/**
	 * ��Ⱦ�༭ҳ��ӱ�
	 */
	function initTableEdit($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			//��Ʒ�������ֵ�
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			        <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
		 			      </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			        <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
		 			      </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
					</tr>
EOT;
			}
		}
		return array($i, $str);
	}

	/**
	 * ���������޸�
	 */
	function proTableEdit($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			//��Ʒ�������ֵ�
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			        <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
		 			      </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			        <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
		 			      </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
					</tr>
EOT;
			}
		}
		return array($i, $str);
	}

	/**
	 * ��Ⱦ�����ôӱ�
	 */
	function chanceTableEdit($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//��Ʒ�������ֵ�
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			      </td>
			            <td>
			                <img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/>
			            </td>
					</tr>
EOT;
		}
		return array($i, $str);
	}


	/*******************************ҳ����ʾ��*********************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($borrowId) {
		$this->searchArr['borrowId'] = $borrowId;
		$this->searchArr['isDel'] = '0';
		$this->asc = false;
		return $this->list_d();
	}


	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getByProId_d($contractId) {
		$this->searchArr['conProductId'] = $contractId;
		$this->searchArr['isDel'] = 0;
		// $this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}


	/**�������´�ɹ�����
	 *author zengzx
	 *2011��9��16�� 14:47:11
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
	 * �豸�б�-������
	 * ���ݶ�����Ż�ȡ�豸�б�
	 */
	function showEquListInByOrder($borrowId, $docType) {
		$sql = 'select e.id,e.borrowId,e.borrowCode,e.productLine,e.productName,e.isDel,e.productId,e.productNo,e.productModel,e.productType,e.number,e.price,e.money,e.warrantyPeriod,e.executedNum,e.onWayNum,e.purchasedNum,e.issuedPurNum,e.uniqueCode from ' . $this->tbl_name . ' e  where e.borrowId = ' . $borrowId;

		$equs = $this->_db->getArray($sql);
		//print_r($equs);
		//��ȡ�豸����������
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
	 * ������ʱ��ʾ�豸
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
							<td width="8%" title="��ǰ�ֿ����������">
								<font color="red">
							     	<a  href="javascript:toLockRecordsPage('$val[id]',true)" >
							     		<div equId="$equId" proId="$proId" id="stockLockNum$i"></div>
							     	</a>
							     </font>
							</td>
							<td width="8%" title="���вֿ�����������ܺ�">
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
	 * ����Ա�������� ����������
	 */
	function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$productCodeClass = "readOnlyTxtItem";
				$productNameClass = "readOnlyTxtNormal";
				$deexecutedNum = $val['number'] - $val['executedNum'];
				if ($deexecutedNum != 0) {
					$seNum = $i + 1;
					$str .= <<<EOT
				<tr align="center" >
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
                     	<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo($i);" title="ѡ�����к�">
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
	 * ������ת����-----���ۺ�ͬ�ӱ���Ⱦ
	 */
	function toOrderBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//���ҽ����õ��� ���ϵ�δ�黹����
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
					    <td><img src="images/removeline.png" onclick="mydel(this,'borrowequ')" title="ɾ����" id="Del$i"/></td>
					    <td>$i</td>
						<td><input type="text" class="readOnlyTxtShort" name="order[borrowequ][$i][productNo]" id="borproductNo$i"  value="$val[productNo]"/>
			                <input type="hidden" name="order[borrowequ][$i][unitName]" id="borunitName$i" value="$val[unitName]"></td>
			            <td><input type="hidden" id="borproductId$i" name="order[borrowequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="order[borrowequ][$i][productName]" id="borproductName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="�鿴������Ϣ"/></td>
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
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" /></td>
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
	 * ������ת����-----�����ͬ�ӱ���Ⱦ
	 */
	function toServiceBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//���ҽ����õ��� ���ϵ�δ�黹����
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
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="�鿴������Ϣ"/></td>
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
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" /></td>
				        <td width="4%"><input type="checkbox" name="serviceContract[borrowequ][$i][isSell]" id="borisSell$i" $checked/>
					        <input type="hidden" name="serviceContract[borrowequ][$i][remark]" id="borremark$i" value="$val[remark]">
					        <input type="hidden" name="serviceContract[borrowequ][$i][businessId]" value="$val[borrowId]" />
					        <input type="hidden" name="serviceContract[borrowequ][$i][businessEquId]" value="$val[id]" />
					        </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'borrowequ')" title="ɾ����" id="borDel$i"/></td>
					</tr>
EOT;

		}
		return array($str, $i);
	}

	/**
	 * ������ת����-----���޺�ͬ�ӱ���Ⱦ
	 */
	function toLeaseBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//���ҽ����õ��� ���ϵ�δ�黹����
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
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="�鿴������Ϣ"/></td>
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
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" /></td>
				        <td width="4%"><input type="checkbox" name="rentalcontract[borrowequ][$i][isSell]" id="borisSell$i" $checked/>
					        <input type="hidden" name="rentalcontract[borrowequ][$i][remark]" id="borremark$i" value="$val[remark]">
					        <input type="hidden" name="rentalcontract[borrowequ][$i][businessId]" value="$val[borrowId]" />
					        <input type="hidden" name="rentalcontract[borrowequ][$i][businessEquId]" value="$val[id]" />
					        </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'borrowequ')" title="ɾ����" id="borborDel$i"/></td>
					</tr>
EOT;

		}
		return array($str, $i);
	}

	/**
	 * ������ת����---- �з���ͬ�ӱ���Ⱦ
	 */
	function toRdprojectBorrowEqu($object) {
		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//���ҽ����õ��� ���ϵ�δ�黹����
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
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('borproductId$i',$i);" title="�鿴������Ϣ"/></td>
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
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" /></td>
				        <td width="4%"><input type="checkbox" name="rdproject[borrowequ][$i][isSell]" id="borisSell$i" $checked/>
					        <input type="hidden" name="rdproject[borrowequ][$i][remark]" id="borremark$i" value="$val[remark]">
					        <input type="hidden" name="rdproject[borrowequ][$i][businessId]" value="$val[borrowId]" />
					        <input type="hidden" name="rdproject[borrowequ][$i][businessEquId]" value="$val[id]" />
					        </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'borrowequ')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;


		}
		return array($str, $i);
	}

	/************�ⲿ�ӿ�*********************/
	//������Ⱦ�ӱ�
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			    </td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
		}
		return array($i, $str);
	}

	//ת����Ⱦ�ӱ�
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			    </td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
		}
		return array($i, $str);
	}

	/************������ ���********************************/
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
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			    </td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody','borrowequ')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
		}
		return array($i, $str);
	}

	/**
	 * �жϽ����õķ���״̬
	 */
	function borrowShipmentsStatus($borrowId) {
		$equinfo = $this->getDetail_d($borrowId);
		$flagArr = array();//�жϷ���״̬������
		foreach ($equinfo as $key => $val) {
			if ($val['executedNum'] == '0') {
				$flagArr[] = 'δ����';
			} else if ($val['number'] > $val['executedNum']) {
				$flagArr[] = '���ַ���';
			} else if ($val['number'] <= $val['executedNum']) {
				$flagArr[] = '��ɷ���';
			}
		}
		if (!in_array("δ����", $flagArr) && !in_array("���ַ���", $flagArr)) {
			return "0";
		} else if ((in_array("���ַ���", $flagArr)) or (in_array("δ����", $flagArr) && in_array("��ɷ���", $flagArr))) {
			return "1";
		} else {
			return "2";
		}
	}

	/**
	 * ����Դ�����ӱ�ID ��ȡδִ������
	 */
	function getDocNotExeNum($docId, $docItemId) {
		$sql = "select (number - executedNum) as nonexecutionNum from oa_borrow_equ where id=" . $docItemId . "";
		$numarr = $this->_db->getArray();
		return $numarr[0]['nonexecutionNum'];
	}

	/***************************************����ȷ�� start*****************************************/
	/**
	 * ��ȡ��Ʒ�µ�������Ϣ
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
	 * @description �������ϴ���ҳ����ʾ��Ʒ��Ϣ
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$i = 0; //�б��¼���
			foreach ($rows as $key => $val) {
				$deployShow = '';
				$deployShow = $goodDao->showDeploy($val['deploy']);
				//��ͬ�豸�Ƿ�����
				$j = $i + 1;
				if ($val['license'] != 0 || $val['license'] != '') {
					$licenseHtml = "<input type='button'  value='��������'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				} else {
					$licenseHtml = '��';
				}
				if ($val['isDel'] == "1") {
					$style .= '<img title="���ɾ���Ĳ�Ʒ" src="images/box_remove.png" />';
				}
				if ($val['changeTips'] == "2") {
					$style .= '<img title="��������Ĳ�Ʒ" src="images/new.gif" />';
				}
				if ($val['changeTips'] == "1") {
					$style .= '<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />';
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
							<input type="button"  value="��������"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>-->
							<td width="8%"><input type="button"  value="��Ʒ����"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">�����嵥</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="չ��" alt="����ѡ��" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:35px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">�����嵥</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="����" alt="����ѡ��" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * @description ���/�༭ ���ϴ���ҳ����ʾ��Ʒ��Ϣ
	 * @param $rows
	 */
	function showItemChange($rows) {
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$equDao = new model_projectmanagent_borrow_borrowequ();
			$i = 0; //�б��¼���
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
					$style .= '<img title="���ɾ���Ĳ�Ʒ" src="images/box_remove.png" />';
				}
				if ($val['changeTips'] == "2") {
					$style .= '<img title="��������Ĳ�Ʒ" src="images/new.gif" />';
				}
				if ($val['changeTips'] == "1") {
					$style .= '<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />';
				}
				$img = '';
				if ($val['license'] != 0 || $val['license'] != '') {
					$licenseHtml = "<input type='button'  value='��������'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				} else {
					$licenseHtml = '��';
				}
				if ($val['isDel'] == '1') {
					$trStyle = " bgcolor='#efefef'";
				} else {
					$trStyle = " bgcolor='#ECFFFF'";
				}
				$img = '';
				//��ͬ�豸�Ƿ�����
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
							<input type="button"  value="��������"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>
							<td width="8%"><input type="button"  value="��Ʒ����"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">�����嵥</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="չ��" alt="����ѡ��" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:20px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">�����嵥</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="����" alt="����ѡ��" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
				$style = '';
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * ��Ⱦ��Ʒ����
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
				<th width="35px">���</th>
				<th>���ϱ���</th>
				<th>��������</th>
				<th>�汾�ͺ�</th>
				<th>����</th>
				<th>������
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
					<td><img src='images/removeline.png' onclick='mydel(this,"contractequ_$id")' title='ɾ����'></td>
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
	 * ������������ ��RCU���� �������ִ������
	 */
	function updateRCU($bid){
          $findIdSql = "select id from oa_borrow_equ where borrowId='".$bid."' and productId='".specialProId."'";
          $idArr = $this->_db->getArray($findIdSql);
          if(!empty($idArr[0]['id'])){
            $updateSqo = "update oa_borrow_equ set executedNum=number,issuedShipNum=number,backNum=number where borrowId='".$bid."' and parentEquId='".$idArr[0]['id']."'";
	        $this->query($updateSqo);
          }
	}

    //��ȡ��ͬ�Ѿ�ȷ���˵�����
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
	 * ���Ϸ��� ����
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
                if ($contObj['ExaStatus'] != '����ȷ��') {
                    echo "<script>alert('�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!')</script>";
                    throw new Exception("�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!");
                }

                // ������ʱ��¼�ɱ�����
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

                //���������
                $linkArr = array(
                    "borrowId" => $contObj['id'],
                    "borrowCode" => $contObj['Code'],
                    "borrowType" => 'oa_borrow_borrow',
                );
                if($act != "audit"){
                    $linkArr['ExaStatus'] = "δ�ύ";
                }else{
                    $linkArr['ExaStatus'] = "���";
                }
                $relativeLinkArr = $linkDao->find(array("borrowId" => $contObj['id']));
                if(!$relativeLinkArr && !isset($relativeLinkArr['id'])){
                    $linkId = $linkDao->add_d($linkArr, true);
                }else{
                    $linkId = $relativeLinkArr['id'];
                }
                $borrowCostDao->updateById(array("id"=>$costId,"linkId" => $linkId));

                // ���ϴ�����ͬ����
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
                throw new Exception("������Ϣ����������ȷ��!");
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
					echo "<script>alert('�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!')</script>";
					throw new Exception("�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!");
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
				//���������
				$linkArr = array(
					"borrowId" => $contObj['id'],
					"borrowCode" => $contObj['Code'],
					"borrowType" => 'oa_borrow_borrow',
				);

                // �ŵ�������ɺ���
				$dateObj = array(
					'id' => $object['id'],
					'standardDate' => $object['standardDate'],
				);
				if ($audti) {
					$dateObj['dealStatus'] = 1;
					$linkArr['ExaStatus'] = '���';
					$linkArr['ExaDT'] = day_date;
					$linkArr['ExaDTOne'] = day_date;
				} else {
					$linkArr['ExaStatus'] = 'δ�ύ';
				}
                $contDao->updateById($dateObj);
                // �ŵ�������ɺ���

				$linkId = $linkDao->add_d($linkArr, true);
				if ($linkId) {
					$linkDao->confirmAudit($linkId);
				} else {
					throw new Exception("������Ϣ����������ȷ��!");
				}
				$linkArr['linkId'] = $linkId;
				//������ͬ����
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
                    // �ŵ�������ɺ���
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
					$this->sendMailAtAudit($object, '�ύ');
					$this->updateRCU($object['id']);
                    // �ŵ�������ɺ���
				}
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
			$this->commit_d();
			return $linkId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ȡĳ�������嵥�������Ϣ
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
	 * ���Ϸ��� �༭
	 */
    function equEditNew_d($object,$act = 'save') {
        try {
            $this->start_d();

            if ($object['id']) {
                $contDao = new model_projectmanagent_borrow_borrow();
                $linkDao = new model_projectmanagent_borrow_borrowequlink();
                $linkObj = $linkDao->findBy('borrowId', $object['id']);
                $contObj = $contDao->get_d($object['id']);
                if ($contObj['ExaStatus'] != '����ȷ��') {
                    echo "<script>alert('�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!')</script>";
                    throw new Exception("�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!");
                }

                // ������ʱ��¼�ɱ�����
                $object['linkId'] = $linkObj['id'];
                $borrowCostDao = new model_projectmanagent_borrow_cost();
                $borrowCostDao->addCostConfirm($object,null,1);

                if($act == "audit"){
                    $linkObj['ExaStatus'] = '���';
                    $linkDao->edit_d($linkObj);
                }

                // ������Ϣ����
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

                //������ͬ����
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
                throw new Exception("������Ϣ����������ȷ��!");
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
					echo "<script>alert('�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!')</script>";
					throw new Exception("�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!");
				}

				// �ŵ�������ɺ���
				$dateObj = array(
					'id' => $object['id'],
					'standardDate' => $object['standardDate'],
				);
				if ($audti) {
					$dateObj['dealStatus'] = 1;
					$linkObj['ExaStatus'] = '���';
					$linkObj['ExaDT'] = day_date;
					$linkObj['ExaDTOne'] = day_date;
					$linkDao->edit_d($linkObj);
				}
				$contDao->updateById($dateObj);
                // �ŵ�������ɺ���

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
				//������ͬ����
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
					$this->sendMailAtAudit($object, '�ύ');
					$this->updateRCU($object['id']);
				}
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
			$this->commit_d();
			return $linkObj['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���Ϸ��� ���
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


			$linkObj['ExaStatus'] = '���';
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
						//						if (!empty ($value['configCode'])) { //������ת���ϵĸ�����
						//							$value['parentRowNum'] = 0;
						//							$value['parentEquId'] = 0;
						//						}
						if (isset ($value['rowNum_'])) { //����
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
				//������������0��ɾ���ƻ��豸�嵥
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

                    //��װ������������

				}
			}

			$contDao->updateShipStatus_d($object['id']);
			$contDao->updateOutStatus_d($object['id']);
			$this->updateRCU($object['id']);

            /**���º������ݣ����º������ϣ�*/
            //��ѯ���ⵥ��������Ϣ
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
//			$this->sendMailAtAudit($object, '���');



			$this->commit_d();
			return $linkObj['id'];
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->rollBack();
			return null;
		}
	}

    /**
     * ���Ϸ��� ���(2017-11-16)
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

            // ������ʱ��¼�ɱ�����
            $object['linkId'] = $linkObj['id'];
            $borrowCostDao = new model_projectmanagent_borrow_cost();
            $borrowCostDao->addCostConfirm($object,null,1);

            $equs = array();
            //echo "<pre>";print_r($object['detail']);
            foreach ($object['detail'] as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        if (isset ($value['rowNum_'])) { //����
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
                //������������0��ɾ���ƻ��豸�嵥
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
//            $this->sendMailAtAudit($object, '���');

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->rollBack();
            return null;
        }
    }

	/**
	 * ����Ա���
	 * @param $object
	 * @return bool
	 */
	function equChangeFromManager_d($object) {
//		echo "<pre>";print_r($object['detail']);die;
		//ʵ���������
		$changeLogDao = new model_common_changeLog('borrowequ');
		$linkDao = new model_projectmanagent_borrow_borrowequlink();
        $linkObj = $linkDao->findBy('borrowId', $object['id']);

		// ���ڱ��������
		$equs = array();

		foreach ($object['detail'] as $v) {
            if (is_array($v)) {
                $delParentEquRow = array();
                foreach ($v as $vi) {
                    if (isset ($vi['rowNum_'])) { //����
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

                    // PMS 2962 ����������ͨ��֮�󣬱��ɾ��������û��ɾ��, ����ֱ��ɾ����������ϵ�ʱ��,�����isDel��1,���ǹ��������ϵ�isDel����ת��Ϊ1,���±�������ϵ�ɾ����ʶisDelû��
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

        // ���³ɱ�����
        $borrowCostDao = new model_projectmanagent_borrow_cost();
        // �����ԭ��δ�ύ�ļ�¼
        $tempLinkObj = $linkDao->findAll(array("borrowId" => $object['id'],"ExaStatus" => "���ύ"));
        // �����ԭ��δ�ύ�ļ�¼
        foreach ($tempLinkObj as $tmpLink){
            $linkDao->delete(array("id" => $tmpLink['id']));
            $deleteEquSql = "delete from oa_borrow_equ where borrowId = '{$object['id']}' and linkid = '{$tmpLink['id']}';";
            $this->query($deleteEquSql);
            $borrowCostDao->delete(array("linkId" => $tmpLink['id'],"isTemp" => 1));
        }
//		echo "<pre>";print_r($equs);die;

		try {
			$this->start_d();

            // ��ȡ������ID
            $linkObj['isTemp'] = '1';
            $linkObj['equs'] = $equs;
            $linkObj['originalId'] = $linkObj['oldId'] = $linkObj['id'];
            $linkObj['ExaStatus'] = WAITAUDIT;
            $tempObjId = $changeLogDao->addLog($linkObj);

            // ������ʱ�����¼
            $borrowCostDao->addCostConfirm(array(
                "id" => ($object['oldId'] == 0)? $object['id'] : $object['oldId'],
                "linkId" => $tempObjId,
                "isTemp" => 1,
                "equEstimate" => $object['equEstimate'],
                "equEstimateTax" => $object['equEstimateTax']
            ));

            // ������ʱ��¼���ϵĸ������Ϲ���ID
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
            // ����ͨ��
            $changeLogDao = new model_common_changeLog('borrowequ');
            $changeLogDao->confirmChange_d($obj, null);

            // ��ԭ��¼������״̬����Ϊ��ɡ�
            $orgObj = $linkDao->get_d($obj['originalId']);
            if (!empty($orgObj)) {
                $orgObj['ExaStatus'] = AUDITED;
                $linkDao->edit_d($orgObj);
            }

            $borrowCostDao = new model_projectmanagent_borrow_cost();
            $contDao = new model_projectmanagent_borrow_borrow();
            $costInfo = $borrowCostDao->find(array("borrowId" => $obj['borrowId'],"linkId" => $obj['id']));
            if($costInfo && !empty($costInfo['confirmMoney'])) {
                $borrowCostDao->updateById(array("id"=>$costInfo['id'],"state" => "1","ExaStatus" => "���"));
                $contDao->updateById(array("id" => $obj['borrowId'], "equEstimate" => $costInfo['confirmMoney'], "equEstimateTax" => $costInfo['confirmMoneyTax']));
            }

            $updateParentEquSql = "update oa_borrow_equ e1 left join oa_borrow_equ e2 on (e1.isCon = e2.isConfig and e1.borrowId = e2.borrowId and e1.linkId = e2.linkId) set e2.parentEquId = e1.id where e1.isTemp = 0 and (e1.isDel = 0 && e2.isDel = 0) and  (e1.isCon <> '' and e1.isCon <> 'NULL') and (e2.isConfig <> '' and e2.isConfig <> 'NULL') and e2.parentEquId <> e1.id and e1.borrowId = '{$obj['borrowId']}' and e1.linkId > 0;";
            $this->query($updateParentEquSql);

            // �����������ϵ�ʱ��
            $tempEqu = $this->findAll(array("borrowId" => $obj['borrowId'],"linkId" => $obj['id']));
            foreach ($tempEqu as $k => $v){
                if(!empty($v['originalId']) && $v['originalId'] > 0){
                    $this->updateById(array("id"=>$v['originalId'],"linkId"=>$obj['originalId'],"isDel" => $v['isDel']));
                }
            }

            $contDao->updateShipStatus_d($obj['borrowId']);
        } else if ($obj['ExaStatus'] == BACK){
            // ��ش���
            $borrowCostDao = new model_projectmanagent_borrow_cost();
            $costInfo = $borrowCostDao->find(array("borrowId" => $obj['borrowId'],"linkId" => $obj['id']));
            if($costInfo && !empty($costInfo['confirmMoney'])) {
                $borrowCostDao->updateById(array("id"=>$costInfo['id'],"state" => "2","ExaStatus" => "���"));
            }
            // ��ԭ��¼������״̬����Ϊ��ɡ�
            $orgObj = $linkDao->get_d($obj['originalId']);
            if (!empty($orgObj)) {
                $orgObj['ExaStatus'] = AUDITED;
                $linkDao->edit_d($orgObj);
            }
        }
    }

    //��������insertSQL���������ݿ����Ӳ�ִ��sql���
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
     * �������ݿ����Ӳ�ִ��sql���
     * @param string sql���
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
	 * ���������嵥��ͬ����
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
	/****************************************����ȷ�� end*********************************************************/
	/**
	 * ������;����
	 * $temId ��ͬ�ӱ�ID
	 * $num ����
	 * $type +/-  ��addΪ + /subtractionΪ����
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
	 * ����ȷ�� ������������
	 */
	function getNoProductEqu_d($contractId) {
		$this->searchArr['borrowId'] = $contractId;
		$this->searchArr['noContProductId'] = 0;
		//		print_R($this->searchArr);
		$rows = $this->list_d();
		return $rows;
	}

	/**
	 * ����ȷ���ʼ�֪ͨ
	 */
	function sendMailAtAudit($object, $title) {
		$mainDao = new model_projectmanagent_borrow_borrow();
		$otherdatas = new model_common_otherdatas ();
		$mainObj = $mainDao->get_d($object['id']);
		$deptName = $otherdatas->getUserDatas($mainObj['salesNameId'], 'DEPT_NAME');
		if ($deptName != '����ҵ��') {
			$outmailArr = array(
				$mainObj['salesNameId'],
				$mainObj['createId']
			);
		} else {
			$outmailArr = array(
				$mainObj['createId']
			);
		}
		if ($mainObj['limits'] == 'Ա��') {
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
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $title, $mainObj['limits'] . '������' . $mainObj['Code'], $outmailStr, $addMsg, '1');
	}


	/**
	 * �ʼ��и���������Ϣ
	 */
	function sendMesAsAdd($object) {
		if ($object['limits'] != 'Ա��') {
			$addmsg = '<br/>�ͻ����ƣ�' . $object['customerName'] . "<br/>";
		}
		if (is_array($object ['borrowequ'])) {
			$j = 0;
            $addmsg .= "������ϸ��";
            $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>���ϱ��</td><td>��������</td><td>����ͺ�</td><td>��λ</td><td>����</td></tr>";
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
			//							"<br><span color='red'>�����б����б���ɫΪ��ɫ�����ϣ�˵���������ǽ�����ת���۵ġ�</span></br>";
		}
		return $addmsg;
	}

	/**
	 * ������������
	 * $id
	 * $num ����
	 */
	function updateApplyBackNum($id, $num) {
		$sql = "update $this->tbl_name set applyBackNum = applyBackNum + $num where id=$id";
		$this->query($sql);
	}

	/**
	 * �ؼ�����ù黹����
	 */
	function updateApplyBackNumEqu($id, $num) {
		$sql = "update $this->tbl_name set applyBackNum = $num where id=$id";
		$this->query($sql);
	}

	/**
	 * ������� - ͬ���ı����ݲ�����ʾ
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
	 * ������������
	 * @param $rows
	 * @param $productIdArr
	 * @return array
	 */
	function settingDeal_d($rows, $productIdArr) {
		// ϵͳֻĬ�ϴ������Ϸ���Ϊ�ֻ������ϵ�����
		// ��ȡ��������Ϊ�ֻ��Ķ�������id
		$otherDatasDao = new model_common_otherdatas();
		$cellphoneIdArr = $otherDatasDao->getConfig('product_type_cellphone_id', null, 'arr');
		if(!empty($cellphoneIdArr)){
			$lrNode = array();// �����ҽڵ����飬��Ϊ������ڵ㣬ֵΪ�����ҽڵ�
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
		// ����ֻ�������ϣ���������ø���
		if (!empty($productIdArr)) {
			// ʵ������������
			$configurationDao = new model_stock_productinfo_configuration();
			$productConfig = $configurationDao->findProductConfig($productIdArr);
			$productConfigMap = array();

			// ת��
			foreach ($productConfig as $v) {
				if (!isset($productConfigMap[$v['hardWareId']])) {
					$productConfigMap[$v['hardWareId']] = array();
				}
				// �����һ��һ��һ�ߣ��ֿ���ʾ���У�һ�� ��һ��һ�ߣ�
				if($v['configName'] == 'һ��һ��һ������'){
					$productConfigMap[$v['hardWareId']][] = array(
						'productId' => -1, // ����IDĬ��-1��ϵͳ����ʱ�����ֿ⴦��
						'productNo' => $v['configCode'],
						'productName' => 'һ��',
						'productModel' => $v['configPattern'],
						'number' => $v['configNum'],
						'maxNum' => 99
					);
					$productConfigMap[$v['hardWareId']][] = array(
						'productId' => -1, // ����IDĬ��-1��ϵͳ����ʱ�����ֿ⴦��
						'productNo' => $v['configCode'],
						'productName' => 'һ��һ��',
						'productModel' => $v['configPattern'],
						'number' => $v['configNum'],
						'maxNum' => 99
					);
				}else{
					$productConfigMap[$v['hardWareId']][] = array(
						'productId' => -1, // ����IDĬ��-1��ϵͳ����ʱ�����ֿ⴦��
						'productNo' => $v['configCode'],
						'productName' => $v['configName'],
						'productModel' => $v['configPattern'],
						'number' => $v['configNum'],
						'maxNum' => 99
					);
				}
			}

			$result = array();

			// ��ѯ��������嵥
			foreach ($rows as $v) {
				$result[] = $v;

				// �������
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
     * ���ݴ����������Ϣͳ�Ƴɱ�����
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
                    if($equObj['number'] <> $v['equNum']){// �޸���������ȡ�µĳɱ�,����ȡ�ϴ�ȷ�ϵ�
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

            if(!empty($conEqu)){// ����б����¼�Ļ�,���ϴα���ĵ���
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
     * ����ȷ���ʼ�֪ͨ
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
        if ($deptName != '����ҵ��') {
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
        $addmsg = "<br/><br/><b>Ϊ���ⵢ������ý���ʱ�䣬����������۸����˳�պ˶ԣ�������������ȷ�ϡ���������������Ȩ����ϵ��</b><br/>
				            <b>OAȷ�ϵ�ַ�����롾���۹���->���ҵĽ����á�->�Ҽ����С���������ȷ�ϡ�</b><br/><br/>";
        $addSubMsg = $this->sendMesAsAdd($object);
        $addmsg .= $addSubMsg;
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $title, $mainObj['limits'] . '������ ' . $mainObj['Code'], $outmailStr, $addmsg, '1');
    }
}