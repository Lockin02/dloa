<?php
/**
 * @author xyt
 * @Date 2013年12月14日 星期五 14:23:17
 * @version 1.0
 * @description:项目设备归还表控制层
 */
class controller_engineering_resources_ereturn extends controller_base_action {

	function __construct() {
		$this->objName = "ereturn";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}
	
	/**
	 * 设备归还单列表
	 */
	function c_page(){
		$this->view('list');
	}
	
	/**
	 * 设备归还单列表
	 */
	function c_pageJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
        // 部门权限处理
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['部门权限'];
        if(strpos($deptLimit,';;') === false){
            if(empty($deptLimit)){
                $service->searchArr['deviceDeptIds'] = $_SESSION['DEPT_ID'];
            }else{
                $service->searchArr['deviceDeptIds'] = $deptLimit.','.$_SESSION['DEPT_ID'];
            }
        }

		$rows = $service->page_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 我的设备归还列表
	 */
	function c_tomylist(){
		$this->view('mylist');
	}
	
	/**
	 * 项目设备归还列表（根据项目id过滤）
	 */
	function c_prolist(){
		$this->assign('projectId',$_GET['projectId']);
		$this->view('prolist');
	}

	/**
	 * 设备归还单列表
	 */
	function c_proPageJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 我的设备归还列表
	 */
	function c_mylistJson(){
		$_REQUEST['charger'] = $_SESSION['USER_ID'];
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d();
		
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
	
	/**
	 * 添加归还单
	 */
	function c_toAdd(){
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());//区域下拉
		$this->assign('rowsId', $_GET['rowsId']);
		$this->assign('applyUser', $_SESSION['USERNAME']);
		$this->assign('applyUserId', $_SESSION['USER_ID']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('applyDate', day_date);

        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_GET['deviceDeptId']);
        $this->assign('deviceDeptName', $deptInfo['DEPT_NAME']);
        $this->assign('deviceDeptId', $_GET['deviceDeptId']);
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->assign('projectName', $_GET['projectName']);
        $this->assign('managerId', $_GET['managerId']);
        $this->assign('managerName', $_GET['managerName']);

        $this->view('add', true);
	}
	
	/**
	 * 重写add方法
	 */
	function c_add(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		if ($this->service->add_d($_POST)) {
			if($object['status'] == "1"){
				msgRf('提交成功');
			}else{
				msgRf('保存成功');
			}
		}
	}
	
	/**
	 * 编辑归还单页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select($obj['areaId']));//区域下拉
		$this->assignFunc($obj);
		$this->view('edit', true);
	}
	
	/**
	 * 编辑归还单
	 */
	function c_edit(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		if (!empty($object['areaId'])) {
			$object['areaName'] = $this->service->findArea($object['areaId']);
		}	
		if ($this->service->edit_d($object,true)) {
			if($object['status'] == "1"){
				msgRf('提交成功');
			}else{
				msgRf('保存成功');
			}
		}
	}
	
	/**
	 * 查看页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('view');
	}
	
	/**
	 * 确认状态
	 */
	function c_confirmStatus(){
        echo $this->service->status_d($_POST['id'],$_POST['status']) ? 1 : 0;
	}
    
    /**
     * 跳转到管理员确认归还页面
     */
    function c_toConfirm() {
    	$this->permCheck(); //安全校验
    	$service = $this->service;
    	$obj = $service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    
    	$this->view('confirm');
    }
    
    /**
     * 管理员确认归还
     */
    function c_confirm() {
    	if ($this->service->confirm_d($_POST[$this->objName])) {
    		msgRf('确认成功');
    	} else {
    		msgRf('确认失败');
    	}
    }
}