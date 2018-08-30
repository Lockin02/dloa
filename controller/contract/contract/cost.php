<?php
/**
 * @author Administrator
 * @Date 2014年2月25日 16:23:44
 * @version 1.0
 * @description:合同成本概算信息控制层
 */
class controller_contract_contract_cost extends controller_base_action {

	function __construct() {
		$this->objName = "cost";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/**
	 * 跳转到合同成本概算信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增合同成本概算信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑合同成本概算信息页面
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
	 * 跳转到查看合同成本概算信息页面
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
    * 成本确认 产品线领导审核列表
    */
   function c_confirmCostAppList(){
   	  $this->view('confirmCostAppList');
   }


	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

        //成本确认权限
        $otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$costLimit = $sysLimit['成本确认审核'];
		if(strstr($costLimit, ';;')){//权限改为部门，暂无全部，预留以防扩展
       	   $rows = $service->page_d ();
        }else{
            $costLimitArr = explode(",",$costLimit);
            $costLimitStr = "";
		   foreach($costLimitArr as $k => $v){
			  $costLimitStr .= $v.",";
		   }
		   $costLimitStr = rtrim($costLimitStr, ',');
		   $service->searchArr['productLineArr'] = $costLimitStr;

			//$service->asc = false;
			$rows = $service->page_d ();
        }
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
	  * 服务成本确认领导审核 打回操作
	  */
	 function c_ajaxBack(){
		try {
//          echo $_POST ['id'];
			$this->service->ajaxBack_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
 }
?>