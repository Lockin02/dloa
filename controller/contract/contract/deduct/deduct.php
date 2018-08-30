<?php
/**
 * @author Administrator
 * @Date 2012-04-11 20:09:54
 * @version 1.0
 * @description:扣款申请控制层
 */
class controller_contract_deduct_deduct extends controller_base_action {

	function __construct() {
		$this->objName = "deduct";
		$this->objPath = "contract_deduct";
		parent::__construct ();
	 }

	/*
	 * 跳转到扣款申请列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增扣款申请页面
	 */
	function c_toAdd() {
		$conId = $_GET['contractId'];
		$conDao = new model_contract_contract_contract();
		$conInfo = $conDao->get_d($conId);
		//数据渲染
		$this->assignFunc($conInfo);
       $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑扣款申请页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看扣款申请页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$dao= new model_contract_contract_contract();
		$conInfo = $dao->get_d($obj['contractId']);
		$this->assign("deductMoneyC",$conInfo['deductMoney']);
		$this->assign("badMoneyC",$conInfo['badMoney']);
		$this->view ( 'view' );
   }


   /**
    * 合同查看页
    */
   function c_deductTolist(){
   	  $this->assign("contractId",$_GET['contractId']);
      $this->view('deductTolist');
   }
	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/contract/deduct/ewf_index.php?actTo=ewfSelect&billId=' . $id);
		} else
			if ($id) {
				msgGo('添加成功！');
			} else {
				msgGo('添加失败！');
			}

		//$this->listDataDict();
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$dao= new model_contract_contract_contract();
		$conInfo = $dao->get_d($obj['contractId']);
		$this->assign("deductMoneyC",$conInfo['deductMoney']);
		$this->assign("badMoneyC",$conInfo['badMoney']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}
    /**
	  * 审批完成后跳转方法
     */
    function c_confirmDeduct(){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAudit_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 扣款处理--跳转页面
     */
    function c_deductDispose(){
    	$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		echo "<pre>";
//		print_R($_SESSION);
		$this->view ( 'deductdispose' );
    }
    /**
     * 扣款处理
     */
    function c_dedispose(){
    	$object = $_POST [$this->objName];
        $this->service->dedispose_d($object);
			msg ( '处理完成！' );
    }
 }
?>