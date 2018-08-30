<?php

/**
 * �ʲ�ά�����Ʋ���
 *  @author linzx
 */
class controller_asset_daily_keep extends controller_base_action {

	function __construct() {
		$this->objName = "keep";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}


	/**
	 * ��ת�б�ҳ��
	 * ��д�͵���action��c_list();
	 */
	function c_list() {
		$this->view ( 'list' );
	}



		/**
	 * ��ʼ������
	 */
	function c_init() {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
            //�ύ������鿴����ʱ���عرհ�ť
			if(isset($_GET['viewBtn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'keeperId', $_SESSION ['USER_ID'] );
		$this->assign ( 'keeper', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'keepDate', date("Y-m-d") );
		$this->view ( 'add' );
	}


 /**
	  * ajaxɾ�����������嵥�ӱ���Ϣ
	  */
	  function c_deletes(){
		$message = "";
		try {
            $keepObj = $this->service->get_d ( $_GET ['id'] );
			$keepitemDao = new model_asset_daily_keepitem();
	  		$condition = array(
	  			'keepId'=>$keepObj['id']
	  		);
	  		$keepitemDao->delete($condition);
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
	 * @linzx
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_keep.php?actTo=ewfSelect&billId='.$id);
			} else {
				echo "<script>alert('�����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('��������ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				 echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
}
/**
	 * �޸Ķ������
	 * @chenzb
	 */
      function c_edit() {
      	 $keepObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($keepObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_keep.php?actTo=ewfSelect&billId='.$keepObj['id'].'&examCode=oa_asset_keep&formName=�ʲ�ά����������' );
			} else {
				 echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				 echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

}
?>