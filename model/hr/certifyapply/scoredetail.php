<?php
/**
 * @author Show
 * @Date 2012��8��24�� ������ 11:43:13
 * @version 1.0
 * @description:��ְ�ʸ���ί��ֱ� Model��
 */
class model_hr_certifyapply_scoredetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyapplyassess_scoredetail";
		$this->sql_map = "hr/certifyapply/scoredetailSql.php";
		parent :: __construct();
	}


	/**
	 * ��ȡ��ί��ֻ��� - ���⴦��
	 * ����Ϊ
	 * array(
	 *     'admin' => array(
	 *	       '��ΪҪ��id' => '�÷�',
	 *         '��ΪҪ��id' => '�÷�',
	 * 	   )
	 * )
	 */
	function getScoreDetail_d($assessId){
		$this->searchArr = array('assessId' => $assessId);
		$rs = $this->list_d();
		if($rs){
			//��������
			$rtArr = array();

			foreach($rs as $key => $val){
				$rtArr[$val['managerId']][$val['detailId']]['score'] = $val['score'];
				$rtArr[$val['managerId']][$val['detailId']]['id'] = $val['id'];
				$rtArr[$val['managerId']][$val['detailId']]['scoreId'] = $val['scoreId'];
			}

			return $rtArr;
		}else{
			return false;
		}
	}
}
?>