<?php

/**
 * @author xyt
 * @Date 2013年12月14日 星期五 14:23:30
 * @version 1.0
 * @description:项目设备归还表model层
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
            //生成归还单号
            $codeRuleDao = new model_common_codeRule();
            $obj['ereturn']['formNo'] = $codeRuleDao->commonCode('设备归还申请', $this->tbl_name, 'SBGH');
            $obj['ereturn']['areaName'] = $this->findArea($obj['ereturn']['areaId']);

            $newId = parent::add_d($obj['ereturn'], true);

            $objDetail = $obj['ereturnDetail'];

            //从表信息
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
     * 查询归还区域
     * @param $areaId
     * @return mixed
     */
    function findArea($areaId) {
        $query = $this->_db->query("select Name from area where ID = " . $areaId);
        $rs = $this->_db->fetch_array($query);
        return $rs['Name'];
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

        return parent::edit_d(array(
            'id' => $id,
            'status' => $status
        ), true);
    }

    /**
     * 管理员确认归还
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
            $ereturndetailDao = new model_engineering_resources_ereturndetail();
            $delItemNum = 0;//	页面删除的设备明细数量
            foreach ($object['item'] as $key => $val){
				if (isset($val['isDelTag'])){
            		$delItemNum ++;
            	} elseif (!isset($val['isChecked'])){// 不处理未删除,未勾选的设备明细
            		unset($object['item'][$key]);
            	}
            }
            if($delItemNum > 0){
            	$itemNum = $ereturndetailDao->findCount(array('mainId' => $id));
            	if($itemNum == $delItemNum){
            		msgRf('如果不想确认所有设备,请用列表的打回功能！');
            	}
            }
            $item = $ereturndetailDao->saveDelBatch($object['item']);
            
            if(count($item) > 0){//	如果设备明细全部被删除,则不执行以下处理
            	//获取单据总借出数量
            	$amount = 0;
            	foreach ($item as $v) {
            		$amount = $amount + $v['confirmNum'];
            	}
            	
            	// 归还处理 -----------------------------------------------
            	$otherDataDao = new model_common_otherdatas();
            	// 获取借出人区域属性
            	$userInfo = $otherDataDao->getUserDatas($object['applyUserId']);
            	// 获取登录人区域属性
            	$loggedUserInfo = $otherDataDao->getUserDatas($_SESSION['USER_ID']);
            	
            	// 新增归还单据
            	$sql = "INSERT INTO device_return_order (userid,dept_id,operatorid,amount,area,date,rand_key) VALUES ('" .
            			$object['applyUserId'] . "','" . $object['deptId'] . "','" . $_SESSION['USER_ID'] . "','" . $amount .
            			"','" . $userInfo['areaid'] . "',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
            	$this->_db->query($sql);
            	$returnId = $this->_db->insert_id();
            	
            	$listIdArr = array(); // 缓存list_id的数组
            	$infoIdArr = array(); // 缓存info_id的数组
            	// 新增归还单据从表并且处理借出单上的信息
            	foreach ($item as $v) { // 这里要去更新设备的借出情况
            		// 新增从表
            		$itemSql = "INSERT INTO
			                    device_return_order_info (orderid,info_id,tid,amount,area,return_area,status,notse,date,rand_key)
			                    VALUES
			                    ('" . $returnId . "','" . $v['borrowItemId'] . "','" . $v['resourceId'] . "','" . $v['confirmNum'] .
			            	    "','" . $userInfo['areaid'] . "','" . $userInfo['areaid'] . "',''," .
			            	    "'归还生成',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
            		$this->_db->query($itemSql);
            	
            		// 归还操作
            		$sql = "UPDATE device_borrow_order_info SET return_num = return_num + " . $v['confirmNum'] .
            				",returndate = IF(return_num = amount,UNIX_TIMESTAMP(),NULL) WHERE id = " . $v['borrowItemId'];
            		$this->_db->query($sql);
            	
            		// 归还时更新设备管理数量,并将设备的归属区域设为当前登录人的区域
            		$sql = "UPDATE device_info SET borrow_num = borrow_num - " . $v['confirmNum'] . ",area = ".$loggedUserInfo['areaid']." WHERE id = " .
            				$v['resourceId'];
            		$this->_db->query($sql);
            	
            		// 更新归还明细状态
            		if($v['number'] == $v['confirmNum']){
            			$ereturndetailDao->update(array('id' => $v['id']),array('status' => 1));// 全部已归还，将状态置为1
            		}
            	
            		// 缓存listId
            		array_push($listIdArr, $v['resourceListId']);
            		//缓存infoId
            		array_push($infoIdArr,$v['resourceId']);
            	}
            	
            	// 库存数量调整
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
            	
            	// 库存状态更新
            	$sql = "UPDATE device_info SET state = IF(amount = borrow_num, 1, 0) WHERE id IN($infoIds) AND state IN(0,1);";
            	$this->_db->query($sql);
            	
            	// 获取资产卡片id
            	$sql = "SELECT GROUP_CONCAT(CAST(assetCardId AS CHAR)) AS assetCardIds FROM device_info WHERE id IN ($infoIds)";
            	$rs = $this->findSql($sql);
            	$assetCardIds = $rs[0]['assetCardIds'];
            	// 若存在关联资产卡片id,则更新其使用人及部门信息
            	if(!empty($assetCardIds)){
            		$assetcardDao = new model_asset_assetcard_assetcard();
            		$assetcardDao->emptyByEsmDevice($assetCardIds);
            	}
            	
            	// 设备锁定记录处理
            	$lockDao = new model_engineering_resources_lock();
            	$applyUserId = $object['applyUserId'];
            	if($lockDao->checkLock_d($applyUserId)){// 若该员工存在锁定记录
            		$lockDao->unlockAuto_d($applyUserId);// 执行解锁方法
            	}
            }
            
            // 更新单据状态
            $unConfirmNum = $ereturndetailDao->findCount(array('mainId' => $id,'status' => 0));//获取未确认的明细记录数
            if($unConfirmNum == 0){
            	$status = '2';// 全部确认
            }else{
            	$status = '4';// 部分确认
            }
            $this->updateById(array('id' => $id, 'status' => $status, 'remark' => $object['remark'], 'confirmId' => $_SESSION['USER_ID'],
            		'confirmName' => $_SESSION['USERNAME'], 'confirmTime' => date('Y-m-d H:i:s')));
            
            if($status == '2'){
            	// 邮件处理
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