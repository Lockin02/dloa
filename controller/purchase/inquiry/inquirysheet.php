<?php
/*采购询价单控制层
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

/*****************************************显示分割线开始********************************************/

	/**跳到询价单添加页面
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
		//获取采购任务的设备清单
		$equipmentDao=new model_purchase_task_equipment();
		$listEqu=$equipmentDao->getTaskEqu_d($idsArry);
		$uniqueEquList=$equipmentDao->getUniqueTaskEqu_d($idsArry);
        $proDao=new model_purchase_inquiry_equmentInquiry();
		if($listEqu){
			$this->show->assign('purcherName',$_SESSION['USERNAME']);
			$this->show->assign('purcherId',$_SESSION['USER_ID']);

			//获取部门ID
			$deptDao=new model_common_otherdatas();
			$this->assign('deptName' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
	        $this->assign('deptId' , $_SESSION['DEPT_ID']);
//			$this->show->assign('list',$proDao->equList($listEqu));
			$this->show->assign('list',$proDao->addEquList($listEqu,$uniqueEquList));
			$this->show->display($this->objPath.'_'.$this->objName.'-add');
		}
	}


	/**跳转到返回页面
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
		//显示附件信息
        $this->show->assign("file",$this->service->getFilesByObjNo($objNo,true));
        foreach($inquiry as $key=>$val){
        	$this->show->assign($key,$val);
        }
        $this->show->display($this->objPath.'_'.$this->objName.'-addedit');
	}

	/**重写init方法
	*author can
	*2011-2-21
	*/
	function c_init() {
		$this->permCheck ();//安全校验
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d ( $_GET ['id'] );
		$objNo=$returnObj['inquiryCode'];
		//显示附件信息
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

	/**我的询价单列表TAB
	*author can
	*2010-12-28
	*/
	function c_isMyInquiryTab () {
		$this->show->display($this->objPath.'_'.$this->objName.'-mylist-tab');
	}

	/**我的询价单列表
	*author can
	*2010-12-28
	*/
	function c_isMyInquiry () {
		$this->show->display($this->objPath.'_'.$this->objName.'-my-list');
	}

	/**我的关闭询价单列表
	*author can
	*2010-12-28
	*/
	function c_isMyCloseList () {
		$this->show->display($this->objPath.'_'.$this->objName.'-myclose-list');
	}


	/**我的询价单列表-物料汇总
	*author can
	*2010-12-28
	*/
	function c_toMyInquiryEquList () {
		$this->show->display($this->objPath.'_'.$this->objName.'-myequ-list');
	}

	/**采购询价单列表
	*author can
	*2010-12-28
	*/
	function c_isManage(){
		$this->show->display($this->objPath.'_'.$this->objName.'-list');
	}

	/**供应商询价详情
	*author can
	*2010-12-29
	*/
	function c_toSupplier(){
		$parentId=isset($_GET['parentId'])?$_GET['parentId']:null;
		$type=isset($_GET['type'])?$_GET['type']:null;
		$this->assign('type',$type);
		//显示附件信息
		$inquiry['file']=$this->service->getFilesByObjId($parentId,false);
		$this->show->assign('parentId',$parentId);
		$this->show->display($this->objPath.'_'.$this->objName.'-supplier');
	}

    /**查看未指定供应商的询价单
	*author can
	*2011-1-1
	*/
	function c_toRead(){
		$this->permCheck ();//安全校验
		$returnObj=$this->objName;
		$$returnObj=$this->service->get_d($_GET['id'],'read');
		$object = $$returnObj;
		$suppDao=new model_purchase_inquiry_inquirysupp();
		$suppproDao=new model_purchase_inquiry_inquirysupppro();
		$suppRows=$suppDao->getSuppByParentId($_GET['id']);
        //获取询价产品清单
		$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($_GET['id']);
		//显示报价详情
        foreach($suppRows as $key=>$val){
			$suppRows[$key]['child']=$suppproDao->getUniqueByParentId($val['id']);
        }

        //获取订单中物料的总数量和协议价格
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		$suppProDao=new model_purchase_contract_applysuppequ();
		for($i = 0; $i < count($uniqueEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $uniqueEquRows[$i]['amountAll']);//加上当前购买数量
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//没有当前数量
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

        $this->show->assign("listSee",$this->service->showSupp_s($suppRows,$uniqueEquRows));
		//显示附件信息
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],false));
		foreach($$returnObj as $key=>$val){
			$this->show->assign($key,$val);
		}
		$this->assign('suppNumb',count($suppRows));
		$this->show->display($this->objPath.'_'.$this->objName.'-read');
	}

	/**跳到指定供应商页面
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
        //获取询价产品清单
		$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($_GET['id']);
		//显示报价详情
        foreach($suppRows as $key=>$val){
			$suppRows[$key]['child']=$suppproDao->getSuppInquiry_d($val['id']);
        }

        //获取订单中物料的总数量和协议价格
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		$suppProDao=new model_purchase_contract_applysuppequ();
		for($i = 0; $i < count($uniqueEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $uniqueEquRows[$i]['amountAll']);//加上当前购买数量
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//没有当前数量
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

        $this->show->assign("listSee",$this->service->showSupp_s($suppRows,$uniqueEquRows));
		//显示附件信息
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

	/**查看已指定供应商的询价单
	*author can
	*2011-1-3
	*/
	function c_toView(){
		$this->permCheck ();//安全校验
		$returnObj=$this->objName;
		$$returnObj=$this->service->get_d($_GET['id'],'view');
		$abc = $$returnObj;
		$suppDao=new model_purchase_inquiry_inquirysupp();
		$suppproDao=new model_purchase_inquiry_inquirysupppro();
		$suppRows=$suppDao->getSuppByParentId($_GET['id']);
        //获取询价产品清单
		$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($_GET['id']);
		//显示报价详情
        foreach($suppRows as $key=>$val){
			$suppRows[$key]['child']=$suppproDao->getSuppInquiry_d($val['id']);
        }

        //获取订单中物料的总数量和协议价格
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		$suppProDao=new model_purchase_contract_applysuppequ();
		for($i = 0; $i < count($uniqueEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $uniqueEquRows[$i]['amountAll']);//加上当前购买数量
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//没有当前数量
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

        $this->show->assign("listSee",$this->service->showSupp_s($suppRows,$uniqueEquRows));
		//显示附件信息
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
	 *待审批列表
	 */
	function c_toAuditList () {
		$this->display('audit-list');
	}
	/**
	 *已审批列表
	 */
	function c_toAuditedList () {
		$this->display('audited-list');
	}
	/**
	 *我的审批列表
	 */
	function c_myAuditList () {
		$this->display('audit-tab');
	}
	/**
	 *我的审批-待审批列表
	 */
	function c_myNoAuditList () {
		$this->display('auditno-list');
	}
	/**
	 *我的审批-已审批列表
	 */
	function c_myYesAuditList () {
		$this->display('audityes-list');
	}
	/**
	 *采购订单查看采购询价单列表
	 */
	function c_listForOrder () {
		$this->assign('idArr',$_GET['idArr']);
		$this->display('order-list');
	}


	/*****************************************显示分割线结束********************************************/

	/*****************************************业务分割线开始********************************************/
	/**添加采购询价单方法
	*author can
	*2010-12-28
	*/
	function c_add () {
		$type=isset($_GET['type'])?$_GET['type']:null;
		$object=$this->service->add_d($_POST[$this->objName]);
		if($object){
            succ_show("?model=purchase_inquiry_inquirysheet&action=toSupplier&parentId=$object&type=$type");
		}else{
			msgGo('物料信息不完整，添加失败',"?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab2");
		}
	}

	/**添加供应商
	*author can
	*2010-12-29
	*/
	function c_addSupp(){
		$supplier=$this->service->addSupp_d($_POST);
        if($supplier){
			echo $supplier;      //输出用于判断是否添加成功
        }

	}

	/**如果已选供应商，则重新保存
	*author can
	*2011-1-3
	*/
	function c_suppAdd(){
		$supplier=$this->service->suppAdd_d($_POST);
		echo $supplier;
	}


	/**我的询价单列表显示方法
	*author can
	*2010-12-28
	*/
	function c_myPageJson(){
		$service=$this->service;
		$service->getParam($_POST);
		$service->asc = true;
		$service->searchArr['purcherId']=$_SESSION['USER_ID'];
		$rows=$service->pageBySqlId("inquirysheet_list");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
	        	$rows[$key]['stateName']=$service->statusDao->statusKtoC($rows[$key]['state'] );    //转换成中文
        	}

		}
		$arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**询价单管理列表的显示方法
	*author can
	*2011-1-4
	*/
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true;
//		$rows = $service->page_d ();
		$rows=$service->pageBySqlId("inquirysheet_list");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
	        	//转换成中文
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
	 * @desription 待审批列表数据过滤方法
	 * @qiaolong
	 */
	function c_myAuditPj() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ('sql_examine');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**已审批列表
	*/
	function c_pageJsonAuditYes(){
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_audited');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**提交询价单
	*author can
	*2010-12-29
	*/
	function c_putInquiry(){
		$service=$this->service;
		$id=isset($_POST['parentId'])?$_POST['parentId']:null;
		$condiction=array('id'=>$id);
		//修改状态为"待指定"
		$updateTag=$service->updateField($condiction,'state','1');
		if($updateTag){
//			msgGo('提交成功',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
			echo 1;
		}else{
			echo 0;
		}

	}

	/**在添加页面和编辑页面提交询价单
	*author can
	*2010-12-29
	*/
	function c_putInquirysheet(){
		$service=$this->service;
		$id=isset($_GET['parentId'])?$_GET['parentId']:null;

//		$object = $_POST [$this->objName];
//		$supp=$this->service->edit_d ( $object);
		$condiction=array('id'=>$id);
		//修改状态为"待指定"
		$updateTag=$service->updateField($condiction,'state','1');
		if($updateTag){
			msgGo('提交成功',"?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab2");
		}

	}

	/**右键提交询价单方法
	*author can
	*2011-2-21
	*/
	function c_menuSupInquiry(){
		$service=$this->service;
		$id=isset($_GET['parentId'])?$_GET['parentId']:null;
		$condiction=array('id'=>$id);
		//修改状态为"待指定"
		$service->updateField($condiction,'state','1');
	}

	/**完成询价单
	*author can
	*2010-12-29
	*/
	function c_closeInquiry(){
		$service=$this->service;
		$id=isset($_GET['id'])?$_GET['id']:null;
		$condiction=array('id'=>$id);
		//修改状态为"已关闭"
		$updateTag=$service->updateField($condiction,'state','3');
		if($updateTag){
			msgGo('关闭成功',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
		}
	}

	/**关闭询价单
	*author can
	*2011-1-13
	*/
	function c_closeMyInquiry(){
		$service=$this->service;
		$id=isset($_POST['id'])?$_POST['id']:null;
		$condiction=array('id'=>$id);

		//维护采购任务设备下达数量
//		$equDao=new model_purchase_inquiry_equmentInquiry();
//		$equDao->del_d($id);
		//修改状态为"已关闭"
		$updateTag=$service->updateField($condiction,'state','3');
		if($updateTag){
//			msgGo('关闭成功',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
			echo 1;
		}else{
			echo 0;
		}
	}

	/**批量关闭询价单
	*author can
	*2011-1-13
	*/
	function c_closeBatch(){
		$service=$this->service;
		$ids=isset($_POST['ids'])?$_POST['ids']:"";
		$idsArr=explode(',',$ids);
		$updateTag=0;
		foreach($ids as $key=>$val){//for循环进行关闭单据
			$condiction=array('id'=>$val);
			$updateTag=$service->updateField($condiction,'state','3');
		}
		if($updateTag){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**删除询价单
	*author can
	*2010-12-29
	*/
	function c_deletesInfo() {
		$deleteId=isset($_POST['id'])?$_POST['id']:exit;
	    $delete=$this->service->deletesInfo_d ($deleteId);
	    //如果删除成功输出1，否则输出0
         if($delete){
//         	msgGo('删除成功',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
			echo 1;
    	}else{
    		echo 0;
    	}

	}

	/**询价单退回任务
	*/
	function c_backToTask() {
		$deleteId=isset($_POST['id'])?$_POST['id']:exit;
	    $delete=$this->service->backToTask_d ($deleteId);
	    //如果删除成功输出1，否则输出0
         if($delete){
			echo 1;
    	}else{
    		echo 0;
    	}

	}

	/**保存询价单后的跳转页面
	*author can
	*2011-1-2
	*/
	function c_saveInquiry(){
		$type=isset($_GET['type'])?$_GET['type']:null;
		if($type==""){
			msgGo('保存成功',"?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1");
		}else{
			msgGo('保存成功',"?model=purchase_task_basic&action=executionList");
		}
	}

	/**添加询价单时返回操作的修改
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

	/**指定供应商
	*author can
	*2011-1-2
	*/
	function c_assignSupp(){
		$type=isset($_GET['type'])?$_GET['type']:null;
		$inquirysheet=$this->service->assignSupp_d($_POST[$this->objName]);
		if($inquirysheet){
			if($type=="todiff"){
				msgGo('指定供应商成功',"?model=purchase_inquiry_inquirysheet&action=toAuditedList");
			}else{
				$skey=$this->md5Row($_POST[$this->objName]['id'],'purchase_inquiry_inquirysheet');
				msgGo('指定供应商成功',"?model=purchase_inquiry_inquirysheet&action=toView&actType=audit&id=".$_POST[$this->objName]['id']."&skey=".$skey);

			}
		}
	}

	/**
	 *在审批页面指定供应商
	 */
	function c_assignSuppByApproval () {
		$inquirysheetRows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			//审批流回调方法
            $this->service->workflowCallBack($_GET['spid'],$inquirysheetRows);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_inquiry_inquirysheet&action=myAuditList'</script>";
		}
	}

	/**修改询价单
	*author can
	*2011-1-4
	*/
	function c_edit() {
		$act = isset($_GET ['act'])? $_GET ['act'] : 'edit';
		$object = $_POST [$this->objName];
		$this->service->edit_d ( $object);
		if ($act == 'audit') {
			succ_show('controller/purchase/inquiry/ewf_index.php?actTo=ewfSelect&billId='.$object['id']. '&examCode=oa_purch_inquiry&formName=采购询价单审批');
		}else{
			msgGo('保存成功',"?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");
		}
	}




	/*****************************************业务分割线结束********************************************/
}
?>
