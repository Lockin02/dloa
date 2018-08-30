<?php

/**
 *
 * �����������Ʋ���
 * @author fengxw
 *
 */

class controller_supplierManage_scheme_scheme extends controller_base_action {

	function __construct() {
		$this->objName = "scheme";
		$this->objPath = "supplierManage_scheme";
		parent :: __construct();
	}

	/**
	 * ��ת����������
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		//��ȡ�����ֵ�
		$this->showDatadicts(array (
			'OptschemeType' => 'FALX'
		));
		$this->assign('formManId', $_SESSION['USER_ID']);
		$this->assign('formManName', $_SESSION['USERNAME']);
		$this->assign('formManDept', $_SESSION['DEPT_NAME']);
		$this->assign('formManDeptId', $_SESSION['DEPT_ID']);
		$this->assign('formDate', date("Y-m-d"));
		$this->view('add',true);
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		//		echo "<pre>";
		//		print_R($_GET);
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			if (isset ($_GET['viewBtn'])) {
				$this->assign('showBtn', 1);
			} else {
				$this->assign('showBtn', 0);
			}
			//��ȡ�����ֵ�
			$this->showDatadicts(array (
				'OptschemeType' => 'FALX'
			));
			$this->view('view');
		} else {
			$this->assign('formManDept', $_SESSION['DEPT_NAME']);
			$this->assign('formManDeptId', $_SESSION['DEPT_ID']);
			//��ȡ�����ֵ�
			$this->showDatadicts(array ('schemeTypeCode' => 'FALX'),$obj['schemeTypeCode']);
			$this->view('edit',true);
		}
	}

 	/**
	  * ��ɾ�ӱ���Ϣ����ɾ������Ϣ
	  */
	  function c_deletes(){
		$message = "";
		try {
            $Obj = $this->service->get_d ( $_GET ['id'] );
			$itemDao = new model_supplierManage_scheme_schemeItem();
	  		$condition = array(
	  			'parentId'=>$Obj['id']
	  		);
	  		$itemDao->delete($condition);
			$this->service->deletes_d ( $_GET ['id'] );
			$message = '<div style="color:red" align="center">ɾ���ɹ�!</div>';
		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">ɾ��ʧ�ܣ��ö�������Ѿ�������!</div>';
		}
		if (isset ( $_GET ['url'] )) {
			$event = "document.location='" . iconv ( 'utf-8', 'gb2312', $_GET ['url'] ) . "'";
			showmsg ( $message, $event, 'button' );
		} else if (isset ( $_SERVER [HTTP_REFERER] )) {
			$event = "document.location='" . $_SERVER [HTTP_REFERER] . "'";
			showmsg ( $message, $event, 'button' );
		} else {
			$this->c_page ();
		}
		msg('ɾ���ɹ���');
	  }

	 /**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/supplierManage/scheme/ewf_index.php?actTo=ewfSelect&billId='.$id);
			} else {
 				msgGo("����ɹ�",'index1.php?model=supplierManage_scheme_scheme');
			}
		}
		else{
			if ("audit" == $actType) {
 				msgGo("�����ύʧ��",'index1.php?model=supplierManage_scheme_scheme');
			} else {
 				msgGo("����ʧ��",'index1.php?model=supplierManage_scheme_scheme');
			}
		}
	}

	/**
	 * �޸Ķ������
	 */
      function c_edit() {
      	$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
      	$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/supplierManage/scheme/ewf_index.php?actTo=ewfSelect&billId='.$object['id']);
			} else {
 				msgGo("�޸ĳɹ�",'index1.php?model=supplierManage_scheme_scheme');
			}
		}
		else{
			if ("audit" == $actType) {
 				msgGo("�����ύʧ��",'index1.php?model=supplierManage_scheme_scheme');
			} else {
 				msgGo("�޸�ʧ��",'index1.php?model=supplierManage_scheme_scheme');
			}

		}
	}


    /**
     * ���ݲ���Id��ȡ��������Ϣ
     */
    function c_getDeptLeader(){
        $deptId=isset($_POST['deptId'])?$_POST['deptId']:'';
        $returnRow=$this->service->getDeptLeader($deptId);
        if(!empty($returnRow)){
            echo util_jsonUtil::encode ( $returnRow );
        }else{
            echo 0;
        }
    }
}
?>