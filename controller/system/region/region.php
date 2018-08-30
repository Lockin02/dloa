<?php

/**
 * @author Administrator
 * @Date 2011年7月22日 9:41:21
 * @version 1.0
 * @description:oa_system_region控制层 大区---负责人表
 */
class controller_system_region_region extends controller_base_action
{

	function __construct() {
		$this->objName = "region";
		$this->objPath = "system_region";
		parent::__construct();
	}

	/**
	 * 跳转到oa_system_region
	 */
	function c_page() {
		$this->view('list');
	}

	function c_toAdd() {
		$this->assign('businessBelong', $_SESSION['Company']);
		//获取公司名称
		$branchDao = new model_deptuser_branch_branch();
		$companyInfo = $branchDao->getByCode($_SESSION['Company']);
		$this->assign('businessBelongName', $companyInfo['NameCN']);

		$this->assign('formBelong', $_SESSION['COM_BRN_PT']);
		$this->assign('formBelongName', $_SESSION['COM_BRN_CN']);

		$this->view('add');
	}


	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
			if ($obj['isStart'] == '0') {
				$this->assign('isStart', '开启');
			} else {
				$this->assign('isStart', '关闭');
			}
			$this->assign('module', $this->getDataNameByCode($obj['module']));
			$this->display('view');
		} else {
			if ($obj['isStart'] == '0') {
				$this->assign('isStartY', 'checked');
			} else {
				$this->assign('isStartN', 'checked');
			}
			if ($obj['expand'] == '0') {
				$this->assign('isExpandN', 'checked');
			} else {
				$this->assign('isExpandY', 'checked');
			}
//			$this->assign('module', $this->getDataNameByCode($obj['module']));
			$this->showDatadicts(array('module' => 'HTBK'), $obj['module']);
			$this->display('edit');
		}
	}

	/**
	 * 区域负责人
	 */
	function c_listAreaPrincipalJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->groupBy = "areaPrincipalId,areaPrincipal";
		$rows = $service->list_d("select_principal");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($this->addRoot($rows, 'areaPrincipal'));
	}

	/**
	 * ajax获取合同所属区域
	 */
	function c_ajaxConRegion() {
		//获取销售负责人信息
        if(isset($_POST['getAll']) && $_POST['getAll']){
            $rs = $this->service->conRegion_d($_POST['customerType'],$_POST['province'],$_POST['module'],
                isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '',$_POST['getAll']);
        }else{
            $rs = $this->service->conRegion_d($_POST['customerType'],$_POST['province'],$_POST['module'],
                isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '');
        }
		echo util_jsonUtil::encode($rs);
	}

	/**
	 * ajax获取合同所属区域的执行区域
	 * 最后一次更新：2016-12-15 PMS 2313
	 */
	function c_ajaxChkExeDept(){
		$areaCode = (isset($_POST['areaCode']))? $_POST['areaCode'] : '';
		$rsArr = $this->service->chkExeDeptByAreaId_d($areaCode);
		echo util_jsonUtil::encode($rsArr);
	}

	//ajax获取对应所属区域 （中文参数）
	function c_ajaxConRegionByName() {
        $businessBelong = '';
	    $needAll = isset($_REQUEST['needAll'])? $_REQUEST['needAll'] : 0;// 判定是否需要返回所有的数据 2017-01-12 关联PMS2383
        $getCompanyByUid = isset($_REQUEST['getCompanyByUid'])? $_REQUEST['getCompanyByUid'] : 0;// 判定是否需要根据UID获取归属公司 2017-01-12 关联PMS2383
        if($getCompanyByUid){
            $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
            $sql = "SELECT b.NameCN AS businessBelong FROM user u LEFT JOIN branch_info b ON u.Company = b.NamePT WHERE u.USER_ID = '{$userId}';";
            $arr = $this->service->_db->getArray($sql);
            if($arr){
                $businessBelong = $arr[0]['businessBelong'];
            }
        }

		//获取销售负责人信息
		$rs = $this->service->conRegionByName_d(util_jsonUtil::iconvUTF2GB($_POST['customerType']),
				util_jsonUtil::iconvUTF2GB($_POST['province']),
				isset($_POST['module']) ? util_jsonUtil::iconvUTF2GB($_POST['module']) : '',
				isset($_POST['businessBelong']) ? util_jsonUtil::iconvUTF2GB($_POST['businessBelong']) : $businessBelong,$needAll);
		echo util_jsonUtil::encode($rs);
	}
	
	/**
	 * ajax获取合同所属板块
	 */
	function c_ajaxConModule() {
		$province = util_jsonUtil::iconvUTF2GB($_POST['province']);
		$city = util_jsonUtil::iconvUTF2GB($_POST['city']);
		$customerTypeName = util_jsonUtil::iconvUTF2GB($_POST['customerTypeName']);
		$personId = $_POST['personId'];
		
		//获取销售负责人信息
		$rs = $this->service->conModule_d($province,$city,$customerTypeName,$personId);
		if($rs){
			echo util_jsonUtil::encode($rs);
		}else{
			echo "";
		}
	}
}