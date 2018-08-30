<?php
/**
 * @author Administrator
 * @Date 2011��1��17�� 13:05:29
 * @version 1.0
 * @description:����ƻ���Ʒ��Ϣ Model��
 */
class model_stock_fillup_filluppro extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_fillup_detail";
		$this->sql_map = "stock/fillup/fillupproSql.php";
		parent::__construct ();
	}

	/*
	 * ���²���ƻ��ĵ�������
	 */
	function updateProArrNum($id, $arrivalNum) {
		$sql = "update " . $this->tbl_name . " set arrivalNum=arrivalNum+$arrivalNum ,unArrivalNum=unArrivalNum-$arrivalNum where id=$id";
		return $this->query ( $sql );
	}

	/**���ݲ��ⵥID����ȡ����ص�������Ϣ
	 *author can
	 *2011-3-10
	 */
	function getItemByFillUpId($fillUpId) {
		return $this->findAll ( array ('fillUpId' => $fillUpId ) );
	}

	/**
	 * ���²���ƻ������´�ɹ�����
	 * @author huangzf
	 */
	function updateIssuedPurNum($id, $issuedPurNum) {
		$sql = "update " . $this->tbl_name . " set issuedPurNum=issuedPurNum+$issuedPurNum  where id=$id";
		return $this->query ( $sql );
	}

		/**
	 * ����ID��ȡ�豸�嵥
	 */
	function getAllEqu_d($planId){
		$this->searchArr=array("fillUpId"=>$planId);
//		$this->groupBy="c.id";
		$equs = $this->listBySqlId('select_all');
		return $equs;
	}

	/**
	 * �������´�ɹ�����  (�����)
	 *@author huangzf
	 *
	 */
	function updateAmountIssued($id, $issuedNum, $lastIssueNum = false) {
		if (isset ( $lastIssueNum ) && $issuedNum == $lastIssueNum) {
			return true;
		} else {
			if ($lastIssueNum) {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=(ifnull(issuedPurNum,0)  + $issuedNum - $lastIssueNum) where id='$id'  ";
			} else {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=ifnull(issuedPurNum,0) + $issuedNum where id='$id' ";
			}
			return $this->query ( $sql );
		}
	}
}
?>