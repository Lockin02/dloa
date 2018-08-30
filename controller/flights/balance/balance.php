<?php
/**
 * @author Show
 * @Date 2013��7��1�� ������ 13:50:47
 * @version 1.0
 */
class controller_flights_balance_balance extends controller_base_action {
	function __construct() {
		$this->objName = "balance";
		$this->objPath = "flights_balance";
		parent::__construct ();
	}
	/**
	 * ��ʾ���㶩�����м�¼
	 */
	function c_list(){
		$this->view('list');
	}

	/**
	 * ��ȡID��ѯ������Ϣ�����
	 */
	function c_toAddBatch(){
		$msgId = isset($_GET['msgId'])?$_GET['msgId']:'';
		$this->assign('msgId', $msgId);
		$object = $this->service->msgInfo_d($_GET['msgId']);
		foreach( $object as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('addbatch');
	}

	/**
	 * ��Ӵӱ���Ϣ
	 */
	function c_addBatch(){
		$object = $_POST [$this->objName];
		$id = $this->service->addBatch_d ( $object );
		if($id){
			msgRf("��ӳɹ�");
		}else{
			msgRf("���ʧ��");
		}
	}

	/**
	 * ���ɽ��㵥 - ��ѯ����
	 */
	function c_toSubAdd(){
		$beginDate = date('Y-m-01', strtotime(day_date));
		$this->assign('beginDate',$beginDate);
		$this->assign('endDate',date('Y-m-d', strtotime("$beginDate +1 month -1 day")));
		$this->view('addsub');
	}

	/**
	 * ��ӽ��㶩��
	 */
	function c_add(){
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object);
		$this->assign('creator',$_SESSION['USERNAME']);
		if($id){
			msgRf("��ӳɹ�");
		}else{
			msgRf("���ʧ��");
		}
	}

	/**
	 * ��ת���༭ҳ��
	 */
	function c_toEdit(){
		$this->permCheck(); //��ȫУ��
		$object = $this->service->get_d($_GET['id']);
		foreach ($object as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * �޸Ľ��㶩��
	 */
	function c_edit(){
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object);
		if($id){
			msgRf("�޸ĳɹ�");
		}else{
			msgRf("�޸�ʧ��");
		}
	}

	/**
	 *�鿴������Ϣ
	 */
	function c_toView(){
		$id = $_GET['id'];
		$obj = $this->service->get_d($id); //��������
		$billObj = $this->service->getBillInfo_d($id); //��Ʊ����
		if(!empty($billObj)){
			$infoArr = array_merge($obj,$billObj);
			$this->assignFunc($infoArr);
		}else{
			$billObj = array(
				"billMoney"=>'',
				"billDate"=>'',
				"billTypeName"=>'',
				"billContent"=>''
			);
			$infoArr = array_merge($obj,$billObj);
			$this->assignFunc($infoArr);
		}
		$this->assign('balanceStatus',$this->service->rtStatus_d($obj['balanceStatus']));
		$this->assign('id',$id);
        //�������{file}
        $this->assign('file',$this->service->getFilesByObjId ( $billObj['id'], false,'oa_flights_balance_bill' )) ;
		$this->view('view');
	}

    /**
     * ��дdelete����
     */
	function c_delete() {
		$id = $_POST['id'];
	   	$rs = $this->service->delete_d($id);
	   	if($rs){
	   		echo 1;
	   	}else{
	   		echo 0;
	   	}
	   	exit();
	}

	/**
	 * ���㵥���ͳ��
	 */
	function c_costForShow(){
		$id = !empty($_GET['id']) ? $_GET['id'] : exit();
		$costDetail = $_GET['costDetail'];
		switch($costDetail){
			case '0' : $datas = $this->service->getCostForShow_d($id);$str = $this->service->showCost_d($datas);break;
			case '1' : $datas = $this->service->getCostForDept_d($id);$str = $this->service->showCostDept_d($datas);break;
			case '2' : $datas = $this->service->getCostForProject_d($id,$costDetail);$str = $this->service->showCostProject_d($datas,$costDetail);break;
			case '3' : $datas = $this->service->getCostForProject_d($id,$costDetail);$str = $this->service->showCostProject_d($datas,$costDetail);break;
			case '4' : $datas = $this->service->getCostForSale_d($id);$str = $this->service->showCostSale_d($datas);break;
			case '5' : $datas = $this->service->getCostForContract_d($id);$str = $this->service->showCostContract_d($datas);break;
		}
		exit(util_jsonUtil::iconvGB2UTF($str));
	}
}