<?php
/**
 * @author sony
 * @Date 2013��7��10�� 17:29:50
 * @version 1.0
 * @description:��Ʊ��Ϣ Model��
 */
class model_flights_message_message extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_message";
		$this->sql_map = "flights/message/messageSql.php";
		parent::__construct ();
	}

	//����״̬
	function rtStatus_d($thisVal){
		switch($thisVal){
			case 1 : return '��ǩ';break;
			case 2 : return '��Ʊ';break;
			case 10 : return '����';break;
			case 11 : return '����';break;
			case 12 : return '����';break;
			default : return '����';
		}
	}

	//����yn
	function rtYesOrNo_d($thisVal){
		if($thisVal == '1'){
			return '��';
		}else{
			return '��';
		}
	}
	/*--------------------------------------------ҵ�����--------------------------------------------*/

	/**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try{
			$this->start_d();

			//�ж�requireId�Ƿ���ֵ���������ɶ�Ʊ��Ϣ
			if ($object['requireId'] != "") {
				$requireDao = new model_flights_require_require ();
				$requireDao->updateMsgState_d($object['requireId']);
			}
			//�޳������޹���Ϣ
			$items = $object ['items'];
			unset ( $object ['items'] );
            $idArr = array(); // ����id
			foreach ( $items as $val ) {
				$mergerArr = array_merge ( $object, $val );
				$mergerArr['costPay'] = $mergerArr['actualCost'];
				$newId = parent::add_d ( $mergerArr, true );

				$newObj = array('id' => $newId,'businessNo' => $newId);
				parent::edit_d($newObj);

                array_push($idArr,$newId); // ��ȡ�²��������Id
			}

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $ids = implode(',',$idArr);
            $projectFeeArr = $this->getProjectFeeArr_d($ids);
            //���ȷʵ�з��ã���ôȥ����
            if($projectFeeArr){
                $esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
                foreach($projectFeeArr as $v){
                    $esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
                }
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
            }

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
        try{
            $this->start_d();
            // ����
            parent::edit_d ( $object, true );

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($object['id']);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

            $this->commit_d();
            return true;
        }catch (Exception $e){
            $this->rollBack();
            return false;
        }
	}

	/**
	 * ɾ������
	 */
	function deletes_d($id){
		try {
			$this->start_d();

			//���¶�Ʊ����
			$obj = $this->get_d($id);

			//ɾ��
			$this->deletes ( $id );

			//��ѯ�Ƿ񻹴��ڶ�Ӧ�Ķ�Ʊ��Ϣ
			$requireMsgArr = $this->find(array('requireId' => $obj['requireId']));
			if(!$requireMsgArr){
				//����Ʊ����״̬���»�δ����
				$requireDao = new model_flights_require_require ();
				$requireDao->updateMsgState_d($obj['requireId'],0);
			}

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($id);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack();
            return false;
		}
	}

	/**
	 * ��ǩ��Ʊ
	 */
	function change_d($object){
		try{
			$this->start_d();

			//��ȡԴ������
			$obj = $this->get_d($object['orgId']);
			unset($obj['id']); //ȥ��id
			unset($obj['auditState']); //ȥ���˵�״̬
			$newId = parent::add_d($obj,true); //����

			//����ǩ�������뵽��������
			$object['id'] = $newId;
			$object['businessState'] = '3';
			$object['msgType'] = '1';
			$object['costPay'] = bcadd($object['costDiff'],$object['feeChange'],2);
			parent::edit_d($object,true);

			//���º˵�״̬
			if($obj['auditState'] != "2"){
				//���δ���㣬�򽫶�Ʊ��Ϣ����Ϊ�������
				$newObj = array('id' => $object['orgId'],'businessState' => '1');
				parent::edit_d($newObj,true);
			}

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($newId);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �޸ĸ�ǩ
	 */
	function changeEdit_d($object){
		try{
			$this->start_d();

			$object['costPay'] = bcadd($object['costDiff'],$object['feeChange'],2);
			parent::edit_d($object,true);

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($object['id']);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	//����ҵ��
	function back_d($object) {
		try{
			$this->start_d();

			//��ȡԴ������
			$obj = $this->get_d($object['orgId']);
			unset($obj['id']); //ȥ��id
			unset($obj['feeChange']); //ȥ����ǩ������
			unset($obj['costDiff']); //ȥ��Ʊ�۲��
			unset($obj['auditState']); //ȥ���˵�״̬
			$obj['beforeCost'] = $obj['actualCost']; //ԭƱ������Ϊ��ǰƱ��
			$newId = parent::add_d($obj,true); //����

			//����Ʊ�������뵽��������
			$object['id'] = $newId;
			$object['businessState'] = '4';
			$object['msgType'] = '2';
			$object['costPay'] = -$object['costBack'];
			parent::edit_d($object,true);

			//���º˵�״̬
			if($obj['auditState'] != "2"){
				//���δ���㣬�򽫶�Ʊ��Ϣ����Ϊ�������
				$newObj = array('id' => $object['orgId'],'businessState' => '2');
				parent::edit_d($newObj,true);
			}

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($newId);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	//����ҵ��
	function editBack_d($object) {
		try{
			$this->start_d();

			//����Ʊ�������뵽��������
			$object['businessState'] = '4';
			$object['msgType'] = '2';
			$object['costPay'] = -$object['costBack'];
			parent::edit_d($object,true);

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($object['id']);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

    //���
    function confirm_d($ids){
        try{
            $this->start_d();

            //�������
            $this->_db->query ( "update ".$this->tbl_name . " set auditState = 1 where id in ($ids)" );

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($ids);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }

    //���˵�
    function unconfirm_d($ids){
        try{
            $this->start_d();

            //������
            $this->_db->query ( "update ".$this->tbl_name . " set auditState = 0 where id in ($ids)" );

            //�ر��� -- ������Ŀ���õ���Ŀ��
            $projectFeeArr = $this->getProjectFeeArr_d($ids);
			//���ȷʵ�з��ã���ôȥ����
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��Ŀ���ò�ѯ
     * @param $ids ��Ʊ��¼��id
     */
    function getProjectFeeArr_d($ids){
        //��ѯ��������Ŀid
        $arr = $this->_db->getArray("SELECT projectId FROM oa_flights_message WHERE id IN ($ids) AND projectType = 'esm' GROUP BY projectId");
        if($arr){
            $rtProjectFeeArr = array();//ʵ����Ҫ���ص�����
            $projectIdArr = array();//������Ŀid
            foreach($arr as $v){
                array_push($projectIdArr,$v['projectId']);//��ѯ���õ���Ŀid
                $rtProjectFeeArr[$v['projectId']] = array('projectId' => $v['projectId'], 'money' => 0);//������������
            }
            $projectIds = implode(',',$projectIdArr);//��Ŀid��
            //��Ŀ����
            $projectFeeArr = $this->_db->getArray("SELECT projectId,SUM(costPay) as costPay FROM oa_flights_message WHERE projectId IN ($projectIds) AND projectType = 'esm' GROUP BY projectId");
            //�����Ŀ�з���,��ô�ͼ���
            if($projectFeeArr){
                foreach($projectFeeArr as $v){
                    $rtProjectFeeArr[$v['projectId']]['money'] = $v['costPay'];
                }
            }
            return $rtProjectFeeArr;
        }
        return false;
    }

	/**
	 * ���˲�ѯ
	 */
	function filterMes_d($ids){
		//ѭ������
		$this->searchArr = array(
            'ids' => $ids
		);
		//����������
		return $this->list_d();
	}
	/**
	 * ���¶���״̬
	 */
	function updateAuditState_d($id,$auditState){
		$object = array(
			'id' => $id,
			'auditState' => $auditState
		);
		return parent::edit_d($object);
	}

	//��������ҳ�涩Ʊ״̬
	function deleteRquery($requireId){
		$requireDao=new model_flights_require_require();
        return $requireDao->updateMsgState_d($requireId,0);
	}

	/**
	 * ��ȡ������Ϣ
	 * @param unknown $param
	 * @param unknown $sort
	 */
	function getlistHtml_d($param){
		$trStr="";
		$i=0;
		$j=1;
		$this->getParam($param);
		$arr = $this->list_d();
		foreach ($arr as $val){//�ж϶�Ʊ��Ϣ����
			if($val['msgType'] == "0"){
				$msgType = '<span class="green">����</span>';
			}else if($val['msgType'] == '1'){
				$msgType = '<span class="blue">��ǩ</span>';
			}else{
				$msgType = '<span class="red">��Ʊ</span>';
			}
			$trStr.=<<<EOT
			<tr class="tr_even" rownum="$i">
				<td><span class="removeBn"><img src="images/removeline.png" onclick="removeRow($i);"></span>
				<input	type="hidden" name="balance[items][$i][rowNum_]" value="$i"></td>
				<td type="rowNum">$j</td>
				<td style="text-align: center;"><div class="divChangeLine"
						id="itemTable_cmp_msgType$i" name="balance[items][$i][msgType]"
						style="width: 70px;">		
						$msgType
					</div></td>
				<td style="text-align: center;">
				<input type="text" id="itemTable_cmp_auditDate$i" class="readOnlyTxtMiddle" name="balance[items][$i][auditDate]" readonly="" style="width: 80px;" value="{$val['auditDate']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_airName$i" class="readOnlyTxtMiddle" name="balance[items][$i][airName]" readonly="" style="width: 90px;"  value="{$val['airName']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden"  id="itemTable_cmp_airId$i" class="txtmiddle" name="balance[items][$i][airId]" value="{$val['airId']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_airline$i" class="readOnlyTxtNormal" name="balance[items][$i][airline]" readonly="" style="width: 140px;" value="{$val['airline']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden"  id="itemTable_cmp_airlineId$i" class="txtmiddle" name="balance[items][$i][airlineId]" value="{$val['airlineId']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_flightNumber$i" class="readOnlyTxtMiddle" name="balance[items][$i][flightNumber]" readonly="" value="{$val['flightNumber']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_flightTime$i" class="readOnlyTxtMiddle" name="balance[items][$i][flightTime]" readonly="" style="width: 130px;" value="{$val['flightTime']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_arrivalTime$i" class="readOnlyTxtMiddle" name="balance[items][$i][arrivalTime]" readonly="" style="width: 130px;" value="{$val['arrivalTime']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_departPlace$i" class="readOnlyTxtShort" name="balance[items][$i][departPlace]" readonly="" value="{$val['departPlace']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_arrivalPlace$i" class="readOnlyTxtShort" name="balance[items][$i][arrivalPlace]" readonly="" value="{$val['arrivalPlace']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_costPay$i" class="readOnlyTxtMiddle" name="balance[items][$i][costPay]" readonly="" value="{$val['costPay']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden" id="itemTable_cmp_msgType$i" class="txtmiddle" name="balance[items][$i][msgType]" readonly="" value="{$val['msgType']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden" id="itemTable_cmp_msgId$i" class="txtmiddle" name="balance[items][$i][msgId]" readonly="" value="{$val['msgId']}"></td>
			</tr>
EOT;
			$i++;
			$j++;
		}
		
		$str = <<<EOT
		<div id="itemTable" style="overflow-x: auto; overflow-y: auto; height: 500px;">
		<table class="form_in_table">
			<thead>
				<tr class="main_tr_header">
					<th width="10"></th>
					<th width="30">���</th>
					<th style="width: 70px;"><div style="min-width: 70px;" class="divChangeLine">��������</div></th>
					<th style="width: 80px;"><div style="min-width: 80px;" class="divChangeLine">��������</div></th>
					<th style="width: 90px;"><div style="min-width: 90px;" class="divChangeLine">�˻���</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">airId</div></th>
					<th style="width: 140px;"><div style="min-width: 140px;" class="divChangeLine">���չ�˾</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">airlineId</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">����/�����</div></th>
					<th style="width: 130px;"><div style="min-width: 130px;" class="divChangeLine">�˻�ʱ��</div></th>
					<th style="width: 130px;"><div style="min-width: 130px;" class="divChangeLine">����ʱ��</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">�����ص�</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">����ص�</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">ʵ�ʸ�����</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">msgType</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">msgId</div></th>
				</tr>
			</thead>
			<tbody>
				$trStr
			</tbody>
		</table>
		</div>
EOT;
		return $str;
	
	}

    /**
     * @param ���ݴ���������ȡ���з���
     * @return int|string
     */
    function getAllCost_d($param){
		$this->getParam($param);
		$arr=$this->list_d();
		$result = 0;
		foreach ($arr as $val){
			$result = bcadd($result,$val['costPay'],2);
		}
		return $result;
	}
}