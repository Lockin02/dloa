<?php

/**
 * �ʲ��黹���Ʋ���
 *  @author linzx
 */
class controller_asset_daily_return extends controller_base_action {

	function __construct() {
		$this->objName = "return";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}


	/**
	 * ��ת�б�ҳ��
	 * ��д�͵���action��c_list();
	 * @author linzx
	 */
	function c_list() {
		$this->view ( 'list' );
	}

	/**
	 * ��ת�б�ҳ��
	 * ��д�͵���action��c_list();
	 * @author zengzx
	 */
	function c_myList() {
		$this->view ( 'mylist' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
	
		$service->getParam ( $_REQUEST );
		//����Ȩ�޴���
		$assetcardDao = new model_asset_assetcard_assetcard();
		$agencyCodeStr = $assetcardDao->getAgencyStr_d();
		if(!empty($agencyCodeStr)){//����Ȩ����Ϊ������ʾ����
			if($agencyCodeStr != ';;'){//����Ȩ�޲�Ϊȫ��
				//ƴװ�Զ���sql
				$sqlStr = "sql: and c.agencyCode in (".$agencyCodeStr.")";
				$service->searchArr['agencyCondition'] = $sqlStr;
			}
			$rows = $service->page_d ();
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
	
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['createId'] = $_SESSION['USER_ID'];
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
	 * @author linzx
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
	 * @author linzx
	 */
	function c_toAdd() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'returnManId', $_SESSION ['USER_ID'] );
		$this->assign ( 'returnMan', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'returnDate', date("Y-m-d") );
		$this->view ( 'add' );
	}

    function c_toBorrowreturn(){
        $borrowNo = isset ($_GET['borrowNo']) ? $_GET['borrowNo'] : null;
		$this->assign('borrowNo',$borrowNo);
		$this->view ( 'borrow' );

    }


	/**
	 * ��ת����д���ù黹�ʲ�ҳ��
	 * @author linzx
	 */
	function c_toReturnCharge(){
		$branchDao = new model_deptuser_branch_branch();
		$borrowId = isset ($_GET['borrowId']) ? $_GET['borrowId'] : null;
		$borrowNo = isset ($_GET['borrowNo']) ? $_GET['borrowNo'] : null;
		if( $borrowId ){
			$docDao = new model_asset_daily_charge();
			$docObj = $docDao->get_d($borrowId);
			$this->assign('agencyCode',$docObj['agencyCode']);
			$this->assign('agencyName',$docObj['agencyName']);
		}
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
//		$object ['billNo']= $codeDao->assetReturnCode ( "oa_asset_return", "GH" ,day_date);
//		$this->assign ( 'billNo', $object ['billNo'] );
		$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'returnManId', $_SESSION ['USER_ID'] );
		$this->assign ( 'returnMan', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'returnDate', date("Y-m-d") );
		$this->assign('borrowNo',$borrowNo);
		$this->assign('borrowId',$borrowId);
		$this->view ( 'charge-add' ,true );
	}

	/**
	 * ��ת����д���ù黹�ʲ�ҳ��
	 * @author linzx
	 */
	function c_toReturnBorrow(){
		$borrowId = isset ($_GET['borrowId']) ? $_GET['borrowId'] : null;
		$borrowNo = isset ($_GET['borrowNo']) ? $_GET['borrowNo'] : null;
		if( $borrowId ){
			$docDao = new model_asset_daily_borrow();
			$docObj = $docDao->get_d($borrowId);
			$this->assign('agencyCode',$docObj['agencyCode']);
			$this->assign('agencyName',$docObj['agencyName']);
		}
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'returnManId', $_SESSION ['USER_ID'] );
		$this->assign ( 'returnMan', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'returnDate', date("Y-m-d") );
		$this->assign('borrowNo',$borrowNo);
		$this->assign('borrowId',$borrowId);
		$this->view ( 'borrow-add' ,true );
	}


	/**
	 * ��ת����д�����黹�ʲ�ҳ��
	 * @author zzx
	 */
	function c_toReturnAsset(){
		$assetId = isset ($_GET['assetId']) ? $_GET['assetId'] : null;
		if( $assetId ){
			$changeDao = new model_asset_change_assetchange();
			$changeObj = $changeDao->find(array('assetId'=>$assetId),'id desc');
//			echo "<pre>";
//			print_R($changeObj);
			$this->assign( 'returnType',"oa_asset_".$changeObj['businessType'] );
			$this->assign( 'borrowNo',$changeObj['businessCode'] );
			$this->assign( 'borrowId',$changeObj['businessId'] );
			$assetDao = new model_asset_assetcard_assetcard();
			$assetObj = $assetDao->get_d($assetId);
			$this->assign( 'agencyCode',$assetObj['agencyCode'] );
			$this->assign( 'agencyName',$assetObj['agencyName'] );
		}
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'returnManId', $_SESSION ['USER_ID'] );
		$this->assign ( 'returnMan', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'returnDate', date("Y-m-d") );
		$this->assign('assetId',$assetId);
		$this->view ( 'asset-add' ,true );
	}


	   /**
	 * �����������
	 * @linzx
	 */
	function c_add() {
		$this->checkSubmit();//��֤�Ƿ��ظ��ύ
		$row =  $_POST [$this->objName];
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if( $actType == "audit" ){
        	$row['ExaStatus']='���';
        }
		$id = $this->service->add_d ($row, true );
		if ($id) {
			echo "<script>alert('�����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
		}else{
			echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		}
//        $userId=0;
//        $conditions = array( 'agencyCode'=>$row['agencyCode'] );
//        $agencyDao = new model_asset_basic_agency();
//        $agencyObj = $agencyDao->find($conditions);
//        $url='';
//        if( is_array($agencyObj)&&$agencyObj['chargeId']!='' ){
//			$url = '&billUser='.$agencyObj['chargeId'];
//        }
//        if ($id) {
//			if ("audit" == $actType) {
//				succ_show ( 'controller/asset/daily/ewf_index_return.php?actTo=ewfSelect&billId='.$id.$url);
//			} else {
//				echo "<script>alert('�����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
//			}
//		}
//		else{
//			if ("audit" == $actType) {
//				 echo "<script>alert('��������ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
//			} else {
//				 echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
//			}
//		}
}
/**
	 * �޸Ķ������
	 * @chenzb
	 */
      function c_edit() {
      	 $returnObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($returnObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_return.php?actTo=ewfSelect&billId='.$returnObj['id'].'&examCode=oa_asset_return&formName=�ʲ��黹��������' );
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



	 /**
	  * ajaxɾ�����������嵥�ӱ���Ϣ
	  *
	  */
	  function c_deletes(){
		$message = "";
		try {
            $returnObj = $this->service->get_d ( $_GET ['id'] );
			$returnitemDao = new model_asset_daily_returnitem();
	  		$condition = array(
	  			'allocateID'=>$returnObj['id']
	  		);
	  		$returnitemDao->delete($condition);
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
	   * ajaxǩ�ս�������
	   */
	   function c_toSign(){
		  	echo $this->service->sign_d($_POST['id']);
	   }

}