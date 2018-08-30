<?php

/**
 *
 * �ʲ��ɹ�������ϸmodel
 * @author fengxw
 *
 */
class model_asset_purchase_task_taskItem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_purchase_task_item";
		$this->sql_map = "asset/purchase/task/taskItemSql.php";
		parent::__construct ();
	}

	/**
	 * ���ݲɹ�����Id����ȡ�ɹ���������
	 *@param $parentId �ɹ�����ID
	 */
	 function getItemByParent_d($parentId){
		$searchArr = array (
			"parentId" => $parentId
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId();
		return $rows;
	 }

	/**
	 * @exclude ���²ɹ����������´ﶩ������
	 */
	function updateIssuedAmount($equId,$issuedAmount,$lastCheckNum=false){
		if(isset($lastCheckNum)&&$issuedAmount==$lastCheckNum){
			return true;
		}else{
			if($lastCheckNum){
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedAmount - $lastCheckNum) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedAmount) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}

}
?>
