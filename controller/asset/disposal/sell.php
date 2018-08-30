<?php
/**
 * �ʲ����ۿ��Ʋ���
 * @linzx
 */
class controller_asset_disposal_sell extends controller_base_action {

	function __construct() {
		$this->objName = "sell";
		$this->objPath = "asset_disposal";
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
		$this->assign ( 'sellerId', $_SESSION ['USER_ID'] );
		$this->assign ( 'seller', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'sellDate', date("Y-m-d") );

		$this->view ( 'add' );
	}


	/**
	 * �ɱ��ϵ���ת�����Ͼ����ʲ�ҳ��
	 * @author linzx
	 */
	function c_toSellScrap(){
		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo',$billNo);
		$allocateID = isset ($_GET['allocateID']) ? $_GET['allocateID'] : null;
		$this->assign('allocateID',$allocateID);

		$this->view ( 'scrapitem-list' );
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
				succ_show ( 'controller/asset/disposal/ewf_index_sell.php?actTo=ewfSelect&billId='.$id);
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
	 * @linzx
	 */
      function c_edit() {
      	 $sellObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($sellObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/disposal/ewf_index_sell.php?actTo=ewfSelect&billId='.$sellObj['id'].'&examCode=oa_asset_sell&formName=�ʲ�������������' );
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
	 * �̶��ʲ�������������ִ�з���
	 * @linzx
	 */
	function c_dealAfterAudit(){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		// �������objIdΪ ���۵�Id
		$objId = $folowInfo ['objId'];
//		$cradId=$this->service->getCardIdById_d($objId);
		if ($folowInfo['examines'] == "ok") {
			$cradId=$this->service->getCardIdById_d($objId);
		}
      	echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

	 /**
	  * ajaxɾ�����������嵥�ӱ���Ϣ
	  */
	  function c_deletes(){
		$message = "";
		try {
            $sellObj = $this->service->get_d ( $_GET ['id'] );
			$sellitemDao = new model_asset_disposal_sellitem();
	  		$condition = array(
	  			'sellId'=>$sellObj['id']
	  		);
	  		$sellitemDao->delete($condition);
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
	   * �ɱ��Ͼ����ʲ���ת����д���ϳ��۵��б�ҳ��
	   */
	  function c_toScrapAssetList(){
	  	$branchDao = new model_deptuser_branch_branch();
	  	$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
	  	$this->assign('applyCompanyCode', $_SESSION['Company']);
	  	$this->assign('applyCompanyName', $branchArr['NameCN']);
	  	$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
	  	$this->assign ( 'sellerId', $_SESSION ['USER_ID'] );
	  	$this->assign ( 'seller', $_SESSION ['USERNAME'] );
	  	$deptDao = new model_deptuser_dept_dept();
	  	$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
	  	$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
	  	$this->assign ( 'sellDate', date("Y-m-d") );
	  	$assetIdArr= $_GET['assetIdArr'];
	  	$this->assign('assetIdArr',$assetIdArr);
	  	
	  	$this->view ( 'scrap-addList' );
	  }
}