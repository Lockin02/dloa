<?php
/*ѯ�۵�_��Ʒ�嵥
 * Created on 2010-12-29
 * can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class controller_purchase_inquiry_equmentInquiry extends controller_base_action {

	function __construct() {
		$this->objName = 'equmentInquiry';
		$this->objPath = 'purchase_inquiry';
		parent::__construct ();
	}

/*****************************************��ʾ�ָ���********************************************/


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		$rows=$service->getPurchName($rows);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * ���ɲɹ�����ʱ���ж����ϵ������Ƿ�Ϊͬһ�ɹ�����
	 *
	 */
	 function c_isSameType(){
	 	$idsArr=$_POST['parentIds'];
	 	$flag=$this->service->isSameType_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }


	/**�ҵ�ѯ�۵��б�-���ϻ���
	*2010-12-28
	*/
	function c_toMyInquiryEquList () {
		$service = $this->service;
		$equNumb = isset( $_GET['equNumb'] )?$_GET['equNumb']:"";
		$equName = isset( $_GET['equName'] )?$_GET['equName']:"";
		$idsArry = isset( $_GET['idsArry'] )?$_GET['idsArry']:"";
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$searchArr = array ( "state" =>2,"purcherId" => $_SESSION ['USER_ID']);
		if($purchType=="contract_sales"){
			$searchArr['purchType'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//�����òɹ�
			$searchArr['purchType'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//��ʾȫ���ɹ����͵����ϻ��ܱ�
			//$searchArr['purchTypeArr'] = "" ;
		}else{
			$searchArr['purchType']=$purchType;
		}
		if($equName!=""){
			$searchArr['productNameSear'] = $equName;
		}
		$service->getParam ( $_GET );
		$service->__SET('sort', "i.suppId");
		$service->__SET('groupBy',"c.id");
		$service->__SET ( 'searchArr', $searchArr );
		//��ȡ���˵���ָ����Ӧ�̵�������Ϣ
		$equRows=$service->getEquList_d();
		//��ȡ���ظ���Ӧ��������Ϣ
		$service->__SET('groupBy',"i.suppId,c.purchType");
		$equUniqueRows=$service->getEquPageList_d();
		$this->pageShowAssign();
		$this->assign("purchType",$purchType);
		$this->show->assign('idsArry', $idsArry);
		$this->assign('equName', $equName);
		$this->assign ( 'list', $service->showEquList ( $equRows,$equUniqueRows ) );

		$this->show->display($this->objPath.'_'.$this->objName.'-myequ-list');
	}

	/**
	 * �´�ɹ�����ʱ���ж����ϵĲɹ����ͺ͹�Ӧ���Ƿ�һ��
	 *
	 */
	 function c_isSameTypeByOrder(){
	 	$idsArr=$_POST ['ids'];
	 	$flag=$this->service->isSameTypeByOrder_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

}
?>
