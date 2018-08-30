<?php

/**
 * @author show
 * @Date 2013年12月9日 19:17:24
 * @version 1.0
 * @description:设备转借申请 Model层
 */
class model_engineering_resources_elent extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_resource_elent";
        $this->sql_map = "engineering/resources/elentSql.php";
        parent::__construct();
    }

    /*********************  增删改查 ********************/
    /**
     * @param $object
     * @return bool|void
     * @throws Exception
     */
    function add_d($object) {
        //截取数据
        $elentdetail = $object['elentdetail'];
        unset($object['elentdetail']);
        try {
            $this->start_d();
            //数据字典
            $object = $this->processDatadict($object);
            //生成转借申请单号
            $codeRuleDao = new model_common_codeRule();
            $object['formNo'] = $codeRuleDao->commonCode('设备转借申请', $this->tbl_name, 'SBZZ');

            $newId = parent::add_d($object, true);

            //从表信息
            $detailDao = new model_engineering_resources_elentdetail();
            $elentdetail = util_arrayUtil::setArrayFn(array('mainId' => $newId), $elentdetail);
            $detailDao->saveDelBatch($elentdetail);

            //提交时，发送邮件通知转借人、接收人
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
        //截取数据
        $elentdetail = $object['elentdetail'];
        unset($object['elentdetail']);
        try {
            $this->start_d();
            //数据字典
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //从表信息
            $detailDao = new model_engineering_resources_elentdetail();
            $elentdetail = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $elentdetail);
            $detailDao->saveDelBatch($elentdetail);

            //提交时，发送邮件通知转借人、接收人
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
     * 更新确认状态
     * @param $id
     * @param $status
     * @return bool
     */
    function status_d($id, $status) {
        $object = $this->get_d($id); // 获取单据信息
        if ($object['status'] == 2) return true; // 后台校验已处理

        return parent::edit_d($object = array(
            'id' => $id,
            'status' => $status
        ), true);
    }

    /**
     * 管理员确认转借
     * @param $object
     * @return bool
     */
    function confirm_d($object) {
    	$id = $object['id'];
    	$obj = $this->get_d($id); // 获取单据信息
    	if ($obj['status'] == 2) return true; // 后台校验已处理
    
    	try {
    		$this->start_d();
    
    		// 明细处理
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
    
    		// 更新单据状态
    		$unConfirmNum = $elentdetailDao->findCount(array('mainId' => $id,'status' => 0));//获取未确认的明细记录数
    		if($unConfirmNum == 0){
    			$status = '2';// 全部确认
    		}else{
    			$status = '5';// 部分确认
    		}
    		$this->updateById(array('id' => $id, 'status' => $status, 'remark' => $object['remark'], 'confirmId' => $_SESSION['USER_ID'],
    				'confirmName' => $_SESSION['USERNAME'], 'confirmTime' => date('Y-m-d H:i:s')));
    
    		if($status == '2'){
    			// 邮件处理,通知接收人进行确认
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
     * 接收人最终确认转借
     * @param $object
     * @return bool
     */
    function finalConfirm_d($object) {
    	$id = $object['id'];
    	$obj = $this->get_d($id); // 获取单据信息
    	if ($obj['status'] == 4) return true; // 后台校验已处理
    
    	$elentDetailDao = new model_engineering_resources_elentdetail(); // 实例化转借申请明细
    	$otherDataDao = new model_common_otherdatas();
    
    	try {
    		$this->start_d();
    
    		// 单据状态修改
    		$this->updateById(array('id' => $id, 'status' => 4));
    
    		// 获取单据总借出数量,并更新设备实际转借日期
    		$amount = 0;
    		foreach ($object['item'] as $v) {
    			$amount = $amount + $v['number'];
    			$elentDetailDao->updateById(array('id' => $v['id'], 'realDate' => $v['realDate']));
    		}
    
    		// 归还处理 -----------------------------------------------
    
    		// 获取借出人区域属性
    		$userInfo = $otherDataDao->getUserDatas($object['applyUserId']);
    
    		// 新增归还单据
    		$sql = "INSERT INTO device_return_order (userid,dept_id,operatorid,amount,area,date,rand_key) VALUES ('" .
    				$object['applyUserId'] . "','" . $object['deptId'] . "','" . $_SESSION['USER_ID'] . "','" . $amount .
    				"','" . $userInfo['areaid'] . "',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
    		$this->_db->query($sql);
    		$returnId = $this->_db->insert_id();
    
    		// 新增归还单据从表并且处理借出单上的信息
    		foreach ($object['item'] as $v) { // 这里要去更新设备的借出情况
    			//归还日期以实际转借日期为准,这里转为时间戳
    			$realDate = strtotime($v['realDate']);
    			// 新增从表
    			$itemSql = "INSERT INTO device_return_order_info
                    (orderid,info_id,tid,amount,area,return_area,status,notse,date,rand_key)VALUES('" .
                        $returnId . "','" . $v['borrowItemId'] . "','" . $v['resourceId'] . "','" . $v['number'] . "','" .
                        $userInfo['areaid'] . "','" . $userInfo['areaid'] . "',''," .
                        "'".$v['remark']."'," . $realDate . ",MD5(UNIX_TIMESTAMP()))";
    			$this->_db->query($itemSql);
    
    			// 归还操作
    			$sql = "UPDATE device_borrow_order_info SET return_num = return_num + " . $v['number'] .
    				",returndate = IF(return_num = amount," . $realDate . ",NULL) WHERE id = " . $v['borrowItemId'];
    			$this->_db->query($sql);
    		}
 
    		// 借出处理 ------------------------------------------------
    
    		// 获取接收人属性
    		$userInfo = $otherDataDao->getUserDatas($object['receiverId']);
    
    		// 项目id转义
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
    
    		$infoIdArr = array(); // 缓存info_id的数组
    		// 从表数据处理
    		foreach ($object['item'] as $v) {
    			// 新增从表
    			$itemSql = "INSERT INTO device_borrow_order_info
                    (orderid,info_id,typeid,list_id,amount,targetdate,notse,claim,date,rand_key)VALUES('" .
                        $orderId . "','" . $v['resourceId'] . "','" . $v['resourceTypeId'] . "','" . $v['resourceListId'] .
                        "','" . $v['number'] . "'," . "'" . strtotime($v['endDate']) . "','".$v['remark']."',1," .
                        strtotime($v['realDate']) . ",MD5(UNIX_TIMESTAMP()))";
    			$this->_db->query($itemSql);
    
    			// 缓存infoId
    			array_push($infoIdArr, $v['resourceId']);
    		}
    
    		$infoIds = implode(',', $infoIdArr);
    
    		// 库存状态更新
    		$sql = "UPDATE device_info SET state = IF(amount = borrow_num, 1, 0) WHERE id IN($infoIds) AND state IN(0,1);";
    		$this->_db->query($sql);
    
    		// 获取资产卡片id
    		$sql = "SELECT GROUP_CONCAT(CAST(assetCardId AS CHAR)) AS assetCardIds FROM device_info WHERE id IN ($infoIds)";
    		$rs = $this->findSql($sql);
    		$assetCardIds = $rs[0]['assetCardIds'];
    
    		// 若存在关联资产卡片id,则更新其使用人及部门信息
    		if (!empty($assetCardIds)) {
    			$assetcardDao = new model_asset_assetcard_assetcard();
    			$assetcardDao->updateByEsmDevice($object['receiverId'], $assetCardIds);
    		}
    		// 设备锁定记录处理
    		$lockDao = new model_engineering_resources_lock();
    		$applyUserId = $object['applyUserId'];
    		if($lockDao->checkLock_d($applyUserId)){// 若该员工存在锁定记录
    			$lockDao->unlockAuto_d($applyUserId);// 执行解锁方法
    		}
    		// 邮件处理,告知申请人接收人已确认
    		$this->mailDeal_d('esmElentConfirm', implode(',', array($object['applyUserId'])), array('id' => $id));
    
    		$this->commit_d();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack();
    		return false;
    	}
    }
}