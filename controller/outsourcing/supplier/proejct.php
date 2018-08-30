<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:11:22
 * @version 1.0
 * @description:供应商项目信息控制层
 */
class controller_outsourcing_supplier_proejct extends controller_base_action {

	function __construct() {
		$this->objName = "proejct";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * 跳转到供应商项目信息列表
	 */
    function c_page() {
      $this->view('list');
    }

    	/**
	 * 跳转到供应商项目信息列表
	 */
    function c_toEditList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('edit-list');
    }

    	/**
	 * 跳转到供应商项目信息列表
	 */
    function c_toViewList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('view-list');
    }

   /**
	 * 跳转到新增供应商项目信息页面
	 */
	function c_toAdd() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 //获取供应商信息
		 $basicinfoDao=new model_outsourcing_supplier_basicinfo();
		 $suppInfo=$basicinfoDao->get_d($suppId);
		 $this->assign('suppId',$suppId);
		 $this->assign('suppCode',$suppInfo['suppCode']);
		 $this->assign('suppName',$suppInfo['suppName']);
     	$this->view ( 'add' );
   }

   /**
	 * 跳转到编辑供应商项目信息页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看供应商项目信息页面
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
	 * 获取分页数据转成Json
	 */
	function c_pageJsonProject() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ("select_project");
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
 }
?>