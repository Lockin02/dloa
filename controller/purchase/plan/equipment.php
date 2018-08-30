<?php
/**
 * �ɹ��ƻ��豸�������
 */
class controller_purchase_plan_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_plan";
		parent::__construct ();
	}

	/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �����������
	 */
	function c_add() {
		$object = $this->service->add_d ( $_POST [$this->objName] );
		if ($object) {
			msgBack2 ( "��ӳɹ���" );
		} else {
			msgBack2 ( "���ʧ�ܣ�" );
		}
	}
	function c_pageJsonExecute() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
		$service->groupBy='p.id';
		$rows = $service->listBySqlId ( "equipment_execute_list" );
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �ɹ��豸-�ƻ�ͳ���б�
	 */
	function c_equList() {
		$equNumb = isset ( $_GET ['equNumb'] ) ? $_GET ['equNumb'] : "";
		$equName = isset ( $_GET ['equName'] ) ? $_GET ['equName'] : "";
		$idsArry = isset ( $_GET ['idsArry'] ) ? $_GET ['idsArry'] : "";
		$purchType = isset ( $_GET ['purchType'] ) ? $_GET ['purchType'] : "";
		$this->assign ( 'purchType', $purchType );
		$searchArr = array ();
		if ($equNumb != "") {
			$searchArr ['seachProductNumb'] = $equNumb;
		}
		if ($equName != "") {
			$searchArr ['productName'] = $equName;
		}
		if ($purchType == "contract_sales") {
			$searchArr ['purchTypeArr'] = "HTLX-XSHT,HTLX-ZLHT,HTLX-FWHT,HTLX-YFHT";
		} else if ($purchType == "borrow_present") { //�����òɹ�
			$searchArr ['purchTypeArr'] = "oa_borrow_borrow,oa_present_present";
		} else if ($purchType == "") { //��ʾȫ���ɹ����͵����ϻ��ܱ�
		//$searchArr['purchTypeArr'] = "" ;
		} else {
			$searchArr ['purchTypeArr'] = $purchType;
		}
		$searchArr ['isNoAsset'] = "on";
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->__SET ( 'searchArr', $searchArr );
		$service->__SET ( 'groupBy', "p.productId,p.productNumb" );

		$rows = $service->pageEqu_d ();
		$this->pageShowAssign ();

		$this->assign ( 'equNumb', $equNumb );
		$this->assign ( 'equName', $equName );
		$this->assign ( 'idsArry', $idsArry );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'list', $this->service->showEqulist_s ( $rows ) );
		$this->display ( 'list-equ' );
		unset ( $this->show );
		unset ( $service );
	}

	/**
	 * ��������id��ȡ�ӱ��嵥
	 */
	function c_pageItemJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		//		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		//		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		//		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * �������κŻ�ȡ�ӱ��嵥
	 */
	function c_getBatchEqu() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
		$service->groupBy='p.id';
		$rows = $service->listBySqlId ( "equpment_batch" );
		if(!empty($rows)){
//			echo util_jsonUtil::encode ( $rows );
			//��ȡ��ʾģ��
			$htmlStr=$this->service->batchEquList($rows);
			echo $htmlStr;
		}else{
			echo 0;
		}
	}
	/**
	 * �ɹ��������ϻ���
	 *
	 */
	 function c_toAllEquList(){
	 	$this->display('allequ-list');
	 }

	 /**
	 * �ɹ��������ϻ���PageJson
	 *
	 */
	function c_pageJsonAllList() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$service->groupBy='p.id';
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->pageBySqlId ( "equpment_batch" );
//		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
		/**
	 * ��ȡ��ҳ����ת��Json(����ȷ��)
	 */
	function c_pageJsonForConfirm() {
		$service = $this->service;
		$purchType=$_POST['purchTypeEqu'];
		unset($_POST['purchTypeEqu']);
		if($purchType=='oa_asset_purchase_apply'){
			$_POST['applyId']=$_POST['basicId'];
			unset($_POST['basicId']);
		}

		//$service->asc = false;
		if($purchType=='oa_asset_purchase_apply'){
			$applyEquDao=new model_asset_purchase_apply_applyItem();
			$applyEquDao->getParam ( $_POST );
			$rows = $applyEquDao->listBySqlId ( "select_applyItem_confirm" );
		}else{
			$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->listBySqlId ( "equipment_list" );
		}
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
	 *��������ִ�������ѯҳ��
	 *
	 */
	 function c_toProgressSearch(){
	 	$this->display('list-progress-search');
	 }
		/**
	 * �ɹ���������ִ������б�
	 */
	function c_toProgressList(){
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchvalue=isset($_GET['searchvalue'])?$_GET['searchvalue']:"";
		$searchArr = array ();
		if($searchvalue!=""){
			$searchArr[$searchCol] = $searchvalue;
		}
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['productNumbSear']!=""){
				$searchArr['seachProductNumb']=$object['productNumbSear'];
			}
			if($object['productName']!=""){
				$searchArr['productName']=$object['productName'];;
			}
			if($object['sourceNumb']!=""){
				$searchArr['sourceNumb']=$object['sourceNumb'];
			}
			if($object['customerName']!=""){
				$searchArr['customerName']=$object['customerName'];
			}
		}
		$searchArr['purchTypeArr']='oa_sale_order,oa_sale_lease,oa_sale_rdproject,oa_sale_service,oa_borrow_borrow,oa_present_present';
		$service = $this->service;
		/*s:-----------------�������-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------�������-------------------*/
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.id");

		$rows = $service->getProcessEqu_d();
		$this->pageShowAssign();


		$this->show->assign('searchvalue', $searchvalue);
		$this->assign ( 'searchCol', $searchCol );
		$this->assign('list', $this->service->showEquProgressList($rows));
		$this->display('list-progress');
		unset($this->show);
		unset($service);
	}

	/**
	 *��������ִ�������ѯҳ��
	 *
	 */
	 function c_toStockProgressSearch(){
	 	$this->display('list-stockprogress-search');
	 }
		/**
	 * �ɹ���������ִ������б�
	 */
	function c_toStockProgressList(){
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchvalue=isset($_GET['searchvalue'])?$_GET['searchvalue']:"";
		$searchArr = array ();
		if($searchvalue!=""){
			$searchArr[$searchCol] = $searchvalue;
		}
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['productNumbSear']!=""){
				$searchArr['seachProductNumb']=$object['productNumbSear'];
			}
			if($object['productName']!=""){
				$searchArr['productName']=$object['productName'];;
			}
			if($object['basicNumbSear']!=""){
				$searchArr['basicNumbSear']=$object['basicNumbSear'];
			}
		}
		$searchArr['purchTypeArr']='stock';
		$service = $this->service;
		/*s:-----------------�������-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------�������-------------------*/
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.id");

		$rows = $service->getProcessEqu_d();
		$this->pageShowAssign();


		$this->show->assign('searchvalue', $searchvalue);
		$this->assign ( 'searchCol', $searchCol );
		$this->assign('list', $this->service->showStockProgressList($rows));
		$this->display('list-stockprogress');
		unset($this->show);
		unset($service);
	}

	/**
	 * ��ͬ����ҳ���ȡ�������ݷ���json
	 */
	function c_deliveryListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			$taskEauDao = new model_purchase_task_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['dateHope'] = ''; //���ﲻ��ʾ�ɹ������Ԥ�����ʱ��
				$rows[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType($val['productId']); //�������
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //��;����

				$stockNumbTotal = 0; //�������
				$taskEquRows = $taskEauDao->findByPlanEquId($val['id']);
				if(is_array($taskEquRows)) {
					foreach($taskEquRows as $tKey => $tVal) {
						//��ȡ����������Ϣ
						$orderEquDao->sort = 'id';
						$orderEquRows = $orderEquDao->getEqusByTaskEquId($tVal['id']);
						if(is_array($orderEquRows)) {
							foreach($orderEquRows as $oKey => $oVal) {
								$stockNumbTotal = $stockNumbTotal + $oVal['amountIssued'];
								$rows[$key]['dateHope'] = $oVal['dateHope']; //�ɹ��ƻ��Ĳɹ�Ԥ�Ƶ�����
							}
						}
					}
				}
				$rows[$key]['stockNum'] = $stockNumbTotal;
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

}
?>
