<?php
/**
 * @author Administrator
 * @Date 2012-09-25 09:54:07
 * @version 1.0
 * @description:销售备货控制层
 */
class controller_projectmanagent_stockup_stockup extends controller_base_action {

	function __construct() {
		$this->objName = "stockup";
		$this->objPath = "projectmanagent_stockup";
		parent::__construct ();
	 }

	/*
	 * 跳转到销售备货列表
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * 我的销售备货列表
     */
    function c_mystockupList(){
    	$this->assign("userId" , $_SESSION['USER_ID']);
        $this->view("mystockupList");
    }

   /**
	 * 跳转到新增销售备货页面
	 */
	function c_toAdd() {
	 //获取源单类型，id
	 $sourceType = isset($_GET['sourceType'])?$_GET['sourceType']:null;
	 $sourceId = isset($_GET['sourceId'])?$_GET['sourceId']:null;
	 $this->assign("sourceType",$sourceType);
	 $this->assign("sourceId",$sourceId);
	 $this->assign("type","XSBH");
     $this->view ( 'add' );
   }

   /**
	 * 新增对象操作
	 */
	function c_add() {
		$rows = $_POST [$this->objName];
		$id = $this->service->add_d ($rows);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
        //判断是否直接提交审批
		if ($id && $_GET ['act'] == "app") {
			succ_show ( 'controller/projectmanagent/stockup/ewf_stockup.php?actTo=ewfSelect&billId=' . $id );
		}else{
			msgRF ( $msg );
		}
		//$this->listDataDict();
	}

   /**
	 * 跳转到编辑销售备货页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看销售备货页面
	 */
	function c_toView() {
        $this->permCheck (); //安全校验
        $viewType = isset ( $_GET ['viewType'] ) ? $_GET ['viewType'] : "";
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('viewType',$viewType);
		$this->view ( 'view');
   }
 }
?>