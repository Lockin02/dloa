<?php
/**
 * @author Show
 * @Date 2011��12��10�� ������ 9:59:32
 * @version 1.0
 * @description:��Ŀ����(oa_esm_project_risk) Model��
 */
class model_engineering_risk_esmrisk extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_risk";
		$this->sql_map = "engineering/risk/esmriskSql.php";
		parent::__construct ();
    }

    /****************************ҵ�񷽷�***************************/

    /**
     * ��ȡ��Ŀ����������Ϣ
     */
    function getObjInfo_d($projectId){
    	$serviceesmRiskDao = new model_engineering_project_esmproject();
    	$serviceRisk = $serviceesmRiskDao->get_d($projectId);
    	return $serviceRisk;
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