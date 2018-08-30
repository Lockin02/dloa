<?php
/**
 * @author Show
 * @Date 2012��9��3�� ����һ 19:51:29
 * @version 1.0
 * @description:����ģ����չ��Ϣ Model��
 */
class model_hr_baseinfo_trialplantemdetailex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_trialplantem_expand";
		$this->sql_map = "hr/baseinfo/trialplantemdetailexSql.php";
		parent :: __construct();
	}

	/**
	 * ���û��ֹ���
	 */
	function setRule_d($object){
		if (! is_array ( $object )) {
			throw new Exception ( "������������飡" );
		}
		$idArr = array ();
		foreach ( $object as $key => $val ) {
			$val=$this->addCreateInfo($val);
			$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;
			if (empty ( $val ['id'] ) && $isDelTag== 1) {

			} else if (empty ( $val ['id'] )) {
				$id = $this->add_d ( $val );
				$val ['id'] = $id;
				array_push ( $idArr, $id );
			} else if ($isDelTag == 1) {
				$this->deletes ( $val ['id'] );
			} else {
				$this->edit_d ( $val );
				array_push ( $idArr, $val ['id'] );
			}
		}
		return implode($idArr,',');
	}

	//��ȡ���ֹ���
	function getRule_d($ids){
		$this->searchArr = array(
			'ids' => $ids
		);
		$this->asc = false;
		return $this->list_d();
	}
}
?>