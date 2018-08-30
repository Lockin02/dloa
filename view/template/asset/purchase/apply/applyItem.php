<?php

/**
 *
 * �ʲ��ɹ�������ϸmodel
 * @author fengxw
 *
 */
class model_asset_purchase_apply_applyItem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_purchase_apply_item";
		$this->sql_map = "asset/purchase/apply/applyItemSql.php";
		parent::__construct ();
	}
//
	/**
	 * �����´�������Ϊnull��0������
	 */
    function findIssuedAmount(){
        return $this->listBySql ( "select * from oa_asset_purchase_apply_item c where (c.issuedAmount is null or c.issuedAmount=0) " );
    }

	/**
	 * ���˼�ɾ����־Ϊ1������
	 */
    function findIsDel($applyID){
        return $this->listBySql ( "select * from oa_asset_purchase_apply_item c where c.applyId=".$applyID." and c.isDel=0" );
    }


	/**
	 * ����Id����ȡ�ɹ���������
	 *@param $idArr id����
	 */
	 function getItemByIdArr($idArr){
		$searchArr = array (
			"idArr" => $idArr
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * ���ݲɹ�����Id����ȡ�ɹ���������
	 *@param $applyID �ɹ�����ID
	 *@param $purchDept �ɹ�����
	 *@param $isDel ��ɾ����־
	 */
	 function getItemByApplyId($applyID,$purchDept=null,$isDel=null){
		$searchArr = array (
			"applyId" => $applyID,
			"purchDept"=>$purchDept,
			"isDel"=>$isDel
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * ���ݲɹ�����Id����ȡû�м�ɾ���ɹ���������
	 *@param $applyID �ɹ�����ID
	 *@param $isDel �ɹ�����
	 */
	 function getDelItemByApplyId($applyID,$isDel=null){
		$searchArr = array (
			"applyId" => $applyID,
			"isDel"=>$isDel
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }


	/**
	 * ���ݲɹ�����Id����ȡ�ɹ���������
	 *@param $applyID �ɹ�����ID
	 */
	 function getItemByApplyIds($applyID){
		$searchArr = array (
			"applyId" => $applyID
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * ���ݲɹ�����Id����ȡ�ɹ���������
	 *@param $applyID �ɹ�����ID
	 */
	 function getItem_d($applyID){
		$searchArr = array (
			"applyId" => $applyID
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * ���ݲɹ�����Id����ȡ�ɹ��������ϣ������ɹ���
	 *@param $applyID �ɹ�����ID
	 */
	 function getPurchItem_d($applyID){
		$searchArr = array (
			"applyId" => $applyID,
			"purchDept"=>'1'
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * @exclude ���²ɹ����������´���������
	 */
	function updateAmountIssued($equId,$issuedNum,$lastIssueNum=false){
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedNum - $lastIssueNum) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedNum) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}

	/**
	 * @exclude ���²ɹ����������´���������
	 */
	function updateAmountCheck($equId,$checkNum,$lastCheckNum=false){
		if(isset($lastCheckNum)&&$checkNum==$lastCheckNum){
			return true;
		}else{
			if($lastCheckNum){
				$sql = " update ".$this->tbl_name." set checkAmount=(ifnull(checkAmount,0) + $checkNum - $lastCheckNum) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set checkAmount=(ifnull(checkAmount,0) + $checkNum) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}

	/**
	 * ��ȡ���������ϸ����
	 */
	function getApplyedNum_d($requireItemId){
		$sql = "select sum(applyAmount) as applyAmount from ".$this->tbl_name." where requireItemId = '$requireItemId' group by requireItemId";
		$rs = $this->_db->getArray($sql);
		if($rs[0]['applyAmount']){
			return $rs[0]['applyAmount'];
		}else{
			return 0;
		}
	}
}
?>
