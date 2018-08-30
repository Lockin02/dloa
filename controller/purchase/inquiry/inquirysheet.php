<?php
/*�ɹ�ѯ�۵����Ʋ�
 * Created on 2010-12-27
 * can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_purchase_inquiry_inquirysheet extends controller_base_action {

	function __construct() {
		$this->objName = 'inquirysheet';
		$this->objPath = 'purchase_inquiry';
		parent::__construct ();
	}

/*****************************************��ʾ�ָ��߿�ʼ********************************************/

	/**����ѯ�۵����ҳ��
	*author can
	*2010-12-27
	*/
	function c_toAdd(){
		$CurrentTime = date("Y-m-d");
		$this->show->assign("inquiryBgDate" , $CurrentTime);
		$this->show->assign("effectiveDate" , $CurrentTime);
		$this->show->assign("inquiryEndDate" , $CurrentTime);
		$this->show->assign("expiryDate" , $CurrentTime);
		$idsArry=isset($_GET['idsArry'])?substr($_GET['idsArry'],1):exit;
		$type=isset($_GET['type'])?$_GET['type']:null;
		$this->assign('type',$type);
		$this->service->getParam($_GET);
		//��ȡ�ɹ�������豸�嵥
		$equipmentDao=new model_purchase_task_equipment();
		$listEqu=$equipmentDao->getTaskEqu_d($idsArry);
		$uniqueEquList=$equipmentDao->getUniqueTaskEqu_d($idsArry);
        $proDao=new model_purchase_inquiry_equmentInquiry();
		if($listEqu){
			$this->show->assign('purcherName',$_SESSION['USERNAME']);
			$this->show->assign('purcherId',$_SESSION['USER_ID']);

			//��ȡ����ID
			$deptDao=new model_common_otherdatas();
			$this->assign('deptName' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
	        $this->assign('deptId' , $_SESSION['DEPT_ID']);
//			$this->show->assign('list',$proDao->equList($listEqu));
			$this->show->assign('list',$proDao->addEquList($listEqu,$uniqueEquList));
			$this->show->display($this->objPath.'_'.$this->objName.'-add');
		}
	}


	/**��ת������ҳ��
	*author can
	*2010-12-30
	*/
	function c_toEditAdd () {
		$id=isset($_GET['id'])?$_GET['id']:null;
		$this->service->searchArr=array("id"=>$id);
        $inquiry=$this->service->get_d($id,null);
		$objNo=$inquiry['inquiryCode'];
		$type=isset($_GET['type'])?$_GET['type']:null;
		$this->assign('type',$type);
		//��ʾ������Ϣ
        $this->show->assign("file",$this->service->getFilesByObjNo($objNo,true));
        foreach($inquiry as $key=>$val){
        	$this->show->assign($key,$val);
        }
        $this->show->display($this->objPath.'_'.$this->objName.'-addedit');
	}

	/**��дinit����
	*author can
	*2011-2-21
	*/
	function c_init() {
		$this->permCheck ();//��ȫУ��
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d ( $_GET ['id'] );
		$objNo=$returnObj['inquiryCode'];
		//��ʾ������Ϣ
        $this->show->assign("file",$this->service->getFilesByObjNo($objNo,true));
		foreach ( $returnObj as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		} else {
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**�ҵ�ѯ�۵��б�TAB
	*author can
	*2010-12-28
	*/
	function c_isMyInquiryTab () {
		$this->show->display($this->objPath.'_'.$this->objName.'-mylist-tab');
	}

	/**�ҵ�ѯ�۵��б�
	*author can
	*2010-12-28
	*/
	function c_isMyInquiry () {
		$this->show->display($this->objPath.'_'.$this->objName.'-my-list');
	}

	/**�ҵĹر�ѯ�۵��б�
	*author can
	*2010-12-28
	*/
	function c_isMyCloseList () {
		$this->show->display($this->objPath.'_'.$this->objName.'-myclose-list');
	}


	/**�ҵ�ѯ�۵��б�-���ϻ���
	*author can
	*2010-12-28
	*/
	function c_toMyInquiryEquList () {
		$this->show->display($this->objPath.'_'.$this->objName.'-myequ-list');
	}

	/**�ɹ�ѯ�۵��б�
	*author can
	*2010-12-28
	*/
	function c_isManage(){
		$this->show->display($this->objPath.'_'.$this->objName.'-list');
	}

	/**��Ӧ��ѯ������
	*author can
	*2010-12-29
	*/
	function c_toSupplier(){
		$parentId=isset($_GET['parentId'])?$_GET['parentId']:null;
		$type=isset($_GET['type'])?$_GET['type']:null;
		$this->assign('type',$type);
		//��ʾ������Ϣ
		$inquiry['file']=$this->service->getFilesByObjId($parentId,false);
		$this->show->assign('parentId',$parentId);
		$this->show->display($this->objPath.'_'.$this->objName.'-supplier');
	}

    /**�鿴δָ����Ӧ�̵�ѯ�۵�
	*author can
	*2011-1-1
	*/
	function c_toRead(){
		$this->permCheck ();//��ȫУ��
		$returnObj=$this->objName;
		$$returnObj=$this->service->get_d($_GET['id'],'read');
		$object = $$returnObj;
		$suppDao=new model_purchase_inquiry_inquirysupp();
		$suppproDao=new model_purchase_inquiry_inquirysupppro();
		$suppRows=$suppDao->getSuppByParentId($_GET['id']);
        //��ȡѯ�۲�Ʒ�嵥
		$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($_GET['id']);
		//��ʾ��������
        foreach($suppRows as $key=>$val){
			$suppRows[$key]['child']=$suppproDao->getUniqueByParentId($val['id']);
        }

        //��ȡ���������ϵ���������Э��۸�
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		$suppProDao=new model_purchase_contract_applysuppequ();
		for($i = 0; $i < count($uniqueEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $uniqueEquRows[$i]['amountAll']);//���ϵ�ǰ��������
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//û�е�ǰ����
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

        $this->show->assign("listSee",$this->service->showSupp_s($suppRows,$uniqueEquRows));
		//��ʾ������Ϣ
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],false));
		foreach($$returnObj as $key=>$val){
			$this->show->assign($key,$val);
		}
		$this->assign('suppNumb',count($suppRows));
		$this->show->display($this->objPath.'_'.$this->objName.'-read');
	}

	/**����ָ����Ӧ��ҳ��
	*author can
	*2011-1-2
	*/
	function c_toAssignSupp () {
		$returnObj=$this->objName;
		$$returnObj=$this->service->get_d($_GET['id'],'read');
		$object = $$returnObj;
		$suppDao=new model_purchase_inquiry_inquirysupp();
		$suppproDao=new model_purchase_inquiry_inquirysupppro();
		$suppRows=$suppDao->getSuppByParentId($_GET['id']);
        //��ȡѯ�۲�Ʒ�嵥
		$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($_GET['id']);
		//��ʾ��������
        foreach($suppRows as $key=>$val){
			$suppRows[$key]['child']=$suppproDao->getSuppInquiry_d($val['id']);
        }

        //��ȡ���������ϵ���������Э��۸�
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		$suppProDao=new model_purchase_contract_applysuppequ();
		for($i = 0; $i < count($uniqueEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $uniqueEquRows[$i]['amountAll']);//���ϵ�ǰ��������
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//û�е�ǰ����
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

        $this->show->assign("listSee",$this->service->showSupp_s($suppRows,$uniqueEquRows));
		//��ʾ������Ϣ
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],false));
		foreach($$returnObj as $key=>$val){
			$this->show->assign($key,$val);
		}
		$actType=isset($_GET['actType'])?$_GET['actType']:null;
		$type=isset($_GET['type'])?$_GET['type']:null;
		if($actType){
			$otherdatasDao=new model_common_otherdatas();
			$flag=$otherdatasDao->isLastStep($_GET['id'],$this->service->tbl_name);
			if($flag){
				$this->show->assign('last', $actType);
			}else{
				$this->show->assign('last', "");
			}
		}
		$this->show->assign('type', $type);
		$this->show->assign('id', $_GET[id]);
		$this->show->assign('amaldarDate',date("Y-m-d"));
		$this->show->assign('amaldarName',$_SESSION['USERNAME']);
		$this->show->assign('amaldarId',$_SESSION['USER_ID']);
		$this->assign('suppNumb',count($suppRows));
		$this->show->display($this->objPath.'_'.$this->objName.'-assignsupp');
	}

	/**�鿴��ָ����Ӧ�̵�ѯ�۵�
	*author can
	*2011-1-3
	*/
	function c_toView(){
		$this->permCheck ();//��ȫУ��
		$returnObj=$this->objName;
		$$returnObj=$this->service->get_d($_GET['id'],'view');
		$abc = $$returnObj;
		$suppDao=new model_purchase_inquiry_inquirysupp();
		$suppproDao=new model_purchase_inquiry_inquirysupppro();
		$suppRows=$suppDao->getSuppByParentId($_GET['id']);
        //��ȡѯ�۲�Ʒ�嵥
		$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($_GET['id']);
		//��ʾ��������
        foreach($suppRows as $key=>$val){
			$suppRows[$key]['child']=$suppproDao->getSuppInquiry_d($val['id']);
        }

        //��ȡ���������ϵ���������Э��۸�
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		$suppProDao=new model_purchase_contract_applysuppequ();
		for($i = 0; $i < count($uniqueEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $uniqueEquRows[$i]['amountAll']);//���ϵ�ǰ��������
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//û�е�ǰ����
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

        $this->show->assign("listSee",$this->service->showSupp_s($suppRows,$uniqueEquRows));
		//��ʾ������Ϣ
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],false));
		foreach($$returnObj as $key=>$val){
			$this->show->assign($key,$val);
		}
		$actType=isset($_GET['actType'])?$_GET['actType']:null;
		$this->assign('actType',$actType);
		$this->assign('suppNumb',count($suppRows));
		$this->show->display($this->objPath.'_'.$this->objName.'-view');
	}
	/**
	 *�������б�
	 */
	function c_toAuditList () {
		$this->display('audit-list');
	}
	/**
	 *�������б�
	 */
	function c_toAuditedList () {
		$this->display('audited-list');
	}
	/**
	 *�ҵ������б�
	 */
	function c_myAuditList () {
		$this->display('audit-tab');
	}
	/**
	 *�ҵ�����-�������б�
	 */
	function c_myNoAuditList () {
		$this->display('auditno-list');
	}
	/**
	 *�ҵ�����-�������б�
	 */
	function c_myYesAuditList () {
		$this->display('audityes-list');
	}
	/**
	 *�ɹ������鿴�ɹ�ѯ�۵��б�
	 */
	function c_listForOrder () {
		$this->assign('idArr',$_GET['idArr']);
		$this->display('order-list');
	}


	/*****************************************��ʾ�ָ��߽���********************************************/

	/*****************************************ҵ��ָ��߿�ʼ********************************************/
	/**��Ӳɹ�ѯ�۵�����
	*author can
	*2010-12-28
	*/
	function c_add () {
		$type=isset($_GET['type'])?$_GET['type']:null;
		$object=$this->service->add_d($_POST[$this->objName]);
		if($object){
            succ_show("?model=purchase_inquiry_inquirysheet&action=toSupplier&parentId=$object&type=$type");
		}else{
			msgGo('������Ϣ�����������ʧ��',"?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab2");
		}
	}

	/**��ӹ�Ӧ��
	*author can
	*2010-12-29
	*/
	function c_addSupp(){
		$supplier=$this->service->addSupp_d($_POST);
        if($supplier){
			echo $supplier;      //��������ж��Ƿ���ӳɹ�
        }

	}

	/**�����ѡ��Ӧ�̣������±���
	*author can
	*2011-1-3
	*/
	function c_suppAdd(){
		$supplier=$this->service->suppAdd_d($_POST);
		echo $supplier;
	}


	/**�ҵ�ѯ�۵��б���ʾ����
	*author can
	*2010-12-28
	*/
	function c_myPageJson(){
		$service=$this->service;
		$service->getParam($_POST);
		$service->asc = true;
		$service->searchArr['purcherId']=$_SESSION['USER_ID'];
		$rows=$service->pageBySqlId("inquirysheet_list");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
	        	$rows[$key]['stateName']=$service->statusDao->statusKtoC($rows[$key]['state'] );    //ת��������
        	}

		}
		$arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**ѯ�۵������б����ʾ����
	*author can
	*2011-1-4
	*/
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
//		$rows = $service->page_d ();
		$rows=$service->pageBySqlId("inquirysheet_list");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
	        	//ת��������
	        	$rows[$key]['stateName']=$service->statusDao->statusKtoC($rows[$key]['state'] );
        }

		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription �������б����ݹ��˷���
	 * @qiaolong
	 */
	function c_myAuditPj() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ('sql_examine');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**�������б�
	*/
	function c_pageJsonAuditYes(){
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_audited');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**�ύѯ�۵�
	*author can
	*2010-12-29
	*/
	function c_putInquiry(){
		$service=$this->service;
		$id=isset($_POST['parentId'])?$_POST['parentId']:null;
		$condiction=array('id'=>$id);
		//�޸�״̬Ϊ"��ָ��"
		$updateTag=$service->updateField($condiction,'state','1');
		if($updateTag){
//			msgGo('�ύ�ɹ�',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
			echo 1;
		}else{
			echo 0;
		}

	}

	/**�����ҳ��ͱ༭ҳ���ύѯ�۵�
	*author can
	*2010-12-29
	*/
	function c_putInquirysheet(){
		$service=$this->service;
		$id=isset($_GET['parentId'])?$_GET['parentId']:null;

//		$object = $_POST [$this->objName];
//		$supp=$this->service->edit_d ( $object);
		$condiction=array('id'=>$id);
		//�޸�״̬Ϊ"��ָ��"
		$updateTag=$service->updateField($condiction,'state','1');
		if($updateTag){
			msgGo('�ύ�ɹ�',"?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab2");
		}

	}

	/**�Ҽ��ύѯ�۵�����
	*author can
	*2011-2-21
	*/
	function c_menuSupInquiry(){
		$service=$this->service;
		$id=isset($_GET['parentId'])?$_GET['parentId']:null;
		$condiction=array('id'=>$id);
		//�޸�״̬Ϊ"��ָ��"
		$service->updateField($condiction,'state','1');
	}

	/**���ѯ�۵�
	*author can
	*2010-12-29
	*/
	function c_closeInquiry(){
		$service=$this->service;
		$id=isset($_GET['id'])?$_GET['id']:null;
		$condiction=array('id'=>$id);
		//�޸�״̬Ϊ"�ѹر�"
		$updateTag=$service->updateField($condiction,'state','3');
		if($updateTag){
			msgGo('�رճɹ�',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
		}
	}

	/**�ر�ѯ�۵�
	*author can
	*2011-1-13
	*/
	function c_closeMyInquiry(){
		$service=$this->service;
		$id=isset($_POST['id'])?$_POST['id']:null;
		$condiction=array('id'=>$id);

		//ά���ɹ������豸�´�����
//		$equDao=new model_purchase_inquiry_equmentInquiry();
//		$equDao->del_d($id);
		//�޸�״̬Ϊ"�ѹر�"
		$updateTag=$service->updateField($condiction,'state','3');
		if($updateTag){
//			msgGo('�رճɹ�',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
			echo 1;
		}else{
			echo 0;
		}
	}

	/**�����ر�ѯ�۵�
	*author can
	*2011-1-13
	*/
	function c_closeBatch(){
		$service=$this->service;
		$ids=isset($_POST['ids'])?$_POST['ids']:"";
		$idsArr=explode(',',$ids);
		$updateTag=0;
		foreach($ids as $key=>$val){//forѭ�����йرյ���
			$condiction=array('id'=>$val);
			$updateTag=$service->updateField($condiction,'state','3');
		}
		if($updateTag){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**ɾ��ѯ�۵�
	*author can
	*2010-12-29
	*/
	function c_deletesInfo() {
		$deleteId=isset($_POST['id'])?$_POST['id']:exit;
	    $delete=$this->service->deletesInfo_d ($deleteId);
	    //���ɾ���ɹ����1���������0
         if($delete){
//         	msgGo('ɾ���ɹ�',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
			echo 1;
    	}else{
    		echo 0;
    	}

	}

	/**ѯ�۵��˻�����
	*/
	function c_backToTask() {
		$deleteId=isset($_POST['id'])?$_POST['id']:exit;
	    $delete=$this->service->backToTask_d ($deleteId);
	    //���ɾ���ɹ����1���������0
         if($delete){
			echo 1;
    	}else{
    		echo 0;
    	}

	}

	/**����ѯ�۵������תҳ��
	*author can
	*2011-1-2
	*/
	function c_saveInquiry(){
		$type=isset($_GET['type'])?$_GET['type']:null;
		if($type==""){
			msgGo('����ɹ�',"?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1");
		}else{
			msgGo('����ɹ�',"?model=purchase_task_basic&action=executionList");
		}
	}

	/**���ѯ�۵�ʱ���ز������޸�
	*author can
	*2010-12-30
	*/
	function c_editAdd(){
		$object=$this->service->edit_d($_POST[$this->objName]);
		$suppDao=new model_purchase_inquiry_inquirysupp();
		$condiction=array('parentId'=>$_POST[$this->objName]['id']);
		$suppDao->delete($condiction);
		if($object){
            succ_show("?model=purchase_inquiry_inquirysheet&action=toSupplier&parentId=$object");
		}
	}

	/**ָ����Ӧ��
	*author can
	*2011-1-2
	*/
	function c_assignSupp(){
		$type=isset($_GET['type'])?$_GET['type']:null;
		$inquirysheet=$this->service->assignSupp_d($_POST[$this->objName]);
		if($inquirysheet){
			if($type=="todiff"){
				msgGo('ָ����Ӧ�̳ɹ�',"?model=purchase_inquiry_inquirysheet&action=toAuditedList");
			}else{
				$skey=$this->md5Row($_POST[$this->objName]['id'],'purchase_inquiry_inquirysheet');
				msgGo('ָ����Ӧ�̳ɹ�',"?model=purchase_inquiry_inquirysheet&action=toView&actType=audit&id=".$_POST[$this->objName]['id']."&skey=".$skey);

			}
		}
	}

	/**
	 *������ҳ��ָ����Ӧ��
	 */
	function c_assignSuppByApproval () {
		$inquirysheetRows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			//�������ص�����
            $this->service->workflowCallBack($_GET['spid'],$inquirysheetRows);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_inquiry_inquirysheet&action=myAuditList'</script>";
		}
	}

	/**�޸�ѯ�۵�
	*author can
	*2011-1-4
	*/
	function c_edit() {
		$act = isset($_GET ['act'])? $_GET ['act'] : 'edit';
		$object = $_POST [$this->objName];
		$this->service->edit_d ( $object);
		if ($act == 'audit') {
			succ_show('controller/purchase/inquiry/ewf_index.php?actTo=ewfSelect&billId='.$object['id']. '&examCode=oa_purch_inquiry&formName=�ɹ�ѯ�۵�����');
		}else{
			msgGo('����ɹ�',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
		}
	}




	/*****************************************ҵ��ָ��߽���********************************************/
}
?>
