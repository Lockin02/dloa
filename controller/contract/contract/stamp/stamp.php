<?php
/**
 * @author Show
 * @Date 2011年12月16日 星期五 11:24:28
 * @version 1.0
 * @description:盖章记录控制层
 */
class controller_contract_stamp_stamp extends controller_base_action {

	function __construct() {
		$this->objName = "stamp";
		$this->objPath = "contract_stamp";
		parent::__construct ();
	}

	/*
	 * 跳转到盖章记录列表
	 */
    function c_page() {
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonForStampType() {
		$service = $this->service;
		$rows = array();
        $_POST['session_uid'] = $_SESSION['USER_ID'];
		$service->getParam ( $_POST );

//		$rs = $this->service->getStampTypeList_d();
//		if(!empty($rs)){
//			$service->searchArr['stampTypes'] = implode(',',$rs);
//
//			//$service->asc = false;
//			$rows = $service->page_d ();
//			//数据加入安全码
//			$rows = $this->sconfig->md5Rows ( $rows );
//		}

        // 个人合同盖章列表信息显示不全问题修复 2017-01-05 huanghaojin
        $rows = $service->listBySqlId('select_list1');
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
	 * 盖章记录
	 */
	function c_listrecords(){
		$this->view ( 'listrecords' );
	}

    /**
     *  我的盖章申请
     */
    function c_myList(){
        $this->view ( 'listmy' );
    }

    /**
     * 我的盖章申请json
     */
    function c_myPageJson(){
        $service = $this->service;
        $rows = array();

        $_POST['applyUserId'] = $_SESSION['USER_ID'];
        $service->getParam ( $_POST );

        //$service->asc = false;
        $rows = $service->page_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode ( $arr );
    }
    /******************** 增删改查 ******************/

	/**
	 * 跳转到新增盖章记录页面
	 */
	function c_toAdd() {
        $initArr = array(
            'userId' => $_SESSION['USER_ID'],
            'userName' => $_SESSION['USERNAME'],
            'deptId' => $_SESSION['DEPT_ID'],
            'deptName' => $_SESSION['DEPT_NAME'],
            'applyDate' => day_date
        );
        $this->assignFunc($initArr);
        $this->showDatadicts ( array ('stampExecution' => 'HTGZXZ'),null,true);
        $this->showDatadicts ( array ('contractType' => 'HTGZYD'),null,true);
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑盖章记录页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//盖章类型

		$this->view ( 'edit');
	}

    /**
     * 修改对象
     */
    function c_editWithBusiness() {
//      $this->permCheck (); //安全校验
        $object = $_POST [$this->objName];
        if ($this->service->editWithBusiness_d ( $object)) {
            msg ( '编辑成功！' );
        }
    }

	/**
	 * 跳转到查看盖章记录页面
	 */
	function c_toView() {
		$id = $_GET['id'];

		//设置盖章基本信息
		$obj = $this->service->get_d($id);

		//获取盖章源单信息并渲染
        if($obj['contractType'] != 'HTGZYD-TB'){
            $newClass = $this->service->getClass($obj['contractType']);
            $initObj = new $newClass();
            $rs = $this->service->initStamp_d($obj,$initObj);
        }

		$contractTypeCN =  $this->getDataNameByCode ( $obj ['contractType']) ;
		//标题渲染
// 		$this->assign ( 'contractTypeCN',$contractTypeCN );

		//扩展页面加载
// 		$this->assignFunc($rs);
// 		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expandview');
		//确认页面加载
		$this->assignFunc($obj);

//		$this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );
		$contractType =  $this->service->getType($obj['contractType']);
		$skey = $this->md5Row($obj['contractId'],$contractType);
		switch ($obj['contractType']){
			case 'HTGZYD-01' : 
				$url = '?model=contract_outsourcing_outsourcing&action=init&perm=view&actType=audit&viewBtn=0&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-02' :
				$url = '?model=contract_other_other&action=viewAlong&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-03' : 
				$url = '?model=purchase_contract_purchasecontract&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-04' :
				$url = '?model=contract_contract_contract&action=init&perm=view&actType=audit&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-05' :
				$url = '?model=contract_stamp_stampapply&action=toView&hideBtn=1&id='.$obj['applyId'].'&skey='.$skey;break;
			case 'HTGZYD-06' :
				$url = '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-07' :
				$url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
            case 'HTGZYD-TB':// PMS 557 新增的一类盖章申请【投资申报】,只有新OA接口会生成,带出的数据是新OA的源单信息
                $url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id=122&skey='.$skey;
                break;
		}
		$this->assign('stampUrl', $url);
// 		print_r($url);die();
		$this->assign ( 'contractTypeCN', $contractTypeCN );
		$this->assign( 'status' , $this->service->rtStampType_d($obj['status']));

        $stampApplyDao = new model_contract_stamp_stampapply();
        $stampapplyData = $stampApplyDao->get_d($obj['applyId']);
        $this->assign('stampCompany',$stampapplyData['stampCompany']);

		$this->view ( 'view' );
	}

	/**
	 * 盖章确认页面
	 */
	function c_toConfirmStamp(){
		$id = $_GET['id'];

		//设置盖章基本信息
		$obj = $this->service->get_d($id);

		//获取盖章源单信息并渲染
		$newClass = $this->service->getClass($obj['contractType']);
		$initObj = new $newClass();
		$rs = $this->service->initStamp_d($obj,$initObj);
		//标题渲染
		$this->assign ( 'contractTypeCN', $this->getDataNameByCode ( $obj ['contractType'] ) );

		//扩展页面加载
// 		$this->assignFunc($rs);
// 		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expand');

		$contractType =  $this->service->getType($obj['contractType']);
		$skey = $this->md5Row($obj['contractId'],$contractType);
		switch ($obj['contractType']){
			case 'HTGZYD-01' :
				$url = '?model=contract_outsourcing_outsourcing&action=init&perm=view&actType=audit&viewBtn=0&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-02' :
				$url = '?model=contract_other_other&action=viewAlong&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-03' :
				$url = '?model=purchase_contract_purchasecontract&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-04' :
				$url = '?model=contract_contract_contract&action=init&perm=view&actType=audit&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-05' :
				$url = '?model=contract_stamp_stampapply&action=toView&hideBtn=1&id='.$obj['applyId'].'&skey='.$skey;break;
			case 'HTGZYD-06' :
				$url = '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-07' : 
				$url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;
                break;
            case 'HTGZYD-TB':// PMS 557 新增的一类盖章申请【投资申报】,只有新OA接口会生成,带出的数据是新OA的源单信息
                $url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id=122&skey='.$skey;
                break;
		}
		$this->assign('stampUrl', $url);

		//确认页面加载
		$this->assignFunc($obj);
		//数据字典渲染
//		$this->showDatadicts ( array ('stampType' => 'HTGZ' ), $obj ['stampType'] ,false);

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//盖章类型

		$this->view( 'confirmstamp');
	}

	/**
	 * 盖章确认 - 新
	 */
	function c_confirmStamp(){
     	if($this->service->confirmStamp_d ( $_POST[$this->objName] )){
			msgRf('确认成功');
     	}else{
     		msgRf('确认失败');
     	}
	}

    /**
     * 确认盖章操作 - 异步
     */
     function c_confirmedSealed(){
     	if($this->service->confirmedSealed_d ( $_POST['id'] )){
			msgRf('确认成功');
     	}else{
     		msgRf('确认失败');
     	}
     }

     /**
	 * 查看Tab中查看盖章记录
	 * 依据是从tab页传过来的合同id和合同类型
	 */
	function c_viewForContract(){
		$this->assignFunc($_GET);
        $this->assign('userId',$_SESSION['USER_ID']);
		$this->view ( 'listforcontract' );
	}
    /**
     * 页面显示
     */
    function c_toViewOnly(){
        $conditions = array(
            "id" => $_GET ['id']
        );
        $obj= $this->service->find($conditions);
        foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
        }
        $this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );
        $this->assign ( 'contractType', $this->getDataNameByCode ( $obj ['contractType'] ) );
        $this->assign ( 'status', $this->service->rtStampType_d ( $obj ['status'] ) );

        $this->view ( 'viewonly' );
    }

	/**
	 * 批量盖章
	 */
	function c_batchStamp(){
	 	$arr = $_POST['rowIds'];
	 	if($this->service->batchStamp_d( $arr )){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 	exit();
	}

	/**
	 * 检验对象是否已盖章
	 */
	function c_isStamped(){
		if($this->service->find(array('contractId' => $_POST['contractId'],'contractType' => $_POST['contractType']),'id')){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * 检查对象是否存在相同的章
	 */
	function c_checkRepeat(){
		$this->service->getParam($_POST);
		$rs = $this->service->list_d();
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * 关闭盖章功能
	 */
	function c_close(){
		$id = $_POST['id'];
		if($this->service->close_d($id)){
			echo 1;
		}else{
			echo 0;
		}
	}
	/**
	 *
	 * 检查合同是否存在盖章申请(未审批)
	 */
	function c_checkStamp(){
		$re=$this->service->checkStamp($_POST);
		if($re){
			echo 1;             //存在
		}else
			echo 0;            //不存在
	}

    /**
     * 重写获取盖章信息分页数据转成Json
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        // 记录搜索条件数组 created by huanghaojin (用于导出时加上搜索的条件)
        $search_Array = $service->searchArr;
        $search_Array['page'] = $_REQUEST['page'];
        $search_Array['pageSize'] = $_REQUEST['pageSize'];
        unset($search_Array['isSearchTag_']);
        $_SESSION['searchArr'] = $search_Array;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 导出合同盖章记录信息EXCEL created by huanghaojin 2016-10-09
     */
    function c_toExportExcel(){
        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(600);

        $exportType = isset($_REQUEST['exportType'])? $_REQUEST['exportType'] : '';
        unset($_REQUEST['exportType']);
        $colIdStr = $_REQUEST['colId'];
        $colNameStr = $_REQUEST['colName'];
        unset($_REQUEST['colId']);
        unset($_REQUEST['colName']);

        // 将记录在session内的搜索条件字段加入传入的数据中(用于导出时加上搜索的条件)
        if(is_array($_SESSION['searchArr'])){
            foreach ($_SESSION['searchArr'] as $k => $v) {
                if(!isset($_REQUEST[$k])){
                    $_REQUEST[$k] = $v;
                }
            }
        }
        $service->getParam($_REQUEST);

        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);
        $rows = array();
        if($exportType == "getAll"){//导出所有满足查询条件的数据
            $rows = $service->listBySqlId('select_default');
        }

        $datadictDao = new model_system_datadict_datadict ();
        if(is_array($rows) && !empty($rows)){
            //匹配导出列
            $dataArr = array ();
            $colIdArr = array_flip($colIdArr);
            foreach ($rows as $key => $row) {
                foreach ($colIdArr as $index => $val) {
                    if($index == 'contractType'){
                        $colIdArr[$index] = $datadictDao->getNameByCode('HTGZYD',$row[$index]);
                    }else if($index == 'status'){
                        switch ($row[$index]){
                            case '1':
                                $colIdArr[$index] = '已盖章';
                                break;
                            case '2':
                                $colIdArr[$index] = '已关闭';
                                break;
                            default:
                                $colIdArr[$index] = '未盖章';
                                break;
                        }
                    }else{
                        $colIdArr[$index] = $row[$index];
                    }
                }
                array_push($dataArr, $colIdArr);
            }
            return model_contract_stamp_stampExportUtil::contractStampExcel($colArr, $dataArr);
        }else{return false;}

    }
}