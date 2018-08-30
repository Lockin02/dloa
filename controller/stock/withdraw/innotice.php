<?php
/**
 * @author Administrator
 * @Date 2012��11��20�� 10:10:05
 * @version 1.0
 * @description:���֪ͨ�����Ʋ�
 */
class controller_stock_withdraw_innotice extends controller_base_action {

	function __construct() {
		$this->objName = "innotice";
		$this->objPath = "stock_withdraw";
		parent :: __construct();
	}

	/**
	 * ��ת�����֪ͨ���б�
	 */
	function c_page() {
		$this->view('list');
	}
	
	/**
	 * ��ת�����֪ͨ���б�-����
	 */
	function c_pageByProduce() {
		$this->view('produce-list');
	}

	/**
	 * ��ת���������֪ͨ��ҳ��
	 */
	function c_toAdd() {
		$docDao = new model_stock_withdraw_withdraw();
		$docObj = $docDao->get_d($_GET['id']);
		foreach ($docObj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('consigneeId',$_SESSION['USER_ID']);
		$this->assign('consignee',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);
        $this->assign('drawCode',$docObj['planCode']);
        $this->assign('drawId',$_GET['id']);
		$this->view('add');
	}

	/**
	 * ��ת���������֪ͨ��ҳ��-����
	 */
	function c_toAddByProduce() {
		$docDao = new model_produce_plan_produceplan();
		$docObj = $docDao->get_d($_GET['relDocId']);
		foreach ($docObj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('consigneeId',$_SESSION['USER_ID']);
		$this->assign('consignee',$_SESSION['USERNAME']);
		$this->assign('drawCode',$docObj['planCode']);
		$this->assign('drawId',$_GET['id']);
		$this->assign('thisDate',day_date);
		$this->view('produce-add');
	}
	
	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * ��ת���༭���֪ͨ��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("itemsList", $this->service->showItemAtEdit($obj['items']));
		$this->assign("itemscount", count($obj['items']));
		$this->view('edit');
	}

	/**
	 * ��ת���鿴���֪ͨ��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		if( $obj['docType']=='oa_contract_exchange' ){
			$obj['docType']='��������';
		}else if ($obj['docType']=='oa_produce_plan') {
			$obj['docType']='�����ƻ�';
		}
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
//		$this->assign("itemsList", $this->service->showItemAtView($obj['items']));
//		$this->assign("itemscount", count($obj['items']));
		$this->view('view');
	}

	/**
	 * ��ȡԴ������
	 */
	 function c_getDocEqu(){
	 	$id=$_POST['id'];
	 	$type=$_POST['docType'];
	 	$row = $this->service->getDocEqu_d($id,$type);
		echo util_jsonUtil::encode ( $row );
	 }

	/**
	 * �����ƻ��б�ӱ�
	 */
	 function c_equJson(){
	 	$outplanEqu = new model_stock_withdraw_equ();
		$outplanEqu->searchArr['mainId'] = $_POST['mainId'];
		$outplanEqu->searchArr['isDel'] = 0;
		$rows = $outplanEqu->list_d ();
//		echo "<pre>";
//		print_R($rows);
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $outplanEqu->count ? $outplanEqu->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $outplanEqu->page;
		echo util_jsonUtil::encode ( $arr );
	 }
}
?>