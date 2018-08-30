<?php
/**
 * @author Show
 * @Date 2011年12月8日 星期四 18:57:10
 * @version 1.0
 * @description:项目质量(oa_esm_project_quality) Model层
 */
class model_engineering_quality_esmquality extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_quality";
		$this->sql_map = "engineering/quality/esmqualitySql.php";
		parent::__construct ();
    }

	/****************************业务方法***************************/
    /**
     * 获取项目质量所需信息
     */
    function getObjInfo_d($projectId){
    	$serviceesmQualityDao = new model_engineering_project_esmproject();
    	$serviceQuality = $serviceesmQualityDao->get_d($projectId);
    	return $serviceQuality;
    }

	/**
	 * 重写新增方法
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * 重写修改方法
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}
}
?>