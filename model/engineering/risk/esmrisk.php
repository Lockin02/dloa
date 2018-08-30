<?php
/**
 * @author Show
 * @Date 2011年12月10日 星期六 9:59:32
 * @version 1.0
 * @description:项目风险(oa_esm_project_risk) Model层
 */
class model_engineering_risk_esmrisk extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_risk";
		$this->sql_map = "engineering/risk/esmriskSql.php";
		parent::__construct ();
    }

    /****************************业务方法***************************/

    /**
     * 获取项目风险所需信息
     */
    function getObjInfo_d($projectId){
    	$serviceesmRiskDao = new model_engineering_project_esmproject();
    	$serviceRisk = $serviceesmRiskDao->get_d($projectId);
    	return $serviceRisk;
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