<?php
/**
 * @author Administrator
 * @Date 2012年10月7日 10:36:31
 * @version 1.0
 * @description:卡片新增临时表控制层
 */
class controller_asset_assetcard_assetTemp extends controller_base_action {

	function __construct() {
		$this->objName = "assetTemp";
		$this->objPath = "asset_assetcard";
		parent::__construct ();
	 }

	/*
	 * 跳转到卡片新增临时表列表
	 */
    function c_page() {
      $this->view('list');
    }

	/*
	 * 跳转到卡片财务确认列表页--确认属性
	 */
    function c_pageToFinancial() {
      $this->view('listtofinancial');
    }

	/*
	 * 跳转到卡片行政确认列表页--确认属性
	 */
    function c_pageToAdmin() {
      $this->view('listtoadmin');
    }

	/**
	 * 获取分页数据转成Json
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
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 获取分页数据转成Json
	 */
	function c_adminJson() {
		$service = $this->service;
		$agencyDao = new model_asset_basic_agency();
		$agencyRows = $agencyDao->getByChargeId($_SESSION['USER_ID']);
		if(is_array($agencyRows)&&count($agencyRows)>0){
			$chargeIdArr = array();
			foreach($agencyRows as $key=>$val){
				$agencyCodeArr[]=$val['agencyCode'];
			}
			$agencyCodeStr = implode($agencyCodeArr);
		}
		if(!$agencyCodeStr){
			$agencyCodeStr='999999999';
		}
		$service->getParam ( $_REQUEST );
		$service->searchArr['agencyCodeArr']=$agencyCodeStr;
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
		echo util_jsonUtil::encode ( $arr );
	}

   /**
	 * 跳转到新增卡片新增临时表页面
	 */
	function c_toAdd() {
		//获取基础数据信息
		$assetDao = new model_asset_assetcard_assetcard();
		$option = $assetDao->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);//资产类型
		$this->assign('chnOption',$option['chnOption']);//变动方式
		$this->assign('deprOption',$option['deprOption']);//折旧方式
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//使用状态 -- 数据字典
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ) );//使用状态 -- 数据字典


		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_asset_card_temp_admin']['TO_ID'];
		$mailName = $mailUser['oa_asset_card_temp_admin']['TO_NAME'];
		$this->assign('TO_ID',$mailId);
		$this->assign('TO_NAME',$mailName);

        $assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName',$assetName);
        $spec = isset ($_GET['spec']) ? $_GET['spec'] : null;
		$this->assign('spec',$spec);
        $origina = isset ($_GET['origina']) ? $_GET['origina'] : 0;
		$this->assign('origina',$origina);

        $num = isset ($_GET['num']) ? $_GET['num'] : null;
		$this->assign('number',$num);

        $isPurch = isset ($_GET['isPurch']) ? $_GET['isPurch'] : null;
		$this->assign('isPurch',$isPurch);

        $receiveItemId = isset ($_GET['receiveItemId']) ? $_GET['receiveItemId'] : null;
		$this->assign('receiveItemId',$receiveItemId);

		$this->assign('companyCode',$_SESSION['COM_BRN_PT']);
		$this->assign('companyName',$_SESSION['COM_BRN_CN']);
		
		/******************物料转资产信息*******************/
		//申请id
		$requireinId = isset ($_GET['requireinId']) ? $_GET['requireinId'] : null;
		$this->assign('requireinId',$requireinId);
		//申请明细id
		$requireinItemId = isset ($_GET['requireinItemId']) ? $_GET['requireinItemId'] : null;
		$this->assign('requireinItemId',$requireinItemId);
		$this->assign('maxNum',$num);
		//物料信息
		$productId = isset ($_GET['productId']) ? $_GET['productId'] : null;
		$this->assign('productId',$productId);
		$productCode = isset ($_GET['productCode']) ? $_GET['productCode'] : null;
		$this->assign('productCode',$productCode);
		$productName = isset ($_GET['productName']) ? $_GET['productName'] : null;
		$this->assign('productName',$productName);
		//品牌
		$brand = isset ($_GET['brand']) ? $_GET['brand'] : null;
		$this->assign('brand',$brand);

		$this->view ( 'add' );
	}

   /**
	 * 跳转到新增卡片新增临时表页面
	 */
	function c_toAddByPurch() {
		//获取基础数据信息
		$assetDao = new model_asset_assetcard_assetcard();
		$option = $assetDao->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);//资产类型
		$this->assign('chnOption',$option['chnOption']);//变动方式
		$this->assign('deprOption',$option['deprOption']);//折旧方式
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//使用状态 -- 数据字典
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ) );//使用状态 -- 数据字典


        $price = isset ($_GET['price']) ? $_GET['price'] : null;
		$this->assign('price',$price);
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


		$this->view ( 'addbypurch' );
	}

   /**
	 * 跳转到编辑卡片新增临时表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看卡片新增临时表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }


   /**
	 * 跳转到查看卡片新增临时表页面
	 */
	function c_toType() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_asset_card_temp_finance']['TO_ID'];
		$mailName = $mailUser['oa_asset_card_temp_finance']['TO_NAME'];
		$this->assign('TO_ID',$mailId);
		$this->assign('TO_NAME',$mailName);

		$this->view ( 'type' );
   }

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$obj = $_POST [$this->objName];
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign('propertyName',$obj['property']==1?"低值耐用品":"固定资产");
			$this->display ( 'view' );
		} else {
			$assetDao = new model_asset_assetcard_assetcard();
			$option = $assetDao->getBaseDate_d();
			$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//资产来源 -- 数据字典
			$this->assign('dirOption',$option['dirOption']);
			$this->assign('chnOption',$option['chnOption']);
			$this->assign('deprOption',$option['deprOption']);
			$this->view ( 'edit' );
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
//		echo "<pre>";
//		print_R($object);
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}
	
	/**
	 * 更新卡片编号
	 */
	function c_updateAssetCode(){
		$this->service->updateAssetCode_d();
	}
	//转到资产卡片记录导入
	function c_toImport(){
		$this->view( 'import' );
	}
	//资产卡片记录导入
	function c_import(){
		$resultArr = $this->service->import_d();
		$title = '资产卡片记录导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
 }