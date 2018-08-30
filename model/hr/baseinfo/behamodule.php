<?php
/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:12:40
 * @version 1.0
 * @description:行为模块配置 Model层
 */
class model_hr_baseinfo_behamodule extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_behamodule";
		$this->sql_map = "hr/baseinfo/behamoduleSql.php";
		parent :: __construct();
	}

	/**************** 增删改查 ************************/
	/**
	 * 重写add_d
	 */
	function add_d($object){
        //获取行为要项
        $behamoduledetail = $object['behamoduledetail'];
        unset($object['behamoduledetail']);

		try {
			$this->start_d ();

			//新增任务
			$newId = parent::add_d ( $object, true );

            //处理任务成员
            $behamoduledetailDao = new model_hr_baseinfo_behamoduledetail();
            $behamoduledetailDao->createBatch($behamoduledetail,array('moduleId' => $newId,'moduleName' => $object['moduleName']));

			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 重写edit_d
	 */
	function edit_d($object){
        //获取行为要项
        $behamoduledetail = $object['behamoduledetail'];
        unset($object['behamoduledetail']);

		try {
			$this->start_d ();

			//新增任务
			parent::edit_d ( $object, true );

            //处理任务成员
            $behamoduledetailDao = new model_hr_baseinfo_behamoduledetail();
            $behamoduledetailDao->saveDelBatch($behamoduledetail);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
}
?>