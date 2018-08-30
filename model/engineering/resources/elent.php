<?php

/**
 * @author show
 * @Date 2013��12��9�� 19:17:24
 * @version 1.0
 * @description:�豸ת������ Model��
 */
class model_engineering_resources_elent extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_resource_elent";
        $this->sql_map = "engineering/resources/elentSql.php";
        parent::__construct();
    }

    /*********************  ��ɾ�Ĳ� ********************/
    /**
     * @param $object
     * @return bool|void
     * @throws Exception
     */
    function add_d($object) {
        //��ȡ����
        $elentdetail = $object['elentdetail'];
        unset($object['elentdetail']);
        try {
            $this->start_d();
            //�����ֵ�
            $object = $this->processDatadict($object);
            //����ת�����뵥��
            $codeRuleDao = new model_common_codeRule();
            $object['formNo'] = $codeRuleDao->commonCode('�豸ת������', $this->tbl_name, 'SBZZ');

            $newId = parent::add_d($object, true);

            //�ӱ���Ϣ
            $detailDao = new model_engineering_resources_elentdetail();
            $elentdetail = util_arrayUtil::setArrayFn(array('mainId' => $newId), $elentdetail);
            $detailDao->saveDelBatch($elentdetail);

            //�ύʱ�������ʼ�֪ͨת���ˡ�������
            if ($object['status'] == "2") {
                $this->mailDeal_d('deviceElent', $object['applyUserId'] . ',' . $object['receiverId'],
                    array('id' => $newId));
            }

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * @param $object
     * @throws Exception
     */
    function edit_d($object) {
        //��ȡ����
        $elentdetail = $object['elentdetail'];
        unset($object['elentdetail']);
        try {
            $this->start_d();
            //�����ֵ�
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //�ӱ���Ϣ
            $detailDao = new model_engineering_resources_elentdetail();
            $elentdetail = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $elentdetail);
            $detailDao->saveDelBatch($elentdetail);

            //�ύʱ�������ʼ�֪ͨת���ˡ�������
            if ($object['status'] == "2") {
                $this->mailDeal_d('deviceElent', $object['applyUserId'] . ',' . $object['receiverId'],
                    array('id' => $object['id']));
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ����ȷ��״̬
     * @param $id
     * @param $status
     * @return bool
     */
    function status_d($id, $status) {
        $object = $this->get_d($id); // ��ȡ������Ϣ
        if ($object['status'] == 2) return true; // ��̨У���Ѵ���

        return parent::edit_d($object = array(
            'id' => $id,
            'status' => $status
        ), true);
    }

    /**
     * ����Աȷ��ת��
     * @param $object
     * @return bool
     */
    function confirm_d($object) {
    	$id = $object['id'];
    	$obj = $this->get_d($id); // ��ȡ������Ϣ
    	if ($obj['status'] == 2) return true; // ��̨У���Ѵ���
    
    	try {
    		$this->start_d();
    
    		// ��ϸ����
    		foreach ($object['item'] as $key => $val){
    			if(!isset($val['isDelTag'])){
    				if(!isset($val['isChecked'])){
    					unset($object['item'][$key]);
    				}else{
    					$object['item'][$key]['status'] = 1;
    				}
    			}
    		}
    		$elentdetailDao = new model_engineering_resources_elentdetail();
    		$elentdetailDao->saveDelBatch($object['item']);
    
    		// ���µ���״̬
    		$unConfirmNum = $elentdetailDao->findCount(array('mainId' => $id,'status' => 0));//��ȡδȷ�ϵ���ϸ��¼��
    		if($unConfirmNum == 0){
    			$status = '2';// ȫ��ȷ��
    		}else{
    			$status = '5';// ����ȷ��
    		}
    		$this->updateById(array('id' => $id, 'status' => $status, 'remark' => $object['remark'], 'confirmId' => $_SESSION['USER_ID'],
    				'confirmName' => $_SESSION['USERNAME'], 'confirmTime' => date('Y-m-d H:i:s')));
    
    		if($status == '2'){
    			// �ʼ�����,֪ͨ�����˽���ȷ��
    			$this->mailDeal_d('esmElentWaitConfirm', implode(',', array($object['receiverId'])), array('id' => $id));
    		}
    
    		$this->commit_d();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack();
    		return false;
    	}
    }
    
    /**
     * ����������ȷ��ת��
     * @param $object
     * @return bool
     */
    function finalConfirm_d($object) {
    	$id = $object['id'];
    	$obj = $this->get_d($id); // ��ȡ������Ϣ
    	if ($obj['status'] == 4) return true; // ��̨У���Ѵ���
    
    	$elentDetailDao = new model_engineering_resources_elentdetail(); // ʵ����ת��������ϸ
    	$otherDataDao = new model_common_otherdatas();
    
    	try {
    		$this->start_d();
    
    		// ����״̬�޸�
    		$this->updateById(array('id' => $id, 'status' => 4));
    
    		// ��ȡ�����ܽ������,�������豸ʵ��ת������
    		$amount = 0;
    		foreach ($object['item'] as $v) {
    			$amount = $amount + $v['number'];
    			$elentDetailDao->updateById(array('id' => $v['id'], 'realDate' => $v['realDate']));
    		}
    
    		// �黹���� -----------------------------------------------
    
    		// ��ȡ�������������
    		$userInfo = $otherDataDao->getUserDatas($object['applyUserId']);
    
    		// �����黹����
    		$sql = "INSERT INTO device_return_order (userid,dept_id,operatorid,amount,area,date,rand_key) VALUES ('" .
    				$object['applyUserId'] . "','" . $object['deptId'] . "','" . $_SESSION['USER_ID'] . "','" . $amount .
    				"','" . $userInfo['areaid'] . "',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
    		$this->_db->query($sql);
    		$returnId = $this->_db->insert_id();
    
    		// �����黹���ݴӱ��Ҵ��������ϵ���Ϣ
    		foreach ($object['item'] as $v) { // ����Ҫȥ�����豸�Ľ�����
    			//�黹������ʵ��ת������Ϊ׼,����תΪʱ���
    			$realDate = strtotime($v['realDate']);
    			// �����ӱ�
    			$itemSql = "INSERT INTO device_return_order_info
                    (orderid,info_id,tid,amount,area,return_area,status,notse,date,rand_key)VALUES('" .
                        $returnId . "','" . $v['borrowItemId'] . "','" . $v['resourceId'] . "','" . $v['number'] . "','" .
                        $userInfo['areaid'] . "','" . $userInfo['areaid'] . "',''," .
                        "'".$v['remark']."'," . $realDate . ",MD5(UNIX_TIMESTAMP()))";
    			$this->_db->query($itemSql);
    
    			// �黹����
    			$sql = "UPDATE device_borrow_order_info SET return_num = return_num + " . $v['number'] .
    				",returndate = IF(return_num = amount," . $realDate . ",NULL) WHERE id = " . $v['borrowItemId'];
    			$this->_db->query($sql);
    		}
 
    		// ������� ------------------------------------------------
    
    		// ��ȡ����������
    		$userInfo = $otherDataDao->getUserDatas($object['receiverId']);
    
    		// ��Ŀidת��
    		if ($object['rcProjectId']) {
    			$esmprojectDao = new model_engineering_project_esmproject();
    			$projectId = $esmprojectDao->getOldProjectId_d($object['rcProjectId']);
    		} else {
    			$projectId = 0;
    		}
    		$sql = "INSERT INTO device_borrow_order
                (userid,dept_id,project_id,operatorid,manager,area,targetdate,date,rand_key,amount,confirm)VALUES('" .
                    $object['receiverId'] . "','" . $object['deptId'] . "','" . $projectId . "','" . $_SESSION['USER_ID'] .
                    "','" . $object['rcManagerId'] . "','" . $userInfo['areaid'] . "'," .
                    "UNIX_TIMESTAMP(),UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()),$amount,1)";
    		$this->_db->query($sql);
    		$orderId = $this->_db->insert_id();
    
    		$infoIdArr = array(); // ����info_id������
    		// �ӱ����ݴ���
    		foreach ($object['item'] as $v) {
    			// �����ӱ�
    			$itemSql = "INSERT INTO device_borrow_order_info
                    (orderid,info_id,typeid,list_id,amount,targetdate,notse,claim,date,rand_key)VALUES('" .
                        $orderId . "','" . $v['resourceId'] . "','" . $v['resourceTypeId'] . "','" . $v['resourceListId'] .
                        "','" . $v['number'] . "'," . "'" . strtotime($v['endDate']) . "','".$v['remark']."',1," .
                        strtotime($v['realDate']) . ",MD5(UNIX_TIMESTAMP()))";
    			$this->_db->query($itemSql);
    
    			// ����infoId
    			array_push($infoIdArr, $v['resourceId']);
    		}
    
    		$infoIds = implode(',', $infoIdArr);
    
    		// ���״̬����
    		$sql = "UPDATE device_info SET state = IF(amount = borrow_num, 1, 0) WHERE id IN($infoIds) AND state IN(0,1);";
    		$this->_db->query($sql);
    
    		// ��ȡ�ʲ���Ƭid
    		$sql = "SELECT GROUP_CONCAT(CAST(assetCardId AS CHAR)) AS assetCardIds FROM device_info WHERE id IN ($infoIds)";
    		$rs = $this->findSql($sql);
    		$assetCardIds = $rs[0]['assetCardIds'];
    
    		// �����ڹ����ʲ���Ƭid,�������ʹ���˼�������Ϣ
    		if (!empty($assetCardIds)) {
    			$assetcardDao = new model_asset_assetcard_assetcard();
    			$assetcardDao->updateByEsmDevice($object['receiverId'], $assetCardIds);
    		}
    		// �豸������¼����
    		$lockDao = new model_engineering_resources_lock();
    		$applyUserId = $object['applyUserId'];
    		if($lockDao->checkLock_d($applyUserId)){// ����Ա������������¼
    			$lockDao->unlockAuto_d($applyUserId);// ִ�н�������
    		}
    		// �ʼ�����,��֪�����˽�������ȷ��
    		$this->mailDeal_d('esmElentConfirm', implode(',', array($object['applyUserId'])), array('id' => $id));
    
    		$this->commit_d();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack();
    		return false;
    	}
    }
}