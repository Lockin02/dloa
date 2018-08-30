<?php
/**
 * @author huangzf
 * @Date 2011��12��3�� 10:33:49
 * @version 1.0
 * @description:�豸�������뵥 Model��
 */
class model_service_change_changeapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_change_apply";
		$this->sql_map = "service/change/changeapplySql.php";
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
                                    <input type="text" name="changeapply[items][$i][productCode]" id="productCode$i" class="readOnlyTxtNormal" readOnly value="{$val['productCode']}" />
                                    <input type="hidden" name="changeapply[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                    <input type="hidden" name="changeapply[items][$i][id]" id="id$i" value="{$val['id']}"  />
                                    <input type="hidden" name="reduceapply[items][$i][relDocItemId]" id="relDocItemId$i" value="{$val['relDocItemId']}"  />
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
                                    <input type="text" name="changeapply[items][$i][remark]" id="remark$i" class="txt" value="{$val['remark']}" />
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
                                    $val[pattern]
                               </td>
                               <td>
                                    $val[unitName]
                               </td>
                               <td>
									$val[serilnoName]
                               </td>
                               <td>
                                    $val[remark]
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
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode ( "oa_service_change_apply", "WXGH" );
				$id = parent::add_d ( $object, true );
				$changeitemDao = new model_service_change_changeitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $id, $object ['items'] );
				$itemsObj = $changeitemDao->saveDelBatch ( $itemsArr );
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
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$editResult = parent::edit_d ( $object, true );
				$changeitemDao = new model_service_change_changeitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $changeitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return true;
			} else {
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
		$changeitemDao = new model_service_change_changeitem ();
		$changeitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $changeitemDao->listBySqlId ();
		return $object;

	}

		/**
	 * ���ù����ӱ�����뵥id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}
}
?>