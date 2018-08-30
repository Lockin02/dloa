<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:11
 * @version 1.0
 * @description:���������嵥���Ʋ�
 */
class controller_projectmanagent_exchange_exchangeequ extends controller_base_action {

	function __construct() {
		$this->objName = "exchangeequ";
		$this->objPath = "projectmanagent_exchange";
		parent::__construct ();
	 }

	/*
	 * ��ת�����������嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���������������嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭���������嵥ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���������嵥ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

	/**
	 * �����б�ӱ����ݻ�ȡ
	 */
	function c_pageJson() {
		if( $_POST['ifDeal'] ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$lockDao = new model_stock_lock_lock ();
			$service = $this->service;
			$service->getParam ( $_POST );
			$this->service->searchArr['isDel']=0;
			$rows=$service->list_d();
			foreach ( $rows as $key=>$val){
				$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,'model_projectmanagent_exchange_exchange' );
				$rows[$key]['exeNum'] =  $inventoryDao->getExeNums( $rows[$key]['productId'], '1' );
			}
		}else{
			$rows = array();
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr);
	}

	/**
	 * �ж�ĳ����ͬ�Ƿ��в�Ʒ�嵥
	 */
	 function c_getEquById(){
	 	$sql = "select count(*) as equNum from " . $this->service->tbl_name . " where presentId=" . $_POST['id'] . " and isDel<>1";
	 	$equNum = $this->service->_db->getArray ( $sql );
	 	echo $equNum[0]['equNum'];
	 }
   	/***************************************����ȷ��   start************************************************/

	/**
	 * ��ȡ��Ʒ����(ע�⣺��ʱ����isTemp��ǰ̨���ݹ���)
	 */
	function c_getConEqu() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$contEqu = $service->list_d();
		echo util_jsonUtil :: encode($contEqu);
	}


	/**
	 * ��ȡ��Ʒ�µ�������Ϣ
	 */
	function c_getProductEqu() {
		$id = $_POST['conProductId'];
		$service = $this->service;
		$equArr = $service->getProductEqu_d($id);
		if( is_array($equArr)&& count($equArr)>0 ){
			foreach( $equArr as $key=>$val ){
				$equArr[$key]['productModel'] = $val['pattern'];
				if( isset($_POST['number']) ){
					$equArr[$key]['number'] = $val['number']*($_POST['number']*1);
				}
			}
		}
		$equArr = $this->sconfig->md5Rows($equArr);
		echo util_jsonUtil :: encode($equArr);
	}

	/**
	 * ��ȡĳ�������嵥�������Ϣ
	 * add by zengzx
	 */
	function c_getEquByParentEquId() {
		$equs = $this->service->getEquByParentEquId_d($_POST['parentEquId']);
//		echo "<pre>";
//		print_R($equs);
		echo util_jsonUtil :: encode($equs);
	}
	/**
		 * ���ϴ����� ����
		 */
	function c_toEquAdd() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($_GET['id']);
		$products = $this->service->showItemView($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('add');
	}

	/**
	 * ���ϴ����� ���
	 */
	function c_toEquChange() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_projectmanagent_exchange_exchange();
		$linkDao = new model_projectmanagent_exchange_exchangeequlink();
		$linkObj = $linkDao->get_d($_GET['linkId']);
		$obj = $contDao->getDetailInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign('ExaDTOne', $linkObj['ExaDTOne']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('change');
	}

	/**
	 * ���ϴ����� �༭
	 */
	function c_toEquEdit() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ���ϴ����� �༭
	 */
	function c_toEquView() {
		$this->permCheck(); //��ȫУ��
		$linkDao = new model_projectmanagent_exchange_exchangeequlink();
		$link = $linkDao->get_d($_GET['linkId']);
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($link['exchangeId']);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $link['exchangeId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("linkId", $link['id']);
		if(!empty($_GET['changeView'])){//����鿴��־
			$this->assign("changeView", $_GET['changeView']);
		}else{
			$this->assign("changeView", '');
		}
		if(!empty($_GET['isShowDel'])){//�Ƿ���ʾɾ������
			$this->assign("isShowDel", $_GET['isShowDel']);
		}else{
			$this->assign("isShowDel", 'true');
		}
		$this->assign("isTemp", $link['isTemp']);
		$this->assign("originalId", $link['originalId']);
		$this->view('view');
	}

	/**
	 * ����ȷ������
	 */
	function c_equAdd($isEditInfo = true) {
		$this->permCheck(); //��ȫУ��
		$object = $_POST['exchange'];
//		echo "<pre>";
//		print_R($object);
		if( $_GET['act'] == "audit" ){
			$id = $this->service->equAdd_d($object,true);
		}else{
			$id = $this->service->equAdd_d($object);
		}
		if ($id && $_GET['act'] == "audit") {
			msg('�ύ�ɹ�����������ת�뵽���������б�ҳ��');
		} else{
			if ($id) {
				msg('����ɹ���');
			} else {
				msg('����ʧ�ܣ�');
			}
		}
	}

	/**
	 * �޸�����
	 */
	function c_equEdit($isEditInfo = false) {
		//		$this->permCheck (); //��ȫУ��
		$object = $_POST['exchange'];
		if( $_GET['act']== "audit" ){
			$flag = $this->service->equEdit_d($object,true);
		}else{
			$flag = $this->service->equEdit_d($object);

		}
		if ($flag && $_GET['act'] == "audit") {
			msg('�ύ�ɹ�����������ת�뵽���������б�ҳ��');
		} else{
			if ($flag) {
				msg('�༭�ɹ���');
			} else {
				msg('�༭ʧ�ܣ�');
			}
		}
	}

	/**
	 * �������
	 */
	function c_equChange($isEditInfo = false) {
		//		$this->permCheck (); //��ȫУ��
		$rows = $_POST['exchange'];
		$id = $this->service->equChange_d($rows);
		if ($id) {
			msg('����ɹ���');
		} else{
			msg('���ʧ�ܣ�');
		}
	}

	/**
	 * ��ת���鿴����ȷ��tab
	 */
	function c_toViewTab() {
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('id', $_GET['id']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/***************************************����ȷ��   end************************************************/
	/**
	 * ����ȷ�� �����������
	 */
	function c_getNoProductEqu(){
		$contractId = $_POST['exchangeId'];
		$this->service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$rows = $this->service->getNoProductEqu_d($contractId);
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->srocArr['equIdArr'] = $_GET['equIdArr'];
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>