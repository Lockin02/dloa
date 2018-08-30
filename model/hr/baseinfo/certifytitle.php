<?php
/**
 * @author Show
 * @Date 2012年9月7日 17:14:21
 * @version 1.0
 * @description:任职资格称谓表 Model层
 */
class model_hr_baseinfo_certifytitle  extends model_base {

	function __construct() {
		$this -> tbl_name = "oa_hr_baseinfo_certifytitle";
		$this -> sql_map = "hr/baseinfo/certifytitleSql.php";
		parent::__construct();
		$this->statusDao = new model_common_status ();
		$this -> statusDao -> status = array(
			1 => array(
				'statusEName' => 'open', 
				'statusCName' => '启用', 
				'key' => '1'
			), 
			0 => array(
				'statusEName' => 'close', 
				'statusCName' => '关闭', 
				'key' => '0'
			)
		);
	}

	/**
	 * 添加数据内容
	 */
	function add_d($object, $isAddInfo = false) {
		$datadictDao = new model_system_datadict_datadict();
		$object['careerDirectionName'] = $datadictDao -> getDataNameByCode($object['careerDirection']);
		$object['baseLevelName'] = $datadictDao -> getDataNameByCode($object['baseLevel']);
		$object['baseGradeName'] = $datadictDao -> getDataNameByCode($object['baseGrade']);
		//获取公司信息
		$userDao = new model_deptuser_user_user();
		$companyDao = new model_deptuser_branch_branch();
		$temp = $userDao -> find(array('USER_ID' => $_SESSION['USER_ID']));
		$companyinfo = $companyDao -> getBranchName_d($temp['Company']);
		$object['sysCompanyName'] = $companyinfo['NameCN'];
		$object['sysCompanyId'] = $companyinfo['ID'];
		//提交添加
		$newId = parent::add_d($object, true);
		return $newId;
	}

	function edit_d($object, $isEditInfo = false) {
		$datadictDao = new model_system_datadict_datadict();
		$object['careerDirectionName'] = $datadictDao -> getDataNameByCode($object['careerDirection']);
		$object['baseLevelName'] = $datadictDao -> getDataNameByCode($object['baseLevel']);
		$object['baseGradeName'] = $datadictDao -> getDataNameByCode($object['baseGrade']);
		$editId = parent::edit_d($object, true);
		return $editId;
	}

}
?>