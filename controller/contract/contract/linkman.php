<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:15:29
 * @version 1.0
 * @description:��ͬ��ϵ����Ϣ����Ʋ�
 */
class controller_contract_contract_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "contract_contract";
		parent::__construct ();
	}

	/*
	 * ��ת����ͬ��ϵ����Ϣ���б�
	 */
    function c_page() {
    	$this->view('list');
    }

   /**
	 * ��ת��������ͬ��ϵ����Ϣ��ҳ��
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
	}

   /**
	 * ��ת���༭��ͬ��ϵ����Ϣ��ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
   	}

   /**
	 * ��ת���鿴��ͬ��ϵ����Ϣ��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
   	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonLimit() {
		$service = $this->service;

		//�ؼ���Ա��Ϣ��ȡ
		$createId = $_POST['createId'];
		$prinvipalId = $_POST['prinvipalId'];
		$areaPrincipalId = $_POST['areaPrincipalId'];
		unset($_POST['createId']);
		unset($_POST['prinvipalId']);
		unset($_POST['areaPrincipalId']);


		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();

		$otherDataDao = new model_common_otherdatas();
		$limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID']);

		if ($createId != $_SESSION ['USER_ID'] && $prinvipalId != $_SESSION ['USER_ID'] && $areaPrincipalId != $_SESSION ['USER_ID']) {
			$rows = $this->service->filterWithoutField ( '��ϵ����Ϣ', $rows, 'keyList', array ('Email', 'telephone','QQ' ),'contract_contract_contract' );
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>