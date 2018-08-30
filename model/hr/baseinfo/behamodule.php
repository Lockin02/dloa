<?php
/**
 * @author Show
 * @Date 2012��8��20�� ����һ 20:12:40
 * @version 1.0
 * @description:��Ϊģ������ Model��
 */
class model_hr_baseinfo_behamodule extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_behamodule";
		$this->sql_map = "hr/baseinfo/behamoduleSql.php";
		parent :: __construct();
	}

	/**************** ��ɾ�Ĳ� ************************/
	/**
	 * ��дadd_d
	 */
	function add_d($object){
        //��ȡ��ΪҪ��
        $behamoduledetail = $object['behamoduledetail'];
        unset($object['behamoduledetail']);

		try {
			$this->start_d ();

			//��������
			$newId = parent::add_d ( $object, true );

            //���������Ա
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
	 * ��дedit_d
	 */
	function edit_d($object){
        //��ȡ��ΪҪ��
        $behamoduledetail = $object['behamoduledetail'];
        unset($object['behamoduledetail']);

		try {
			$this->start_d ();

			//��������
			parent::edit_d ( $object, true );

            //���������Ա
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