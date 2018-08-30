<?php

/**
 * @author Administrator
 * @Date 2012��11��20�� 11:41:36
 * @version 1.0
 * @description:���֪ͨ�� Model��
 */
class model_stock_withdraw_innotice extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_innotice";
		$this->sql_map = "stock/withdraw/innoticeSql.php";
		parent :: __construct();
	}

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

	/*--------------------------------------------ҵ�����--------------------------------------------*/

	/**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if (is_array($object['items'])) {
				$codeRuleDao = new model_common_codeRule();
				$object['noticeCode'] = $codeRuleDao->sendNoticeCode($object['docType'],$this->tbl_name);
				$id = parent :: add_d($object, true);

				$equDao = new model_stock_withdraw_noticeequ();
				$itemsArr = util_arrayUtil::setArrayFn(array('mainId' => $id,'drawId' =>$object['drawId'] ),$object['items']);
				$itemsObj = $equDao->saveDelBatch($itemsArr);

		  		if( $object['drawId'] ){
					//�����´�����
					$withEquDao = new model_stock_withdraw_equ();
					foreach($itemsArr as $val){
						$withEquDao->updateExecutedInfo($val['planEquId'],$val['number']);
					}

					//����֪ͨ��״̬
		  			$planDao = new model_stock_withdraw_withdraw();
					$planDao->updateBusinessByNotice($object['drawId']);
		  		}
			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			if (is_array($object['items'])) {
				$editResult = parent :: edit_d($object, true);
				$equDao = new model_stock_withdraw_noticeequ();
				$itemsArr = util_arrayUtil :: setItemMainId("mainId", $object['id'], $object['items']);
				$itemsObj = $equDao->saveDelBatch($itemsArr);
			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent :: get_d($id);
		$equDao = new model_stock_withdraw_noticeequ();
		$equDao->searchArr['mainId'] = $id;
		$object['items'] = $equDao->listBySqlId();
		return $object;
	}

	/**
	 * ��ȡԴ������
	 */
	function getDocEqu_d($id) {
		$equDao = new model_stock_withdraw_equ();
		$equDao->searchArr['mainId'] = $id;
		$equDao->searchArr['isDel'] = 0;
		$row = $equDao->list_d();
		if($row){
			foreach($row as &$val){
				$canApplyNum = $val['qPassNum'] + $val['qBackNum'];
				if($canApplyNum == 0) continue;
				$val['docNumber'] = $val['number'];
				$val['number'] = $canApplyNum - $val['executedNum'];
			}
		}
		return $row;
	}

	/**
	 * ������ʱ���и�������֪ͨ����Ϣ
	 * @param  $id   ����֪ͨ��ID
	 * @param  $equId   �����嵥ID
	 * @param  $productId   ����ID
	 * @param  $proNum    �������
	 */
	function updateInStock($id,$equId,$productId,$proNum){
		try {
//			$this->start_d();
			$noticeequDao=new model_stock_withdraw_noticeequ();
			$noticeequDao->updateNumb_d($id,$equId,$proNum);//�������ϵ��������

			//����״̬
			$this->updateStatus_d($id);

//			$this->commit_d();
		} catch (Exception $e) {
//			$this->rollBack();
			return null;
		}
	}

	/**
	 * ��������ʱ���и�������֪ͨ����Ϣ
	 * @param  $id   ����֪ͨ��ID
	 * @param  $equId   �����嵥ID
	 * @param  $productId   ����ID
	 * @param  $proNum    �������
	 */
	function updateInStockCancel($id,$equId,$productId,$proNum){
		try {
//			$this->start_d();
			$noticeequDao=new model_stock_withdraw_noticeequ();
			$noticeequDao->updateNumb_d($id,$equId,-$proNum);

			//����״̬
			$this->updateStatus_d($id);

//			$this->commit_d();
		} catch (Exception $e) {
//			$this->rollBack();
			return null;
		}
	}

	/**
	 * ���µ���״̬
	 */
	function updateStatus_d($id){
		$sql = "SELECT sum(number) as number,sum(executedNum) as executedNum FROM oa_stock_innotice_equ where mainId = $id";
		$arr = $this->_db->getArray($sql);
		if($arr[0]['number'] == $arr[0]['executedNum']){
			$docStatus = "YRK";
		}else{
			$docStatus = "WRK";
		}
		return $this->update(array('id'=>$id),array('docStatus'=>$docStatus));
	}
}
?>