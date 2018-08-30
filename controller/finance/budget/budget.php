<?php
/**
 * @author zengqin
 * @Date 2015-2-10
 * @version 1.1
 * @description:费用预算控制层
 */
class controller_finance_budget_budget extends controller_base_action{

	function __construct() {
        $this->objName = "budget";
        $this->objPath = "finance_budget";
        parent::__construct();
    }
	/**
	 * 费用预算列表
	 */
    function c_page() {
		$this->view('list');
    }

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->view('add',true);
	}

	/**
	 * 跳转到查看页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('view',true);
	}

	/**
	 * 跳转到查看页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('edit',true);
	}

	/**
	 * 费用报销提交审批后更新费用预算表
	 */
	function c_updateByExpense() {
		$expenseId = $_GET['billId'];
		$this->service->updateBudgetDetail($expenseId);
		echo "<script language=javascript>location.href='view/reloadParent.php';</script>";
    	exit( );
	}

	/**
	 * 费用报销提交审批后更新费用预算表
	 */
	function c_subFinal() {
		$expenseId = $_GET['billId'];
		return $this->service->subFinal($expenseId);
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			echo "<script language=javascript>alert('编辑成功！');location.href='view/reloadParent.php';</script>";
    		exit( );
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$obj = $_POST [$this->objName];
		$flag = $this->service->checkUnique($obj['year'],$obj['expenseTypeCode'],$obj['expenseClass']);
		if(!$flag){
			$id = $this->service->add_d ( $obj, $isAddInfo );
			if ($id) {
				echo "<script language=javascript>alert('新增成功！');location.href='view/reloadParent.php';</script>";
	    		exit( );
			}
		}else{
			msg('新增失败！'.$obj['year'].'年'.$obj['expenseType'].'类的预算已存在！');
		}
	}

}
 ?>