<?php

/**
 * @author show
 * @Date 2013��10��24�� 19:30:28
 * @version 1.0
 * @description:�⳥�� Model��
 */
class model_finance_compensate_compensate extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_compensate";
        $this->sql_map = "finance/compensate/compensateSql.php";
        parent:: __construct();
    }

    public $datadictFieldArr = array(
        'dutyType', 'payType'
    );

    /**
     * ״̬
     * @param $v string
     * @return string
     */
    function rtStatus_d($v) {
        switch ($v) {
            case '0' :
                return '��ȷ��';
            case '1' :
                return '�����ȷ��';
            case '2' :
                return '���⳥ȷ��';
            case '3' :
                return '���������';
            case '4' :
                return '�����';
            case '5' :
                return '�ر�';
            default :
                return $v;
        }
    }

    /********************** S ���Բ��� ***********************/
    /**
     *  Դ������
     */
    private $relDocType = array(
        'PCYDLX-01' => 'model_finance_compensate_strategy_sborrowreturn',
        'PCYDLX-02' => 'model_finance_compensate_strategy_swithdraw'
    );

    /**
     * ��������
     * @param $v string
     * @return string
     */
    public function getRelType($v) {
        return $this->relDocType[$v];
    }

    /**
     * �����⳥ʱ���ò���
     * @param $relId
     * @param $strategy
     * @return mixed
     */
    function businessGet_d($relId, icompensate $strategy) {
        return $strategy->businessGet_i($relId, $this);
    }

    /**
     * ��ȡԴ���⳥��Ϣ - ��ϸ
     * @param $condition
     * @param $strategy
     * @return mixed
     */
    function businessGetDetail_d($condition, icompensate $strategy) {
        return $strategy->businessGetDetail_i($condition, $this);
    }

    /**
     * ��ȡԴ���⳥��Ϣ - ��ϸ
     * @param $condition
     * @param $strategy
     * @return mixed
     */
    function getSerialNos_d($condition, icompensate $strategy) {
        return $strategy->getSerialNos_i($condition, $this);
    }

    /**
     *  �����⳥ʱ���ò���
     * @param $obj
     * @param $detail
     * @param $strategy
     * @return mixed
     */
    function businessAdd_d($obj, $detail, icompensate $strategy) {
        return $strategy->businessAdd_i($obj, $detail, $this);
    }

    /********************** E ���Բ��� ***********************/

    /**
     * ��дadd
     * @param $object array
     * @return boolean
     */
    function add_d($object) {
        $detail = $object['detail'];
        foreach ($detail as $dk => $dv){
            $detail[$dk]['price'] = str_replace(',','',$dv['price']);
        }
        unset($object['detail']);
        try {
            $this->start_d();

            //��ֵ
            $codeRuleDao = new model_common_codeRule();
            $object['ExaStatus'] = WAITAUDIT;
            $object['formCode'] = $codeRuleDao->commonCode('�⳥��', $this->tbl_name, 'PCD');
            $object = $this->processDatadict($object);

            //����������Ϣ
            $newId = parent::add_d($object, true);

            //�����ӱ���Ϣ
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $detail = util_arrayUtil::setArrayFn(array('mainId' => $newId), $detail);
            $compensateDetailDao->saveDelBatch($detail);

            //Դ�����ֲ��Դ���
            $relClass = $this->relDocType[$object['relDocType']];
            if ($relClass) {
                $relClassM = new $relClass();//����ʵ��
                $this->businessAdd_d($object, $detail, $relClassM);
            }

            //ҵ����� -- ����

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��дedit
     * @param $object array
     * @return boolean
     */
    function edit_d($object) {
        $detail = $object['detail'];
        foreach ($detail as $dk => $dv){
            $detail[$dk]['price'] = str_replace(',','',$dv['price']);
        }
        unset($object['detail']);
        //���÷�̯��ϸ
        $costShare = $object['costshare'];
        unset($object['costshare']);
        try {
            $this->start_d();

            //�����ȷ��,����ȷ����Ϣ
            if (!empty($object['isConfirm'])) {
                $object['confirmId'] = $_SESSION['USER_ID'];
                $object['confirmName'] = $_SESSION['USERNAME'];
                $object['confirmTime'] = date('Y-m-d H:i:s');
                $object['formStatus'] = 1;
            }

            //����������Ϣ
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //�����ӱ���Ϣ
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $compensateMoneyTotal = 0;
            foreach ($detail as $k => $v){
                if($v['money'] == 0){
                    $detail[$k]['compensateMoney'] = $v['price'];
                    $compensateMoneyTotal += $v['price'];
                }else{
                    $compensateMoneyTotal += $v['money'];
                }
            }

            //���µ����ܽ��
            $compensateDao = new model_finance_compensate_compensate();
            $compensateDao->updateCompansate_d($object['id'],$compensateMoneyTotal);
            $detail = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $detail);
            $compensateDetailDao->saveDelBatch($detail);

            //���÷�̯����
            if ($costShare) {
                $costShareDao = new model_finance_cost_costshare();
                $costShare = util_arrayUtil::setArrayFn(array('objId' => $object['id'], 'objType' => '1'), $costShare);
                $costShareDao->saveDelBatch($costShare);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ȷ���⳥ - ��
     * @param $object array
     * @return boolean
     */
    function confirm_d($object) {
        //���÷�̯��ϸ
        $costShare = $object['costshare'];
        unset($object['costshare']);
        try {
            $this->start_d();

            //�����ȷ��,����ȷ����Ϣ
            $object['comConfirmId'] = $_SESSION['USER_ID'];
            $object['comConfirmName'] = $_SESSION['USERNAME'];
            $object['comConfirmTime'] = date('Y-m-d H:i:s');
            $object['formStatus'] = 3;

            //����������Ϣ
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //���÷�̯����
            if ($costShare) {
                $costShareDao = new model_finance_cost_costshare();
                $costShare = util_arrayUtil::setArrayFn(
                    array('objId' => $object['id'], 'objCode' => $object['formCode'], 'objType' => '1',
                        'company' => $_SESSION['USER_COM'], 'companyName' => $_SESSION['USER_COM_NAME']),
                    $costShare
                );
                $costShareDao->saveDelBatch($costShare);
                $costShareDao->setDataAudited_d($object['id'], 1);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ȷ���⳥
     * @param $id string
     * @return mixed
     */
    function comConfirm_d($id) {
        $condition = array("id" => $id);
        $object = array(
            'formStatus' => 3, 'comConfirmId' => $_SESSION['USER_ID'],
            'comConfirmTime' => date('Y-m-d H:i:s'), 'comConfirmName' => $_SESSION['USERNAME']
        );
        return $this->update($condition, $object);
    }

    /**
     * ȷ���⳥
     * @param $id string
     * @return boolean
     */
    function unComConfirm_d($id) {
        try {
            $this->start_d();

            //ȡ���⳥ȷ��
            $condition = array("id" => $id);
            $object = array(
                'formStatus' => 2, 'comConfirmId' => '',
                'comConfirmTime' => '0000-00-00 00:00:00', 'comConfirmName' => ''
            );
            $this->update($condition, $object);

            //ȡ����̯��¼���
            $costShareDao = new model_finance_cost_costshare();
            $costShareDao->setDataUnAudited_d($id, 1);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ȷ���⳥
     * @param $id string
     * @return mixed
     */
    function audit_d($id) {
        $condition = array("id" => $id);
        $object = array(
            'formStatus' => 4, 'auditorId' => $_SESSION['USER_ID'],
            'auditTime' => date('Y-m-d H:i:s'), 'auditorName' => $_SESSION['USERNAME']
        );
        return $this->update($condition, $object);
    }

    /**
     * ȷ���⳥
     * @param $id string
     * @return mixed
     */
    function unAudit_d($id) {
        $condition = array("id" => $id);
        $object = array(
            'formStatus' => 3, 'auditorId' => '',
            'auditTime' => '0000-00-00 00:00:00', 'auditorName' => ''
        );
        return $this->update($condition, $object);
    }

    /**
     * �ر�
     * @param $id string
     * @return boolean
     */
    function close_d($id) {
        try {
            $this->start_d();

            //�������ݸ���
            $condition = array("id" => $id);
            $object = array(
                'formStatus' => 5, 'closerId' => $_SESSION['USER_ID'],
                'closeTime' => date('Y-m-d H:i:s'), 'closerName' => $_SESSION['USERNAME'],
                'compensateMoney' => 0
            );
            $this->update($condition, $object);

            //�ӱ����ݸ��� - ��Ϊ����
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $compensateDetailDao->close_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �����⳥������Ϣ
     * @param $id
     * @param $dutyType
     * @param $dutyObjId
     * @param $dutyObjName
     * @return bool
     */
    function updateDutyInfo_d($id, $dutyType, $dutyObjId, $dutyObjName) {
        $object = $this->processDatadict(array(
            'id' => $id,
            'dutyType' => $dutyType,
            'dutyObjId' => $dutyObjId,
            'dutyObjName' => $dutyObjName
        ));
        return parent::edit_d($object, true);
    }

    /**
     * ��������
     * @param $spid int
     * @return int
     */
    function workflowCallBack($spid) {
        $otherDatas = new model_common_otherdatas ();
        $flowInfo = $otherDatas->getStepInfo($spid);
        $objId = $flowInfo['objId'];
        $obj = $this->get_d($objId);
        if ($obj['ExaStatus'] == AUDITED) {
            // ��������ʼ����豸������
            $this->mailDeal_d('compensateAudit', $obj['chargerId'], array('id' => $objId));

            if($obj['dutyType'] == "PCZTLX-01"){// ����⳥�����Ǹ��˵�, �����ʼ�֪ͨ�⳥������
                // ���������� (ȷ�Ϻ�ȡ��һ��������,�������˲����ܼ�)
                $getFirstAuditManIdSql = "select u.USER_NAME as userName,p.User as userId from flow_step_partent p left join user u on p.User = u.USER_ID where p.Wf_task_ID = {$flowInfo['task']} and p.SmallID = 1;";
                $firstAuditor = $this->_db->getArray($getFirstAuditManIdSql);
                $firstAuditor = ($firstAuditor)? $firstAuditor[0]['userName'] : '';


                // �ʼ�֪ͨ�⳥������
                $this->mailDeal_d('compensateAuditForPersonal', $obj['chargerId'], array('objCode' => $obj['objCode'],'compensateMoney' => $obj['compensateMoney'],'firstAuditor' => $firstAuditor));
//                $sendIds = rtrim($obj['chargerId'],",");
//                $sql = "select GROUP_CONCAT(EMAIL) as address  from user where USER_ID in('{$sendIds}')";
//                $adrsArr = $this->_db->getArray($sql);
//                $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";
//                $mailContent = "���ã����ڽ��õ���{$obj['objCode']} ���豸��ʧ�����⳥��˾ {$obj['compensateMoney']}Ԫ���������ʿ���ѯ�����쵼��{$firstAuditor}��лл��";
//                $title = "���⳥֪ͨ";
//
//                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','{$title}','{$mailContent}','{$addresses}','',NOW(),'','','1')";
//                $this->_db->query($sql);
            }
        }

        return $spid;
    }

    /**
     * �����⳥���
     * @param $id
     * @param $compansateMoney
     * @return mixed
     */
    function updateCompansate_d($id, $compansateMoney) {
        return $this->update(array('id' => $id), array('compensateMoney' => $compansateMoney));
    }
    
    /**
     * ��ȡ�⳥����¼��ۿ���
     * @param $id
     * @return mixed
     */
    function getDeductMoney_d($id) {
    	$sql = "
    		SELECT
				SUM(deductMoney) AS deductMoney
			FROM
				oa_finance_compensate_deduct
			WHERE
				compensateId = ".$id;
    	$rs = $this->findSql($sql);
    	return $rs[0]['deductMoney'];
    }
    
    /**
     * ¼��ۿ��
     * @param $object array
     * @return boolean
     */
    function deduct_d($object) {
    	try {
    		$this->start_d();
    		
    		//���Ӳ�������Ϣ
    		$object['operateId'] = $_SESSION['USER_ID'];
    		$object['operateName'] = $_SESSION['USERNAME'];
    		$object['operateTime'] = date('Y-m-d H:i:s');
    		//�����ֵ䴦��
    		$object = $this->processDatadict($object);
    		//����¼��ۿ��¼
			$deductDao = new model_finance_compensate_compensatededuct();
    		$deductDao->add_d($object);
    		//�����⳥��״̬
    		$id = $object['compensateId'];//�⳥��id
    		$deductMoney = $this->getDeductMoney_d($id);//�⳥����¼��ۿ���
			$rs = $this->find(array('id' => $id),null,'compensateMoney');
			$compensateMoney = $rs['compensateMoney'];//�⳥��ȷ���⳥���
			if($deductMoney == $compensateMoney){
				$this->update(array('id' => $id), array('formStatus' => '4'));
			}
    
    		$this->commit_d();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack();
    		return false;
    	}
    }
}