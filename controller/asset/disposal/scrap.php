<?php
/**
 * �ʲ����Ͽ��Ʋ���
 * @linzx
 */
class controller_asset_disposal_scrap extends controller_base_action {

	function __construct() {
		$this->objName = "scrap";
		$this->objPath = "asset_disposal";
		parent :: __construct();
	}

	/**
	 * ��ת�б�ҳ��
	 * ��д�͵���action��c_list();
	 */
	function c_list() {
		$this->view('list');
	}

	/**
	* ��ʼ������
	*/
	function c_init() {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		//����
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$this->view('view');
		} else {
			$this->view('edit');
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
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('deptName', $deptInfo['DEPT_NAME']);
		$this->assign('scrapDate', date("Y-m-d"));

		$type = $_GET['type'];

		if ($type == "lose") {
			//��ʧ��id
			$loseId = $_GET['loseId'];
			$this->assign('loseId', $loseId);
			//��ʧ�����
			$loseBillNo = $_GET['loseBillNo'];
			$this->assign('loseBillNo', $loseBillNo);
			//��ʧ�ʲ���������ҳ��
			$this->view('lose-add');
		} else {
			//�ʲ���������ҳ��
			$this->view('add');
		}
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAddByCard() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('deptName', $deptInfo['DEPT_NAME']);
		$this->assign('scrapDate', date("Y-m-d"));
		$this->assign('assetId', $_GET['assetId']);
		//�ʲ���������ҳ��
		$this->view('addbycard');
	}

	/**
	* �����������
	* @linzx
	*/
	function c_add() {
		$object = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		//�ύ����ȷ��
		if($actType == "finance"){
			$object['financeStatus'] = "����ȷ��";
		}else{
			$object['financeStatus'] = "���ύ";
		}
		$id = $this->service->add_d($object, true);
		if($id){
			if($actType == "finance"){
				echo "<script>alert('�ύ����ȷ�ϳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}else{
			if($actType == "finance"){
				echo "<script>alert('�ύ����ȷ��ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}
	/**
	 * �޸Ķ������
	 * @linzx
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		//�ύ����ȷ��
		if($actType == "finance"){
			$object['financeStatus'] = "����ȷ��";
		}else{
			$object['financeStatus'] = "���ύ";
		}
		$id = $this->service->edit_d($object, true);
		if($id){
			if($actType == "finance"){
				echo "<script>alert('�ύ����ȷ�ϳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}else{
			if($actType == "finance"){
				echo "<script>alert('�ύ����ȷ��ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}
	/**
	* ajaxɾ�����������嵥�ӱ���Ϣ
	*/
	function c_deletes() {
		$message = "";
		try {
			$scrapObj = $this->service->get_d($_GET['id']);
			$scrapitemDao = new model_asset_disposal_scrapitem();
			$condition = array (
				'allocateID' => $scrapObj['id']
			);
			//����ӱ�����loseId��loseId��Ϊ�� ��д��ʧ��ϸ����ı���״̬
			foreach ($scrapObj['details'] as $key => $val) {
				$assetId = $val['assetId'];
				$loseId = $val['loseId'];
				if ($loseId) {
					$changeStatus = new model_asset_daily_loseitem();
					$changeStatus->setNoScrapStatus($loseId, $assetId);
				}

			}

			$scrapitemDao->delete($condition);
			$this->service->deletes_d($_GET['id']);
			//			echo util_jsonUtil::encode ( $scrapObj);
			$message = '<div style="color:red" align="center">ɾ���ɹ�!</div>';

		} catch (Exception $e) {
			$message = '<div style="color:red" align="center">ɾ��ʧ�ܣ��ö�������Ѿ�������!</div>';
		}
		if (isset ($_GET['url'])) {
			$event = "document.location='" . iconv('utf-8', 'gb2312', $_GET['url']) . "'";
			showmsg($message, $event, 'button');
		} else
			if (isset ($_SERVER[HTTP_REFERER])) {
				$event = "document.location='" . $_SERVER[HTTP_REFERER] . "'";
				showmsg($message, $event, 'button');
			} else {
				$this->c_page();
			}

		msg('ɾ���ɹ���');

	}

	/**
	 * �̶��ʲ�������������ִ�з���
	 * @linzx
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		// �������objIdΪ ���ϵ�Id
		$objId = $folowInfo['objId'];
		//$cradId=$this->service->getAssetIdById_d($objId);
		if ($folowInfo['examines'] == "ok") {
			$cradId = $this->service->getAssetIdById_d($objId);
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
	/**
	 * ת��ȷ�ϱ��������б�
	 */	
	function c_requireList() {
		$this->view('require-list');
	}
	/**
	 * ת���˶Ա�������
	 */
	function c_toCheckRequire() {
		$obj = $this->service->get_d($_GET['id']);
		//����
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		//�ʼ������˴���
		$mailId = implode(',', array_unique(array($obj['proposerId'],$obj['payerId'])));
		$mailName = implode(',', array_unique(array($obj['proposer'],$obj['payer'])));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('mailId', $mailId);
		$this->assign('mailName', $mailName);
		$this->view('check-require');
	}
	/**
	 * �˶ԡ���ر�������
	 */
	function c_confirmRequire() {
		$object = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == "check"){
			$object['financeStatus'] = "��ȷ��";
		}else{
			$object['financeStatus'] = "���";
		}
		$id = $this->service->edit_d($object, true);
		if($id){
			if($actType == "check"){
				echo "<script>alert('�˶Գɹ�!');</script>";
				//�˶Գɹ�����������
				succ_show('controller/asset/disposal/ewf_index.php?actTo=ewfSelect&billId='.$object['id']);
			}else{
				echo "<script>alert('��سɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}else{
			if($actType == "check"){
				echo "<script>alert('�˶�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('���ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}
	/**
	 * ����
	 */
	function c_recall(){
		$object = $this->service->get_d($_POST['id']);
		$object['financeStatus'] = "���ύ";
		$object['recallFlag'] = "y";  //���ر�־
		$object['item'] = $object['details'];
		unset($object['details']);
		$id = $this->service->edit_d($object, true);
		if($id){
			echo 1;
		}else{
			echo 0;
		}
	}
	/**
	 * ȷ�ϱ��������ҳJson
	 */
	function c_requirePageJson() {
		$service = $this->service;
	
		$service->getParam ( $_REQUEST );
		//ƴװ�Զ���sql
		$sqlStr = "sql: and c.financeStatus in ('����ȷ��','��ȷ��') and c.ExaStatus in ('��������','���ύ','���')";
		$service->searchArr['statusCondition'] = $sqlStr;
		$rows = $service->page_d ();
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
}