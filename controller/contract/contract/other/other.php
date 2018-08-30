<?php
/**
 * @author Show
 * @Date 2011年12月5日 星期一 10:19:51
 * @version 1.0
 * @description:其他合同控制层
 */
class controller_contract_other_other extends controller_base_action {

	function __construct() {
		$this->objName = "other";
		$this->objPath = "contract_other";
		parent::__construct ();
	}

	/*
	 * 跳转到其他合同
	 */
    function c_page() {
       $this->view('list');
    }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonFinanceInfo() {
		$service = $this->service;

		//初始化搜索条件
		if(isset($_POST['payandinv'])){
			$thisSet = $service->initSetting_c($_POST['payandinv']);
			$_POST[$thisSet] = 1;
			unset($_POST['payandinv']);
		}

		//系统权限
		$deptLimit = $this->service->this_limit['部门权限'];

		//办事处 － 全部 处理
		if(strstr($deptLimit,';;')){
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->pageBySqlId('select_financeInfo');
		}else{//如果没有选择全部，则进行权限查询并赋值

			if(!empty($deptLimit)){
				$_POST['deptsIn'] = $deptLimit ;
				$service->getParam ( $_POST ); //设置前台获取的参数信息
				$rows = $service->page_d ('select_financeInfo');
			}
		}

		if(is_array($rows)){
			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $rows );

			//合计加入
			$rows = $this->service->pageCount_d($rows);

			$objArr = $service->listBySqlId('count_list');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['createDate'] = '合计';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;//加载扩展SQL
		echo util_jsonUtil::encode ( $arr );
	}

     /**
     * 重写toadd
     */
    function c_toAdd() {
		$this->showDatadicts ( array ('fundType' => 'KXXZ' ));
		$this->showDatadicts ( array ('signCompanyType' => 'QYDX' ));
		$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),null,true);

		//付款申请信息渲染
		$this->showDatadicts ( array ('payFor' => 'FKLX' ),null,false,array('expand1' => 1) );//付款类型
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//结算方式

		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('deptName',$_SESSION['DEPT_NAME']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$this->assign('isSysCode',ORDERCODE_INPUT);//是否手工输入合同号
		$this->assign('isShare',PAYISSHARE);//是否启用费用分摊

		$this->assign('userId',$_SESSION['USER_ID']);

		//获取归属公司名称
		$this->assign('formBelong',$_SESSION['USER_COM']);
		$this->assign('formBelongName',$_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong',$_SESSION['USER_COM']);
		$this->assign('businessBelongName',$_SESSION['USER_COM_NAME']);

		$this->view ( 'add' );
	}

	/**
	 * 独立新增
	 */
	function c_toAddDept(){
		$this->showDatadicts ( array ('fundType' => 'KXXZ' ));
		$this->showDatadicts ( array ('signCompanyType' => 'QYDX' ));
		$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),null,true);

		//付款申请信息渲染
		$this->showDatadicts ( array ('payFor' => 'FKLX' ),null,false,array('expand1' => 1) );//付款类型
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//结算方式

		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('deptName',$_SESSION['DEPT_NAME']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$this->assign('isSysCode',ORDERCODE_INPUT);//是否手工输入合同号
		$this->assign('isShare',PAYISSHARE);//是否启用费用分摊

		$this->assign('userId',$_SESSION['USER_ID']);

		//获取归属公司名称
		$this->assign('formBelong',$_SESSION['USER_COM']);
		$this->assign('formBelongName',$_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong',$_SESSION['USER_COM']);
		$this->assign('businessBelongName',$_SESSION['USER_COM_NAME']);

		$this->view ( 'adddept' );
	}

    //新增 - 用于外部付款
    function c_toAddPay(){
        $this->showDatadicts ( array ('fundType' => 'KXXZ' ),'KXXZB');
        $this->assign('fundTypeHidden','KXXZB');
        $this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),$_GET['projectType'],true);
        $this->assign('projectTypeHidden',$_GET['projectType']);

        //付款申请信息渲染
		$this->showDatadicts ( array ('payFor' => 'FKLX' ),null,false,array('expand1' => 1) );//付款类型
        $this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//结算方式

        $this->assign('projectId',$_GET['projectId']);
        $this->assign('projectCode',$_GET['projectCode']);
        $this->assign('projectName',$_GET['projectName']);
        $this->assign('orderMoney',$_GET['orderMoney']);
        $this->assign('deptId',$_SESSION['DEPT_ID']);
        $this->assign('deptName',$_SESSION['DEPT_NAME']);
        $this->assign('principalId',$_SESSION['USER_ID']);
        $this->assign('principalName',$_SESSION['USERNAME']);
        $this->assign('thisDate',day_date);

        $this->assign('isSysCode',ORDERCODE_INPUT);//是否手工输入合同号
        $this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('isShare',PAYISSHARE);//是否启用费用分摊

        $this->view ( 'addpay' );
    }

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object );
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					if($object ['payapply']['isSalary'] == 1){//工资类付款申请审批流
						succ_show('controller/contract/other/ewf_forsalarypayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}
				}else{
					if($object['fundType'] == 'KXXZB'){
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['deptId'].'&billCompany='.$object['businessBelong']);
					}
				}
			}else{
				msgRf('保存成功');
			}
		}else{
			msgRf('保存失败');
		}
	}

	/**
	 * 独立新增对象操作
	 */
	function c_addDept($isAddInfo = true) {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object );
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					if($object ['payapply']['isSalary'] == 1){//工资类付款申请审批流
						succ_show('controller/contract/other/ewf_forsalarypayapplydept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					} 
				}else{
					if($object['fundType'] == 'KXXZB'){
						succ_show('controller/contract/other/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['deptId'].'&billCompany='.$object['businessBelong']);
					}
				}
			}else{
				msgGo ( '保存成功！', '?model=contract_other_other&action=toAddDept' );
			}
		}else{
			msgGo ( '保存失败！', '?model=contract_other_other&action=toAddDept' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = true) {
		$object = $_POST [$this->objName];
		$id = $this->service->editInfo_d ( $object);
		if ($id) {
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					if($object ['payapply']['isSalary'] == 1){//工资类付款申请审批流
						succ_show('controller/contract/other/ewf_forsalarypayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}
				}else{
					if($object['fundType'] == 'KXXZB'){
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['deptId'].'&billCompany='.$object['businessBelong']);
					}
				}
			}else{
				msgRf('保存成功');
			}
		}else{
			msgRf('保存失败');
		}
	}

	/**
	 * 跳转审批工作流的查看页面
	 */
	function c_viewAccraditation(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['orgFundType'] = $obj['fundType'];

		$this->assignFunc($obj);

		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

		$this->assign ( 'fundType', $this->getDataNameByCode ( $obj ['fundType'] ) );

		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
		$this->view ( 'viewAccraditation' );
	}

	/**
	 * 查看页面 - 包含付款申请信息
	 */
	function c_viewAlong(){
		$this->permCheck (); //安全校验
		$obj = $this->service->getInfo_d ( $_GET ['id'] );
		$obj['orgFundType'] = $obj['fundType'];
		$this->assignFunc($obj);

		//提交审批后查看单据时隐藏关闭按钮
		if(isset($_GET['hideBtn'])){
			$this->assign('hideBtn',1);
		}else{
			$this->assign('hideBtn',0);
		}

		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->assign('file1',$this->service->getFilesByObjId ( $obj ['id'], false,'oa_sale_otherpayapply' ));

		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

		$this->assign ( 'payFor', $this->getDataNameByCode ( $obj ['payFor'] ) );
		$this->assign ( 'payType', $this->getDataNameByCode ( $obj ['payType'] ) );

		$this->assign('isInvoice',$this->service->rtYesOrNo_d($obj['isInvoice']));

		//委托付款
		$this->assign('isEntrust',$this->service->rtYesOrNo_d($obj['isEntrust']));
		//是否工资类付款
		$this->assign('isSalary',$this->service->rtYesOrNo_d($obj['isSalary']));

		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);
		$this->view ( $thisObjCode .'-viewAlong' );
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {

			$obj = $this->service->getInfo_d ( $_GET ['id'] );
			$obj['orgFundType'] = $obj['fundType'];
			$this->assignFunc($obj);
			  //提交审批后查看单据时隐藏关闭按钮
			if(isset($_GET['viewBtn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}
			//附件添加{file}
			$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

			$this->assign ( 'fundType', $this->getDataNameByCode ( $obj ['fundType'] ) );

			$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
			$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

			//签收状态
			$this->assign('signedStatusCN',$this->service->rtIsSign_d($obj ['signedStatus'])) ;

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

			$this->view ( 'view' );
		} else {
			$obj = $this->service->getInfo_d ( $_GET ['id'] );

			$this->assignFunc($obj);

			//附件
		   	$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
			$this->assign('file1',$this->service->getFilesByObjId ( $obj ['id'], true,'oa_sale_otherpayapply' )) ;

			$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),$obj ['projectType'],true);
			$this->showDatadicts ( array ('fundType' => 'KXXZ' ), $obj ['fundType'] );

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

			//付款申请信息渲染
			$this->showDatadicts ( array ('payFor' => 'FKLX' ),$obj['payFor'],false,array('expand1' => 1) );//付款类型
			$this->showDatadicts ( array ('payType' => 'CWFKFS' ),$obj['payType']);//结算方式
			$this->assign('userId',$_SESSION['USER_ID']);
			$this->assign('isShare',PAYISSHARE);//是否启用费用分摊
			$this->assign('payee',$this->getDataNameByCode($obj['payee']));
			$this->assign('comments',$this->getDataNameByCode($obj['comments']));

			$this->view ('edit' );
		}
	}

	/**
	 * 合同tab页
	 */
	function c_viewTab(){
		$this->assign('id',$_GET['id']);
		$obj = $this->service->get_d ( $_GET ['id'] );
		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);
		$this->display($thisObjCode . '-viewtab');
	}

	/**
	 * 更新返款金额
	 */
	function c_toUpdateReturnMoney(){
		//获取合同自身信息
		$obj = $this->service->getInfoAndPay_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('updatereturnmoney');
	}

	/**
	 * 更新返款金额
	 */
	function c_updateReturnMoney(){
		$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object);
		if($rs){
			msg('保存成功');
		}else{
			msg('保存失败');
		}
	}

	/**
	 *跳转申请盖章页面
	 */
	 function c_toStamp(){
	 	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('applyDate',day_date);
// 		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
		$this->assign('file','暂无任何附件');

		//当前盖章申请人
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('thisUserName',$_SESSION['USERNAME']);

		$this->view ( 'stamp' );
	 }

	/**
	 * 新增盖章信息操作
	 */
	function c_stamp(){
		$rs = $this->service->stamp_d($_POST[$this->objName]);
		if ($rs) {
			msg ( "申请成功！" );
		}else{
			msg ( "申请失败！" );
		}
	}

	/**
	 * 审批完成后处理盖章的方法
	 */
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 其他合同立项付款申请
	 */
	function c_dealAfterAuditPayapply(){
       	$this->service->dealAfterAuditPayapply_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 跳转个人其他合同
	 */
	 function c_myOther(){
	 	$this->view('mylist');
	 }

	/**
     * 我的其他合同
     */
    function c_myOtherListPageJson() {
    	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['principalIdAndCreateId'] = $_SESSION['USER_ID'];
		$service->setCompany(0); # 个人列表,不需要进行公司过滤
		$rows = $service->page_d ('select_financeInfo');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转个人其他合同 - 状态合并列表
	 */
	function c_myStatusList(){
	 	$status = isset($_GET['status']) ? $_GET['status'] : 0 ;
	 	$this->assign('status',$status);
	 	$this->view('mystatuslist');
	}

	/**
	 * 关闭合同
	 */
	function c_changeStatus() {
		if($this->service->edit_d(array('id' => $_POST['id'] , 'status' => '3'))){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * 附件上传
	 */
	function c_toUploadFile(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->assignFunc($obj);

		$this->view('uploadfile');
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S 变更系列 *********************/
	/**
	 * 变更申请页面
	 */
	function c_toChange(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);

		//附件
	   	$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),$obj ['projectType'],true);

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType',null, true , $stampArr);//盖章类型

	   	//付款条件必填选项
		$datadictDao = new model_system_datadict_datadict ();
		$rs = $datadictDao->find(array('dataCode' => $obj['fundType']),null,'expand1');
		$this->assign('isNeed',$rs['expand1']);

		$this->view('change');
	}

	/**
	 * 变更操作
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		$object = $_POST [$this->objName];
		try {
			$id = $this->service->change_d ( $object );
			if($object['fundType'] == 'KXXZB'){
				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
			}else{
				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billCompany='.$object['businessBelong']);
			}
		} catch ( Exception $e ) {
			msgBack2 ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}

	/**
	 * 审批完成后处理盖章的方法
	 */
	function c_dealAfterAuditChange(){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAuditChange_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 变更查看tab
	 */
	function c_changeTab(){
		$this->permCheck (); //安全校验
		$newId = $_GET ['id'];
		$this->assign('id',$newId);

		$rs = $this->service->find(array('id' => $newId ),null,'originalId,fundType');
		$this->assign('originalId',$rs['originalId']);

		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($rs['fundType']);
		$this->display($thisObjCode . '-changetab');
	}

	/**
	 * 变更查看合同  - 查看原合同
	 */
	function c_changeView(){
		$this->permCheck (); //安全校验
		$id = $_GET ['id'];

		$obj = $this->service->get_d ( $id );

		$this->assignFunc($obj);

		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $id, false,$this->service->tbl_name )) ;


		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;
		$this->assign('isNeedRestamp',$this->service->rtYesOrNo_d($obj ['isNeedRestamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
		$this->view ( 'changeview' );
	}
	/******************* E 变更系列 *********************/



	/******************* S 签收系列 *********************/
	/**
	 * 合同签收 - 列表tab页
	 */
	function c_signTab(){
		$this->display('signTab');
	}

	/**
	 * 合同签收 - 待签收合同列表
	 */
	function c_signingList(){
		$this->view('signinglist');
	}

	/**
	 * 合同签收 - 已签收合同列表
	 */
	function c_signedList(){
		$this->view('signedlist');
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_toSign(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ), $obj ['payType'] );//合同付款方式
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//合同外包方式

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType',null, true , $stampArr);//盖章类型

		$this->view('sign');
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_sign(){
		$object = $_POST [$this->objName];
		$id = $this->service->sign_d ( $object);
		if ($id) {
			msgRf('签收成功');
		}else{
			msgRf('签收失败');
		}
	}

	/******************* E 签收系列 *********************/

	/******************* S 导入导出部分 *******************/
	/**
	 * 导出数据
	 */
	function c_exportExcel(){
		$service = $this->service;
		$service->getParam ( $_REQUEST ); //设置前台获取的参数信息
		$service->sort = 'c.createTime';
		$rows = $service->listBySqlId ('select_financeInfo');

		return model_contract_common_contractExcelUtil::otherContratOut_e ( $rows );
	}
	//add chenrf
	/**
	 * 跳转到分摊明细导入页面
	 */
	function c_toPayImportExcel(){
		$this->view('pay-import');
	}
	/**
	 * 分摊明细导入页面
	 */
	function c_payImportExcel(){
		$resultArr=$this->service->payImportExcel();
		$title='分摊明细导入结果';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E 导入导出部分 *******************/
	/**
	 * 验证方法
	 */
	function c_canPayapply(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapply_d($id);
		echo $rs;
		exit();
	}

	/**
	 * 退款申请验证
	 */
	function c_canPayapplyBack(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapplyBack_d($id);
		echo $rs;
		exit();
	}
	/**
	 *
	 *删除导入的临时数据
	 */
	function c_delTempImport(){
		$this->service->delPayDetail();
	}
	/**
	 * 关闭合同
	 */
	function c_toClose(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('close');
	}
	
	/**
	 * 关闭时提交审批
	 */
	function c_close(){
		$object = $_POST[$this->objName];
		$id = $this->service->edit_d($object);
		if ($id) {
			succ_show('controller/contract/other/ewf_close.php?actTo=ewfSelect&billId='.$object['id']);
		}else{
			msg('保存失败');
		}
	}
}