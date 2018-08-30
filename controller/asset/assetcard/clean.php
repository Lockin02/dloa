<?php


/**
 * ��Ƭ������Ʋ���
 *  @author linzx
 */
class controller_asset_assetcard_clean extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "clean";
		$this->objPath = "asset_assetcard";
		parent :: __construct();
	}
	/**
	 * ��ת������Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}
	/**
	 * ��ת�����۵��ʲ��б�
	 */
	function c_toCleanSell() {

		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo', $billNo);

		$sellID = isset ($_GET['sellID']) ? $_GET['sellID'] : null;
		$this->assign('sellID', $sellID);
		$this->view('sellitem-list');

	}

	/**
	 * ��ת�����ϵ��ʲ��б�
	 */
	function c_toCleanScrap() {

		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo', $billNo);

		$allocateID = isset ($_GET['allocateID']) ? $_GET['allocateID'] : null;
		$this->assign('allocateID', $allocateID);
		$this->view('scrapitem-list');

	}
	/**
	 * ��ת������ҳ�� �����ʲ�����
	 **/
	function c_toAdd() {

		$billType = isset ($_GET['billType']) ? $_GET['billType'] : null;
		$this->assign('billType', $billType);

		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo', $billNo);

		$billID = isset ($_GET['billID']) ? $_GET['billID'] : null;
		$this->assign('billID', $billID);

		$assetCode = isset ($_GET['assetCode']) ? $_GET['assetCode'] : null;
		$this->assign('assetCode', $assetCode);

		$assetId = isset ($_GET['assetId']) ? $_GET['assetId'] : null;
		$this->assign('assetId', $assetId);

		$assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName', $assetName);

		$assetTypeName = isset ($_GET['assetTypeName']) ? $_GET['assetTypeName'] : null;
		$this->assign('assetTypeName', $assetTypeName);

		$this->assign('cleanDate', date("Y-m-d"));

		//�䶯��ʽ������
		$methodDao = new model_asset_basic_change();
		$names = $methodDao->listBySqlId("select_changeIsDel");
		//$names=$methodDao->list_d();
		$list = $this->service->showSelect_d($names);
		$this->assign("changeWayOption", $list);

		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName], true);
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show('controller/asset/assetcard/ewf_index_clean.php?actTo=ewfSelect&billId=' . $id);
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
	 * ��ʼ������ �鿴������Ϣ
	 */
	function c_init() {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//�ύ������鿴����ʱ���عرհ�ť
		if (isset ($_GET['viewBtn'])) {
			$this->assign('showBtn', 1);
		} else {
			$this->assign('showBtn', 0);
		}
		$this->view('view');
	}

	/**
	 * �̶��ʲ�������������ִ�з���
	 * @linzx
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		// �������objIdΪ ����Id  [examines] => ok  [examines] => no
		$objId = $folowInfo['objId'];
		//����ͨ���͸ı俨Ƭ������״̬��ûͨ���Ͳ��ı�
		if ($folowInfo['examines'] == "ok") {
			$cradId = $this->service->getAssetIdById_d($objId);
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

}
?>