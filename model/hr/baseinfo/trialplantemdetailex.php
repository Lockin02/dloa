<?php
/**
 * @author Show
 * @Date 2012年9月3日 星期一 19:51:29
 * @version 1.0
 * @description:任务模板扩展信息 Model层
 */
class model_hr_baseinfo_trialplantemdetailex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_trialplantem_expand";
		$this->sql_map = "hr/baseinfo/trialplantemdetailexSql.php";
		parent :: __construct();
	}

	/**
	 * 设置积分规则
	 */
	function setRule_d($object){
		if (! is_array ( $object )) {
			throw new Exception ( "传入对象不是数组！" );
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

	//获取评分规则
	function getRule_d($ids){
		$this->searchArr = array(
			'ids' => $ids
		);
		$this->asc = false;
		return $this->list_d();
	}
}
?>