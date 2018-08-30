<?php
/**
 * @author show
 * @Date 2014��08��01�� 
 * @version 1.0
 * @description:�豸������������ Model�� 
 */
class model_engineering_resources_lock extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_lock";
		$this->sql_map = "engineering/resources/lockSql.php";
		parent::__construct ();
	}
		
	/**
	 * �Զ���������
	 */
	function autoLock_d($obj){
		//��Ϊ����״̬
		$obj['status'] = 1;
		//��������
		$obj['lockDate'] = day_date;
		//��ѯ��Ա���Ƿ����������¼
		$rs = $this->find(array('userId' => $obj['userId'],'userName' => $obj['userName']),null,'id');
		if(!empty($rs)){//��������¸ü�¼
			$obj['id'] = $rs['id'];
			return parent::edit_d($obj);
		}else{//������������
			return parent::add_d($obj);
		}
	}
	
	/**
	 * �ֶ���������
	 */
	function unlock_d($id){
		$obj = array(
				'id' => $id,
				'status' => 0
		);
		return parent::edit_d($obj);
	}
	
	/**
	 * �Զ���������
	 */
	function unlockAuto_d($userId){
		//��ȡ��Ա�����������豸����
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
		if($rs[0]['count'] == 0){//�������������豸������н���
			return $this->update(array('userId' => $userId),array('status' => 0));
		}else{
			return  false;
		}	
	}
	
	/**
	 * ��ѯԱ���Ƿ����������¼
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