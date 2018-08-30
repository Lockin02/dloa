<?php
/**
 * ��ϵ�˿��Ʋ���
 */
class controller_customer_linkman_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "customer_linkman";
		parent::__construct ();
	}
	/*
      * �ͻ���ϵ���б�
      */
	function c_linkmanInfo() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listinfo' );
	}

     /**
      * �ͻ���ϵ�˵��� by Liub
      */
    function c_toUplod(){
        $this->display("upload");
    }
    /**
     * ����Excel by  Liub
     */
    function c_importExcel() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_customer_customer_importCustomerUtil ();
			$excelData = $dao->linkmanDate ( $filename, $temp_name );
            $objNameArr =  array(
                 0 => 'province',//����ʡ��
                 1 => 'city',//����
                 2 => 'area',//��������
                 3 => 'customrType',//�ͻ�����
                 4 => 'customerName', //�ͻ�����
                 5 => 'linkmanName',  //��ϵ������
                 6 => 'sex' , //�Ա�
                 7 => 'age', // ����
                 8 => 'duty', //ְ��
                 9 => 'phone', //�绰����
                 10 =>'mobile',//�ֻ�����
                 11 =>'email',//Eamil
                 12 =>'address',//��ַ
                 13 =>'QQ',//qq
                 14 =>'MSN',//MSN
                 15 =>'height',//���
                 16 =>'weight',//����
                 17 =>'remark'//��ע
            );
            $objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}

			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importExcel ( $objectArr );

			if (is_array ( $resultArr ))
				echo util_excelUtil::showResult ( $resultArr, "�ͻ���ϵ����Ϣ������", array ("��ϵ������", "���" ) );

			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}


	function c_toAdd() {
		$customerId=$_GET['id'];
		if(!empty($customerId)){
			$this->assign('customerId',$customerId);
			$this->assign('customerName',$_GET['customerName']);
			$this->assign('isFromCustomer',$_GET['isFromCustomer']);//�Ƿ�ӿͻ�ģ��������ϵ�ˣ��������ֱ༭
		}else{
			$this->assign('customerName','');
			$this->assign('customerId','');
			$this->assign('isFromCustomer','');
		}
		$this->assign('createId',$_SESSION['USER_ID']);
	    $this->assign('createName',$_SESSION['USERNAME']);
		$this->view ( 'add' );
	}


	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}


	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$linkman=$_POST [$this->objName];
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$linkman['id']=$id;
	//	if ($id) {
	//		msg ( '��ӳɹ���' );
	//	}
		//$this->listDataDict();
		if ($id) {
			echo "<script>window.returnValue='" . util_jsonUtil::encode ( $linkman ) . "';</script>";
			msg ( '��ӳɹ���' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���', '?model=customer_linkman_linkman&action=linkmanInfo' );
		}
	}

	/**
	 * �鿴
	 */
	function c_readInfo() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * �ڿͻ�������Ϣ�в鿴��ϵ��
	 */
	function c_readInfoInS() {
		$rows = $this->service->get_d ( $_GET ['id'], 'read' );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-readins' );
	}

	/**
	 * �ڿͻ���Ϣ����ʾ��ϵ���б�
	 */
	function c_listLinkManInCustomer() {
		$this->permCheck ($_GET ['id'],'customer_customer_customer');//��ȫУ��
		$this->show->assign ( 'id', $_GET ['id'] );
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr = "";
		$service->searchArr ['customerId'] = $_GET ['id'];
		$rows = $service->getLinkManBySId ();
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $service->count ) );
		$this->show->assign ( 'list', $service->showlistInCustomer ( $rows, $showpage ) );
		//		$this->show->assign('list',$rows);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listincustomer' );
	}
	/*
     * ��д�ͻ���Ϣ����ʾ��ϵ���б��PageJson
     */
	function c_linsinCustomerPageJson() {
		//         	$this->show->assign ('id' , $_GET ['id']);
		$customerId = $_GET ['id'];

		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;

		$service->searchArr ['customerId'] = $customerId;
		$rows = $service->pageBySqlId ( "select_linkman" );

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**ѡ����ϵ��
	 * @author suxc
	 *
	 */
	function c_selectLinkman() {
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->view ( 'select' );
	}


	/**
	 * ����Ȩ�޺ϲ�
	 */
	function regionMerge($privlimit,$arealimit){
		$str = null;
		if(!empty($privlimit)||!empty($arealimit)){
			if($privlimit){
				$str .= $privlimit;
			}
			if(!empty($str) && !empty($arealimit)){
				$str .= ','.$arealimit;
			}else{
				$str .= $arealimit;
			}
			return $str;
		}else{
			return null;
		}
	}

	/**
	 * ��ϵ���б�PageJson
	 * by Liub
	 */

	function c_linkmanPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $otherDao = new model_common_otherdatas();
        $privlimit = $otherDao->getUserPriv('customer_customer_customer',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);

		$userId = $_SESSION ['USER_ID'];
//		$privlimit = isset ( $this->service->this_limit ['�ͻ�����'] ) ? $this->service->this_limit ['�ͻ�����'] : null; //�ⲿ����
	   $arealimit = $service->getAreaIds_d();//�ڲ�����
		$thisAreaLimit = $this->regionMerge($privlimit['�ͻ�����'],$arealimit);

       $cusDao = new model_customer_customer_customer();
       $cusInfo = $cusDao->customerRows($privlimit['�ͻ�����'],$thisAreaLimit,$userId);
       $rows = $service->linkmanInfo($cusInfo);
//echo "<pre>";
//print_r($privlimit['�ͻ�����']);
//		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>