<?php
/**
 * @author Show
 * @Date 2011��12��8�� ������ 18:57:10
 * @version 1.0
 * @description:��Ŀ����(oa_esm_project_quality) Model��
 */
class model_engineering_quality_esmquality extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_quality";
		$this->sql_map = "engineering/quality/esmqualitySql.php";
		parent::__construct ();
    }

	/****************************ҵ�񷽷�***************************/
    /**
     * ��ȡ��Ŀ����������Ϣ
     */
    function getObjInfo_d($projectId){
    	$serviceesmQualityDao = new model_engineering_project_esmproject();
    	$serviceQuality = $serviceesmQualityDao->get_d($projectId);
    	return $serviceQuality;
    }

	/**
	 * ��д��������
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * ��д�޸ķ���
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}
}
?>