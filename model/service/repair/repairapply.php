<?php
/**
 * @author huangzf
 * @Date 2011��12��1�� 14:40:13
 * @version 1.0
 * @description:ά�����뵥 Model��
 */
class model_service_repair_repairapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_repair_apply";
		$this->sql_map = "service/repair/repairapplySql.php";
		parent::__construct ();
	}
	/**
	 *
	 * �༭ҳ��ӱ���ʾģ��
	 * @param  $rows
	 */
	function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���


			foreach ( $rows as $key => $val ) {
				if ($val ['isGurantee'] == '0') {
					$isGuranteeYes = "selected";
					$isGuranteeNo = "";
				} else {
					$isGuranteeYes = "";
					$isGuranteeNo = "selected";
				}

				$repairType0 = "";
				$repairType1 = "";
				$repairType2 = "";

				if ($val ['repairType'] == '0') {
					$repairType0 = "selected";
					$repairType1 = "";
					$repairType2 = "";
				} else if ($val ['repairType'] == '1') {
					$repairType0 = "";
					$repairType1 = "selected";
					$repairType2 = "";
				} else if ($val ['repairType'] == '2') {
					$repairType0 = "";
					$repairType1 = "";
					$repairType2 = "selected";
				}
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
			                        <img align="absmiddle" src="images/icon/icon105.gif" onclick="copyItem(this);" title="������" />
			                    </td>
                                <td>
                                	<input type="hidden" name="repairapply[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                    <input type="text" name="repairapply[items][$i][productCode]" id="productCode$i" class="txtshort" value="{$val['productCode']}" />
                                    <input type="hidden" name="repairapply[items][$i][id]" id="id$i" value="{$val['id']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][productName]" id="productName$i" class="txt" value="{$val['productName']}" />
                                </td>
                                 <td>
                                    <input type="text" name="repairapply[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][serilnoName]" id="serilnoName$i" class="txt" value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][fittings]" id="fittings$i" class="txt"  value="{$val['fittings']}"/>
                                </td>

                                <td>
                                    <input type="text" name="repairapply[items][$i][troubleInfo]" id="troubleInfo$i" class="txt"  value="{$val['troubleInfo']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][place]" id="place$i" class="txt"  value="{$val['place']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][checkInfo]" id="checkInfo$i" class="txt "  value="{$val['checkInfo']}" />
                                </td>

                                <td>
                                    <select  name="repairapply[items][$i][isGurantee]" id="isGurantee$i" value="{$val['isGurantee']}" class="txtshort ">
                                        <option $isGuranteeYes value="0">��</option>
                                        <option $isGuranteeNo value="1">��</option>
                                     </select>

                                </td>
                                <td>
                                    <select   name="repairapply[items][$i][repairType]" id="repairType$i" class="txtshort "  value="{$val['repairType']}">
                                          <option $repairType0 value="0">�շ�ά��</option>
                                          <option $repairType1 value="1">����ά��</option>
                                          <option $repairType2 value="2">�ڲ�ά��</option>
                                     </select>
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][repairCost]" id="repairCost$i" class="txtshort formatMoney"  value="{$val['repairCost']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][cost]" id="cost$i" class="txtshort formatMoney"  value="{$val['cost']}" />
                                </td>
		                </tr>
EOT;

				$i ++;
			}
			return $str;
		}
	}

	/**
	 *
	 * ����ȷ�ϴӱ���ʾģ��
	 * @param  $rows
	 */
	function showItemAtQuote($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���


			foreach ( $rows as $key => $val ) {
				if ($val ['isQuote'] == 0) {
					if ($val ['isGurantee'] == '0') {
						$isGuranteeYes = "selected";
						$isGuranteeNo = "";
					} else {
						$isGuranteeYes = "";
						$isGuranteeNo = "selected";
					}
					$repairType0 = "";
					$repairType1 = "";
					$repairType2 = "";

					if ($val ['repairType'] == '0') {
						$repairType0 = "selected";
						$repairType1 = "";
						$repairType2 = "";
					} else if ($val ['repairType'] == '1') {
						$repairType0 = "";
						$repairType1 = "selected";
						$repairType2 = "";
					} else if ($val ['repairType'] == '2') {
						$repairType0 = "";
						$repairType1 = "";
						$repairType2 = "selected";
					}
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
                                	<input type="hidden" name="repairapply[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                    <input type="text" name="repairapply[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" value="{$val['productCode']}" />
                                    <input type="hidden" name="repairapply[items][$i][id]" id="id$i" value="{$val['id']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="{$val['productName']}" />
                                </td>
                                <td>
                                    <select  name="repairapply[items][$i][isGurantee]" id="isGurantee$i" value="{$val['isGurantee']}" class="txtshort ">
                                        <option $isGuranteeYes value="0">��</option>
                                        <option $isGuranteeNo value="1">��</option>
                                     </select>

                                </td>
                                <td>
                                    <select   name="repairapply[items][$i][repairType]" id="repairType$i" class="txtshort "  value="{$val['repairType']}">
                                          <option $repairType0 value="0">�շ�ά��</option>
                                          <option $repairType1 value="1">����ά��</option>
                                          <option $repairType2 value="2">�ڲ�ά��</option>
                                     </select>
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][repairCost]" id="repairCost$i" class="txtshort formatMoney"  value="{$val['repairCost']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][cost]" id="cost$i" class="txtshort formatMoney"  value="{$val['cost']}" />
                                </td>
                                 <td>
                                    <input type="text" name="repairapply[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][pattern]" id="pattern" class="readOnlyTxtShort" value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][unitName]" id="unitNames$i" class="readOnlyTxtShort" value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyTxtNormal" value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][fittings]" id="fittings$i" class="readOnlyTxtNormal"  value="{$val['fittings']}"/>
                                </td>

                                <td>
                                    <input type="text" name="repairapply[items][$i][troubleInfo]" id="troubleInfo$i" class="readOnlyTxtNormal"  value="{$val['troubleInfo']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repairapply[items][$i][checkInfo]" id="checkInfo$i" class="readOnlyTxtNormal "  value="{$val['checkInfo']}" />
                                </td>

		                </tr>
EOT;

					$i ++;
				}
			}
			return $str;
		}
	}

	/**
	 *
	 * �鿴ҳ��ӱ���ʾģ��
	 * @param  $rows
	 */
	function showItemAtView($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				if ($val [isGurantee] == "0") {
					$val [isGurantee] = "��";
				} else {
					$val [isGurantee] = "��";
				}

				if ($val [repairType] == "0") {
					$val [repairType] = "�շ�ά��";
				} else if ($val [repairType] == "1") {
					$val [repairType] = "����ά��";
				} else if ($val [repairType] == "2") {
					$val [repairType] = "�ڲ�ά��";
				}
				$seNum = $i + 1;
				$str .= <<<EOT
						<tr align="center" >
                               <td>
                                    $seNum
                               </td>
                                <td>
									$val[productCode]

                               </td>
                               <td>
									$val[productName]
                               </td>
                                 <td>
                                    $val[productType]
                               </td>
                               <td>
                                    $val[pattern]
                               </td>
                               <td>
									$val[unitName]

                               </td>
                               <td>
									$val[serilnoName]
                               </td>
                               <td>
                                    $val[fittings]
                               </td>

                               <td>
									$val[troubleInfo]
                               </td>
                               <td>
                                    $val[checkInfo]
                               </td>
                                <td>
                                	$val[isGurantee]
                               </td>
                               <td>
                                    $val[repairType]
                               </td>
                               <td class="formatMoney">
									$val[repairCost]
                               </td>
                               <td class="formatMoney">
									$val[cost]
                               </td>
                               <td class="formatMoney">
									$val[reduceCost]
                               </td>
		                </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}

	/**
	 *
	 * ���ά�޷��ü������뵥�ӱ���ʾģ��
	 * @param  $rows
	 */
	function showItemAtReduce($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
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
                                    <input type="text" name="reduceapply[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" readOnly value="{$val['productCode']}" />
                                    <input type="hidden" name="reduceapply[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                                    <input type="hidden" name="reduceapply[items][$i][applyItemId]" id="applyItemId$i" value="{$val['id']}"  />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][productName]" id="productName$i" class="readOnlyText" readOnly value="{$val['productName']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" readOnly value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" readOnly value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" readOnly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyText" readOnly value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][fittings]" id="fittings$i" class="readOnlyText" readOnly value="{$val['fittings']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][cost]" id="cost$i" class="readOnlyTxtShort formatMoney" readOnly value="{$val['cost']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][reduceCost]" id="reduceCost$i" class="txtshort formatMoney" value="{$val['reduceCost']}" />
                                </td>
		                </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}

	/**
	 *
	 * ���ά�޷��ü������뵥�ӱ���ʾģ��
	 * @param  $rows
	 */
	function showItemAtChange($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
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
                                    <input type="text" name="changeapply[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" readOnly value="{$val['productCode']}" />
                                    <input type="hidden" name="changeapply[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                                    <input type="hidden" name="changeapply[items][$i][relDocItemId]" id="relDocItemId$i" value="{$val['id']}"  />
                                </td>
                                <td>
                                    <input type="text" name="changeapply[items][$i][productName]" id="productName$i" class="readOnlyText" readOnly value="{$val['productName']}" />
                                </td>
                                <td>
                                    <input type="text" name="changeapply[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" readOnly value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="changeapply[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" readOnly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="changeapply[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyText" readOnly value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="changeapply[items][$i][remark]" id="remark$i" class="txt"  value="{$val['remark']}" />
                                </td>
		                </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}

	/*--------------------------------------------ҵ�����--------------------------------------------*/

	/**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$sql = "SELECT MAX(docDate) as applyDate from ".$this->tbl_name;
				$applyDateArr = $this->_db->get_one($sql);
				$applyDate = $applyDateArr['applyDate'];
				$thisDate = day_date;
				$codeDao = new model_common_codeRule ();
				if( $applyDate!= $thisDate ){
					$object ['docCode'] = $codeDao->accessorderCode ( "oa_service_repair_apply","DLWX",$object['prov'],"ά�����뵥",true );
				}else{
					$object ['docCode'] = $codeDao->accessorderCode ( "oa_service_repair_apply","DLWX",$object['prov'],"ά�����뵥",false );
				}
				$id = parent::add_d ( $object, true );
				$applyitemDao = new model_service_repair_applyitem ();
				$itemsArr = $this->setItemMainId ( "mainId", $id, $object ['items'] );

				$itemsObj = $applyitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$editResult = parent::edit_d ( $object, true );
				$applyitemDao = new model_service_repair_applyitem ();
				$itemsArr = $this->setItemMainId ( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $applyitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $editResult;
			} else

			{
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$applyitemDao = new model_service_repair_applyitem ();
		$applyitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $applyitemDao->listBySqlId ();
		return $object;
	}

	/**
	 *
	 * �������ձ�����Ϣ
	 * @param  $object
	 */
	function quote_d($object) {
		try {
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$subCost = 0;
				$applyitemDao = new model_service_repair_applyitem ();
				foreach ( $object ['items'] as $key => $value ) {
					if (! isset ( $value ['isDelTag'] )) {
						$value ['isQuote'] = 1;
						$applyitemDao->updateById ( $value );
					}
				}
				
				$applyObj=$this->get_d($object['id']);
				foreach ($applyObj['items'] as $key=>$value){
					$subCost=$subCost+$value['cost'];
				}
				
				$object['subCost'] = $subCost;
				$object ['docStatus'] = "ZXZ";
				$this->updateById ( $object );
				$this->commit_d ();
				return true;
			} else {
				throw new Excetion ( "û��ȷ���嵥!" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * ���ù����嵥�Ĵӱ������id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			if (! isset ( $value ['id'] )) {
				$value [$mainIdName] = $mainIdValue;
				$value ['isDetect'] = 0;
				$value ['isShip'] = 0;
				$value ['isQuote'] = 0;
			}
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}
}
?>