<?php

/**
 * @author xyt
 * @Date 2013��12��14�� ������ 14:23:30
 * @version 1.0
 * @description:��Ŀ�豸�黹��model��
 */
class model_engineering_resources_ereturn extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_resource_ereturn";
        $this->sql_map = "engineering/resources/ereturnSql.php";
        parent::__construct();
    }

    /**
     * @param $obj
     * @return bool|void
     * @throws Exception
     */
    function add_d($obj) {
        try {
            $this->start_d();
            //���ɹ黹����
            $codeRuleDao = new model_common_codeRule();
            $obj['ereturn']['formNo'] = $codeRuleDao->commonCode('�豸�黹����', $this->tbl_name, 'SBGH');
            $obj['ereturn']['areaName'] = $this->findArea($obj['ereturn']['areaId']);

            $newId = parent::add_d($obj['ereturn'], true);

            $objDetail = $obj['ereturnDetail'];

            //�ӱ���Ϣ
            if (isset($objDetail)) {
                foreach ($objDetail as $key => $value) {
                    $objDetail[$key]['mainId'] = $newId;
                }
                $ereturnDetailDao = new model_engineering_resources_ereturndetail();
                $ereturnDetailDao->addBatch_d($objDetail);
            }
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ��ѯ�黹����
     * @param $areaId
     * @return mixed
     */
    function findArea($areaId) {
        $query = $this->_db->query("select Name from area where ID = " . $areaId);
        $rs = $this->_db->fetch_array($query);
        return $rs['Name'];
    }

    /**
     * �޸�״̬
     * @param $id
     * @param $status
     * @return bool
     */
    function status_d($id, $status) {
        $object = $this->get_d($id); // ��ȡ������Ϣ
        if ($object['status'] == 2) return true; // ��̨У���Ѵ���

        return parent::edit_d(array(
            'id' => $id,
            'status' => $status
        ), true);
    }

    /**
     * ����Աȷ�Ϲ黹
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
            $ereturndetailDao = new model_engineering_resources_ereturndetail();
            $delItemNum = 0;//	ҳ��ɾ�����豸��ϸ����
            foreach ($object['item'] as $key => $val){
				if (isset($val['isDelTag'])){
            		$delItemNum ++;
            	} elseif (!isset($val['isChecked'])){// ������δɾ��,δ��ѡ���豸��ϸ
            		unset($object['item'][$key]);
            	}
            }
            if($delItemNum > 0){
            	$itemNum = $ereturndetailDao->findCount(array('mainId' => $id));
            	if($itemNum == $delItemNum){
            		msgRf('�������ȷ�������豸,�����б�Ĵ�ع��ܣ�');
            	}
            }
            $item = $ereturndetailDao->saveDelBatch($object['item']);
            
            if(count($item) > 0){//	����豸��ϸȫ����ɾ��,��ִ�����´���
            	//��ȡ�����ܽ������
            	$amount = 0;
            	foreach ($item as $v) {
            		$amount = $amount + $v['confirmNum'];
            	}
            	
            	// �黹���� -----------------------------------------------
            	$otherDataDao = new model_common_otherdatas();
            	// ��ȡ�������������
            	$userInfo = $otherDataDao->getUserDatas($object['applyUserId']);
            	// ��ȡ��¼����������
            	$loggedUserInfo = $otherDataDao->getUserDatas($_SESSION['USER_ID']);
            	
            	// �����黹����
            	$sql = "INSERT INTO device_return_order (userid,dept_id,operatorid,amount,area,date,rand_key) VALUES ('" .
            			$object['applyUserId'] . "','" . $object['deptId'] . "','" . $_SESSION['USER_ID'] . "','" . $amount .
            			"','" . $userInfo['areaid'] . "',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
            	$this->_db->query($sql);
            	$returnId = $this->_db->insert_id();
            	
            	$listIdArr = array(); // ����list_id������
            	$infoIdArr = array(); // ����info_id������
            	// �����黹���ݴӱ��Ҵ��������ϵ���Ϣ
            	foreach ($item as $v) { // ����Ҫȥ�����豸�Ľ�����
            		// �����ӱ�
            		$itemSql = "INSERT INTO
			                    device_return_order_info (orderid,info_id,tid,amount,area,return_area,status,notse,date,rand_key)
			                    VALUES
			                    ('" . $returnId . "','" . $v['borrowItemId'] . "','" . $v['resourceId'] . "','" . $v['confirmNum'] .
			            	    "','" . $userInfo['areaid'] . "','" . $userInfo['areaid'] . "',''," .
			            	    "'�黹����',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
            		$this->_db->query($itemSql);
            	
            		// �黹����
            		$sql = "UPDATE device_borrow_order_info SET return_num = return_num + " . $v['confirmNum'] .
            				",returndate = IF(return_num = amount,UNIX_TIMESTAMP(),NULL) WHERE id = " . $v['borrowItemId'];
            		$this->_db->query($sql);
            	
            		// �黹ʱ�����豸��������,�����豸�Ĺ���������Ϊ��ǰ��¼�˵�����
            		$sql = "UPDATE device_info SET borrow_num = borrow_num - " . $v['confirmNum'] . ",area = ".$loggedUserInfo['areaid']." WHERE id = " .
            				$v['resourceId'];
            		$this->_db->query($sql);
            	
            		// ���¹黹��ϸ״̬
            		if($v['number'] == $v['confirmNum']){
            			$ereturndetailDao->update(array('id' => $v['id']),array('status' => 1));// ȫ���ѹ黹����״̬��Ϊ1
            		}
            	
            		// ����listId
            		array_push($listIdArr, $v['resourceListId']);
            		//����infoId
            		array_push($infoIdArr,$v['resourceId']);
            	}
            	
            	// �����������
            	$listIds = implode(',', $listIdArr);
            	$sql = "UPDATE
		            	device_list AS a,
		            	(
		            		SELECT SUM(amount) AS num ,SUM(borrow_num) AS borrow_num,AVG(price) AS average ,list_id
		            		FROM device_info WHERE quit=0 AND list_id IN ($listIds) GROUP BY list_id
		            	) AS b
		            	SET
		            		a.total=b.num,a.average=b.average,a.borrow=b.borrow_num,a.surplus=b.num-b.borrow_num,
		            		a.rate=(CAST((b.borrow_num/b.num) AS DECIMAL(11,2)))
		            	WHERE a.id=b.list_id AND a.id IN($listIds)";
            	$this->_db->query($sql);
            	
            	$infoIds = implode(',',$infoIdArr);
            	
            	// ���״̬����
            	$sql = "UPDATE device_info SET state = IF(amount = borrow_num, 1, 0) WHERE id IN($infoIds) AND state IN(0,1);";
            	$this->_db->query($sql);
            	
            	// ��ȡ�ʲ���Ƭid
            	$sql = "SELECT GROUP_CONCAT(CAST(assetCardId AS CHAR)) AS assetCardIds FROM device_info WHERE id IN ($infoIds)";
            	$rs = $this->findSql($sql);
            	$assetCardIds = $rs[0]['assetCardIds'];
            	// �����ڹ����ʲ���Ƭid,�������ʹ���˼�������Ϣ
            	if(!empty($assetCardIds)){
            		$assetcardDao = new model_asset_assetcard_assetcard();
            		$assetcardDao->emptyByEsmDevice($assetCardIds);
            	}
            	
            	// �豸������¼����
            	$lockDao = new model_engineering_resources_lock();
            	$applyUserId = $object['applyUserId'];
            	if($lockDao->checkLock_d($applyUserId)){// ����Ա������������¼
            		$lockDao->unlockAuto_d($applyUserId);// ִ�н�������
            	}
            }
            
            // ���µ���״̬
            $unConfirmNum = $ereturndetailDao->findCount(array('mainId' => $id,'status' => 0));//��ȡδȷ�ϵ���ϸ��¼��
            if($unConfirmNum == 0){
            	$status = '2';// ȫ��ȷ��
            }else{
            	$status = '4';// ����ȷ��
            }
            $this->updateById(array('id' => $id, 'status' => $status, 'remark' => $object['remark'], 'confirmId' => $_SESSION['USER_ID'],
            		'confirmName' => $_SESSION['USERNAME'], 'confirmTime' => date('Y-m-d H:i:s')));
            
            if($status == '2'){
            	// �ʼ�����
            	$this->mailDeal_d('esmEreturnConfirm', implode(',', array($object['applyUserId'])),
            			array('id' => $id,'remark' => $object['remark']));
            }
   
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
}