<?php
/**
 * @author ACan
 * @Date 2016年6月14日 14:51:41
 * @version 1.0
 * @description:供应商曾用名控制层 
 */
class controller_supplierManage_formal_usedname extends controller_base_action {

	function __construct() {
		$this->objName = "usedname";
		$this->objPath = "supplierManage_formal";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到供应商曾用名列表
	 */
    function c_page() {
        $this->show->assign ( 'suppId', $_GET ['suppId'] );
      $this->view('list');
    }
    
   /**
	 * 跳转到新增供应商曾用名页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑供应商曾用名页面
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
	 * 跳转到查看供应商曾用名页面
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
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
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
 }
?>