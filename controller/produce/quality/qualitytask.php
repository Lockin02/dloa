<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 15:09:09
 * @version 1.0
 * @description:交检任务单控制层
 */
class controller_produce_quality_qualitytask extends controller_base_action {

	function __construct() {
		$this->objName = "qualitytask";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * 跳转到质检任务Tab
	 */
	function c_toPageTab() {
		$this->view ( 'list-tab' );
	}


    /**
     * 跳转到质检任务Tab
     */
    function c_toTaskReportTab() {
        $this->assign('sourceId',isset($_GET['sourceId'])?$_GET['sourceId']:"");
        $this->view ( 'taskreport-tab' );
    }


    /**
	 * 跳转到交检任务单列表
	 */
	function c_page() {
		$this->assign('acceptStatusArr',$_GET['acceptStatusArr']);
		$this->view('list');
	}

    /**
     * 跳转到质检任务单列表（收料查看）
     */
    function c_arrivalPage() {
        $this->assign('sourceId',$_GET['sourceId']);
        $this->view('arrival-list');
    }

	/**
	 * 跳转到质检任务Tab
	 */
	function c_toMyTab() {
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->assign("relDocType",isset($_GET['relDocType']) ? $_GET['relDocType'] : '');
		$this->view ( 'mylist-tab' );
	}

	/**
	 * 跳转到新增交检任务单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit();
		if ($this->service->add_d( $_POST[$this->objName] )) {
			msg ('下达成功');
		}
	}

	/**
	 * 跳转到编辑交检任务单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看交检任务单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//状态渲染
		$this->assign("acceptStatusName",$this->service->rtStatus($obj['acceptStatus']));
		$this->view ( 'view' );
	}

	/**
	 * 查看页面 - 质检处理
	 */
	function c_toTaskDeal(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//状态渲染
		$this->assign("acceptStatusName",$this->service->rtStatus($obj['acceptStatus']));
		$this->view ( 'taskdeal' );
	}

	/**
	 * 跳转到下达任务页面
	 */
	function c_toIssued(){
		$this->assign('applyId',$_GET['applyId']);
		$this->assign('itemId',$_GET['itemId']);
		$this->view ( 'issued' );
	}

	/**
	 * 接收任务
	 */
	function c_acceptTask(){
        echo $this->service->acceptTask_d( $_GET['id'] ) ? 1 : 0;
	}

	/**
	 * ajax下达任务
	 */
	function c_ajaxTask(){
		if(empty($_GET['itemId'])){
			echo 0;
		}else{
            $docType = isset($_GET['docType'])? $_GET['docType'] : '';
            if($docType == 'ZJSQDLBF'){
                echo util_jsonUtil::encode ( $this->service->ajaxTaskForDLBF( $_GET['itemId'] ) );
            }else{
                echo $this->service->ajaxTask( $_GET['itemId'] ) ? 1 : 0;
            }
        }
	}
	
	/**
	 * 质检报告显示列表
	 */
	function c_pageJsonDetail(){
		$service = $this->service;
		$service->getParam( $_REQUEST );
		$rows = $service->page_d( 'select_detail' );
		$rows = $this->sconfig->md5Rows( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到质检任务单
	 */
	function c_taskList(){
		$this->view ( 'listTask' );
	}
}