<?php
/**
 * @author show
 * @Date 2014年08月01日 
 * @version 1.0
 * @description:设备借用申请锁定 Model层 
 */
class model_engineering_resources_lock extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_lock";
		$this->sql_map = "engineering/resources/lockSql.php";
		parent::__construct ();
	}
		
	/**
	 * 自动锁定方法
	 */
	function autoLock_d($obj){
		//置为锁定状态
		$obj['status'] = 1;
		//锁定日期
		$obj['lockDate'] = day_date;
		//查询该员工是否存在锁定记录
		$rs = $this->find(array('userId' => $obj['userId'],'userName' => $obj['userName']),null,'id');
		if(!empty($rs)){//存在则更新该记录
			$obj['id'] = $rs['id'];
			return parent::edit_d($obj);
		}else{//不存在则新增
			return parent::add_d($obj);
		}
	}
	
	/**
	 * 手动解锁方法
	 */
	function unlock_d($id){
		$obj = array(
				'id' => $id,
				'status' => 0
		);
		return parent::edit_d($obj);
	}
	
	/**
	 * 自动解锁方法
	 */
	function unlockAuto_d($userId){
		//获取该员工被锁定的设备数量
		$sql = "
				SELECT
					COUNT(*) as count
				FROM
					device_borrow_order_info i
				LEFT JOIN device_borrow_order o ON o.id = i.orderid
				WHERE
					i.amount > i.return_num
				AND DATEDIFF(
					CURDATE(),
					FROM_UNIXTIME(i.targetdate, '%Y-%m-%d')
				) >= 8
				AND o.userid = '".$userId."'";
		$rs = $this->findSql($sql);
		if($rs[0]['count'] == 0){//不存在锁定的设备，则进行解锁
			return $this->update(array('userId' => $userId),array('status' => 0));
		}else{
			return  false;
		}	
	}
	
	/**
	 * 查询员工是否存在锁定记录
	 */
	function checkLock_d($userId){
		$rs = $this->find(array('userId' => $userId,'status' => 1),null,'id');
		if(!empty($rs)){
			return true;
		}else{
			return false;
		}
	}
}