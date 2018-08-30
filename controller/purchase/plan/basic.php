<?php
/**
 * @filename	basic.php
 * @function	�ɹ��ƻ����������
 * @author		ouyang
 * @version	1.0
 * @datetime	2011-1-12
 * @lastmodify	2011-1-12
 * @package	oae/controller/purchase/plan
 * @link		no
 */
class controller_purchase_plan_basic extends controller_base_action {

	/**
	 * ���캯��
	 *
	 */
	function __construct() {
		$this->objName = 'basic';
		$this->objPath = 'purchase_plan';
		parent::__construct ();
	}
	/*****************************************�ɹ�����-�ɹ��ƻ�����********************************************/

	/**
	 * �ɹ�����-�ɹ��ƻ� ������frameplanChangeList
	 */
	function c_index() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display ( 'frame' );
	}

	/**
	 * �ɹ�����-�ɹ��ƻ�--�󵼺���
	 */
	function c_toIndexMenu() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display (  'menu' );
	}

	/**
	 * ��ת���༭ҳ��
	 *
	 * @param tags
	 */
	function c_toEdit () {
		$this->permCheck ();//��ȫУ��
		$id=isset($_GET['id'])?$_GET['id']:null;
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign('invnumber',count( $plan ["childArr"] ));
		switch($purchType){
			case 'assets':
			$this->assign ( 'list', $this->service->showAssetEdit_s ( $plan ) );
			$this->display('assets-edit');
			break;
			case 'rdproject':
			$this->assign ( 'list', $this->service->showRdEdit_s ( $plan  ) );
			$this->display('rdproject-edit');
			break;
//			case 'rdproject':$this->view('rd-edit');break;
			case 'produce':
					$equipmentDao = new model_purchase_plan_equipment ();
					$this->showDatadicts(array('qualityList'=>'CGZJSX'));
					$this->assign ( 'listWithType', $this->service->showEditType_s ( $plan ["childArr"] ) );
					$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], true ) );
					if($plan['batchNumb']!=""){
						$batchEquRows=$equipmentDao->getBatchEqu_d($id,$plan['batchNumb']);
						$this->assign ( 'batchEquList', $equipmentDao->batchEquList ( $batchEquRows));
					}else{
						$this->assign ( 'batchEquList', "");
					}
					$this->display('produce-edit');
					break;
			default:
			$this->assign ( 'list', $this->service->showEdit_s ( $plan ["childArr"] ) );
		}
	}

		/**
	 * ��ת�����༭ҳ��
	 *
	 * @param tags
	 */
	function c_toAuditEdit () {
		$this->permCheck ();//��ȫУ��
		$id=isset($_GET['id'])?$_GET['id']:null;
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$otherdatasDao=new model_common_otherdatas();
		$flag=$otherdatasDao->isLastStep($_GET['id'],$this->service->tbl_name);
		if($flag){
			$plan = $this->service->getPlan_d ( $id );
			if($plan['isPlan']==1){
				$this->assign('isPlanYes','checked');
			}else{
				$this->assign('isPlanNo','checked');
			}
			foreach ( $plan as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->assign('invnumber',count( $plan ["childArr"] ));
			$equipmentDao = new model_purchase_plan_equipment ();
						$this->assign ( 'listWithType', $this->service->showEditAudit_s ( $plan ["childArr"] ) );
			$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
			if($plan['batchNumb']!=""){
				$batchEquRows=$equipmentDao->getBatchEqu_d($id,$plan['batchNumb']);
				$this->assign ( 'batchEquList', $equipmentDao->batchEquList ( $batchEquRows));
			}else{
				$this->assign ( 'batchEquList', "");
			}
			$this->display('produce-audit-edit');
		}else{
			$this->c_read();
		}
	}
	    /**
     * �����ɹ�������
     */
     function c_dealApproval(){
		if (! empty ( $_GET ['spid'] )) {
			//�������ص�����
            $this->service->workflowCallBack_deal($_GET['spid']);
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}


	/**
	 * ��ת��ȷ������ҳ��(�̶��ʲ����з��ɹ���)
	 * add by chengl 2012-04-07
	 * @param tags
	 */
	function c_toConfirmProduct () {
		$this->permCheck ();//��ȫУ��
		$id=isset($_GET['id'])?$_GET['id']:null;

		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$purchType=$plan['purchType'];
		$this->assign('invnumber',count( $plan ["childArr"] ));
		$this->assign ( 'list', $this->service->showConfirmEdit_s ( $plan ["childArr"] ) );

		switch($purchType){
			case 'assets':
			$this->display('assets-confirm');
			break;
			case 'rdproject':
			$this->display('rdproject-confirm');
		}
	}

	/**
	 * ��ת������ɹ�Աҳ��(�̶��ʲ����з��ɹ���)
	 * add by chengl 2012-04-07
	 * @param tags
	 */
	function c_toConfirmUser () {
		$this->permCheck ();//��ȫУ��
		$id=isset($_GET['id'])?$_GET['id']:null;

		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$purchType=$plan['purchType'];
		$this->assign('invnumber',count( $plan ["childArr"] ));
		if($purchType=="rdproject"){
			$this->assign ( 'list', $this->service->showConfirmRdRead_s ( $plan ["childArr"] ) );
		}else{
			$this->assign ( 'list', $this->service->showConfirmAssetRead_s ( $plan ["childArr"] ) );
		}

		switch($purchType){
			case 'assets':
			$this->display('assets-confirmuser');
			break;
			case 'rdproject':
			$this->display('rdproject-confirmuser');
		}
	}


	/**
	 * �ɹ�����-�ɹ��ƻ�--�ұߵ���Tabҳ
	 */
	function c_toTabList() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display ( 'list-tab' );
	}

	/**
	 * ����Ĳɹ������б�
	 */
	function c_planChangeList () {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display ( 'change-list' );
	}

	/**
	 * @description �������б�
	 * @author qian
	 * @date 2011-2-17 14:57
	 */
	function c_changePageJson() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		if($purchType=="contract_sales"){
			$service->searchArr ['purchTypeArr'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//�����òɹ�
			$service->searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//���вɹ�����
			//$service->searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else{
			$service->searchArr ['purchType']=$purchType;
		}
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_change");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *���ݺ�ͬID���г��ɹ������б�
	 */
	function c_toListByContractId () {
		$planNumb = isset ( $_GET ['planNumb'] ) ? $_GET ['planNumb'] : "";
		$contractId = isset ( $_GET ['contractId'] ) ? $_GET ['contractId'] : "";
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		if ($planNumb != "") {
			$searchArr ['seachPlanNumb'] = $planNumb;
		}
		$searchArr['purchType']=$purchType;
		$searchArr['sourceID']=$contractId;
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr=$searchArr;
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->pagePlan_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign ();
		$this->assign ( 'planNumb', $planNumb );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'contractId', $contractId );
		$this->assign ( 'list', $this->service->showListByContract_s ( $rows ) );
		$this->display ( 'list-contract' );
		unset ( $this->show );
	}

	/*****************************************���˰칫-���´�Ĳɹ��ƻ�********************************************/

	/**
	 * ���´�Ĳɹ��ƻ�-ͳ���б�
	 */
	function c_myPlanList() {
		$planNumb = isset ( $_GET ['planNumb'] ) ? $_GET ['planNumb'] : "";
		$searchArr = array();
		if ($planNumb != "") {
			$searchArr ['seachPlanNumb'] = $planNumb;
		}
		$searchArr ['createId'] = $_SESSION ['USER_ID'];
		$searchArr ['state'] = $this->service->stateToSta ( "execute" );

		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr = $searchArr;
		$showpage = new includes_class_page ();
		$rows = $service->pagePlan_d ();
		$this->pageShowAssign ();	//��ҳ
		$this->assign ( 'planNumb', $planNumb );
		$this->assign ( 'purchType', $rows[0]['purchType'] );
		$this->assign ( 'list', $service->showMyPlanlist_s ( $rows ) );

		$this->display ( 'my-list-plan' );
		unset ( $this->show );
	}

	/**�ҵĲɹ������б�
	*author can
	*2011-6-19
	*/
	function c_toMyList(){
		$this->display('myplan-list');
	}

	/**�ҵĲɹ����뵼��
	*author can
	*2011-6-19
	*/
	function c_toMenu(){
		$this->display('my-menu');
	}

	/**
	 *�ҵĲɹ�����ҳ��
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_myApplyList () {
		$this->display('my-apply-list');
	}

	/**
	 *�з��ɹ�����ȷ���б�
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toConfirmList () {
		$this->view('rd-confirm-list');
	}
	/**
	 *�з��ɹ�����ȷ��ҳ��
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toConfirm () {
//		$this->permCheck ();//��ȫУ��
		$id=isset($_GET['id'])?$_GET['id']:null;
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'list', $this->service->showEdit_s ( $plan ["childArr"] ) );
		$this->assign('invnumber',count( $plan ["childArr"] ));
		$this->view('rd-confirm');
	}

	/**
	 * �ҵ�����ɲɹ��ƻ��б�
	 */
	function c_myPlanEndList() {
//		$searchvalue= isset ( $_GET ['searchvalue'] ) ? $_GET ['searchvalue'] : "";//����ֵ
//		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
//		if ($searchvalue!= "") {
//			$searchArr [$searchCol] =$searchvalue;
//		}
//		$searchArr ['createId'] = $_SESSION ['USER_ID'];
//		$searchArr ['stateInArr'] = $this->service->stateToSta ( "end" ) . "," . $this->service->stateToSta ( "close" );
//		$service = $this->service;
//		$service->getParam ( $_GET );
//		$service->searchArr=$searchArr;
//		$rows = $service->pageEndPlan_d ();
//		$rows = $this->sconfig->md5Rows ( $rows );
//		//��ҳ
//		$this->pageShowAssign ();
//		$this->assign ( 'searchvalue', $searchvalue );
//		$this->assign ( 'searchCol', $searchCol );
//		$this->assign ( 'list', $service->showMyPlanEndlist_s ( $rows ) );
//		$this->display ( 'my-list-end-plan' );
//		unset ( $this->show );
		$this->display('my-close-list');
	}

	/**
	 * �ҵı���Ĳɹ������б�
	 */
	function c_myPlanChangeList () {
		$this->display ( 'my-change-list' );
	}
		/**
	 * @description �������κ�
	 */
	function c_batchNumbPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
		$service->groupBy = "c.batchNumb";
		$rows = $service->pageBySqlId ("select_batchnumb");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description �ҵ��������б�
	 */
	function c_pageJsonMy() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_change");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description �ҵ������б�
	 */
	function c_myListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->pageBySqlId ("plan_list_page");
		$rows = $this->sconfig->md5Rows ( $rows );
		//���ýӿ�������齫��ȡһ��objAss��������
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//״̬����
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//��������
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description �����Ϸ���Ĳɹ�����
	 */
	function c_myConfirmListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['productSureUserIdUnion'] = $_SESSION ['USER_ID'];
//		$service->searchArr ['productSureStatusArr'] ='0,2';
		//$service->searchArr ['ExaStatusArr'] = array("���","��������","����");

		$service->asc = true;
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->pageBySqlId ("plan_list_union");
		$rows = $this->sconfig->md5Rows ( $rows );
		//���ýӿ�������齫��ȡһ��objAss��������
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
//				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//״̬����
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//��������
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description �ʲ��ɹ������б�
	 */
	function c_assetListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_page");
		$rows = $this->sconfig->md5Rows ( $rows );
		//���ýӿ�������齫��ȡһ��objAss��������
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//״̬����
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//��������
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description �з��ɹ�ȷ���б�
	 */
	function c_rdConfirmJson() {
		$service = $this->service;
//		$service->searchArr['purchType']="rdproject";
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_page");
		$rows = $this->sconfig->md5Rows ( $rows );
		//���ýӿ�������齫��ȡһ��objAss��������
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//״̬����
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//��������
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*****************************************�ɹ�����-�ɹ��ƻ�********************************************/

	/**
	 * �ɹ��ƻ�-ͳ���б�
	 */
	function c_planList() {
		$searchvalue= isset($_GET ['searchvalue'] ) ? $_GET ['searchvalue'] : "";//����ֵ
		$idsArry = isset($_GET ['idsArry'] ) ? $_GET ['idsArry'] : "";
		$purchType = isset($_GET['purchType']) ? $_GET['purchType'] : "";
		$searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//�����ֶ�
		if ($searchvalue != "") {
			$searchArr[$searchCol] = $searchvalue;
		}
		if($purchType=="contract_sales") {
			$searchArr['purchTypeArrUnion'] = "HTLX-XSHT,HTLX-ZLHT,HTLX-FWHT,HTLX-YFHT";
		}else if($purchType=="borrow_present") {//�����òɹ�
			$searchArr['purchTypeArrUnion'] = "oa_borrow_borrow,oa_present_present";
		}else if($purchType=="") {//��ʾȫ���ɹ����͵Ĳɹ�����
		} else{
			$searchArr['purchTypeArrUnion'] = $purchType;
		}
		$searchArr['ExaStatusUnionArr'] = array("���","����ȷ�ϴ��");
//		$searchArr['sureStatusNo']="0";//����δȷ�ϵ��з��ɹ�
		$searchArr ['stateUnionArr'] ='0';
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr = $searchArr;

		$rows = $service->pageListUnion_d ();

		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign ();
		$this->assign ( 'searchvalue', $searchvalue );
		$this->assign ( 'searchCol', $searchCol );
		$this->assign ( 'idsArry', $idsArry );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'list', $this->service->showPlanlist_s ( $rows ) );
		$this->display ( 'list-plan' );
		unset ( $this->show );
	}

	/**
	 * ������뵥�´�����ʱ�������Ƿ�����ָ�ĵĹ���
	 */
	function c_chkDataAvailable(){
		$service = $this->service;
		$ids = isset($_REQUEST['ids'])? $_REQUEST['ids'] : '';
		$assetIds = '';
		$idsArr = explode(",",$ids);
		foreach ($idsArr as $k => $v){
			$assetIdArr = explode('asset',$v);
			if($v == ''){
				unset($idsArr[$k]);
			}else if(isset($assetIdArr[1])){
				unset($idsArr[$k]);
				$assetIds .= $assetIdArr[1].',';
			}
		}
		$ids = implode($idsArr , ',');
		$assetIds = trim($assetIds,',');

		$result = array();
		if($ids != '' || $assetIds != ''){
			$noPassNum = 0;$businessBelong=$purchType = '';
			if($assetIds != ''){
				$sql = "select b.id,b.formBelong,b.formBelongName,b.businessBelong,b.businessBelongName from oa_asset_purchase_apply_item e left join oa_asset_purchase_apply b ON e.applyId = b.id where e.id in({$assetIds}) ;";
				$data = $service->_db->getArray($sql);
			}else if($ids != ''){
				$sql = "select b.id,e.purchType,b.formBelong,b.formBelongName,b.businessBelong,b.businessBelongName from oa_purch_plan_equ e left join oa_purch_plan_basic b ON e.basicId = b.id where e.id in({$ids}) ;";
				$data = $service->_db->getArray($sql);
			}else{
				$data = array();
			}
			if($data){
				foreach ($data as $k => $v){
					// ��������Ƿ�һ��
//					$purchType = ($purchType == '')? $v['purchType'] : $purchType;
//					if($v['purchType'] != $purchType && $purchType!=''){
//						$noPassNum+=1;
//					}

					// ��������˾�Ƿ�һ��
					$result['businessBelong'] = (!isset($result['businessBelong']) || (isset($result['businessBelong']) && $result['businessBelong'] == ''))? $v['businessBelong'] : $result['businessBelong'];
					if($v['businessBelong'] != $result['businessBelong'] && $result['businessBelong']!=''){
						$result['errorType'] = 'businessBelong';
						echo json_encode($result);exit();
					}else{
						$result['businessBelong'] = ($ids != '' && $assetIds != '')? 'dl' : $result['businessBelong'];
						$result['errorType'] = '';
					}
				}
			}else{
				$result['errorType'] = 'emptydata';
			}
		}else{
			$result['errorType'] = 'emptyIds';
		}
		echo json_encode($result);
	}

	/**
	 * ����ɲɹ��ƻ��б�
	 */
	function c_planEndList() {
		$searchvalue= isset ( $_GET ['searchvalue'] ) ? $_GET ['searchvalue'] : "";//����ֵ
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		if ($searchvalue!= "") {
			$searchArr [$searchCol] =$searchvalue;
		}
		if($purchType=="contract_sales"){
			$searchArr['purchTypeArrUnion'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//�����òɹ�
			$searchArr['purchTypeArrUnion'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//��ʾ�������͵Ĳɹ�����
			//$searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else{
			$searchArr['purchTypeArrUnion']=$purchType;
		}
		$searchArr ['stateUnionArr'] = $this->service->stateToSta ( 'end' ) . ',' . $this->service->stateToSta ( 'close' );
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr=$searchArr;
//		$service->sort='dateEnd';
		$rows = $service->pageEndListUnion_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign ();	//��ҳ
		$this->assign ( 'searchvalue', $searchvalue );
		$this->assign ( 'searchCol', $searchCol );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'list', $service->showPlanEndlist_s ( $rows ) );
		$this->display (  'list-end-plan' );
		unset ( $this->show );
	}

	/**�ʲ��ɹ���������TABҳ
	*author can
	*2011-7-6
	*/
	function c_toAssetAuditTab(){
		$this->display('asset-audit-tab');
	}

	/**�ʲ��ɹ�����δ�����б�
	*author can
	*2011-7-6
	*/
	function c_toAssetAuditNo(){
		$this->display('asset-audit-no');
	}

	/**�ʲ��ɹ������������б�
	*author can
	*2011-7-6
	*/
	function c_toAssetAuditYes(){
		$this->display('asset-audit-yes');
	}

	/**����Դ��ID���Ҳɹ������б�
	*author can
	*2011-7-6
	*/
	function c_toSourceList(){
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$sourceId=isset($_GET['sourceId'])?$_GET['sourceId']:null;//Դ��ID
		$this->assign('purchType',$purchType);
		$this->assign('sourceId',$sourceId);
		$this->display('source-list');
	}

	/**
	 * @desription �ʲ��ɹ��������б����ݹ��˷���
	 * @qiaolong
	 */
	function c_myAuditPj() {
		$interfObj = new model_common_interface_obj();
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ('sql_examine');
		$rows = $this->sconfig->md5Rows ( $rows );
		$newRows=array();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$val['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//������������
				array_push($newRows,$val);
			}
		}
		$arr = array ();
		$arr ['collection'] = $newRows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**�ʲ��ɹ��������б�
	*/
	function c_pageJsonAuditYes(){
		$interfObj = new model_common_interface_obj();
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_audited');
		$rows = $this->sconfig->md5Rows ( $rows );
		$newRows=array();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$val['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//������������
				array_push($newRows,$val);
			}
		}
		$arr = array ();
		$arr ['collection'] = $newRows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**�з��ɹ���������TABҳ
	*author can
	*2011-7-6
	*/
	function c_toRdprojectAuditTab(){
		$this->display('rdproject-audit-tab');
	}

	/**�ʲ��ɹ�����δ�����б�
	*author can
	*2011-7-6
	*/
	function c_toRdprojectAuditNo(){
		$this->display('rdproject-audit-no');
	}

	/**�ʲ��ɹ������������б�
	*author can
	*2011-7-6
	*/
	function c_toRdprojectAuditYes(){
		$this->display('rdproject-audit-yes');
	}

	/**�����ɹ���������TABҳ
	*author can
	*2011-7-6
	*/
	function c_toProduceAuditTab(){
		$this->display('produce-audit-tab');
	}

	/**�����ɹ�����δ�����б�
	*author can
	*2011-7-6
	*/
	function c_toProduceAuditNo(){
		$this->display('produce-audit-no');
	}

	/**�����ɹ������������б�
	*author can
	*2011-7-6
	*/
	function c_toProduceAuditYes(){
		$this->display('produce-audit-yes');
	}

	/*****************************************��ɾ�Ĳ鷽��********************************************/

	/**
	 * �����ɹ��ƻ�����
	 */
	function c_add() {
		$object = $this->service->add_d ( $_POST [$this->objName] );
		if ($object) {
			showmsg ( "��Ӳɹ��ƻ��ɹ���" );
		} else {
			showmsg ( "���ʧ�ܣ�" );
		}
	}

	/**
	 * �ɹ��ƻ�ͳ��ҳ--��ת�������ɹ�����ҳ��
	 * ͨ���ƻ����´�ɹ�����
	 */
	function c_toAddTask() {
		$idsArry = isset ( $_GET ['idsArry'] ) ? substr ( $_GET ['idsArry'], 1 ) : exit ();
		$businessBelong = isset ( $_GET ['businessBelong'] ) ? $_GET ['businessBelong'] : 'dl';
		$this->service->getParam ( $_GET );
		$obj = new model_purchase_plan_equipment ();
		$listEqu = $obj->getEquForTask_d ( $idsArry );

//		echo "<pre>";
//		print_r($listEqu);
		$this->assign('sendTime',date("Y-m-d"));
		$infoRow=$this->service->getRemarkInfo_d($listEqu);//��ȡ�ɹ����뱸ע��Ϣ
		$this->assign('instruction',$infoRow['instruction']);

		$branchDao = new model_deptuser_branch_branch();
		$businessBelongArr = $branchDao->getByCode($businessBelong);
		if($businessBelongArr && !empty($businessBelongArr)){
			$businessBelongName = $businessBelongArr['NameCN'];
		}else{
			$businessBelong = 'dl';
			$businessBelongName = '���Ͷ���';
		}

		$this->assign('formBelong',$businessBelong);
		$this->assign('formBelongName',$businessBelongName);
		$this->assign('businessBelong',$businessBelong);
		$this->assign('businessBelongName',$businessBelongName);

		$this->assign('remark','');
		//��ȡ��Сϣ�����ʱ��
//		$minHopeDate=$obj->getMinHopeDate_d($idsArry);
		$this->assign('dateHope',date("Y-m-d"));
		if ($listEqu) {
			// ��ȡ�������ݹ�����˾��Ϣ
			foreach ($listEqu as $k => $v){
				$listEqu[$k]['formBelong'] = $businessBelong;
				$listEqu[$k]['formBelongName'] = $businessBelongName;
				$listEqu[$k]['businessBelong'] = $businessBelong;
				$listEqu[$k]['businessBelongName'] = $businessBelongName;
			}

			$this->show->assign ( 'list', $obj->newTask ( $listEqu ) );

			$taskNumb = "ptask-" . date ( "YmdHis" ) . rand ( 10, 99 );
			$this->assign ( 'taskNumb', $taskNumb );
			$this->display ( 'task-add' );
		} else {
			showmsg ( '����', 'temp', 'button' );
		}
		unset ( $this->show );
	}

	/**
	 * ��ת��ȷ��tabҳ��
	 */
	function c_tabpage() {
		$this->display("confim-tabs");
	}

	/**
	 * �鿴����
	 */
	function c_read() {
		$testTypeArr = array(
			'0'=>'ȫ��',
			'1'=>'���',
			'2'=>'���',
		);

		$this->permCheck ();//��ȫУ��
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$readType=isset($_GET['actType'])?$_GET['actType']:null;
		$plan = $this->service->getPlan_d ( $id );
		if(is_array( $plan['childArr'] )&&count( $plan['childArr'] )>0){
			foreach( $plan['childArr'] as $key => $val ){
				if( $val['testType']!='' ){
					$testType = $val['testType'];
					$plan['childArr'][$key]['testType']=$testTypeArr[$testType];
				}
			}
		}
		//�ж��Ƿ���ʾ�����ɹ����밴ť
		if($_GET['show']){
			$this->assign('show', $_GET['show']);
		}

//		echo "<pre>";
//		print_R($plan);
		//��ȡ���ϵ�ִ�����
		$equipmentDao = new model_purchase_plan_equipment ();
//		$executRows=$equipmentDao->getEquExecute_d($id);
//		$this->assign ( 'listExecute', $this->service->showExecute_s ($executRows));
		$this->assign ( 'listEquExecute', $this->service->showEquExecuteList_s ( $plan ["childArr"] ));
		//
		$purchType=$plan['purchType'];
		$this->assign (  'readType',$readType );
		if($plan['isPlan']==1){
			$plan['isPlan']="��";
		}else{
			$plan['isPlan']="��";
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($plan['purchType'] == "oa_borrow_borrow"){
			$this->assign ( "purchTypeCName", "�����ú�ͬ" );
		}
//		if($purchTypeCon=="contract_sales"){
			switch($plan[purchType]){
			case"oa_sale_order":$skey=$this->md5Row($plan['sourceID'],'projectmanagent_order_order');$this->assign ( 'contractdao', 'projectmanagent_order_order');break;
			case"oa_sale_lease":$skey=$this->md5Row($plan['sourceID'],'contract_rental_rentalcontract');$this->assign ( 'contractdao','contract_rental_rentalcontract' );break;
			case"oa_sale_service":$skey=$this->md5Row($plan['sourceID'],'engineering_serviceContract_serviceContract');$this->assign ( 'contractdao', 'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$skey=$this->md5Row($plan['sourceID'],'rdproject_yxrdproject_rdproject');$this->assign ( 'contractdao','rdproject_yxrdproject_rdproject' );break;
			case"oa_borrow_borrow":$skey=$this->md5Row($plan['sourceID'],'projectmanagent_borrow_borrow');$this->assign ( 'contractdao','projectmanagent_borrow_borrow' );break;
			case"oa_present_present":$skey=$this->md5Row($plan['sourceID'],'projectmanagent_present_present');$this->assign ( 'contractdao','projectmanagent_present_present' );break;
		}
//		}
		$this->assign ( 'list', $this->service->showRead_s ( $plan ["childArr"] ) );
		if($purchType=="oa_sale_order"||$purchType=="oa_sale_lease"||$purchType=="oa_sale_service"||$purchType=="oa_sale_rdproject"){
			switch($purchType){
			case"oa_sale_order":$this->assign ( 'contractdao', 'projectmanagent_order_order');break;
			case"oa_sale_lease":$this->assign ( 'contractdao','contract_rental_rentalcontract' );break;
			case"oa_sale_service":$this->assign ( 'contractdao', 'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$this->assign ( 'contractdao','rdproject_yxrdproject_rdproject' );break;
			}
		}
		//�º�ͬ�ɹ�
		if($purchType=="HTLX-XSHT"||$purchType=="HTLX-ZLHT"||$purchType=="HTLX-FWHT"||$purchType=="HTLX-YFHT"){
			$skey=$this->md5Row($plan['sourceID'],'contract_contract_contract');
			$this->assign ( 'contractdao','contract_contract_contract' );
		}
		switch($purchType){
			case"oa_sale_order":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_sale_lease":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_sale_service":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_sale_rdproject":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_borrow_borrow":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_present_present":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"assets":
			$this->assign ( 'list', $this->service->showAssetRead_s ( $plan ["childArr"] ) );
            $this->display ('assets-view' );break;
			case"stock":$skey=$this->md5Row($plan['sourceID'],'stock_fillup_fillup');$this->assign('skey',$skey);
				$this->assign ( 'list', $this->service->showStockRead_s ( $plan ["childArr"] ) );
				$this->display ( 'stock-view' );break;

			case"rdproject":
			$this->assign ( 'list', $this->service->showRdRead_s ( $plan ["childArr"] ) );
			$this->display ( 'rdproject-view' );break;
			case"produce":
					$equRows=$equipmentDao->getAllEqu_d($id);
					$this->assign ( 'listWithType', $this->service->showReadType_s ( $equRows ) );
					//��ʾ������Ϣ
					$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
					if($plan['batchNumb']!=""){
						$batchEquRows=$equipmentDao->getBatchEqu_d($id,$plan['batchNumb']);
						$this->assign ( 'batchEquList', $equipmentDao->batchEquList ( $batchEquRows));
					}else{
						$this->assign ( 'batchEquList', "");
					}
					$this->display ( 'produce-view' );
					break;
			case "HTLX-XSHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case "HTLX-ZLHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case "HTLX-FWHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case "HTLX-YFHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			default:$this->display ( 'read' );break;
		}
	}

	/**
	 * @exclude ��ɲɹ��ƻ�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 ����07:40:19
	 */
	function c_end() {
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$val = $this->service->end_d ( $id );
		if ($val == 1) {
			msgGo ( "�����ɹ�" );
		} else if ($val == 2) {
			msgGo ( "����Ϊδ��ɵ��豸���������" );
		} else {
			msgGo ( "����ʧ�ܣ��������Ƿ������������Ժ�����" );
		}
	}
	/**
	 *�������ر�ҳ��
	 *
	 */
	 function c_toClose(){
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] :"";
		$purchTypeCon=isset($_GET['purchType'])?$_GET['purchType']:null;
		$readType=isset($_GET['actType'])?$_GET['actType']:null;
		$plan = $this->service->getPlan_d ( $id );
		//��ȡ���ϵ�ִ�����
		$equipmentDao = new model_purchase_plan_equipment ();
		$purchType=$plan['purchType'];
		$this->assign (  'readType',$readType );
		if($plan['isPlan']==1){
			$plan['isPlan']="��";
		}else{
			$plan['isPlan']="��";
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$testTypeArr = array(
			'0'=>'ȫ��',
			'1'=>'���',
			'2'=>'���',
		);
		if(is_array( $plan['childArr'] )&&count( $plan['childArr'] )>0){
			foreach( $plan['childArr'] as $key => $val ){
				if( $val['testType']!='' ){
					$testType = $val['testType'];
					$plan['childArr'][$key]['testType']=$testTypeArr[$testType];
				}
			}
		}
		$this->assign ( 'list', $this->service->showRead_s ( $plan ["childArr"] ) );

		$this->display ( 'close' );

	 }

	/**
	 * @exclude �رղɹ�����
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 ����09:58:09
	 */
	function c_close() {
		if ($this->service->dealClose_d ( $_POST ['basic'] )) {
			msgBack2 ( "�رճɹ�" );
		} else {
			msgBack2 ( "�ر�ʧ�ܣ��������Ƿ������������Ժ�����" );
		}
	}

    /**
     * �ʲ��ɹ� �ʼ�����
     */
     function c_toFeedback(){
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] :"";
		$purchTypeCon=isset($_GET['purchType'])?$_GET['purchType']:null;
		$readType=isset($_GET['actType'])?$_GET['actType']:null;
		$plan = $this->service->getPlan_d ( $id );
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->view ( 'feedback' );
	 }
	 /**
	  * �ʲ��ɹ��ʼ���������
	  */
    function c_feedbackpush() {
		$rows = $_POST['objInfo'];
        $emailDao = new model_common_mail();
		$content = $rows['content'];
		$emailInfo = $emailDao->batchEmail("1",$_SESSION['USERNAME'],$_SESSION['EMAIL'],'assetPurchase_feedback','������',null,$rows['TO_ID'],$content);
	    msgBack2 ( "���ͳɹ�" );
	}
	/*****************************************�������********************************************/

	/**
	 * @exclude �����ת����
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 ����03:54:10
	 */
	function c_toChange() {
		$this->permCheck ();//��ȫУ��
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$productIds = isset ( $_GET ['productIds'] ) ? $_GET ['productIds'] : "";
		$productArr = explode ( ',', $productIds );
		//��ȡ�ɹ��ƻ���ϸ��Ϣ
		$plan = $this->service->getPlan_d ( $id );
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'list', $this->service->showChange_s ( $plan ["childArr"] ) );
		$this->display ( 'change' );
	}

	/**
	 * ������淽��
	 */
	function c_change() {
		$val = $this->service->change_d ( $_POST ['basic'] );
		if ($val) {
			msgBack2 ( "����ɹ���");
		}
		else {
			msgBack2 ( "���ʧ��" );
		}
	}
	/**
	 * ɾ���ɹ�����
	 */
	function c_deletesInfo() {
		$deleteId=isset($_POST['id'])?$_POST['id']:exit;
	    $delete=$this->service->deletesInfo_d ($deleteId);
	    //���ɾ���ɹ����1���������0
         if($delete){
			echo 1;
    	}else{
    		echo 0;
    	}
	}


	/**
	 * ��ͬ�ɹ��б�
	 */
	 function c_contPurchPage(){
	 	$this->assign('purchType',$_GET['objType']);
	 	$this->assign('orderId',$_GET['orderId']);
	 	$this->display('pagebyorder');
	 }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageByOrder() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**��ת���ʲ��ɹ��б�
	 * */
	function c_assetList () {
		$this->display('list-asset');
	}

	/**
	 * �ʲ��ɹ��´�
	 *
	 */
	function c_pushPurch () {
		$id=$_POST['id'];
		$applyNumb=$_POST['applyNumb'];
		$applyObj=array("id"=>$id,
							"ExaStatus"=>"���");
		//����״̬
		$flag=$this->service->updateById($applyObj);
		if($flag){
			//�����ʼ�֪ͨ�ɹ���������
			$mailArr=$this->service->pushPurch;  //��ȡĬ���ռ�������
			$this->service->sendEmail_d($id,$applyNumb,$mailArr);
			echo 1;
		}else{
			echo 0;
		}

	}

	/**
	 * �ʲ��ɹ�����ͨ�������ʼ�֪ͨ��ظ�����
	 *
	 */
	 function c_emailNotice(){
		if (! empty ( $_GET['spid'] )) {
			//�������ص�����
            $this->service->workflowCallBack($_GET['spid']);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_plan_basic&action=toAssetAuditTab'</script>";
		}

	 }

	/**
	 * �������ͨ��������
	 *
	 */
	 function c_dealChange(){
		if (! empty ( $_GET ['spid'] )) {
			//�������ص�����
            $this->service->workflowCallBack_change($_GET['spid']);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_plan_basic&action=toAssetAuditTab'</script>";
		}

	 }

	 /**
	 * �������²ɹ������״̬Ϊ���
	 *
	 */
	 function c_updateStateEnd(){
		$searchArr ['state'] = $this->service->statusDao->statusEtoK ( "execute" );
		$service = $this->service;
		$service->searchArr=$searchArr;
		$flag = $service->updateData_d ();
		if($flag){
			echo "<script>alert('���³ɹ�');this.location='?model=purchase_plan_basic&action=planList'</script>";
		}else{
			echo "<script>alert('����ʧ��');this.location='?model=purchase_plan_basic&action=planList'</script>";
		}


	 }

	/*****************************************��ʾ�ָ���********************************************/

	/********************************************����************************************************/

	/**
	 * ��ת���ҵĲɹ����뵼��ҳ��
	 */
	 function c_toExport(){
	 	$this->view('my-apply-export');
	 }

	/**
	 * �ҵĲɹ����뵼��
	 */
	 function c_myPlanExport(){
//	 	echo '<pre>';
//	 	print_R($_POST['data']);
	 	$object = $_POST[$this->objName];
	 	$state = substr($object['stateVal'],0,-1);
	 	$this->service->searchArr['preDateHope'] = $object['beginDate'];
	 	$this->service->searchArr['afterDateHope'] = $object['endDate'];
	 	$this->service->searchArr['stateInArr'] = $state;
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		$this->service->searchArr['createId'] = $_SESSION['USER_ID'];
		$planEquRows = $this->service->listBySqlId('plan_export_list');
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['contractCode']=$planEquRows[$key]['sourceNumb'];
			$exportData[$key]['contractName']=$planEquRows[$key]['contractName'];
			$exportData[$key]['purchaseCode']=$planEquRows[$key]['basicNumb'];
			$exportData[$key]['issuedDate']=$planEquRows[$key]['dateIssued'];
			$exportData[$key]['hopeDate']=$planEquRows[$key]['dateHope'];
			$exportData[$key]['prodectName']=$planEquRows[$key]['productName'];
			$exportData[$key]['number']=$planEquRows[$key]['amountAll'];
			$exportData[$key]['remark']=$planEquRows[$key]['remark'];
		}
		return model_purchase_plan_purchaseExportUtil::export2ExcelUtil ( $exportData );
	 }

	/**
	 * ��ת���ҵĲɹ����뵼������ʵЧ��ҳ��
	 */
	 function c_toExportAging(){
	 	$this->assign('beginDate',date('Y-m') . '-01');
	 	$this->assign('endDate',day_date);
	 	$this->view('my-apply-exportaging');
	 }

	/**
	 * �ɹ�ʵЧ����
	 */
	function c_myPlanExportAging(){
	 	$object = $_POST[$this->objName];

		$rs = $this->service->myPlanExportAging_d($object);
		return model_purchase_plan_purchaseExportUtil::exportAging_e ( $rs );
	}
	/********************************************end************************************************/


	/**
	 * ȷ�ϲɹ���������
	 *add by chengl 2012-04-07
	 * @param tags
	 * @return return_type
	 */
	function c_confirmProduct () {
		$object=$this->service->confirmProduct_d($_POST['basic']);
		if($object){
			msgGo('ȷ�ϳɹ�','index1.php?model=purchase_plan_basic&action=toConfirmProductList');
		}else{
			msgGo('ȷ��ʧ��','index1.php?model=purchase_plan_basic&action=toConfirmProductList');

		}
	}

	/**
	 * ȷ�ϲɹ����Ϸ�����
	 *add by chengl 2012-04-07
	 * @param tags
	 * @return return_type
	 */
	function c_confirmProductUser () {
		$object=$this->service->confirmProductUser_d($_POST['basic']);
		if($object){
			msgGo('�´�ɹ�','index1.php?model=purchase_plan_basic&action=toTabList');
		}else{
			msgGo('�´�ʧ��','index1.php?model=purchase_plan_basic&action=toTabList');

		}
	}

	/**
	 * ����ȷ�ϴ�ظ�������
	 *add by chengl 2012-04-07
	 * @param tags
	 * @return return_type
	 */
	function c_backBasicToApplyUser() {
		$object=$this->service->backBasicToApplyUser_d($_POST['basic']);
		if($object){
			msgGo('��سɹ�','index1.php?model=purchase_plan_basic&action=toTabList');
		}else{
			msgGo('���ʧ��','index1.php?model=purchase_plan_basic&action=toTabList');

		}
	}

	/**
	 * ��ת����ȷ�ϵĲɹ�����
	 */
	function c_toConfirmProductList(){
		$this->view("confirm-list");
	}

	/**
	 * �ɹ���������ͨ������
	 */
	function c_confirmAudit(){
		if (! empty ( $_GET ['spid'] )) {
			$this->service->confirmAudit($_GET ['spid'] );
		}
		$urlType = isset ( $_GET ['urlType'] ) ? $_GET ['urlType'] : null;
		//��ֹ�ظ�ˢ��
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_plan_basic&action=toAssetAuditTab'</script>";
		}
	}

	/**
	 * ���������ɹ���������ҳ��
	 *
	 */
	 function c_toExportProduceEqu(){
		if(!$this->service->this_limit['������������']){
			echo "<script>alert('û��Ȩ�޽��в���!');self.parent.tb_remove();</script>";
			exit();
		}
		$this->display('produceequ');
	 }

	/**
	 * ���������ɹ���������ҳ��
	 *
	 */
	 function c_exportProduceEqu(){
	 	$object = $_POST[$this->objName];
		//��ȡͬ��������
		$equipmentDao = new model_purchase_plan_equipment ();
		$rows = $equipmentDao->getBatchEquWithBatch_d($object['batchNumb']);
		//�����������ϻ�ȡ�������
		$rows= $this->service->getStockNumbTotal($rows);
		//�����������ϻ�ȡ��������
		$receiveNum = new model_purchase_task_equipment();
		$rows = $receiveNum->getReceiveNum($rows);
		return model_purchase_plan_purchaseExportUtil::exportProduceEqu_e ( $rows,$object['batchNumb']);
	 }

	/**
	 * �����ɹ���������ҳ��
	 *
	 */
	 function c_toExportPlanEqu(){
		if(!$this->service->this_limit['������������']){
			echo "<script>alert('û��Ȩ�޽��в���!');self.parent.tb_remove();</script>";
			exit();
		}
		$this->display('planequ');
	 }

	/**
	 * �����ɹ���������
	 *
	 */
	 function c_exportPlanEqu(){
	 	$object = $_POST[$this->objName];
		$searchArr = array ();
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['purchType']!=""){
				$searchArr['purchType']=$object['purchType'];;
			}
		}
		//��ȡͬ��������
		$equipmentDao = new model_purchase_plan_equipment ();
		$rows = $equipmentDao->getPlanEquList_d($searchArr);
		return model_purchase_plan_purchaseExportUtil::exportPlanEqu_e ( $rows);
	 }

	 	/**
	 * @exclude ���������ɹ�����
	 * @param
	 */
	function c_startPlan() {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		if( $this->service->startPlan_d($id) ){
			msgGo("�����ɹ�");
		}else{
			msgGo("����ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}

		 	/**
	 * @exclude ���������ɹ�����
	 * @param
	 */
	function c_startApply() {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		$applyDao=new model_asset_purchase_apply_apply();
		if($applyDao->startApply_d($id) ){
			msgGo("�����ɹ�");
		}else{
			msgGo("����ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}

		/**��ת�������ɹ�����ҳ��
	*author can
	*2011-7-5
	*/
	function c_toProduceByMaterial(){
		$parentProductID= isset($_GET['parentProductID'])? $_GET['parentProductID']:"";
		$neednum= isset($_GET['needNum'])? $_GET['needNum']:1;
		$materialDao=new model_stock_material_material();
		$materialRows=$materialDao->getMaterialList($parentProductID);
		if(is_array($materialRows)){
			$rows=$materialDao->treeCondList($materialRows,-1,$neednum);
			$leafRows=$materialDao->dealMaterialList($rows);
//			print_r($leafRows);
			//��ȡҶ�ӽڵ�
			$allLeafList=$materialDao->getMaterialLeafList($parentProductID);
			$purchList=$materialDao->dealPurchList($leafRows,$allLeafList);
			$this->assign ( 'listWithType', $this->service->showProductApply_s( $purchList) );
		}else{
			$this->assign ( 'listWithType', '');
		}


		$this->assign("sendTime" , date("Y-m-d"));
		$this->assign("dateHope" , date("Y-m-d"));
		$this->assign("sendUserId" , $_SESSION['USER_ID']);
		$this->assign("sendName" , $_SESSION['USERNAME']);

		//��ȡ����ID
		$deptDao=new model_common_otherdatas();
		$deptmentDao=new model_deptuser_dept_dept();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
        $purchDepart = $deptmentDao->getDeptId_d('������');
        $this->assign('purchDepart','������');
        $this->assign('purchDepartId',$purchDepart['DEPT_ID']);
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->show->display('purchase_external_produce-material-add');
	}

	/*
	 *
	 * ����/�����ɹ�������������
	 *
	 * */
	function c_toProAppList(){
		$appType=($_POST['appType']?$_POST['appType']:$_GET['appType']);
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$appTypeI=array(1=>'�����ɹ�',2=>'����',3=>'���۲ɹ�',4=>'����ɹ�',5=>'���޲ɹ�',6=>'�з��ɹ�',7=>'����(������)');
		$keyTypeI=array('planNumb,productNumb,productName,updateName'=>'�� �� ','planNumb'=>'���ݱ��','productNumb'=>'���ϱ���','productName'=>'��������','updateName'=>'������');
		foreach($appTypeI as $key=>$val){
			$appTypeStr.="<option value='$key'".($key==$appType?'selected':'')." >$val</option>";
		}
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'appType', $appTypeStr);
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->showAppList() );
		$this->display('produce-prAppList');
	}

	/**
	 *�����ɹ�������ϲ�����
	 *
	 */
	 function c_toAuditTab(){
	 	$this->view('audit-tab');
	 }

	/*
	 *
	 * ����/�����ɹ�������������
	 *
	 * */
	function c_toProAppCloseList(){
		$appType=($_POST['appType']?$_POST['appType']:$_GET['appType']);
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$appTypeI=array(1=>'�����ɹ�',2=>'����',3=>'���۲ɹ�',4=>'����ɹ�',5=>'���޲ɹ�',6=>'�з��ɹ�',7=>'����(������)');
		$keyTypeI=array('planNumb,productNumb,productName,updateName'=>'�� �� ','planNumb'=>'���ݱ��','productNumb'=>'���ϱ���','productName'=>'��������','updateName'=>'������');
		foreach($appTypeI as $key=>$val){
			$appTypeStr.="<option value='$key'".($key==$appType?'selected':'')." >$val</option>";
		}
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'appType', $appTypeStr);
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->showAppCloseList() );
		$this->display('produce-close-list');
	}
	/*
	 * ����/�����ɹ�ͨ����������
	 *
	 **/
	function c_toApproved(){
		$appType=($_POST['appType']?$_POST['appType']:$_GET['appType']);
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);

		$appTypeI=array(1=>'�����ɹ�',2=>'����',3=>'���۲ɹ�',4=>'����ɹ�',5=>'���޲ɹ�',6=>'�з��ɹ�',7=>'����(������)');
		$keyTypeI=array('planNumb,productNumb,productName,updateName'=>'�� �� ','planNumb'=>'���ݱ��','productNumb'=>'���ϱ���','productName'=>'��������','updateName'=>'������');
		foreach($appTypeI as $key=>$val){
			$appTypeStr.="<option value='$key'".($key==$appType?'selected':'')." >$val</option>";
		}
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'appType', $appTypeStr);
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->ApprovedList() );
		$this->pageShowAssign();
		$this->display('approved-list');
	}
	/*
	 *
	 * ����/�����ɹ�������������ҳ��
	 *
	 * */
	function c_inProAppList(){
		// ����/���������ʼ�֪ͨ
		$this->service->sendNotification($_POST['basic']);
		if($_POST['basic']&&$this->service->inProAppList($_POST['basic'])==1){
			echo "<script>alert('�����ɹ�!');window.location='?model=purchase_plan_basic&action=toProAppList';</script>";
		}else{
			echo "<script>alert('����ʧ��!');window.location='?model=purchase_plan_basic&action=toProAppList';</script>";
		}
	}

		/*
	 *
	 * ����/�����ɹ�������������ҳ��(����)
	 *
	 * */
	function c_inProAppClose(){
		if($_POST['basic']&&$this->service->inProAppClose($_POST['basic'])==1){
			echo "<script>alert('�����ɹ�!');window.location='?model=purchase_plan_basic&action=toProAppCloseList';</script>";
		}else{
			echo "<script>alert('����ʧ��!');window.location='?model=purchase_plan_basic&action=toProAppCloseList';</script>";
		}
	}

	/**
	 *
	 * ��������ɹ���Ϣҳ��
	 */
	function c_searchPage(){
		$this->view('search');
	}

	/**
	 *
	 * ��������ɹ���Ϣ
	 */
	function c_searchList(){
		$arr = $this->service->search_d($_POST);
		$arr = $this->sconfig->md5Rows ( $arr );
		echo util_jsonUtil::encode ( $arr );
	}
}
?>
