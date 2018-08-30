<?php

/**
 * �ʲ����ÿ��Ʋ���
 *  @author chenzb
 */
class controller_asset_daily_borrow extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "borrow";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}
	/**
	 * ��ת������Ϣ�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}


	/**
	 * ��ת������Ϣ�б� --- ��������
	 */
	function c_requirelist() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('requireId',$_GET['requireId']);
		$this->view ( 'require-list' );
	}

  /**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$codeDao = new model_common_codeRule ();
		$requireDao = new model_asset_require_requirement();
		$requireId = isset ($_GET['requireId']) ? $_GET['requireId'] : null;
		$requireCode = isset ($_GET['requireCode']) ? $_GET['requireCode'] : null;
		$requireObj = $requireDao->get_d($requireId);
		$chargeManId =  $requireObj['userId'];
		$chargeMan =  $requireObj['userName'];
		$applyCompanyCode =  $requireObj['userCompanyCode'];
		$applyCompanyName =  $requireObj['userCompanyName'];
		$chargeDeptId =  $requireObj['userDeptId'];
		$chargeDept =  $requireObj['userDeptName'];
		$reposeManId =  $requireObj['applyId'];
		$reposeMan =  $requireObj['applyName'];
		$reposeDeptId =  $requireObj['applyDeptId'];
		$reposeDept =  $requireObj['applyDeptName'];
		$this->assign ( 'deptId', $chargeDeptId );
		$this->assign ( 'deptName', $chargeDept );
		$this->assign ( 'applyCompanyCode', $applyCompanyCode );
		$this->assign ( 'applyCompanyName', $applyCompanyName );
		$this->assign ( 'chargeManId', $chargeManId );
		$this->assign ( 'chargeMan', $chargeMan );
		$this->assign ( 'reposeManId', $reposeManId );
		$this->assign ( 'reposeMan', $reposeMan );
		$this->assign ( 'reposeDeptId', $reposeDeptId );
		$this->assign ( 'reposeDept', $reposeDept );
		$this->assign ( 'remark', '�ջ���ַ��'.$requireObj['address'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'borrowDate', day_date );
		$this->assign ( 'returnDate', $requireObj['returnDate'] );
		$this->assign ( 'requireId', $requireId );
		$this->assign ( 'requireCode', $requireCode );
		$this->view ( 'add',true );
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		//����
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		if(isset($_GET['btn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}

		foreach ( $obj as $key => $val ) {
				$this->show->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

 /**
	  * ajaxɾ���������������嵥�ӱ���Ϣ
	  */
	  function c_deletes(){
		$message = "";
		try {
            $borrowObj = $this->service->get_d ( $_GET ['id'] );
			$borrowitemDao = new model_asset_daily_borrowitem();
	  		$condition = array(
	  			'borrowId'=>$borrowObj['id']
	  		);
	  		$borrowitemDao->delete($condition);
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
		$this->checkSubmit(); //�����Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName], true );
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_asset_borrow&formName=�ʲ���������' );
			}else{
				msgRf('�����ɹ�');
			}
		}else{
			if ("audit" == $actType) {
				 echo "<script>alert('��������ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				msgRf('����ʧ��');
			}
		}
    }

 	  /**
	 * �޸Ķ������
	 * @chenzb
	 */
      function c_edit() {
      	 $borrowObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($borrowObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index.php?actTo=ewfSelect&billId='.$borrowObj['id'].'&examCode=oa_asset_borrow&formName=�ʲ���������' );
			} else {
				msgRf('�༭�ɹ�');
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				msgRf('�༭ʧ��');
			}
		}
	}


	/**
	 *Ĭ��action��ת����
	 */
	function c_myList() {
		$userId = $_SESSION['USER_ID'];
		$this->assign('userId',$userId);
		$this->view ( 'mylist' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['userId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	  /**
	   * ajaxǩ�ս�������
	   */
	   function c_toSign(){
		  	echo $this->service->sign_d($_POST['id']);
	   }

}