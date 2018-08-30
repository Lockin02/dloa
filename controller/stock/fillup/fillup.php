<?php
/**
 * @author huangzf
 * @Date 2011��1��17�� 11:51:07
 * @version 1.0
 * @description:����ƻ����Ʋ�
 */
class controller_stock_fillup_fillup extends controller_base_action {

	function __construct() {
		$this->objName = "fillup";
		$this->objPath = "stock_fillup";
		parent::__construct ();
	}

	/*
	 * ��ת������ƻ�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * @desription ��ӷ���
	 * @param tags
	 * @date 2011-1-17 ����02:07:21
	 * @qiaolong
	 */
	function c_toAdd() {
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 *
	 * ���ݿ�澯����Ϣ���Ʋ���ƻ�
	 */
	function c_toPush() {
		$this->view ( "push" );
	}
	/**
	 * @desription ��ת���鿴ҳ��
	 * @param tags
	 * @date 2011-1-17 ����01:28:20
	 * @qiaolong
	 */
	function c_init() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$fillup = $this->service->get_d ( $_GET ['id'] );

		foreach ( $fillup as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showViewDePro ( $val );
// 						echo"<pre>";
// 						print_r($str);
// 						die();
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}

		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;

		$this->show->assign ( "actType", $actType ); //����ҳ��(һ��Ĳ鿴ҳ�桢��Ƕ����������)
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

		/**
	 * ��ת�����༭ҳ��
	 *
	 * @param tags
	 */
	function c_toAuditEdit () {
		$this->permCheck ();//��ȫУ��
		$id=isset($_GET['id'])?$_GET['id']:null;
		$otherdatasDao=new model_common_otherdatas();
		$flag=$otherdatasDao->isLastStep($_GET['id'],$this->service->tbl_name);
		if(0){
			$id = $_GET ['id'];
			$fillup = $this->service->get_d ( $_GET ['id'] );
			$this->assign('invnumber',count( $fillup ["details"] ));
			foreach ( $fillup as $key => $val ) {
				if ($key == 'details') {
					$str = $this->service->showEditAudit ( $val );
					$this->show->assign ( 'list', $str );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			$this->display('audit-edit');
		}else{
			$this->c_init();
		}
	}

	/**
	 *��ϸҳ��
	 */
	function c_detail() {
		$this->show->assign ( "id", $_GET ['id'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-detail' );
	}

	/**
	 * @desription ��ת���޸�ҳ��
	 * @linzx
	 */
	function c_toEdit() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$fillup = $this->service->get_d ( $_GET ['id'] );
		foreach ( $fillup as $key => $val ) {

			if ($key == 'details') {
				$str = $this->service->showEditDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$this->show->assign ( "itemscount", count ( $fillup ['details'] ) );
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
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
				succ_show ( 'controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_stock_fillup&formName=��������' );
			} else {
				echo "<script>alert('�����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
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
		$fillUpObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $fillUpObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId=' . $fillUpObj ['id'] . '&examCode=oa_stock_fillup&formName=��������' );
			} else {
				echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}
	/**
	 * �ı䵥�ݵ�״̬
	 */
	function c_changeAuditStatus() {
		$service = $this->service;
		if ($service->changeAuditStatus ( $_GET ['id'] )) {
			echo 0; //�ɹ�
		} else {
			echo 1;
		}
	}

	/**�ж��Ƿ����´�ɹ��ƻ�
	 *author can
	 *2011-3-30
	 */
	function c_isAddPlan() {
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$flag = $this->service->isAddPlan_d ( $id );
		if ($flag) {
			echo 1;
		} else {
			echo 0;
		}
	}
	/**************************************�������****************************************************************/

	/**
	 * �ҵ����� - tab
	 */
	function c_auditTab() {
		$this->display ( 'audittab' );
	}

	/**
	 * �ҵ����� �� δ����ҳ��
	 */
	function c_toAuditNo() {
		$this->display ( 'auditno' );
	}

	/**
	 * �ҵ����� �� ��������ҳ��
	 */
	function c_toAuditYes() {
		$this->display ( 'audityes' );
	}

	/**
	 * @desription �ҵ�������� �̵���Ϣ��� �б��ȡ���ݷ���
	 * @param tags
	 * @date 2011-8-17
	 * @chenzb
	 */
	function c_myApprovalPJ() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true; //���ý�������
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		//$service->searchArr ['Flag'] = 0;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ( 'sql_examine' );
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *
	 * �����������ҵ�񷽷�
	 */
	function c_dealAfterAudit() {
		$rows=isset($_GET['rows'])?$_GET['rows']:null;

	    //�������ص�����
        $this->service->workflowCallBack($_GET['spid']);

		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
}

?>