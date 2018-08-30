<?php
/**
 * @author show
 * @Date 2013年12月9日 19:17:43
 * @version 1.0
 * @description:续借申请单控制层 
 */
class controller_engineering_resources_erenew extends controller_base_action {
	function __construct() {
		$this->objName = "erenew";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	}
	
	/**
	 * 跳转到续借申请单
	 */
	function c_page() {
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
	 * 项目设备续借列表（根据项目id过滤）
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
	 * 添加续借单
	 */
	function c_toAdd(){
		//flag为标识,1为在建/筹备状态,2为关闭状态
		$flag = $_GET['flag'];
		//在建状态的项目，验证其是否为试用项目
		if($flag == '1'){
			$esmprojectDao = new model_engineering_project_esmproject();
			if($esmprojectDao->isPK_d($_GET['projectId'])){
				$flag = '3';//标识为试用项目
			}
		}
		$this->assign('flag', $flag);
		$this->assign('projectId', $_GET['projectId']);
		$this->assign('projectCode', $_GET['projectCode']);
		$this->assign('projectName', $_GET['projectName']);
		$this->assign('managerId', $_GET['managerId']);
		$this->assign('managerName', $_GET['managerName']);

        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_GET['deviceDeptId']);
        $this->assign('deviceDeptName', $deptInfo['DEPT_NAME']);
        $this->assign('deviceDeptId', $_GET['deviceDeptId']);

		$this->assign('rowsId', $_GET['rowsId']);
		$this->assign('applyUser', $_SESSION['USERNAME']);
		$this->assign('applyUserId', $_SESSION['USER_ID']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('applyDate', day_date);
	
		$this->view('add', true);
	}
	
	/**
	 * 重写add方法
	 */
	function c_add(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		if($this->service->add_d($object)) {
			if($object['status'] == "1"){
				msg('提交成功');
			}
			else
				msg('保存成功');
		}
		else
			msg('保存失败');
	}

	/**
	 * 编辑续借单页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$service = $this->service;
		$obj = $service->get_d($_GET['id']);
		$flag = '';//flag为标识,1为在建/筹备状态,2为关闭状态
		if(!empty($obj['projectId'])){
			$flag = $service->get_table_fields('project_info','projectId='.$obj['projectId'],'flag');
			//在建/筹备状态的项目，验证其是否为试用项目
			if($flag == '1'){
				$esmprojectDao = new model_engineering_project_esmproject();
				if($esmprojectDao->isPK_d($obj['projectId'])){
					$flag = '3';//标识为试用项目
				}
			}
		}
		$this->assign('flag', $flag);
		$this->assignFunc($obj);
		$this->view('edit', true);
	}

	/**
	 * 编辑续借单
	 */
	function c_edit(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object,true);	
		if ($rs) {
			if($object['status'] == "1"){
				msg('提交成功');
			}else{
				msg('保存成功');
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
	 * 修改单据状态
	 */
	function c_confirmStatus(){
        echo $this->service->status_d($_POST['id'],$_POST['status']) ? 1 : 0;
	}
    
    /**
     * 跳转到管理员确认续借页面
     */
    function c_toConfirm() {
    	$this->permCheck(); //安全校验
    	$service = $this->service;
    	$obj = $service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    
    	$this->view('confirm');
    }
    
    /**
     * 管理员确认续借
     */
    function c_confirm() {
    	if ($this->service->confirm_d($_POST[$this->objName])) {
    		msgRf('确认成功');
    	} else {
    		msgRf('确认失败');
    	}
    }
    
    /**
     * 跳转到申请人最终确认续借页面
     */
    function c_toFinalConfirm() {
    	$this->permCheck(); //安全校验
    	$service = $this->service;
    	$obj = $service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    	$this->assign('today', day_date);
    	 
    	$this->view('finalconfirm');
    }
    
    /**
     * 申请人最终确认续借
     */
    function c_finalConfirm() {
    	$object = $_POST[$this->objName];
    	if ($this->service->finalConfirm_d($object)) {
    		msgRf('确认成功');
    	} else {
    		msgRf('确认失败');
    	}
    }
}