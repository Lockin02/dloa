<?php
/**
 * @author show
 * @Date 2014��09��01��
 * @version 1.0
 * @description:�ʲ�ת����������Ʋ�
 */
class controller_asset_require_requireout extends controller_base_action {

	function __construct() {
		$this->objName = "requireout";
		$this->objPath = "asset_require";
		parent :: __construct();
	}
	
	/**
	 * ��ת���ʲ�ת���������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���ʲ�����б�
	 */
	function c_awaitList() {
		$this->view('awaitList');
	}
	
	/**
	 * ��ת�����������ϸ
	 */
	function c_detailList(){
		$this->view('detailList');
	}
	
	/**
	 * �ʲ���������ϸ��
	 */
	function c_jsonDetail(){
		$service = $this->service;
		
		$service->getParam ( $_REQUEST );
		//ֻ��ʾ������������0���ʲ���������ϸ
		$sqlStr = "sql: and i.executedNum > 0";
		$service->searchArr['condition'] = $sqlStr;
		$rows = $service->page_d ('select_detail');
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
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->assign('applyDate', day_date);
		$this->assign('applyId', $_SESSION['USER_ID']);
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyDeptId', $_SESSION['DEPT_ID']);
		$this->assign('applyDeptName', $_SESSION['DEPT_NAME']);
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		$this->view('add');
	}
	
	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST[$this->objName];
	
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType != 'audit'){
			$object['ExaStatus']="���ύ";
		}
		$id = $this->service->add_d($object,true);
		if ($id) {
			if ($actType == 'audit') {
				succ_show('controller/asset/require/ewf_index_requireout.php?actTo=ewfSelect&billId=' . $id);
			} else {
				msgRf('����ɹ�');
			}
		}else{
			if ($actType == 'audit') {
				msgRf('�ύʧ��');
			} else {
				msgRf('����ʧ��');
			}
		}
	}
	
	/**
	 * ��ת���༭ҳ��
	 */
	function c_toEdit() {
// 		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * �༭�������
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
	
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($actType != 'audit' && $actType != 'confirm') {
			$object['ExaStatus']="���ύ";
		}
		$flag = $this->service->edit_d($object, true);
		if ($flag) {				
			if ($actType == 'audit') {
				succ_show('controller/asset/require/ewf_index_requireout.php?actTo=ewfSelect&billId=' . $object['id']);
			} elseif ($actType == 'confirm') {
				msgRf('ȷ�ϳɹ�');
			} else {
				msgRf('�༭�ɹ�');
			}
		} else {
			if ($actType == 'audit') {
				msgRf('�ύʧ��');
			} elseif ($actType == 'confirm') {
				msgRf('ȷ��ʧ��');
			} else {
				msgRf('�༭ʧ��');
			}
		}
	}

	/**
	 * ��ת���鿴ҳ��
	 */
	function c_toView() {
		// 		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	
	/**
	 * ��ת��ȷ������ҳ��
	 */
	function c_toConfirm() {
		// 		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('confirm');
	}
	
	/**
	 * �������ʱ��Ӵӱ���Ϣ
	 */
	function c_getItemList () {
		$requireId = isset($_POST['requireId'])?$_POST['requireId']:null;
		$requireitemDao = new model_asset_require_requireoutitem();
		$rows = $requireitemDao->getDetail_d($requireId);
		echo util_jsonUtil::iconvGB2UTF ($requireitemDao->showProAtEdit($rows));
	}
	
	/**
	 * ��������ִ�з���
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		// �������objIdΪ ���뵥id
		$objId = $folowInfo['objId'];
		//����ͨ���������ʼ�֪ͨ�����Ա�����ʲ�����
		if ($folowInfo['examines'] == "ok") {
			$this->service->mailDeal_d('assetRequireout',null,array('id' => $objId));
		}else{//������ͨ��������Ƭ״̬����Ϊ����
			$object = $this->service->get_d($objId);
			foreach($object['items'] as $key => $val){
				$cardDao = new model_asset_assetcard_assetcard();
				$cardDao->update(array('id'=>$val['assetId']),array('useStatusCode'=>'SYZT-XZ','useStatusName'=>'����'));
			}
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
}