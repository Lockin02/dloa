<?php
/*
 * Created on 2010-7-26
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_licenselist_licenselist extends model_base {
	function __construct() {
		parent::__construct ();
		$this->tbl_name = "oa_contract_licenselist";
	}

	function batchInsert($contracId, $contNumber, $contName, $rows) {
		if ($rows) {
			$checkDao = new model_product_licensecheck_licensecheck ();
			foreach ( $rows as $key => $val ) {
				if ($val ['amount'] != "" || $val ['licenseType'] != "") {
					$val ['contractId'] = $contracId;
					$val ['contNumber'] = $contNumber;
					$val ['contName'] = $contName;
					$id = parent::add_d ( $val );
					if (! empty ( $val ['nodeId'] )) {
						$nodeIdArr = explode ( ",", $val ['nodeId'] );
						$nodeNameArr = explode ( ",", $val ['nodeName'] );
						for($i = 0; $i < count ( $nodeIdArr ); $i ++) {
							$check = array ();
							$check ['nodeId'] = $nodeIdArr [$i];
							$check ['nodeName'] = $nodeNameArr [$i];
							$check ['contractNo'] = $contNumber;
							$check ['contractId'] = $contracId;
							$check ['contractLicenseId'] = $id;
							$checkDao->add_d ( $check );
						}
					}
				}
			}
		}
		return true;
	}

	function showLicenseList($id) {
		$licenseArr = $this->findAll ( array ('contractId' => $id ) );
		$checkDao = new model_product_licensecheck_licensecheck ();
		if (! empty ( $licenseArr )) {
			foreach ( $licenseArr as $key => $value ) {
				$nodeId = null;
				$nodeName = null;
				$nodeArr = $checkDao->getLicensecheckByContractLicenseId ( $value ['id'] );
				if (is_array ( $nodeArr )) {
					foreach ( $nodeArr as $k => $v ) {
						$nodeId .= $v ['nodeId'] . ',';
						$nodeName .= $v ['nodeName'] . ',';
					}
					$licenseArr [$key] ['nodeId'] = substr ( $nodeId, 0, - 1 );
					$licenseArr [$key] ['nodeName'] = substr ( $nodeName, 0, - 1 );
				}
			}
		}
		return $licenseArr;
	}

	/**
	 * ���ݺ�ͬID�ͺ�ͬ���ɾ�������б�
	 */
	function delectByIdAndNumber($id, $contNumber) {
		try {
			$rows = $this->delete ( array ('contractId' => $id, 'contNumber' => $contNumber ) );
			return $rows;
		} catch ( exception $e ) {
			throw $e;
			return false;
		}
	}
	/**************************************ҳ����ʾ**********************************/

	function showlist($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ( $rows as $val ) {

				$equipDatadictDao = new model_system_datadict_datadict ();
				$productLine = $equipDatadictDao->getDataNameByCode($val['productLine']);
				$i ++;
				if(!empty($val['isSell'])){
					$checked = '��';
				}else{
					$checked = '��';
				}
				$str .= <<<EOT
					<tr align="center">
						<td width="5%">
							<a title="�������xml" href="?model=product_licensecheck_licensecheck&action=toxml&contractLicenseId=$val[id]">$i</a></td>
						<td width="10%">$productLine </td>
						<td width="10%">$val[softdogType] </td>
						<td width="10%">$val[amount] </td>
						<td width="10%">$val[licenseType] </td>
						<td><textarea id="licenseNodeName$i" name="sales[licenselist][$i][nodeName]" title="$val[nodeName]" rows="3" cols="20" readonly>$val[nodeName]</textarea></td>
						<td width="10%">$val[validity] </td>
						<td> $val[remark] </td>
						<td width="4%">$checked</td>
					</tr>
EOT;
			}
		} else {
			return '<tr align="center"><td colspan="9">�����������</td></tr>';
		}
		return $str;
	}

	function showlistInEdit($rows) {
		$i = 0;
		$str = "<input type='hidden' id='licensetypenum' value='" . count ( $rows ) . "' />";
//		print_r($rows);
		if ($rows) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			//license����
			$licensetypeDao = new model_product_licensetype_licensetype ();
			$licensetypeArr = $licensetypeDao->list_d ();

			$num = count ( $rows );
			foreach ( $rows as $val ) {
				//��ȡ��Ʒ�������ַ���
				$productLineStr = $this->getDatadictsStr ( $datadictArr ['CPX'], $val ['productLine'] );

				$i ++;
				$value1 = $value2 = $value3 = $value4 = $value5 = null;
				if ($val ['validity'] == "����") {
					$value1 = "selected";
				} elseif ($val ['validity'] == "һ��") {
					$value2 = "selected";
				} elseif ($val ['validity'] == "����") {
					$value3 = "selected";
				}

				if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}

				$str .= <<<EOT
					<tr align="center">
					 	<td>
							<a title="�������xml" href="?model=product_licensecheck_licensecheck&action=toxml&contractLicenseId=$val[id]">
							$i</a>
					 	</td>
					 	<td>

					 	<select
							name="sales[licenselist][$i][productLine]" id="productLine$i" class="txtshort">
							$productLineStr
							</select>
					 		</td>
					 	<td>
					 		<select name="sales[licenselist][$i][softdogType]" id="softdogType$i" class="txtshort">
					 			<option value="$val[softdogType]">$val[softdogType]</option>
					 		</select>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[licenselist][$i][amount]" id="softdogAmount$i" value="$val[amount]" size="10" maxlength="40" class="txtshort"/>
					 	</td>
					 	<td width="10%" onMouseOver="this.style.cursor='pointer';">
							<input type="text" class="txtmiddle" value="$val[licenseType]" id="licenseType$i" name="sales[licenselist][$i][licenseType]" />
							<input type="hidden" value="$val[licenseTypeIds]" id="licenseinput$i" name="sales[licenselist][$i][licenseTypeIds]" />
					 	</td>
				 		<td >
				 			<input type="hidden" id="licenseNodeId$i" name="sales[licenselist][$i][nodeId]" value="$val[nodeId]" />
				 			<textarea id="licenseNodeName$i" name="sales[licenselist][$i][nodeName]" onmouseover="this.style.cursor='pointer'" onclick="openDia($i)"  title="$val[nodeName]" rows="3" cols="20">$val[nodeName]</textarea>
			 			</td>
					 	<td width="10%">
					 		<select name="sales[licenselist][$i][validity]" id="validity$i" class="txtshort">
					 			<option value="����" $value1>����</option>
					 			<option value="һ��" $value2>һ��</option>
					 			<option value="����" $value3>����</option>
					 		</select>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[licenselist][$i][remark]" id="licenseRemark$i" value="$val[remark]" size="25" maxlength="100" class="txt"/>
					 	</td>
					 	<td width="4%">
				        	<input type="checkbox" name="sales[licenselist][$i][isSell]" id="licenseisSell$i" $checked/>
						</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onclick="mydel(this,'mylicense')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
			$str .= "<input type='hidden' id='licenselistnum' value='$num' />";
		}
		return array($i ,$str);
	}
}
?>
