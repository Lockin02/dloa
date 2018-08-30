<?php
/**
 * @author huangzf
 * @Date 2012��5��12�� ������ 14:05:49
 * @version 1.0
 * @description:�������� Model��
 */
class model_produce_task_producetask extends model_base {
	function __construct() {
		$this->tbl_name = "oa_produce_producetask";
		$this->sql_map = "produce/task/producetaskSql.php";
		parent::__construct ();
	}
	
	/**
	 * ����״ֵ̬�Ҷ�Ӧ����
	 */
	function getStatusVal_d($docStatus) {
		switch ($docStatus) {
			case '0' :
				$statusVal = 'δ����';
				break;
			case '1' :
				$statusVal = '�ѽ���';
				break;
			case '2' :
				$statusVal = '���ƶ��ƻ�';
				break;
			case '3' :
				$statusVal = '�ر�';
				break;
			case '4' :
				$statusVal = '�����';
				break;
			case '5' :
				$statusVal = '�ѷ���';
				break;
			default :
				$statusVal = '--';
				break;
		}
		return $statusVal;
	}
	
	/**
	 * @description �´���������,�嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAtIssued($obj) {
		if ($obj ['items']) {
			$i = 0; // �嵥��¼���
			$str = ""; // ���ص�ģ���ַ���
			foreach ( $obj ['items'] as $key => $val ) {
				$notExeNum = $val ['produceNum'] - $val ['exeNum'];
				if ($notExeNum > 0) {
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
							<input type="text" name="producetask[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" readOnly value="{$val['productCode']}" />
							<input type="hidden" name="producetask[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
							<input type="hidden" name="producetask[items][$i][applyDocId]" id="applyDocId$i" value="{$val['mainId']}"  />
							<input type="hidden" name="producetask[items][$i][applyDocCode]" id="applyDocCode$i" value="{$obj['docCode']}"  />
							<input type="hidden" name="producetask[items][$i][applyDocItemId]" id="applyDocItemId$i" value="{$val['id']}"  />
							<input type="hidden" name="producetask[items][$i][goodsConfigId]" id="goodsConfigId$i" value="{$val['goodsConfigId']}"  />
							<input type="hidden" name="producetask[items][$i][licenseConfigId]" id="licenseConfigId$i" value="{$val['licenseConfigId']}"  />
						</td>
						<td>
							<input type="text" name="producetask[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readOnly value="{$val['productName']}" />
						</td>
						<td>
							<input type="text" name="producetask[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" readOnly value="{$val['pattern']}" />
						</td>
						<td>
							<input type="text" name="producetask[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" readOnly value="{$val['unitName']}" />
						</td>
						<td>
							{$val['produceNum']}
						</td>
						<td>
							{$val['exeNum']}
						</td>
						<td>
							<input type="text" name="producetask[items][$i][taskNum]" id="taskNum$i"  class="txtshort" value="$notExeNum" />
							<input type="hidden" id="notExeNum$i"  value="$notExeNum" />
						</td>
						<td>
							<input type="text"  class="readOnlyTxtItem" readOnly  value="{$val['planEndDate']}" />
						</td>
						<td>
							<input type="text" name="producetask[items][$i][planStartDate]" id="planStartDate$i" class="txtshort" onblur="setEstimateInfo($i)" onfocus="WdatePicker()"  />
						</td>
						<td>
							<input type="text" name="producetask[items][$i][planEndDate]"  id="planEndDate$i" class="txtshort" onblur="setEstimateInfo($i)" onfocus="WdatePicker()"/>
						</td>
						<td>
							<input type="text" name="producetask[items][$i][estimateDay]" id="estimateDay$i" class="txtshort" />
						</td>
						<td>
							<input type="text" name="producetask[items][$i][estimateHour]"  id="estimateHour$i" class="txtshort"  />
						</td>
						<td>
							<input type="text" name="producetask[items][$i][remark]" id="storageNum$i" class="txt" value="{$val['storageNum']}" />
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
	 * ��дadd
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			
			// ��ȡ������˾����
			$object ['formBelong'] = $_SESSION ['USER_COM'];
			$object ['formBelongName'] = $_SESSION ['USER_COM_NAME'];
			$object ['businessBelong'] = $_SESSION ['USER_COM'];
			$object ['businessBelongName'] = $_SESSION ['USER_COM_NAME'];
			
			$codeDao = new model_common_codeRule ();
			$object ['fileNo'] = $codeDao->setCommonCode ( $this->tbl_name . '_fileNo', 'DLJL/JF020-', 3, '���������ļ����', 'SCRWWJBH' ); // �ļ����
			$object ['docCode'] = $this->setDocCode_d (); // ���ݱ��
			$object ['productionBatch'] = $this->setBatch_d (); // ��������
			
			$dictDao = new model_system_datadict_datadict ();
			$object ['purpose'] = $dictDao->getDataNameByCode ( $object ['purposeCode'] ); // ������;
			$object ['technology'] = $dictDao->getDataNameByCode ( $object ['technologyCode'] ); // ��������
			
			$applyItemDao = new model_produce_apply_produceapplyitem ();
			
			$applyDao = new model_produce_apply_produceapply ();
			$object ['weekly'] = $applyDao->getWeekly_d ( $object ['docDate'] ); // ��ȡ��ǰ�·��ܴ�
			
			$id = parent::add_d ( $object, true );
			
			if ($id) {
				$applyDao->updateById ( array (
						'id' => $object ['applyDocId'],
						'docStatus' => 1 
				) ); // �������뵥״̬Ϊ�����´�
				                                                                               
				// �ӱ���
				if (is_array ( $object ['configPro'] )) {
					$configProDao = new model_produce_task_configproduct ();
					$taskconfigDao = new model_produce_task_taskconfig ();
					$taskconfigitemDao = new model_produce_task_taskconfigitem ();
					$processequDao = new model_produce_task_processequ ();
					$taskNum = 0;//������������
					foreach ( $object ['configPro'] as $aKey => $aVal ) {
						$taskNum += $aVal ['num'];
						$aVal ['taskId'] = $id;
						$parentId = $configProDao->add_d ( $aVal );
						if ($parentId) {
							$applyItemObj = $applyItemDao->get_d ( $aVal ['planId'] );
							$exeNum = $applyItemObj ['exeNum'] + $aVal ['num'];
							$applyItemDao->updateById ( array (
									'id' => $applyItemObj ['id'],
									'exeNum' => $exeNum 
							) ); // �������뵥�嵥�е����´�����
							$applyDao->dealDocStatus_d ( $object ['applyDocId'] ); // ������״̬
						}
						// ������
						if (is_array ( $object ['configPro'] [$aKey] ['process'] )) {
							foreach ( $object ['configPro'] [$aKey] ['process'] as $pKey => $pVal ) {
								$pVal ['parentId'] = $parentId;
								$processequDao->add_d ( $pVal );
							}
						}
						
						// ���ô���
						if (is_array ( $object ['thead'] [$aKey] ) && is_array ( $object ['info'] [$aKey] )) {
							$theadArr = array (); // ��ͷ����
							foreach ( $object ['thead'] [$aKey] as $key => $val ) {
								array_push ( $theadArr, $val ); // �����������飬��֤�±겻���
							}
							$tbodyArr = array (); // ����������
							$i = 0;
							foreach ( $object ['info'] [$aKey] as $key => $val ) {
								unset ( $val ['rowNum_'] );
								$tbodyArr [$i] = array ();
								foreach ( $val as $k => $v ) {
									array_push ( $tbodyArr [$i], $v ); // �����������飬��֤�±겻���
								}
								$i ++;
							}
							
							foreach ( $theadArr as $key => $val ) {
								$addData = array ();
								$addData ['taskId'] = $id;
								$addData ['colName'] = $val;
								$addData ['colCode'] = 'column' . $key;
								$addData ['configId'] = $aVal ['productId'];
								$addData ['configCode'] = $aVal ['productCode'];
								$addData ['configName'] = $aVal ['productName'];
								$taskconfigId = $taskconfigDao->add_d ( $addData );
								
								if ($taskconfigId) { // ������
									foreach ( $tbodyArr as $k => $v ) {
										$itemAddData = array ();
										$itemAddData ['parentId'] = $taskconfigId;
										$itemAddData ['colName'] = $val;
										$itemAddData ['colCode'] = 'column' . $key;
										$itemAddData ['colContent'] = $v [$key];
										$taskconfigitemDao->add_d ( $itemAddData );
									}
								}
							}
						}
						
						if (isset ( $object ['configPro'] [$aKey] ['classify'] ) && is_array ( $object ['configPro'] [$aKey] ['classify'] )) {
							$VALUES = array ();
							$SQL = "INSERT INTO `oa_produce_producetask_classify` (`produceTaskId`,`code`,`productId`,`proType`,`productName`,`productCode`,`pattern`,`unitName`,`num`) VALUES ";
							foreach ( $object ['configPro'] [$aKey] ['classify'] as $val ) {
								$VALUES [] = "('" . $id . "','" . $object ['configPro'] [$aKey] ['productCode'] . "','" . $val ['productId'] . "','" . $val ['proType'] . "','" . $val ['productName'] . "','" . $val ['productCode'] . "','" . $val ['pattern'] . "','" . $val ['unitName'] . "','" . $val ['num'] . "')";
							}
							$this->_db->query ( $SQL . implode ( ",", $VALUES ) );
						}
					}
					$this->updateById(array('id' => $id,'taskNum' => $taskNum));
				}
			}
			
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * ����������
	 */
	function setDocCode_d() {
		$docCode = 'SCRW' . (date ( 'Ymd' )) . (sprintf ( "%03d", rand ( 0, 999 ) )); // 3λ�������׺
		$count = $this->findCount ( array (
				'docCode' => $docCode 
		) );
		if ($count > 0) {
			return $this->setDocCode_d ();
		} else {
			return $docCode;
		}
	}
	
	/**
	 * �����������κ�
	 * DL + ��λ����ݵ�ʮ������ + ���� + 3λ��ˮ��
	 */
	function setBatch_d() {
		$codeDao = new model_common_codeRule ();
		$prefix = sprintf ( "DL%X%s", date ( 'y' ), date ( 'md' ) );
		return $codeDao->setCommonCode ( $this->tbl_name . '_batch', $prefix, 3, '����������������', 'SCRWSCPC' );
	}
	
	/**
	 * �޸�
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			$this->updateById ( $object );
			
			// �����������Ϣ
			$taskactorDao = new model_produce_task_taskactor ();
			$taskactorDao->delete ( array (
					"taskId" => $object ['id'] 
			) );
			if (! empty ( $object ['actorIds'] )) {
				$actorIdArr = explode ( ",", $object ['actorIds'] );
				$actorNameArr = explode ( ",", $object ['actorNames'] );
				
				foreach ( $actorIdArr as $key => $value ) {
					$tempObj = array (
							"taskId" => $object ['id'],
							"actUserCode" => $actorIdArr [$key],
							"actUserName" => $actorNameArr [$key] 
					);
					$taskactorDao->add_d ( $tempObj );
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
	 * �������
	 */
	function change_d($object) {
		try {
			$this->start_d ();
			// �������
			$object ['oldId'] = $object ['id'];
			$changeLogDao = new model_common_changeLog ( 'producetask', false );
			$changeLogDao->addLog ( $object );
			
			// �������뵥�� ���´�����
			$applyItemDao = new model_produce_apply_produceapplyitem ();
			$lastObj = $this->get_d ( $object ['id'] );
			$sql = "update oa_produce_produceapply_item set exeNum=exeNum+" . $object ['taskNum'] . "-" . $lastObj ['taskNum'] . " where id=" . $lastObj ['applyDocItemId'];
			$applyItemDao->query ( $sql );
			
			$editResult = parent::edit_d ( $object, true );
			
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * ���Ȼ㱨����
	 * @param $object
	 */
	function report($object) {
		try {
			$this->start_d ();
			$this->updateById ( $object );
			// ͬ���������뵥�Ľ�����Ϣ
			$applyItemDao = new model_produce_apply_produceapplyitem ();
			$applyItemObj = array (
					"id" => $object ['applyDocItemId'],
					"qualityNum" => $object ['qualityNum'],
					"qualifiedNum" => $object ['qualifiedNum'],
					"stockNum" => $object ['stockNum'] 
			);
			
			$applyItemDao->updateProcess ( $applyItemObj );
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * ����id������״̬(��������ı�״̬������������)
	 */
	function dealDocStatus_d($id) {
		$obj = $this->get_d ( $id );
		$planDao = new model_produce_plan_produceplan ();
		$planObjs = $planDao->findAll ( array (
				'taskId' => $id,
				'isCancel' => 0 
		) );
		if (is_array ( $planObjs )) {
			$taskNum = 0;
			foreach ( $planObjs as $key => $val ) {
				$taskNum += $val ['planNum'];
			}
			if ($obj ['taskNum'] <= $taskNum) {
				$this->updateById ( array (
						'id' => $id,
						'docStatus' => 4 
				) );
			} else if ($taskNum > 0 && $obj ['taskNum'] > $taskNum) {
				$this->updateById ( array (
						'id' => $id,
						'docStatus' => 2 
				) );
			} else {
				$this->updateById ( array (
						'id' => $id,
						'docStatus' => 1 
				) );
			}
		} else {
			$this->updateById ( array (
					'id' => $id,
					'docStatus' => 1 
			) );
		}
	}
	function get_product($id, $code = null) {
		if (! empty ( $code )) {
			$SQL = "SELECT * FROM `oa_produce_producetask_classify` WHERE `produceTaskId` = " . $id . " AND `code`='" . $code . "'";
		} else {
			$SQL = "SELECT * FROM `oa_produce_producetask_classify` WHERE `produceTaskId` = " . $id;
		}
		$query = $this->_db->query ( $SQL );
		$datas = array ();
		while ( ($rs = $this->_db->fetch_array ( $query )) != false ) {
			$datas [] = $rs;
		}
		
		return $datas;
	}
	function get_templateConf($id) {
		$SQL = "SELECT * FROM `oa_manufacture_produceconfiguration` WHERE `id` = " . $id;
		$query = $this->_db->query ( $SQL );
		$datas = $this->_db->fetch_array ( $query );
		return $datas;
	}
	
	/**
	 * ��ȡ��Ʒ����
	 * $post ����ǰ̨��ȡ�Ĳ�����Ϣ
	 */
	function get_classify_produce($post) {
		// ��������
		if (! empty ( $post ['productCode'] )) { // ���ϱ���
			$condition = " AND c.productCode like concat('%','" . util_jsonUtil::iconvUTF2GB ( $post ['productCode'] ) . "','%')";
		}
		if (! empty ( $post ['codes'] )) { // ���ϱ���
			$condition = " AND c.code in(" . util_jsonUtil::strBuild ( util_jsonUtil::iconvUTF2GB ( $post ['codes'] ) ) . ")";
		}
		if (! empty ( $post ['productName'] )) { // ��������
			$condition = " AND c.productName like concat('%','" . util_jsonUtil::iconvUTF2GB ( $post ['productName'] ) . "','%')";
		}
		if (! empty ( $post ['taskCode'] )) { // ���񵥺�
			$condition = " AND n.docCode like concat('%','" . util_jsonUtil::iconvUTF2GB ( $post ['planCode'] ) . "','%')";
		}
		if (! empty ( $post ['relDocCode'] )) { // ��ͬ���
			$condition = " AND p.relDocCode like concat('%','" . util_jsonUtil::iconvUTF2GB ( $post ['relDocCode'] ) . "','%')";
		}
		if (! empty ( $post ['productionBatch'] )) { // �������κ�
			$condition = " AND p.productionBatch like concat('%','" . util_jsonUtil::iconvUTF2GB ( $post ['productionBatch'] ) . "','%')";
		}
		if (! empty ( $post ['productCode,productName,taskCode,relDocCode,productionBatch'] )) { // ����
			$search = util_jsonUtil::iconvUTF2GB ( $post ['productCode,productName,taskCode,relDocCode,productionBatch'] );
			$condition = " AND (c.productCode like concat('%','" . $search . "','%') OR " . "c.productName like concat('%','" . $search . "','%') OR " . "p.docCode like concat('%','" . $search . "','%') OR " . "p.relDocCode like concat('%','" . $search . "','%') OR " . "p.productionBatch like concat('%','" . $search . "','%'))";
		}
		$sql = "SELECT p.relDocCode,p.productionBatch,c.* FROM oa_produce_producetask_classify as c " . "INNER JOIN oa_produce_producetask as p ON c.produceTaskId = p.id " . "WHERE c.produceTaskId in (" . $post ['ids'] . ") $condition";
		return $this->_db->getArray ( $sql );
	}
	
	/**
	 * ��ȡ������ϸ
	 * $productCode ���ϱ��
	 * $taskId ����id
	 */
	function get_produceTask($productCode, $taskId) {
		$sql = "SELECT p.id AS taskId,p.docCode,p.relDocCode,p.productionBatch,c.* FROM oa_produce_producetask_classify as c " . "INNER JOIN oa_produce_producetask as p ON c.produceTaskId = p.id " . "WHERE c.produceTaskId IN (" . $taskId . ") AND c.productCode IN (" . util_jsonUtil::strBuild ( $productCode ) . ") " . "GROUP BY p.relDocCode,p.id,c.productId";
		return $this->_db->getArray ( $sql );
	}
	
	/**
	 * ��ȡ������ϸ-�����Ƿ���������
	 * @param $productCodes ���ϱ���,�����ж��        	
	 * @param $taskIds ����id,�����ж��        	
	 * @return array
	 */
	function getMeetProduction($productCodes, $taskIds) {
		$sql = "SELECT p.id,p.taskId,i.relDocItemId,i.relDocId FROM oa_produce_producetask_classify c " . "LEFT JOIN oa_produce_taskconfig_product p ON c.produceTaskId = p.taskId AND c.`code` = p.productCode " . "LEFT JOIN oa_produce_produceapply_item i ON p.planId = i.id " . "WHERE c.produceTaskId IN (" . $taskIds . ") AND c.productCode IN (" . util_jsonUtil::strBuild ( $productCodes ) . ") " . "GROUP BY p.id";
		return $this->_db->getArray ( $sql );
	}
	
	/**
	 * ���ϼ���-������������
	 * @param $productCodes ���ϱ���,�����ж��        	
	 * @param $taskIds ����id,�����ж��        	
	 * @return boolean
	 */
	function isMeetProduction_d($productCodes, $taskIds) {
		try {
			$datas = $this->getMeetProduction ( $productCodes, $taskIds );
			if (! empty ( $datas )) {
				foreach ( $datas as $v ) {
					// �����������������������
					if (! empty ( $v ['id'] )) {
						$this->_db->query ( "UPDATE oa_produce_taskconfig_product SET isMeetProduction = 1 WHERE id =" . $v ['id'] );
					}
					// �����ͬ�����嵥�����������
					if (! empty ( $v ['relDocItemId'] )) {
						$this->_db->query ( "UPDATE oa_contract_equ SET isMeetProduction = 0 WHERE id =" . $v ['relDocItemId'] );
					}
				}
				foreach ( $datas as $v ) {
					// �������������������
					if (! empty ( $v ['taskId'] )) {
						$this->updateTaskIsMeetProduction ( $v ['taskId'] );
					}
					// �����ͬ�����������
					if (! empty ( $v ['relDocId'] )) {
						$this->updateConIsMeetProduction ( $v ['relDocId'] );
					}
				}
			}
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	
	/**
	 * ���ϼ���-��������������
	 * @param $productCodes ���ϱ���,�����ж��        	
	 * @param $taskIds ����id,�����ж��        	
	 * @return boolean
	 */
	function isNotMeetProduction_d($productCodes, $taskIds) {
		try {
			$datas = $this->getMeetProduction ( $productCodes, $taskIds );
			if (! empty ( $datas )) {
				foreach ( $datas as $v ) {
					// �����������������������
					if (! empty ( $v ['id'] )) {
						$this->_db->query ( "UPDATE oa_produce_taskconfig_product SET isMeetProduction = 2 WHERE id =" . $v ['id'] );
					}
					// �����ͬ�����嵥�����������
					if (! empty ( $v ['relDocItemId'] )) {
						$this->_db->query ( "UPDATE oa_contract_equ SET isMeetProduction = 1 WHERE id =" . $v ['relDocItemId'] );
					}
				}
				foreach ( $datas as $v ) {
					// �������������������
					if (! empty ( $v ['taskId'] )) {
						$this->updateTaskIsMeetProduction ( $v ['taskId'] );
					}
					// �����ͬ�����������
					if (! empty ( $v ['relDocId'] )) {
						$this->updateConIsMeetProduction ( $v ['relDocId'] );
					}
				}
			}
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	
	/**
	 * ���ȷ��-���Զ�������
	 * @param $row
	 * @return boolean
	 */
	function mark_d($row) {
		try {
			$datas = $this->getMeetProduction ( $row ['productCodes'], $row ['taskIds'] );
			if (! empty ( $datas )) {
				foreach ( $datas as $v ) {
					// �����������������������
					if (! empty ( $v ['id'] )) {
						$this->_db->query ( "UPDATE oa_produce_taskconfig_product SET isMeetProduction = " . $row ['isFirstInspection'] . ",remark = '" . $row ['remark'] . "' WHERE id =" . $v ['id'] );
					}
					// �����ͬ�����嵥�����������
					if (! empty ( $v ['relDocItemId'] )) {
						if ($row ['isFirstInspection'] == '1') {
							$this->_db->query ( "UPDATE oa_contract_equ SET isMeetProduction = 0,meetProductionRemark = '" . $row ['remark'] . "' WHERE id =" . $v ['relDocItemId'] );
						} elseif ($row ['isFirstInspection'] == '2') {
							$this->_db->query ( "UPDATE oa_contract_equ SET isMeetProduction = 1,meetProductionRemark = '" . $row ['remark'] . "' WHERE id =" . $v ['relDocItemId'] );
						}
					}
				}
				foreach ( $datas as $v ) {
					// �������������������
					if (! empty ( $v ['taskId'] )) {
						$this->updateTaskIsMeetProduction ( $v ['taskId'] );
					}
					// �����ͬ�����������
					if (! empty ( $v ['taskId'] )) {
						$this->updateConIsMeetProduction ( $v ['relDocId'] );
					}
				}
			}
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	
	/**
	 * ��������������������״̬
	 * @param $id ����id        	
	 */
	function updateTaskIsMeetProduction($id) {
		$sql = "SELECT
					isMeetProduction
				FROM
					oa_produce_taskconfig_product
				WHERE
					taskId = $id
				GROUP BY
					isMeetProduction";
		$rs = $this->_db->getArray ( $sql );
		if (! empty ( $rs )) {
			if (count ( $rs ) == 1) { // 0��δȷ��,1������,2��������
				$this->_db->query ( "UPDATE " . $this->tbl_name . " SET isMeetProduction = " . $rs [0] ['isMeetProduction'] . " WHERE id =" . $id );
			} else { // 3����������
				$this->_db->query ( "UPDATE " . $this->tbl_name . " SET isMeetProduction = 3 WHERE id =" . $id );
			}
		}
	}
	
	/**
	 * ���º�ͬ��������״̬
	 * @param $id ��ͬid        	
	 */
	function updateConIsMeetProduction($id) {
		$sql = "SELECT * FROM oa_contract_equ WHERE contractId = " . $id . " AND isMeetProduction = 1";
		$rs = $this->_db->getArray ( $sql );
		$isMeetProduction = 0;
		if (! empty ( $rs )) {
			$isMeetProduction = 1;
		}
		$this->_db->query ( "UPDATE oa_contract_contract SET isMeetProduction = " . $isMeetProduction . " WHERE id =" . $id );
	}
	
	/**
	 * ������������״̬
	 * @param $id ��������id
	 * @param $docStatus ����״̬
	 */
	function setDocStatus($id, $docStatus) {
		$this->update ( array (
				"id" => $id 
		), array (
				"docStatus" => $docStatus 
		) );
	}
	
	/**
	 * �Ƿ����뷢���ƻ����Ѵ���
	 * @param $rows
	 */
	function addOutPlanInfo($rows) {
		$sql = "SELECT
					COUNT(*) AS num
				FROM
					oa_produce_taskconfig_product c
				INNER JOIN oa_produce_produceapply_item i ON c.planId = i.id
				INNER JOIN oa_stock_outplan_product p ON i.relDocItemId = p.contEquId
				AND p.docType = 'oa_contract_contract'
				WHERE
					c.taskId = ";
		foreach ($rows as $k => $v){
			$rs = $this->_db->getArray($sql.$v['id']);
			$rows[$k]['isOutPlan'] = $rs[0]['num'] > 0 ? 1 : 0;
		}
		return $rows;
	}
}