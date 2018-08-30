<?php

/**
 * @author show
 * @Date 2013年12月9日 19:17:43
 * @version 1.0
 * @description:续借申请单 Model层
 */
class model_engineering_resources_erenew extends model_base
{
    function __construct() {
        $this->tbl_name = "oa_esm_resource_erenew";
        $this->sql_map = "engineering/resources/erenewSql.php";
        parent::__construct();
    }

    /**
     * 重写add_d
     * @param $obj
     * @return bool|void
     * @throws Exception
     */
    function add_d($obj) {
        //截取数据
        $erenewdetail = $obj['erenewdetail'];
        unset($obj['erenewdetail']);
        try {
            $this->start_d();
            //生成归还单号
            $codeRuleDao = new model_common_codeRule();
            $obj['formNo'] = $codeRuleDao->commonCode('设备续借申请', $this->tbl_name, 'SBXJ');

            $newId = parent::add_d($obj, true);

            //从表信息
            $detailDao = new model_engineering_resources_erenewdetail();
            $erenewdetail = util_arrayUtil::setArrayFn(array('mainId' => $newId), $erenewdetail);
            $detailDao->saveDelBatch($erenewdetail);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 重写edit方法
     * @param $object
     * @throws Exception
     */
    function edit_d($object) {
        //截取数据
        $erenewdet = $object['erenewdetail'];
        unset($object['erenewdetail']);
        try {
            $this->start_d();
            //数据字典
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //从表信息
            $detailDao = new model_engineering_resources_erenewdetail();
            $erenewdet = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $erenewdet);
            $detailDao->saveDelBatch($erenewdet);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 修改状态
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
     * 管理员确认续借
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
        	$erenewdetailDao = new model_engineering_resources_erenewdetail();
        	$erenewdetailDao->saveDelBatch($object['item']);
        
        	// 更新单据状态
        	$unConfirmNum = $erenewdetailDao->findCount(array('mainId' => $id,'status' => 0));//获取未确认的明细记录数
        	if($unConfirmNum == 0){
        		$status = '2';// 全部确认
        	}else{
        		$status = '5';// 部分确认
        	}
        	$this->updateById(array('id' => $id, 'status' => $status, 'remark' => $object['remark'], 'confirmId' => $_SESSION['USER_ID'],
        			'confirmName' => $_SESSION['USERNAME'], 'confirmTime' => date('Y-m-d H:i:s')));
        
        	if($status == '2'){
        		// 邮件处理
        		$this->mailDeal_d('esmRenewWaitConfirm', implode(',', array($object['applyUserId'])), array('id' => $id));
        	}
  	 
        	$this->commit_d();
        	return true;
        } catch (Exception $e) {
        	$this->rollBack();
        	return false;
        }
    }
    
    /**
     * 申请人最终确认续借
     * @param $object
     * @return bool
     */
    function finalConfirm_d($object) {
    	$id = $object['id'];
    	$obj = $this->get_d($id); // 获取单据信息
    	if ($obj['status'] == 4) return true; // 后台校验已处理
    
    	$erenewdetailDao = new model_engineering_resources_erenewdetail(); // 实例化续借申请明细
    	$otherDataDao = new model_common_otherdatas();
    
    	try {
    		$this->start_d();
    
    		// 单据状态修改
    		$this->updateById(array('id' => $id, 'status' => 4));
    
    		// 获取单据总借出数量,并更新设备实际转借日期
    		$amount = 0;
    		foreach ($object['item'] as $v) {
    			$amount = $amount + $v['number'];
    			$erenewdetailDao->updateById(array('id' => $v['id'], 'realDate' => $v['realDate']));
    		}
    
    		// 归还处理 -----------------------------------------------
    
    		// 获取借出人区域属性
    		$userInfo = $otherDataDao->getUserDatas($object['applyUserId']);
    
    		// 新增归还单据
    		$sql = "INSERT INTO device_return_order (userid,dept_id,operatorid,amount,area,date,rand_key) VALUES ('" .
    				$object['applyUserId'] . "','" . $object['deptId'] . "','" . $_SESSION['USER_ID'] . "','" . $amount . "','" . $userInfo['areaid'] . "'," .
    				"UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
    		$this->_db->query($sql);
    		$returnId = $this->_db->insert_id();
    
    		// 新增归还单据从表并且处理借出单上的信息
    		foreach ($object['item'] as $v) { // 这里要去更新设备的借出情况
    			//归还日期以实际转借日期为准,这里转为时间戳
    			$realDate = strtotime($v['realDate']);
    			// 新增从表
    			$itemSql = "INSERT INTO device_return_order_info (orderid,info_id,tid,amount,area,return_area,status,notse,date,rand_key)VALUES('" .
    					$returnId . "','" . $v['borrowItemId'] . "','" . $v['resourceId'] . "','" . $v['number'] . "','" . $userInfo['areaid'] . "','" . $userInfo['areaid'] . "',''," .
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
    		if ($object['projectId']) {
    			$esmprojectDao = new model_engineering_project_esmproject();
    			$projectId = $esmprojectDao->getOldProjectId_d($object['projectId']);
    		} else {
    			$projectId = 0;
    		}
    		// 主表数据处理
    		$sql = "INSERT INTO device_borrow_order
                (userid,dept_id,project_id,operatorid,manager,area,targetdate,date,rand_key,amount,confirm)VALUES('" .
                    $object['applyUserId'] . "','" . $object['deptId'] . "','" . $projectId . "','" . $_SESSION['USER_ID'] . "','" .
                    $object['managerId'] . "','" . $userInfo['areaid'] . "'," .
                    "UNIX_TIMESTAMP(),UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()),$amount,1)";
    		$this->_db->query($sql);
    		$orderId = $this->_db->insert_id();
    
    		// 从表数据处理
    		foreach ($object['item'] as $v) {
    			// 新增从表
    			$itemSql = "INSERT INTO device_borrow_order_info
                    (orderid,info_id,typeid,list_id,amount,targetdate,notse,claim,date,rand_key)VALUES('" .
                        $orderId . "','" . $v['resourceId'] . "','" . $v['resourceTypeId'] . "','" . $v['resourceListId'] .
                        "','" . $v['number'] . "'," . "'" . strtotime($v['endDate']) . "','".$v['remark']."',1," .
                        strtotime($v['realDate']) . ",MD5(UNIX_TIMESTAMP()))";
    			$this->_db->query($itemSql);
    		}
    		// 设备锁定记录处理
    		$lockDao = new model_engineering_resources_lock();
    		$applyUserId = $object['applyUserId'];
    		if($lockDao->checkLock_d($applyUserId)){// 若该员工存在锁定记录
    			$lockDao->unlockAuto_d($applyUserId);// 执行解锁方法
    		}
    		
    		$this->commit_d();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack();
    		return false;
    	}
    }
}