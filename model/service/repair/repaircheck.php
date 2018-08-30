<?php

/**
 * @author huangzf
 * @Date 2011��12��2�� 10:22:13
 * @version 1.0
 * @description:���ά������ Model��
 */
class model_service_repair_repaircheck extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_repair_check";
		$this->sql_map = "service/repair/repaircheckSql.php";
		parent::__construct ();
	}

	/*
	 *  ajax ����ؼ�
	 */
	function stateBack_d($id) {
		try {
			$sql = "update oa_service_repair_check set docStatus = 'DHCJ' where id = $id ";
			$this->_db->query($sql);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	}


	/**
	 * �´�������ʱ�嵥ģ��
	 */
	function showItemAtAdd($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				if($val['isDetect']=="0"){
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
                                    <input type="text" name="repaircheck[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" value="{$val['productCode']}" />
                                    <input type="hidden" name="repaircheck[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                    <input type="hidden" name="repaircheck[items][$i][applyItemId]" id="applyItemId$i" value="{$val['id']}" />
                                </td>
                                <td>
                                    <input type="text" name="repaircheck[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="{$val['productName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repaircheck[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="repaircheck[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="repaircheck[items][$i][unitName]" id="unitNames$i" class="readOnlyTxtShort" value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repaircheck[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyTxtNormal"  value="{$val['serilnoName']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repaircheck[items][$i][fittings]" id="fittings$i" class="readOnlyTxtNormal"  value="{$val['fittings']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repaircheck[items][$i][troubleInfo]" id="subCost$i" class="readOnlyTxtNormal"  value="{$val['troubleInfo']}" />
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
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$codeDao = new model_common_codeRule ();
				$itemsArr = array (); //ƴװ��ɺ�ļ�������嵥
				foreach ( $object ['items'] as $key => $itemObj ) {
					$itemObj ['docCode'] = $codeDao->stockCode ( "oa_service_repair_check", "JCWX" );
					$itemObj ['docStatus'] = "WJC";
					$itemObj ['isAgree'] = "2";
					$itemObj ['issuedUserName'] = $object ['issuedUserName'];
					$itemObj ['issuedUserCode'] = $object ['issuedUserCode'];
					$itemObj ['issuedTime'] = $object ['issuedTime'];
					$itemObj ['applyDocCode'] = $object ['applyDocCode'];
					$itemObj ['applyDocId'] = $object ['applyDocId'];
					$itemObj ['repairDeptName'] = $object ['repairDeptName'];
					$itemObj ['repairDeptCode'] = $object ['repairDeptCode'];
					$itemObj ['repairUserName'] = $object ['repairUserName'];
					$itemObj ['repairUserCode'] = $object ['repairUserCode'];
					$itemObj ['remark'] = $object ['remark'];
					array_push ( $itemsArr, $itemObj );

					//��������´��ı�״̬
					$repairApplyItemDao = new model_service_repair_applyitem ();
					$repairApplyItem=array("isDetect"=>1,"id"=>$itemObj ['applyItemId']);
					$repairApplyItemDao->updateById ( $repairApplyItem );
				}
				//������������
				$id = $this->addBatch_d ( $itemsArr );
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
	 * ��ȡά��������Ϣ
	 */
	function getRepairItems_d($id) {
		$repairapplyDao = new model_service_repair_repairapply ();
		$repairapply = $repairapplyDao->get_d ( $id );
		return $repairapply;
	}

	/**
	 *
	 * ����ȷ�Ϸ�����Ϣ
	 * @param  $object
	 */
	function feedback_d($object) {
		try {
			$this->start_d ();
			
			//���±�������Ϣ
			$this->updateById($object);
			//�ύʱִ��
			if($object['docStatus'] == "YJC"){
				/*s:--��д���뵥�嵥��⴦����--*/
				$applyItemId = $object ['applyItemId'];
				$repairApplyItemDao = new model_service_repair_applyitem ();
				$repairApplyItem = $repairApplyItemDao->get_d ( $applyItemId );
				$repairApplyItem ['checkInfo'] = $repairApplyItem ['checkInfo'] . " &nbsp;�����:" . $object ['checkInfo'];
				//�Ƿ���Ϊ����,����ǣ������뵥��Ϊ����
				if($object['isByHuman'] == 1){
					$repairApplyItem ['isGurantee'] = 0;
				}
				$repairApplyItemDao->updateById ( $repairApplyItem );
				/*e:--��д���뵥�嵥��⴦����--*/
			}

			//���¸���������ϵ
			$this->updateObjWithFile($object['id']);
			
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->start_d ();
			$applycheckobj = $this->get_d ( $ids );
			$repairApplyItemDao = new model_service_repair_applyitem ();
			$repairApplyItem=array("id"=>$applycheckobj ['applyItemId'],"isDetect"=>0);
			$repairApplyItemDao->updateById ( $repairApplyItem );
			$this->deletes ( $ids );
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ���������޸ļ�⴦����
	 */
	function editCheckInfo_d($object) {
		try {
			$this->start_d();
			
			$id = $object['id'];
			$oldObj = parent::get_d($id);
			parent::edit_d($object, true);
	
			$this->commit_d();
	
			//���²�����־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object, '�޸ļ��ά�޷���');
			//�ʼ�֪ͨ
			if($object['mailInfo']['issend'] == 'y' && !empty($object['mailInfo']['TO_ID'])){
				$this->mailDeal_d('editCheckInfo',$object['mailInfo']['TO_ID'],array('id'=>$id));
			}
			
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}