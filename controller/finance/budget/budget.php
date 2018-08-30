<?php
/**
 * @author zengqin
 * @Date 2015-2-10
 * @version 1.1
 * @description:����Ԥ����Ʋ�
 */
class controller_finance_budget_budget extends controller_base_action{

	function __construct() {
        $this->objName = "budget";
        $this->objPath = "finance_budget";
        parent::__construct();
    }
	/**
	 * ����Ԥ���б�
	 */
    function c_page() {
		$this->view('list');
    }

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->view('add',true);
	}

	/**
	 * ��ת���鿴ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('view',true);
	}

	/**
	 * ��ת���鿴ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('edit',true);
	}

	/**
	 * ���ñ����ύ��������·���Ԥ���
	 */
	function c_updateByExpense() {
		$expenseId = $_GET['billId'];
		$this->service->updateBudgetDetail($expenseId);
		echo "<script language=javascript>location.href='view/reloadParent.php';</script>";
    	exit( );
	}

	/**
	 * ���ñ����ύ��������·���Ԥ���
	 */
	function c_subFinal() {
		$expenseId = $_GET['billId'];
		return $this->service->subFinal($expenseId);
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			echo "<script language=javascript>alert('�༭�ɹ���');location.href='view/reloadParent.php';</script>";
    		exit( );
		}
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$obj = $_POST [$this->objName];
		$flag = $this->service->checkUnique($obj['year'],$obj['expenseTypeCode'],$obj['expenseClass']);
		if(!$flag){
			$id = $this->service->add_d ( $obj, $isAddInfo );
			if ($id) {
				echo "<script language=javascript>alert('�����ɹ���');location.href='view/reloadParent.php';</script>";
	    		exit( );
			}
		}else{
			msg('����ʧ�ܣ�'.$obj['year'].'��'.$obj['expenseType'].'���Ԥ���Ѵ��ڣ�');
		}
	}

}
 ?>