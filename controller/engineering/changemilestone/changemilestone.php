<?php
/**
 * @author Show
 * @Date 2012年2月10日 星期五 14:05:49
 * @version 1.0
 * @description:项目里程碑变更表控制层
 */
class controller_engineering_changemilestone_changemilestone extends controller_base_action {

	function __construct() {
		$this->objName = "changemilestone";
		$this->objPath = "engineering_changemilestone";
		parent::__construct ();
	}

	/**
	 * 变更列表选择页
	 */
	function c_toChange(){
		//获取传入的项目id
		$projectId = $_GET['projectId'];

		$this->assignFunc($_GET);
		//判断项目是否存在变更申请且变更申请是否变更里程碑
		$isChangeMileStone = $this->service->isChangeMilestone_d($projectId);

		//如果要变更，则进入列表
		if($isChangeMileStone == 1){
			$this->view('list');
		}else{
			$this->view('needadd');
		}
	}

	/*
	 * 跳转到项目里程碑变更表列表
	 */
    function c_page() {
    	$this->assign('changeId',$_GET['changeId']);
		$this->view('list');
    }

    /**
     * 里程碑变更查看列表
     */
    function c_viewPage(){
    	$this->assign('changeId',$_GET['changeId']);
		$this->view('listview');
    }

    /**
	 * 跳转到新增项目里程碑变更表页面
	 */
	function c_toAdd() {
		$rs = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assignFunc($rs);
		$this->assign('projectId',$_GET['projectId']);
		$this->assign('changeId',$_GET['changeId']);
		$this->assign('versionNo',$_GET['versionNo']);
		$this->showDatadicts ( array ('status' => 'LCBZT' ) );

		$this->view ( 'add' );
	}

    /**
	 * 跳转到编辑项目里程碑变更表页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('status' => 'LCBZT' ) ,$obj['status']);
		$this->view ( 'edit');
	}

    /**
	 * 跳转到查看项目里程碑变更表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );

		//去除实际开始日期空的问题
		if($obj['actBeginDate'] == '0000-00-00'){
			$obj['actBeginDate'] = "";
		}

		//去除实际结束日期空的问题
		if($obj['actEndDate'] == '0000-00-00'){
			$obj['actEndDate'] = "";
		}
		$this->assignFunc($obj);

		$this->assign('status',$this->getDataNameByCode($obj['status']));
		$this->view ( 'view' );
	}

	/**
	 * 异步新增里程碑 - 从项目中直接复制
	 */
	function c_addMilestone(){
		$projectId = $_POST['projectId'];
		$changeId = $_POST['changeId'];
		$versionNo = $_POST['versionNo'];
		//项目信息渲染啊
		$rs = $this->service->addMilestone_d($projectId,$changeId,$versionNo);
		if($rs){
			echo $rs;
		}else{
			echo 0;
		}
	}

	/**
	 * 异步新增里程碑 - 从项目中直接复制
	 */
	function c_addMileAndChange(){
		$projectId = $_POST['projectId'];
		//项目信息渲染啊
		$rs = $this->service->addMileAndChange_d($projectId);
		if($rs){
			echo util_jsonUtil::encode ( $rs );
		}else{
			echo 0;
		}
	}
}
?>