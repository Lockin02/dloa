<?php
/**
 * @author huangzf
 * @Date 2011��12��3�� 10:11:04
 * @version 1.0
 * @description:ά�ޱ����걨�� Model��
 */
class model_service_repair_repairquote extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_service_repair_quote";
		$this->sql_map = "service/repair/repairquoteSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * ���������걨��ҳ��ӱ���ʾģ��
	 * @param  $rows
	 */
	function showItemAtAdd($rows) {
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
                                    <input type="text" name="repairquote[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" readonly value="{$val['productCode']}" />
                                    <input type="hidden" name="repairquote[items][$i][id]" id="id$i" value="{$val['id']}" />
                                    <input type="hidden" name="repairquote[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readonly value="{$val['productName']}" />
                                </td>
                                 <td>
                                    <input type="text" name="repairquote[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" readonly value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][pattern]" id="pattern" class="readOnlyTxtShort" readonly value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][unitName]" id="unitNames$i" class="readOnlyTxtShort" readonly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyTxtNormal" readonly value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][fittings]" id="fittings$i" class="readOnlyTxtNormal" readonly  value="{$val['fittings']}"/>
                                </td>

                                <td>
                                    <input type="text" name="repairquote[items][$i][troubleInfo]" id="troubleInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['troubleInfo']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][checkInfo]" id="checkInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['checkInfo']}" />
                                </td>
                                <td>
                                    <select  name="repairquote[items][$i][isGurantee]" id="isGurantee$i" value="{$val['isGurantee']}" class="txtshort ">
                                        <option $isGuranteeYes value="0">��</option>
                                        <option $isGuranteeNo value="1">��</option>
                                     </select>

                                </td>
                                <td>
                                    <select   name="repairquote[items][$i][repairType]" id="repairType$i" class="txtshort "  value="{$val['repairType']}">
                                          <option $repairType0 value="0">�շ�ά��</option>
                                          <option $repairType1 value="1">����ά��</option>
                                          <option $repairType2 value="2">�ڲ�ά��</option>
                                     </select>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][repairCost]" id="repairCost$i" class="txtshort formatMoney"  value="{$val['repairCost']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][cost]" id="cost$i" class="txtshort formatMoney"  value="{$val['cost']}" />
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
	 * �༭�����걨��ҳ��ӱ���ʾģ��
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
                                    <input type="text" name="repairquote[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" readonly value="{$val['productCode']}" />
                                    <input type="hidden" name="repairquote[items][$i][id]" id="id$i" value="{$val['id']}" />
                                    <input type="hidden" name="repairquote[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readonly value="{$val['productName']}" />
                                </td>
                                 <td>
                                    <input type="text" name="repairquote[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" readonly value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][pattern]" id="pattern" class="readOnlyTxtShort" readonly value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][unitName]" id="unitNames$i" class="readOnlyTxtShort" readonly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyTxtNormal" readonly value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][fittings]" id="fittings$i" class="readOnlyTxtNormal" readonly  value="{$val['fittings']}"/>
                                </td>

                                <td>
                                    <input type="text" name="repairquote[items][$i][troubleInfo]" id="troubleInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['troubleInfo']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][checkInfo]" id="checkInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['checkInfo']}" />
                                </td>
                                <td>
                                    <select  name="repairquote[items][$i][isGurantee]" id="isGurantee$i" value="{$val['isGurantee']}" class="txtshort ">
                                        <option $isGuranteeYes value="0">��</option>
                                        <option $isGuranteeNo value="1">��</option>
                                     </select>

                                </td>
                                <td>
                                    <select   name="repairquote[items][$i][repairType]" id="repairType$i" class="txtshort "  value="{$val['repairType']}">
                                          <option $repairType0 value="0">�շ�ά��</option>
                                          <option $repairType1 value="1">����ά��</option>
                                          <option $repairType2 value="2">�ڲ�ά��</option>
                                     </select>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][repairCost]" id="repairCost$i" class="txtshort formatMoney"  value="{$val['repairCost']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][cost]" id="cost$i" class="txtshort formatMoney"  value="{$val['cost']}" />
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
									$val[place]
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
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode ( "oa_service_repair_quote", "WXBJ" );
				$id = parent::add_d ( $object, true );
				
				$quoteitemDao = new model_service_repair_applyitem ();
				$repairapplyDao = new model_service_repair_repairapply ();
				
				foreach ( $object ['items'] as $key => $value ) {
					//���Ҹ���֮ǰ�嵥��ά�޷���
					$oldquoteitem = $quoteitemDao->get_d ( $value ['id'] );
					
					//����֮ǰ��֮����嵥��ά�޷���֮��
					$changeCost = $value ['cost'] - $oldquoteitem ['cost'];
					
					//�����嵥��Ӧ�����뵥
					$repairObj = $repairapplyDao->get_d ( $oldquoteitem ['mainId'] );
					
					//�ѻ�ȡ���޸�֮��Ľ��ֵ�ۼӵ���Ӧ���뵥��ά�޷��ò��������뵥
					$repairObj ['subCost'] = $repairObj ['subCost'] + $changeCost;
					$repairapplyDao->updateById ( $repairObj );
					
					if (! isset ( $value ['isDelTag'] )) { //ɾ�� ���ύ���۵�
						$value ['quoteId'] = $id;
						$quoteitemDao->updateById ( $value );
					}
				}
				$this->commit_d ();
				return $id;
			} else {
				throw new Excetion ( "û��ȷ���嵥!" );
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
			
			$id = parent::edit_d ( $object, true );
			$quoteitemDao = new model_service_repair_applyitem ();
			$repairapplyDao = new model_service_repair_repairapply ();
			
			foreach ( $object ['items'] as $key => $value ) {
				//���Ҹ���֮ǰ�嵥��ά�޷���
				$oldquoteitem = $quoteitemDao->get_d ( $value ['id'] );
				$changeCost = $value ['cost'] - $oldquoteitem ['cost'];
				
				//�����嵥��Ӧ�����뵥
				$repairObj = $repairapplyDao->get_d ( $oldquoteitem ['mainId'] );
				$repairObj ['subCost'] = $repairObj ['subCost'] + $changeCost;
				$repairapplyDao->updateById ( $repairObj );
				if (isset ( $value ['isDelTag'] )) { //ɾ�� ���ύ���۵�
					$value ['quoteId'] = null;
					$quoteitemDao->updateById ( $value );
				} else {
					$quoteitemDao->updateById ( $value );
				}
			}
			$this->commit_d ();
			return true;
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
		$quoteitemDao = new model_service_repair_applyitem ();
		$quoteitemDao->searchArr ['quoteId'] = $id;
		$object ['items'] = $quoteitemDao->listBySqlId ();
		return $object;
	}
	
	/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->deletes ( $ids );
			//���嵥��Ϣ�ı��۵�id�ÿ�
			$quoteitemDao = new model_service_repair_applyitem ();
			$quoteitemDao->query ( "update oa_service_repair_applyitem set quoteId=null where quoteId in($ids)" );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	
	/**
	 * ��ȡά��������Ϣ
	 */
	function getRepairItems_d($id) {
		$repairapplyDao = new model_service_repair_repairapply ();
		$repairapply = $repairapplyDao->get_d ( $id );
		return $repairapply;
	}
	
	/**
	 * 
	 * ��ȡ���������嵥������
	 */
	function getItemMaxMoney($id) {
		$sql="select max(cost) as maxCost from oa_service_repair_applyitem where quoteId='$id'";
		$result= $this->findSql($sql);//
		return $result[0]['maxCost'];
	}
}
?>