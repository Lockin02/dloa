<?php
/**
 * @author Bingo
 * @Date 2015年5月11日 
 * @version 1.0
 * @description:生产退料申请单控制层
 */
class controller_produce_plan_back extends controller_base_action {

	function __construct() {
		$this->objName = "back";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产退料申请单页面
	 */
	function c_page() {
		$this->view('list-tab');
	}

	/**
	 * 跳转到生产退料申请单列表
	 */
	function c_pageList() {
		$this->assign('finish' ,isset($_GET['backType']) ? 'yes' : 'no');
		$this->view('list');
	}

	/**
	 * 跳转到生产领料申请退料记录列表
	 */
	function c_myPage() {
		$this->assign('userId' ,$_SESSION['USER_ID']);
		$this->view('mylist');
	}
	
	/**
	 * 跳转到待入库生产退料列表-tab
	 */
	function c_pageManage() {
		$this->view('list-tab-manage');
	}
	
	/**
	 * 跳转到待入库生产退料列表
	 */
	function c_pageListManage() {
		$this->assign('perform' ,isset($_GET['perform']) ? 'yes' : 'no');
		$this->view('list-manage');
	}
	
	/**
	 * 跳转到新增生产退料申请单页面-单独新增
	 */
	function c_toAdd() {
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('docDate' ,day_date);
		$this->view ('add',true);
	}
	
	/**
	 * 跳转到新增生产退料申请单页面-由领料下推
	 */
	function c_toAddByPicking() {
		$pickingId = $_GET['pickingId'];
		$pickingDao = new model_produce_plan_picking();
		$obj = $pickingDao->get_d($pickingId);
		$this->assign('pickingId' ,$pickingId);
		$this->assign('pickingCode' ,$obj['docCode']);
		$this->assign('docType' ,$obj['docType']);
		$this->assign('docTypeCode' ,$obj['docTypeCode']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('docDate' ,day_date);
		$this->view ('add-picking',true);
	}
	
	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit();
		$obj = $_POST[$this->objName];
		if(isset($_GET['act']) && $_GET['act'] == 'sub'){
			$obj['docStatus'] = 1;
		}else{
			$obj['docStatus'] = 0;
		}
		$id = $this->service->add_d($obj);
		if ($id) {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('提交成功');
			}else{
				msg('保存成功');
			}
		} else {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('提交失败');
			}else{
				msg('保存失败');
			}
		}
	}
	
	/**
	 * 跳转到编辑生产退料申请页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if(empty($obj['pickingCode'])){
			$this->view ('edit');
		}else{
			$this->view ('edit-picking');
		}
	}
	
	/**
	 * 编辑生产退料申请
	 */
	function c_edit() {
		$obj = $_POST[$this->objName];
		if(isset($_GET['act']) && $_GET['act'] == 'sub'){
			$obj['docStatus'] = 1;
		}
		$id = $this->service->edit_d($obj);
		if ($id) {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('提交成功');
			}else{
				msg('编辑成功');
			}
		} else {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('提交失败');
			}else{
				msg('编辑失败');
			}
		}
	}
	
	/**
	 * 跳转到确认生产退料申请页面
	 */
	function c_toConfirm() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('confirm');
	}

	/**
	 * 确认生产退料申请
	 */
	function c_confirm() {
		$id = $this->service->confirm_d($_POST[$this->objName]);
		if ($id) {
			msg('确认成功');
		} else {
			msg('确认失败');
		}
	}
	
	/**
	 * 跳转到查看生产领料申请退料记录页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if(empty($obj['pickingCode'])){
			$this->view ('view');
		}else{
			$this->view ('view-picking');
		}
	}
	
	/**
	 * 打回
	 */
	function c_applyBack() {
		if ($this->service->applyBack_d($_POST['id'])) {
			echo 1;
		} else {
			echo 0;
		}
	}
 }