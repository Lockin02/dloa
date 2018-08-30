<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:物料转资产申请控制层
 */
class controller_asset_require_requirein extends controller_base_action {

	function __construct() {
		$this->objName = "requirein";
		$this->objPath = "asset_require";
		parent :: __construct();
	}
	
	/**
	 * 跳转到物料转资产申请列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到资产出库列表
	 */
	function c_awaitList() {
		$this->view('awaitList');
	}
	
	/**
	 * 跳转到待确认资产出库列表
	 */
	function c_confirmList() {
		$this->view('confirmList');
	}
	
	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );//设置前台获取的参数信息
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增物料转资产申请页面
	 */
	function c_toAdd() {
		//加入验证
		$requireId = $_GET['requireId'];	//资产需求申请id
		$requireCode = isset ($_GET['requireCode']) ? $_GET['requireCode'] : null;	//资产需求申请编号
		$docItemDao = new model_asset_require_requireitem();
		$docItemRs = $docItemDao->requireinJsonApply_d($requireId);
		if(empty($docItemRs)){
			msgRf('物料转资产数量已申请完毕，不能继续申请');
			exit();
		}
		$docDao = new model_asset_require_requirement();
		$docRs = $docDao->get_d($requireId);
		$this->assign('applyDate', $docRs['applyDate']);
		$this->assign('applyId', $docRs['applyId']);
		$this->assign('applyName', $docRs['applyName']);
		$this->assign('applyDeptId', $docRs['applyDeptId']);
		$this->assign('applyDeptName', $docRs['applyDeptName']);
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign ( 'requireId', $requireId );
		$this->assign ( 'requireCode', $requireCode );

		$this->view('add',true);
	}
	
	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit(); //检验是否重复提交
		$object = $_POST[$this->objName];
		
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'audit'){
			$object['status'] = "待确认";
		}else{
			$object['status'] = "待提交";
		}
		$id = $this->service->add_d($object,true);
		if ($id) {
			if($actType == 'audit'){
				msgRf('提交成功');
			}else{
				msgRf('保存成功');
			}
		}else{
			if($actType == 'audit'){
				msgRf('提交失败');
			}else{
				msgRf('保存失败');
			}
		}
	}
	
	/**
	 * 跳转到编辑物料转资产申请页面
	 */
	function c_toEdit() {
// 		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
	
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'audit'){
			$object['status']="待确认";
		}
		$flag = $this->service->edit_d($object, true);
		if ($flag) {
			if ($actType == 'audit') {
				msgRf('提交成功');;
			} elseif ($actType == 'confirm') {
				msgRf('确认成功');
			} else {
				msgRf('编辑成功');
			}
		}else{
			if ($actType == 'audit') {
				msgRf('提交失败');
			} elseif ($actType == 'confirm') {
				msgRf('确认失败');
			} else {
				msgRf('编辑失败');
			}
		}
	}

	/**
	 * 跳转到查看物料转资产申请页面
	 */
	function c_toView() {
		// 		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	
	/**
	 * 跳转到确认物料页面
	 */
	function c_toConfirm() {
		// 		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('confirm');
	}
	
	/**
	 * 确认由仓管资产出库
	 */
	function c_ajaxConfirm(){
		echo $this->service->confirm_d($_POST['id']) ? 1 : 0 ;
	}
	
	/**
	 * 跳转到物料转资产申请列表 -- 关联资产需求的物料转资产列表页
	 */
	function c_listByRequire() {
		$this -> assign('requireId', $_GET['requireId']);
		$this -> view('listbyrequire');
	}
	
	/**
	 * 跳转到打回单据页面
	 */
	function c_toBack(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->assign('sendUserId', $obj['createId']);
		$this->assign('sendName', $obj['createName']);
	
		$this->view('back');
	}
	
	/**
	 * 打回单据
	 */
	function c_back() {
		$object = $_POST[$this->objName];
		if ($this->service->back_d($object)) {
			msg('打回成功');
		}else{
			msg('打回失败');
		}
	}
}