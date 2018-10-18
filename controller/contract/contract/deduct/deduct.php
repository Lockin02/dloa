<?php
/**
 * @author Administrator
 * @Date 2012-04-11 20:09:54
 * @version 1.0
 * @description:�ۿ�������Ʋ�
 */
class controller_contract_deduct_deduct extends controller_base_action {

	function __construct() {
		$this->objName = "deduct";
		$this->objPath = "contract_deduct";
		parent::__construct ();
	 }

	/*
	 * ��ת���ۿ������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������ۿ�����ҳ��
	 */
	function c_toAdd() {
		$conId = $_GET['contractId'];
		$conDao = new model_contract_contract_contract();
		$applyMoney = $this->service->getApplyMoney($conId);
		$conInfo = $conDao->get_d($conId);
		$conInfo['applyMoney'] = $applyMoney;
		//������Ⱦ
		$this->assignFunc($conInfo);
       $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�ۿ�����ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�ۿ�����ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
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
    * ��ͬ�鿴ҳ
    */
   function c_deductTolist(){
   	  $this->assign("contractId",$_GET['contractId']);
      $this->view('deductTolist');
   }
	/**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/contract/deduct/ewf_index.php?actTo=ewfSelect&billId=' . $id);
		} else
			if ($id) {
				msgGo('��ӳɹ���');
			} else {
				msgGo('���ʧ�ܣ�');
			}

		//$this->listDataDict();
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$dao= new model_contract_contract_contract();
		$conInfo = $dao->get_d($obj['contractId']);
		$this->assign("deductMoneyC",$conInfo['deductMoney']);
		$this->assign("badMoneyC",$conInfo['badMoney']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}
    /**
	  * ������ɺ���ת����
     */
    function c_confirmDeduct(){
       	//�������ص�����
        $this->service->workflowCallBack($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * �ۿ��--��תҳ��
     */
    function c_deductDispose(){
    	$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj['dispose'] == "deductMoney") {
				$this->assign('disa', 'checked');
			} else {
				$this->assign('disb', 'checked');
			}
		$this->assign('thisDate',day_date);
//		echo "<pre>";
//		print_R($_SESSION);
		$this->view ( 'deductdispose' );
    }
    /**
     * �ۿ��
     */
    function c_dedispose(){
    	$object = $_POST [$this->objName];

		try {
			$tag = $this->service->dedispose_d($object);
			if($tag){
				msg ( '������ɣ�' );
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
    }
 }
?>