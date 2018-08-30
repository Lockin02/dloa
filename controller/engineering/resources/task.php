<?php
/**
 * @author Administrator
 * @Date 2012-11-14 11:05:47
 * @version 1.0
 * @description:项目设备任务单控制层
 */
class controller_engineering_resources_task extends controller_base_action {

	function __construct() {
		$this->objName = "task";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}

	/**
	 * 跳转到项目设备任务单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = array();
		//加入区域权限
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);

		if(isset($sysLimit['设备区域权限']) && !empty($sysLimit['设备区域权限'])){
			if(strpos($sysLimit['设备区域权限'],';;') === false){
				$_REQUEST['areaIdArr'] = $sysLimit['设备区域权限'];
			}
			$service->getParam ( $_REQUEST );
			$rows = $service->page_d ();
			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $rows );
		}
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
	 * 跳转到新增项目设备任务单页面
	 */
	function c_toAdd() {
		//设备申请单信息
		$resourceapplyDao = new model_engineering_resources_resourceapply();
		$resourceapplyObj = $resourceapplyDao->get_d($_GET['id']);
		//数据渲染
		$this->assignFunc($resourceapplyObj);
		$this->assign("taskManager", $_SESSION['USERNAME']);
		$this->assign("taskManagerId", $_SESSION['USER_ID']);
		$this->showDatadicts(array('applyType' => 'GCSBSQ'));
		//获取系统默认操作员区域  
		$areaName = $this->service->get_table_fields('area','ID='.$_SESSION['AREA'],'Name');
		$this->assign("areaId", $_SESSION['AREA']);
		$this->assign("areaName", $areaName);
		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		if ($this->service->add_d($_POST[$this->objName])) {
			msgRf('下达成功');
		}
	}

	/**
	 * 跳转到编辑项目设备任务单页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		if ($this->service->edit_d($_POST[$this->objName])) {
			msgRf('编辑成功！');
		}
	}

	/**
	 * 跳转到查看项目设备任务单页面
	 */
	function c_toView() {
		$id = $_GET['id'];
		$obj = $this->service->get_d($id);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	/**
	 * 打印
	 */
	function c_toBatchPrintAlone(){
		//id串
		$ids = null;	
		if(isset($_GET['id'])&&!empty($_GET['id'])){
			$ids = $_GET['id'];
			$idArr = explode(',',$ids);
		}else{
			msg("请至少选择一张单据打印");
		}
	
		$this->view('batchprinthead');
	
		foreach($idArr as $val){
			$id = is_array($val) ? $val['id'] : $val;
			$obj = $this->service->get_d($id);
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('print-expand');
		}
		$this->assign('ids',$ids);
		$this->assign('allNum',count($idArr));
		$this->display('batchprintalone');
	}

    /**
     * 出库
     */
    function c_toOutStock(){
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('outstock');
    }

    /**
     * 选择出库设备
     */
    function c_outStockConfirm(){
        //加载选上的设备信息
        $obj = $this->service->initInfo_d($_POST[$this->objName]);
        if(!empty($obj)){
        	$this->assignFunc($obj);
        	//邮件默认接收人为申请人,接收人,项目经理
        	$this->assign('sendUserId', implode(',',array_unique(explode(',', $obj['applyUserId'].','.$obj['receiverId'].','.$obj['managerId']))));
        	$this->assign('sendUserName', implode(',',array_unique(explode(',', $obj['applyUser'].','.$obj['receiverName'].','.$obj['managerName']))));
        	$this->view('outstockconfirm', true);
        }else{
        	msgRf('没有需要出库的设备');
        }
    }

    /**
     * 确认最终出库单
     */
    function c_outStockFinal(){
    	$this->checkSubmit();//验证是否重复提交
        if($this->service->outStockFinal_d($_POST[$this->objName])){
            msgRf('借出成功');
        }
    }
    
    /**
     * 跳转到修改发货任务分配数量
     */
    function c_toEditNumber() {
    	$this->permCheck(); //安全校验
    	$obj = $this->service->get_d($_GET['id']);
    	foreach ($obj as $key => $val) {
    		$this->assign($key, $val);
    	}
    	$this->view('editNumber');
    }
    
    /**
     * 修改发货任务分配数量
     */
    function c_editNumber() {
    	if ($this->service->editNumber_d($_POST[$this->objName])) {
    		msgRf('提交成功！');
    	}else{
    		msgRf('提交失败！');
    	}
    }
}