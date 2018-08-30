<?php
/**
 * @author Administrator
 * @Date 2013年10月8日 10:16:55
 * @version 1.0
 * @description:产品库存报表基本信息控制层
 */
class controller_report_report_stockinfo extends controller_base_action {

	function __construct() {
		$this->objName = "stockinfo";
		$this->objPath = "report_report";
		parent::__construct ();
	 }

	/**
	 * 跳转到产品库存报表基本信息列表
	 */
    function c_page() {

      $dataType = isset($_GET['dataType'])?$_GET['dataType']: 0 ;
	  $this->assign("dataType",$dataType);
      $this->view('list');
    }

   /**
	 * 跳转到新增产品库存报表基本信息页面
	 */
	function c_toAdd() {
	  //获取并处理 支持网络和支持软件
	  $newWorkStr = $this->service->getNetWorkStr();
	  $this->assign("newWorkStr",$newWorkStr);
	  $softWareStr = $this->service->getsoftWareStr();
	  $this->assign("softWareStr",$softWareStr);

	  $dataType = isset($_GET['dataType'])?$_GET['dataType']: 0 ;
	  $this->assign("dataType",$dataType);

      $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑产品库存报表基本信息页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

	  //获取并处理 支持网络和支持软件
	  $newWorkStr = $this->service->getNetWorkStr($obj);
	  $this->assign("newWorkStr",$newWorkStr);
	  $softWareStr = $this->service->getsoftWareStr($obj);
	  $this->assign("softWareStr",$softWareStr);
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看产品库存报表基本信息页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

     /**
      * 产品库存报表
      */
    function c_reportView(){
    	$object = array(
			'budgetTypeName' => '',
			'budgetTypeId' => '',
			'brand' => '',
			'equName' => '',
			'equId' => '',
			'netWork' => '',
			'software' => '',
			'isStop' => ''
		);
		$object = isset($_GET['isSearch']) ? $_GET : $object;
		$this->assignFunc($object);

		$dataType = isset($_GET['dataType'])?$_GET['dataType']: 0 ;
	  $this->assign("dataType",$dataType);
    	$this->view("reportView");
    }
    //高级搜索
    function c_listinfoSearch(){
//     	print_r($_GET);
//     	die();
    	$newWorkStr = $this->service->getNetWorkStr();
    	$this->assign("newWorkStr",$newWorkStr);
    	$softWareStr = $this->service->getsoftWareStr();
    	$this->assign("softWareStr",$softWareStr);
		$this->assignFunc( $_GET );
		$this->assign("dataType",$_GET['dataType']);
		$this->view('listinfosearch');
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
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
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * json
	 */

	/**
	 * 获取分页数据转成Json
	 */
	function c_stockJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$service->__SET('groupBy', "c.id");
		$service->sort = "budgetTypeName";
		$rows = $service->pageBySqlId('select_gridinfo');
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
	 * 需求数量明细 tab
	 */
	function c_numViewTab(){
		$cids = $_GET['cids'];
		$bids = $_GET['bids'];
		$pids = $_GET['pids'];
		$this->assign("cids",$cids);
		$this->assign("bids",$bids);
		$this->assign("pids",$pids);
         $this->view("view-tab");
	}

	//合同需求列表
	function c_conViewList(){
		$cids = $_GET['cids'];
		$this->assign("cids",$cids);
		$this->view("conViewList");
	}
	//借用需求列表
	function c_borrowViewList(){
		$bids = $_GET['bids'];
		$this->assign("bids",$bids);
		$this->view("borrowViewList");
	}
	//赠送需求列表
	function c_preViewList(){
		$pids = $_GET['pids'];
		$this->assign("pids",$pids);
		$this->view("preViewList");
	}

 }
?>