<?php
/**
 * �ʲ����ÿ��Ʋ���
 *  @author linzx
 */
class controller_asset_daily_charge extends controller_base_action {

	function __construct() {
		$this->objName = "charge";
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
	 * ��ת������Ϣ�б� --- ��������
	 */
	function c_requirelist() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('requireId',$_GET['requireId']);
		$this->view ( 'require-list' );
	}

	/**
	 * ��ת�б�ҳ��
	 * ��д�͵���action��c_list();
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
	 * ��ʼ������
	 */
	function c_init() {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		//����
		$obj ['file'] = $this->service->getFilesByObjId ( $obj ['id'], false );
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
		$requireId = isset ($_GET['requireId']) ? $_GET['requireId'] : null;
		$requireCode = isset ($_GET['requireCode']) ? $_GET['requireCode'] : null;
		$codeDao = new model_common_codeRule ();
		$requireDao = new model_asset_require_requirement();
		$requireObj = $requireDao->get_d($requireId);
		$applyCompanyCode =  $requireObj['userCompanyCode'];
		$applyCompanyName =  $requireObj['userCompanyName'];
		$chargeManId =  $requireObj['userId'];
		$chargeMan =  $requireObj['userName'];
		$chargeDeptId =  $requireObj['userDeptId'];
		$chargeDept =  $requireObj['userDeptName'];
		$reposeManId =  $requireObj['applyId'];
		$reposeDept =  $requireObj['applyDeptName'];
		$object ['billNo']= $codeDao->assetRequireCode ( "oa_asset_charge", "LY" ,day_date,$applyCompanyCode,'�̶��ʲ����õ�');
		$this->assign ( 'applyCompanyCode', $applyCompanyCode );
		$this->assign ( 'applyCompanyName', $applyCompanyName );
		$this->assign ( 'chargeDate', day_date );
		$this->assign( 'returnDate',$requireObj['returnDate'] );
		$this->assign ( 'deptName', $chargeDept );
		$this->assign ( 'deptId', $chargeDeptId );
		$this->assign ( 'chargeManId', $chargeManId );
		$this->assign ( 'chargeMan', $chargeMan );
		$this->assign ( 'reposeManId', $_SESSION['USER_ID'] );
		$this->assign ( 'reposeMan', $_SESSION['USERNAME'] );
		$this->assign ( 'reposeDeptId', $_SESSION['DEPT_ID'] );
		$this->assign ( 'reposeDept', $_SESSION['DEPT_NAME'] );
		$this->assign ( 'borrowDate', date("Y-m-d") );
		$this->assign ( 'returnDate', $requireObj['returnDate'] );
		$this->assign ( 'requireId', $requireId );
		$this->assign ( 'requireCode', $requireCode );
		$this->assign ( 'remark', '�ջ���ַ��'.$requireObj['address'] );
		$this->view ( 'add',true );
	}

	  /**
	   * ajaxǩ�ս�������
	   */
	   function c_toSign(){
		  	echo $this->service->sign_d($_GET['id']);
	   }

 	 /**
	  * ajaxɾ���������������嵥�ӱ���Ϣ
	  */
	  function c_deletes(){
		$message = "";
		try {
            $chargeObj = $this->service->get_d ( $_GET ['id'] );
			$chargeitemDao = new model_asset_daily_chargeitem();
	  		$condition = array(
	  			'allocateID'=>$chargeObj['id']
	  		);
	  		$chargeitemDao->delete($condition);
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
				succ_show ( 'controller/asset/daily/ewf_index_charge.php?actTo=ewfSelect&billId='.$id);
			} else {
				msgRf('�����ɹ�');
			}
		}
		else{
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
      	 $chargeObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($chargeObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_charge.php?actTo=ewfSelect&billId='.$chargeObj['id'].'&examCode=oa_asset_charge&formName=�ʲ�������������' );
			} else {
				msgRf('�༭�ɹ�');
			}
		}else{
			if ("audit" == $actType) {
				 echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				msgRf('�༭ʧ��');
			}
		}
	}
	
	/**
	 * ��ת���ʲ�����Աǩ��ʱȷ��ת���豸ҳ��
	 */
	function c_toSignToDevice() {
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		//����
		$obj ['file'] = $service->getFilesByObjId ( $obj ['id'], false );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('signtodevice',true);
	}
	
	/**
	 * �ʲ�����Աǩ��ʱȷ��ת���豸
	 */
	function c_signToDevice() {
		$this->checkSubmit(); //�����Ƿ��ظ��ύ
		$result = $this->service->signToDevice_d ($_POST [$this->objName]);
		if($result){
			msgRf('ȷ�ϳɹ�');
		}else{
			msgRf('ȷ��ʧ��');
		}
	}
}