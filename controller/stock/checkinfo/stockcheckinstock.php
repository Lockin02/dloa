<?php
/**
 * @author Administrator
 * @Date 2010��12��21�� 20:57:46
 * @version 1.0
 * @description:�̵������Ʋ�
 */
class controller_stock_checkinfo_stockcheckinstock extends controller_base_action {

	function __construct() {
		$this->objName = "stockcheckinstock";
		$this->objPath = "stock_checkinfo";
		parent::__construct ();
	 }

	/*
	 * ��ת���̵����
	 */
    function c_page() {
    	$this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
	/*
	 * ��ת���̵����(demo)
	 */
    function c_page1() {
    	$this->show->display($this->objPath . '_' . $this->objName . '-list1');
    }
    /**
	 * @desription ��ת������µ��̵������Ϣҳ��
	 * @param tags
	 * @date 2010-12-21 ����09:47:00
	 * @qiaolong
	 */
	function c_toAdd () {
		$this->showDatadicts ( array ('checkType' => 'PDLX' ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}
	/**
	 * @desription �޸��̵������Ϣ
	 * @param tags
	 * @date 2010-12-22 ����11:09:47
	 * @qiaolong
	 */
	function c_toEdit () {
		//��ʾ�̵���Ϣ
		$rows = $this->service->get_d ( $_GET ['id'] );
		$rows['file']=$this->service->getFilesByObjId($_GET ['id'],'oa_stock_check_instock');
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('checkType' => 'PDLX' ), $rows ['checkType'] );

		//��ʾ�̵��Ʒ��Ϣ
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$rows = $service->getCheckProductInfo_d ($checkId);
		$this->show->assign ( 'list', $service->showProductInfoEdit ( $rows ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}
	/**
	 * @desription �鿴�̵������Ϣ
	 * @param tags
	 * @date 2010-12-22 ����11:21:43
	 * @qiaolong
	 */
	function c_toRead () {
		//��ʾ�̵���Ϣ
		$rows = $this->service->get_d ( $_GET ['id'] );
		$rows['file'] = $this->service->getFilesByObjId($_GET ['id'], false);
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$checkType = $this->getDataNameByCode($rows['checkType']);
		$this->show->assign('checkType',$checkType);


		//��ʾ�̵��Ʒ��Ϣ
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$arr = $service->getCheckProductInfo_d ($checkId);
		$actType=isset($_GET['actType'])?$_GET['actType']:null;
		$this->show->assign("actType",$actType);//����ҳ��(һ��Ĳ鿴ҳ�桢��Ƕ����������)

		$this->show->assign ( 'list', $service->showProductInfoList ( $arr ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-view');


	}
	/**
	 * @desription �ҵ�������� �̵���Ϣ���
	 * @param tags
	 * @date 2010-12-22 ����04:10:25
	 * @qiaolong
	 */
	function c_toMytaskCheckinfo () {
		$this->show->display($this->objPath . '_' . $this->objName . '-mychecktask-list');
	}
	/**
	 * @desription �ҵ�������� �̵���Ϣ��� �б��ȡ���ݷ���
	 * @param tags
	 * @date 2010-12-22 ����04:28:15
	 * @qiaolong
	 */
	function c_mytaskPJ () {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;
//		$auditNameId = isset ($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : null;
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
//		$rows = $service->mychecktaskinfo_d ();
		$rows = $service->pageBySqlId ('sql_examine');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription �̵���Ϣ�޸ı��淽��
	 * @param tags
	 * @date 2010-12-22 ����04:28:15
	 * @qiaolong
	 */
	function c_checkInfoEdit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}
	/**
	 * @desription ��ת���ύ���ҳ�淽��
	 * @param tags
	 * @date 2010-12-23 ����02:20:35
	 * @qiaolong
	 */
	function c_submitAudit () {
		//��ʾ�̵���Ϣ
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$checkType = $this->getDataNameByCode($rows['checkType']);
		$this->show->assign('checkType',$checkType);


		//��ʾ�̵��Ʒ��Ϣ
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$rows = $service->getCheckProductInfo_d ($checkId);
		$this->show->assign ( 'list', $service->showProductInfoList ( $rows ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-submitaudit-page');
	}
	/**
	 * @desription �ύ��������
	 * @param tags
	 * @date 2010-12-22 ����04:28:15
	 * @qiaolong
	 */
	function c_submitEdit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�ύ�����ɹ���' );
		}
	}

	/**
	 * @desription ��ת���̵����ҳ�淽��
	 * @param tags
	 * @date 2010-12-24 ����10:40:15
	 * @qiaolong
	 */
	function c_intostock () {
		//��ʾ�̵���Ϣ
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$checkType = $this->getDataNameByCode($rows['checkType']);
		$this->assign('checkT',$checkType);

		//��ʾ�̵��Ʒ��Ϣ
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$rows = $service->getCheckProductInfo_d ($checkId);
		$this->show->assign ( 'list', $service->showProductInfoList ( $rows ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-intostock-page');
	}
	/**
	 * @desription �̵���ⷽ��
	 * @param tags
	 * @date 2010-12-24 ����10:44:24
	 * @qiaolong
	 */
//	function c_intoShockInfo () {
//		$id = $_GET['id'];
//		$service = $this->service;
//		$service->searchArr['id']=$id;
//		$rows = $service->listBySqlId('select_default');
//		$stockCode = $rows[0]['stockCode'];
//		$checktype = $rows[0]['checkType'];
//		$arr = $service->getProductInfo($id);
//		if(!$arr){
//			msg('û�в�Ʒ�������');
//		}else{
//			$updatesql ="update oa_stock_check_instock set ExaStatus='�����' where id='".$id."'";
//			$service->query($updatesql);
//			foreach($arr as $key=>$val){
//				if($checktype == 'PDPK'){
//					 $adjust = - $arr[$key]['adjust'];
//				}else{
//					 $adjust = $arr[$key]['adjust'];
//				}
//				$productId = $arr[$key]['productId'];
//				$sql = "update oa_stock_inventory_info  c  set c.exeNum=c.exeNum+(".$adjust."),c.actNum=c.actNum+(".$adjust.") where c.stockCode='".$stockCode."' and c.productId='".$productId."'";
//				$service->query($sql);
//			}
//			if($service->query($sql)){
//
//				msg('�̵����ɹ�');
//			}
//		}
//	}

	/**�̵����
	*author can
	*2011-1-20
	*/
	function c_intoShockInfo(){
//		echo "<pre>";
//		print_r($_POST);
		$flag=$this->service->intoShockInfo_d($_POST[$this->objName]);
		if($flag){
			msg("�̵����ɹ���");
		}else{
			msg('�̵���ⲻ�ɹ�');
		}

	}


 }
?>