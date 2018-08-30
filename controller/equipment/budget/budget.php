<?php
/**
 * @author Administrator
 * @Date 2012-10-31 09:22:20
 * @version 1.0
 * @description:设备预算表控制层
 */
class controller_equipment_budget_budget extends controller_base_action {

	function __construct() {
		$this->objName = "budget";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * 跳转到设备预算表列表
	 */
    function c_page() {
      $this->view('list');
    }
    /**
     * 我的预算表
     */
    function c_mybudget() {
      $this->assign("userId",$_SESSION['USER_ID']);
      $this->view('mybudgetList');
    }

   /**
    * 填写预算表
    */
   function c_addBudget(){
//      $this->view('addBudget');
      $this->c_toAdd();
   }

   /**
	 * 跳转到新增设备预算表页面
	 */
	function c_toAdd() {
		$equId = $_GET ['equId'];
	  if(!empty($equId)){
	  	 $baseDao = new model_equipment_budget_budgetbaseinfo();
         $baseinfo = $baseDao->get_d($equId);
	  }else{
	  	 $baseinfo = array("budgetTypeName"=>"","equName"=>"","remark"=>"");
	  }

        foreach ( $baseinfo as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$list = $this->service->deployList($equId);
//		$this->assign("list",$list);
     //说明
     //获取文件内容
    	$url =  WEB_TOR . 'view/template/equipment/budget/budgetExplain.txt';
    	if($url){
			$fileSize = filesize($url);
			if($file = fopen($url ,'r')){
				$str = stripslashes(fread($file,$fileSize));
			}else{
				$str = "找不到文件";
			}
			fclose($file);
    	}else{
			$str = "不存在的类型";
    	}
    	$str = nl2br($str);
    	$this->assign('explain',$str);

     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑设备预算表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$list = $this->service->deployEditList($_GET ['id']);
//		$this->assign("list",$list);
	    $this->view ( 'edit');
   }

   /**
	 * 跳转到查看设备预算表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$list = $this->service->deployViewList($_GET ['id']);
//		$this->assign("list",$list);
		$this->view ( 'view' );
   }


	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msgGo ( $msg ,'?model=equipment_budget_budget&action=addBudget');
		}
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
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

    /**
     * 预算表说明维护
     */
    function c_explain(){
    	//获取文件内容
    	$url =  WEB_TOR . 'view/template/equipment/budget/budgetExplain.txt';
    	if($url){
			$fileSize = filesize($url);
			if($file = fopen($url ,'r')){
				$str = stripslashes(fread($file,$fileSize));
			}else{
				$str = "找不到文件";
			}
			fclose($file);
    	}else{
			$str = "不存在的类型";
    	}
    	$this->assign('content',$str);
       $this->view("explain");
    }

    function c_editExplain(){
      $object = $_POST ['explain'];
      $this->service->editExplain_d($object);
      msgGo("修改成功", '?model=equipment_budget_budget&action=explain');
    }

    /**
     * 从表数据
     */
    function c_baseinfoList(){
    	 $ids = isset ($_POST['ids']) ? addslashes($_POST['ids']) : "";
    	 $type = isset($_POST['type']) ? addslashes($_POST['type']) : "";
         $listHTML = $this->service->baseinfoList_d($ids,$type);
//         echo $listHTML;
         echo util_jsonUtil :: iconvGB2UTF($listHTML);
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

		foreach($rows as $key => $val){
           $useEndDate = $service->getUseEndDate($val['id']);
           $rows[$key]['useEndDate']  = $useEndDate;
		}

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