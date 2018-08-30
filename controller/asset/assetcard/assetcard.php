<?php
/**
 * @author Administrator
 * @Date 2011年11月16日 14:35:53
 * @version 1.0
 * @description:固定资产卡片控制层
 */
class controller_asset_assetcard_assetcard extends controller_base_action {

	function __construct() {
		$this->objName = "assetcard";
		$this->objPath = "asset_assetcard";
		parent::__construct ();
	 }

	/**
	 * 跳转到固定资产卡片
	 */
    function c_page() {
      	$this->view('list');
    }

	/**
	 * 跳转到个人固定资产卡片
	 */
    function c_mypage() {
    	$this->assign('userId',$_SESSION['USER_ID']);
    	$this->assign('userName',$_SESSION['USERNAME']);
      	$this->view('mylist');
    }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );

		//资产卡片-管理列表
		if(!isset($_REQUEST['currentId']) && !isset($_REQUEST['userId'])){
			//获取区域权限字符串
			$agencyStr = $service->getAgencyStr_d();
			if(!empty($agencyStr)){//区域权限若为空则不显示数据
				if($agencyStr != ';;'){//区域权限不为全部
					//拼装自定义sql
					$sqlStr = "sql: and c.agencyCode in (".$agencyStr.")";
					$service->searchArr['agencyCondition'] = $sqlStr;
				}
				$rows = $service->pageBySqlId ('select_list');
			}		
		}else{//资产卡片-个人资产列表
			$rows = $service->pageBySqlId ('select_list');
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['listSql'] = $service->listSql;
		session_start();
		$_SESSION['listSql']=$service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 获取分页数据转成Json
	 */
	function c_selectPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$service->groupBy = 'assetCode';
		$rows = $service->pageBySqlId ('select_list');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['listSql'] = $service->listSql;
		session_start();
		$_SESSION['listSql']=$service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到固定资产卡片
	 */
    function c_pageByPro() {
		$projectId = isset ($_GET['projectId']) ? $_GET['projectId'] : "";
		$this->assign('projectId',$projectId);
		$this->display('listbypro');
    }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageByProJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$this->service->searchArr['hasUseProId'] = 1;
		$service->groupBy = 'assetCode';
		$rows = $service->pageBySqlId ('select_list');
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
	 * 跳转到固定资产卡片 --  根据productId过滤
	 */
    function c_pageByProduct() {
		$productId = isset ($_GET['productId']) ? $_GET['productId'] : "";
		$this->assign('productId',$productId);
		$this->display('listbyproduct');
    }

	/**
	 * 跳转到固定资产卡片
	 */
    function c_pageToView() {
		$this->view('list-view');
    }

	/**
	 * 跳转到新增页面
	 */
	function c_toCreat() {
		$tempDao = new model_asset_assetcard_assetTemp();
		$tempInfo = $tempDao->get_d($_GET['id']);
		//获取基础数据信息
		$option = $this->service->getBaseDate_d();
		$this->assign('tempId',$_GET['id']);//资产类型
		$this->assign('dirOption',$option['dirOption']);//资产类型
		$this->assign('chnOption',$option['chnOption']);//变动方式
		$this->assign('deprOption',$option['deprOption']);//折旧方式
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//使用状态 -- 数据字典

		foreach ( $tempInfo as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$tempInfo['assetSource'] );//资产来源 -- 数据字典


		$this->view ( 'update' );
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		//获取基础数据信息
		$option = $this->service->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);//资产类型
		$this->assign('chnOption',$option['chnOption']);//变动方式
		$this->assign('deprOption',$option['deprOption']);//折旧方式
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//使用状态 -- 数据字典
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ) );//使用状态 -- 数据字典


        $assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName',$assetName);
        $spec = isset ($_GET['spec']) ? $_GET['spec'] : null;
		$this->assign('spec',$spec);
        $origina = isset ($_GET['origina']) ? $_GET['origina'] : null;
		$this->assign('origina',$origina);

        $num = isset ($_GET['num']) ? $_GET['num'] : null;
		$this->assign('number',$num);

        $receiveItemId = isset ($_GET['receiveItemId']) ? $_GET['receiveItemId'] : null;
		$this->assign('receiveItemId',$receiveItemId);
		$this->view ( 'add' );
	}
	
	/**
	 * 新增对象操作
	 */
	function c_add() {
//		echo $_POST [$this->objName][num];
		if($_POST [$this->objName]['num']!=""){
			$this->service->addCard_d($_POST [$this->objName]);
			msg ( '成功生成资产卡片！' );
		}else{
			$id = $this->service->add_d ( $_POST [$this->objName], true );
		}
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
		//$this->listDataDict();
	}

	/**
	 * 新增对象操作
	 */
	function c_addBeach() {
//		echo "<pre>";
//		print_R($_POST [$this->objName]);
		if($_POST [$this->objName]['num']!=""){
			$this->service->addCard_d($_POST [$this->objName]);
			msg ( '成功生成资产卡片！' );
		}else{
			$id = $this->service->addBeach_d ( $_POST [$this->objName], true );
		}
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
		//$this->listDataDict();
	}

	/**
	 * 批量更新卡片
	 */
	function c_updateBeach() {
//		echo "<pre>";
//		print_R($_POST [$this->objName]);
		$flag = $this->service->updateBeach_d($_POST [$this->objName]);
//		$id = $this->service->addBeach_d ( $_POST [$this->objName], true );
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '添加成功！';
		$tempId = $_POST [$this->objName]['tempId'];
		if($tempId){
			$tempDao = new model_asset_assetcard_assetTemp();
		 	$statusInfo = array(
		 		'id' => $_POST [$this->objName]['tempId'],
		 		'isFinancial' => 1,
		 	);
		 	$tempDao->updateById( $statusInfo );
		}
		if ($flag) {
			msg ( $msg );
		}
		//$this->listDataDict();
	}
	
	/**
	 * 新增对象操作
	 */
	function c_createBeach() {
//		echo "<pre>";
//		print_R($_POST [$this->objName]);
		$id = $this->service->addBeach_d ( $_POST [$this->objName], true );
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '添加成功！';
		$tempId = $_POST [$this->objName]['tempId'];
		if($tempId){
			$tempDao = new model_asset_assetcard_assetTemp();
		 	$statusInfo = array(
		 		'id' => $_POST [$this->objName]['tempId'],
		 		'isCreate' => 1,
		 	);
		 	$tempDao->updateById( $statusInfo );
		}
		if ($id) {
			msg ( $msg );
		}
		//$this->listDataDict();
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$option = $this->service->getBaseDate_d();
			$this->showDatadicts ( array ('useOption' => 'SYZT' ),$obj['useStatusCode'] );
			$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//资产来源 -- 数据字典
			$this->assign('dirOption',$option['dirOption']);
			$this->assign('chnOption',$option['chnOption']);
			$this->assign('deprOption',$option['deprOption']);
			$this->view ( 'edit' );
		}
	}

	/**
	 * 初始化对象
	 */
	function c_toEditByAdmin() {
		$this->permCheck (); //安全校验
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($obj['property']=='0'){
			$this->assign('assetProperty','固定资产');
		}else{
			$this->assign('assetProperty','低值耐用品');
		}
		$option = $service->getBaseDate_d();
		$this->showDatadicts ( array ('useOption' => 'SYZT' ),$obj['useStatusCode'] );
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//资产来源 -- 数据字典
		$this->assign('agencyLimit',$service->this_limit['区域变动权限']);//控制操作员是否可以对行政区域进行变动
		$this->assign('dirOption',$option['dirOption']);
		$this->assign('chnOption',$option['chnOption']);
		$this->assign('deprOption',$option['deprOption']);
		$this->view ( 'editbyadmin' );
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '编辑成功！';
			msg ( $msg );
		}
	}

	/**
	 * 初始化对象
	 */
	function c_toViewDetail() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$dailyDao = new model_asset_daily_dailyCommon();
		$changeDao = new model_asset_change_assetchange();
		$changeInfo = $changeDao->getChangeInfoByAssetId($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch ($changeInfo['businessType']) {
			case 'allocation' :
				$this->assign ( 'relDocTypeName', '调拨单' );
				break;
			case 'borrow' :
				$this->assign ( 'relDocTypeName', '借用单' );
				break;
			case 'return' :
				$this->assign ( 'relDocTypeName', '归还单' );
				break;
			case 'rent' :
				$this->assign ( 'relDocTypeName', '租用单' );
				break;
			case 'keep' :
				$this->assign ( 'relDocTypeName', '维保单' );
				break;
			case 'lose' :
				$this->assign ( 'relDocTypeName', '遗失单' );
				break;
			case 'charge' :
				$this->assign ( 'relDocTypeName', '领用单' );
				break;
			default :
				break;
		}
		$businessCodeArr = $dailyDao->find(array('changeCode'=>$changeInfo['businessType']),null,'businessCode');
		$businessCode = $businessCodeArr['businessCode'];
		$className = $dailyDao->relatedClassNameArr[$businessCode];
		$this->assign('businessType',$className);
		$this->assign('businessId',$changeInfo['businessId']);
		$this->view ( 'viewdetails' );
	}
	
	/**
	 * 变动跳转页面
	 */
	function c_tochange(){
		$this->permCheck (); //安全校验
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		//版本号+1
		$obj['version']=$obj['version']*1+1;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//获取基础信息
		$option = $service->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);
		$this->assign('chnOption',$option['chnOption']);
		$this->assign('deprOption',$option['deprOption']);
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//资产来源 -- 数据字典
		$this->showDatadicts ( array ('useOption' => 'SYZT' ),$obj['useStatusCode'] );
		$this->assign('agencyLimit',$service->this_limit['区域变动权限']);//控制操作员是否可以对行政区域进行变动
		$this->view ( 'change' );
	}

	/**
	 * 变动对象
	 */
	function c_change($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->change_d ( $object, $isEditInfo )) {
			msg ( '变动成功！' );
		}
	}

	/**
	 * 跳转到选择固定资产单选页面
	 */
	function c_selectAsset(){
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}
	
	/**
	 * 跳转到选择固定资产多选页面
	 */
	function c_selectCard(){
		$this->assign ( 'showType', $_GET ['showType'] );
		$this->assign ( 'agencyCode', $_GET ['agencyCode'] );
		$this->assign ( 'deptId', $_GET ['deptId'] );
		
		$this->view ( "selectCard" );
	}
	
	/**
	 * 是否关联业务
	 */
	 function c_isRelated(){
        $assetId = isset ($_POST['id']) ? $_POST['id'] : null;
        $this->service->isRelated_d($assetId);
	 }
	 
	/****************************************导入导出部分****************************************/
	/**
	 * 跳转到资产信息导入页面 ---初始化信息
	 * @create 2012年7月9日 09:51:04
	 * @author zengzx
	 */
    function c_toOldImport() {
      $this->view( 'oldimport' );
    }

	/**
	 * 跳转到资产信息导入页面
	 * @create 2012年1月30日 14:32:59
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * 资产卡片信息导入
	 * @create 2012年1月30日 10:08:59
	 * @author zengzx
	 */
	function c_import(){
		$this->service->import_d();
	}

	/**
	 * 资产卡片信息导入
	 * @create 2012年1月30日 10:08:59
	 * @author zengzx
	 */
	function c_oldImport(){
		$objKeyArr = array (
			0 => 'serial',//序号
			1 => 'assetCode',//卡片编号
			2 => 'brand',//品牌
			3 => 'spec',//型号
			4 => 'assetName',//名称
			5 => 'machineCode',//机身码
			6 => 'unit',//单位
			7 => 'number',//数量
			8 => 'origina',//数量
			9 => 'buyDate',//购置日期
			10 => 'belongMan',//所属人
			11 => 'orgName',//所属部门
			12 => 'temp',//配件
			13 => 'remark',//备注
			14 => 'assetTypeName',//资产类型
			15 => 'agencyName'//办事处
		); //字段数组
		$this->service->oldImport_d ( $objKeyArr );
	}

	function c_checkAsset(){
		$code = $_POST['assetCode'];
		if($code){
			echo $this->service->checkAsset_d($code);
		}else{
			echo 0;
		}
	}
	
	/**
	 * 导出卡片信息
	 */
	function c_exportExcel() {
		set_time_limit(0);	//执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        ini_set('memory_limit', '1024M');	//设置内存
        $rows = $this->service->_db->getArray($_SESSION['listSql']);

        if (empty($rows)) exit(util_jsonUtil::iconvGB2UTF('没有可以导出的内容'));

		//表头Id数组
		$colIdArr = array_filter(explode(',', $_GET['colId']));

		//表头Name数组
		if(util_jsonUtil::is_utf8($_GET['colName'])){
			$colNameArr = explode(',', util_jsonUtil::iconvUTF2GB($_GET['colName']));
		}else{
			$colNameArr = explode(',', $_GET['colName']);
		}
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);

		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
        unset($rows);
        unset($colIdArr);
		foreach ($dataArr as $key => $val) {
			if ($val['property'] == 0) {
				$dataArr[$key]['property'] = "固定资产";
			}else{
				$dataArr[$key]['property'] = "低值耐用品";
			}
		}
		return model_asset_assetcard_assetExcelUtil :: export2ExcelUtil($colArr, $dataArr);
	}
	
	/**
	 * 导出卡片信息(CSV)
	 */
	function c_exportCSV() {
		set_time_limit(0);	//执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		$rows = $this->service->_db->getArray($_SESSION['listSql']);
	
		if (empty($rows)) exit(util_jsonUtil::iconvGB2UTF('没有可以导出的内容'));
	
		//表头Id数组
		$colIdArr = array_filter(explode(',', $_GET['colId']));
	
		//表头Name数组
		if(util_jsonUtil::is_utf8($_GET['colName'])){
			$colNameArr = explode(',', util_jsonUtil::iconvUTF2GB($_GET['colName']));
		}else{
			$colNameArr = explode(',', $_GET['colName']);
		}
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
	
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		unset($rows);
		unset($colIdArr);
		foreach ($dataArr as $key => $val) {
			if ($val['property'] == 0) {
				$dataArr[$key]['property'] = "固定资产";
			}else{
				$dataArr[$key]['property'] = "低值耐用品";
			}
		}
		return model_asset_assetcard_assetExcelUtil::exportCSV($colArr, $dataArr, '卡片信息');
	}
	
	/**
	 * 导出盘点信息
	 */
	function c_exportCheckExcel(){
		set_time_limit(0);	//执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		ini_set('memory_limit', '1024M');	//设置内存
	
		$service = $this->service;
		$service->sort = 'c.agencyName,c.createTime';
		$rows = $this->service->_db->getArray($_SESSION['listSql']);
		return model_asset_assetcard_assetExcelUtil::exportCheckExcel ( $rows );
	}
	
	/**
	 * 导出盘点信息(CSV)
	 */
	function c_exportCheckCSV() {
		set_time_limit(0);	//执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
			
		$service = $this->service;
		$service->sort = 'c.agencyName,c.createTime';
		$rows = $this->service->_db->getArray($_SESSION['listSql']);	
		return model_asset_assetcard_assetExcelUtil::exportCSV(array(
				'assetCode' => '资产编号', 'assetName' => '资产名称', 'machineCode' => '机身号', 'userName' => '使用人',
				'useOrgName' => '使用部门', 'brand' => '品牌', 'spec' => '型号', 'deploy' => '配置',
				'belongMan' => '所属人', 'orgName' => '所属部门', 'agencyName' => '所属区域', 'wirteDate' => '入账日期',
				'origina' => '原值', 'remark' => '备注'
		), $rows, '固定资产盘点表');
	}
	
	/**
	 * 财务金额导入权限
	 */
    function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * 获取合同付款条件
	 */
	function c_selectPayCon(){
		$this->assign('checkIds',$_GET['checkIds']);
		//选择模式
		$modeType = isset($_GET['modeType']) ? $_GET['modeType'] : 0;
		$this->assign('modeType',$modeType);

		$this->view('selectlist');
	}
	
	/**
	 * 转到资产卡片更新所属人
	 */
	function c_toUpdateBelongMan() {
		$this->view( 'updateBelongMan' );
	}
	
	/**
	 * 资产卡片更新所属人
	 */
	function c_updateBelongMan() {
		$resultArr = $this->service->updateBelongMan_d();
		$title = '资产卡片记录导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	
	/**
	 * 转到报废卡片库
	 */
	function c_scrapStock(){
		$this->view('scrap-stock');
	}
	
	/**
	 * 获取所有数据返回json
	 */
	function c_getScrapListJson() {
		$assetIdArr = $_GET['assetIdArr'];
		$idArr = explode(",",$assetIdArr);
		$cardInfos = $this->service->getCardsByIdArr($idArr,true);
		echo util_jsonUtil::encode ( $cardInfos );
	}
	
	/**
	 * 检查卡片状态
	 */
	function c_checkCardStatus() {
		$idArr = $_POST['assetIdArr'];
		$cardInfos = $this->service->getCardsByIdArr($idArr,true);
		$cardsUsed = array();
		foreach ($cardInfos as $key => $val){
			if($val['useStatusCode'] != 'SYZT-XZ'){
				array_push($cardsUsed, $val['assetCode']);
			}
		}
		echo util_jsonUtil::encode ( $cardsUsed );
	}
	
	/**
	 * 转到批量更新资产卡片
	 */
	function c_toUpdateCard() {
		$this->view( 'updateCard' );
	}
	
	/**
	 * 批量更新资产卡片
	 */
	function c_updateCard() {
		if($this->service->updateCard_d($_POST[$this->objName])){
            msg('更新成功');
        }else{
        	msg('无任何更新');
        }
	}
	
	/**
	 * 获取需要批量更新的卡片信息
	 */
	function c_getUpdateData(){
		echo util_jsonUtil::iconvGB2UTF($this->service->getUpdateData_d($_POST));
	}
	
	/**
	 * 跳转到修改财务数据页面
	 * 目前只修改原值、预计使用期间数
	 */
	function c_toEditfinancial(){
		$this->permCheck(); //安全校验
		$id = $_GET ['id'];
		$obj = $this->service->get_d($id);
		$this->assign('id',$id);
		$this->assign('origina',$obj['origina']);
		$this->assign('estimateDay',$obj['estimateDay']);

		$this->view('editfinancial');
	}
	
	/**
	 * 修改财务数据
	 */
	function c_editfinancial(){
		$object = $_POST [$this->objName];
		if($this->service->updateById($object,true)){
			$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '编辑成功！';
			msg ( $msg );
		}
	}
	
	/**
	 *下拉资产名称表格组件Json
	 */
	function c_comboAssetInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//加载类型
		$searchType = $_REQUEST['searchType'];
		$service->searchArr['comboAssetInfo'] = "sql: and ".$searchType." <> '' and ".$searchType." is not null";
		$service->groupBy = $searchType;
		
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
		echo util_jsonUtil::encode ( $arr );
	}
	
	/**
	 *根据区域权限获取区域信息
	 */
	function c_getAgency() {
		$agencyDao = new model_asset_basic_agency();
		//获取区域权限字符串
		$agencyStr = $this->service->getAgencyStr_d();
		if(!empty($agencyStr)){
			if($agencyStr != ';;'){//区域权限不为全部
				$sql = "SELECT agencyCode,agencyName FROM oa_asset_agency WHERE agencyCode IN($agencyStr)";
				$rows = $agencyDao->listBySql ($sql);
			}else{
				$rows = $agencyDao->list_d ();
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ($rows);
		echo util_jsonUtil::encode ($rows);
	}
	
	/**
	 *根据区域权限获取区域编号字符串
	 */
	function c_getAgencyStr() {
		$agencyStr = $this->service->getAgencyStr_d();
		echo $agencyStr;
	}
	
	/****************************************清理低值耐用品业务部分****************************************/
	/**
	 * 跳转到待清理低值耐用品页面
	 */
	function c_toCleanLowValueGoods(){
		$this->view('cleanlowvaluegoods');
	}
	
	/**
	 * 查询待清理低值耐用品
	 */
	function c_searchCleanLowValueGoods(){
		set_time_limit(0);
		$service = $this->service;
		$rows = $service->searchCleanLowValueGoods_d ($_POST);
		//这里转html
		$rows = $service->serachHtml_d($rows);
		echo util_jsonUtil::iconvGB2UTF ( $rows );
	}
	
	/**
	 * ajax方式清理低值耐用品
	 */
	function c_ajaxCleanLowValueGoods() {
		$ids =  $_POST['id'];
		if($this->service->cleanLowValueGoods_d($ids)){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * 跳转到已清理低值耐用品页面
	 */
	function c_cleanedLowValueGoods(){
		$this->view('cleanedlowvaluegoods');
	}
	
	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonOther() {
		$service = $this->service;
	
		$service->getParam ( $_REQUEST );
		$rows = $service->pageBySqlId ('select_other');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
	
	/**
	 * 归还资产时,验证卡片是否已提交过归还
	 */
	function c_isReturning(){
		if($this->service->isReturning_d($_POST['id'],$_SESSION['USER_ID'])){
			echo 1;
		}else{
			echo 0;
		}
	}
 }