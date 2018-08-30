<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:11
 * @version 1.0
 * @description:换货物料清单控制层
 */
class controller_projectmanagent_exchange_exchangeequ extends controller_base_action {

	function __construct() {
		$this->objName = "exchangeequ";
		$this->objPath = "projectmanagent_exchange";
		parent::__construct ();
	 }

	/*
	 * 跳转到换货物料清单列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增换货物料清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑换货物料清单页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看换货物料清单页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

	/**
	 * 发货列表从表数据获取
	 */
	function c_pageJson() {
		if( $_POST['ifDeal'] ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$lockDao = new model_stock_lock_lock ();
			$service = $this->service;
			$service->getParam ( $_POST );
			$this->service->searchArr['isDel']=0;
			$rows=$service->list_d();
			foreach ( $rows as $key=>$val){
				$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,'model_projectmanagent_exchange_exchange' );
				$rows[$key]['exeNum'] =  $inventoryDao->getExeNums( $rows[$key]['productId'], '1' );
			}
		}else{
			$rows = array();
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr);
	}

	/**
	 * 判断某个合同是否有产品清单
	 */
	 function c_getEquById(){
	 	$sql = "select count(*) as equNum from " . $this->service->tbl_name . " where presentId=" . $_POST['id'] . " and isDel<>1";
	 	$equNum = $this->service->_db->getArray ( $sql );
	 	echo $equNum[0]['equNum'];
	 }
   	/***************************************物料确认   start************************************************/

	/**
	 * 获取产品物料(注意：临时数据isTemp从前台传递过来)
	 */
	function c_getConEqu() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$contEqu = $service->list_d();
		echo util_jsonUtil :: encode($contEqu);
	}


	/**
	 * 获取产品下的物料信息
	 */
	function c_getProductEqu() {
		$id = $_POST['conProductId'];
		$service = $this->service;
		$equArr = $service->getProductEqu_d($id);
		if( is_array($equArr)&& count($equArr)>0 ){
			foreach( $equArr as $key=>$val ){
				$equArr[$key]['productModel'] = $val['pattern'];
				if( isset($_POST['number']) ){
					$equArr[$key]['number'] = $val['number']*($_POST['number']*1);
				}
			}
		}
		$equArr = $this->sconfig->md5Rows($equArr);
		echo util_jsonUtil :: encode($equArr);
	}

	/**
	 * 获取某个物料清单的配件信息
	 * add by zengzx
	 */
	function c_getEquByParentEquId() {
		$equs = $this->service->getEquByParentEquId_d($_POST['parentEquId']);
//		echo "<pre>";
//		print_R($equs);
		echo util_jsonUtil :: encode($equs);
	}
	/**
		 * 物料处理方法 新增
		 */
	function c_toEquAdd() {
		$this->permCheck(); //安全校验
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($_GET['id']);
		$products = $this->service->showItemView($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('add');
	}

	/**
	 * 物料处理方法 变更
	 */
	function c_toEquChange() {
		$this->permCheck(); //安全校验
		$contDao = new model_projectmanagent_exchange_exchange();
		$linkDao = new model_projectmanagent_exchange_exchangeequlink();
		$linkObj = $linkDao->get_d($_GET['linkId']);
		$obj = $contDao->getDetailInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign('ExaDTOne', $linkObj['ExaDTOne']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('change');
	}

	/**
	 * 物料处理方法 编辑
	 */
	function c_toEquEdit() {
		$this->permCheck(); //安全校验
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 物料处理方法 编辑
	 */
	function c_toEquView() {
		$this->permCheck(); //安全校验
		$linkDao = new model_projectmanagent_exchange_exchangeequlink();
		$link = $linkDao->get_d($_GET['linkId']);
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($link['exchangeId']);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $link['exchangeId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("linkId", $link['id']);
		if(!empty($_GET['changeView'])){//变更查看标志
			$this->assign("changeView", $_GET['changeView']);
		}else{
			$this->assign("changeView", '');
		}
		if(!empty($_GET['isShowDel'])){//是否显示删除物料
			$this->assign("isShowDel", $_GET['isShowDel']);
		}else{
			$this->assign("isShowDel", 'true');
		}
		$this->assign("isTemp", $link['isTemp']);
		$this->assign("originalId", $link['originalId']);
		$this->view('view');
	}

	/**
	 * 物料确认新增
	 */
	function c_equAdd($isEditInfo = true) {
		$this->permCheck(); //安全校验
		$object = $_POST['exchange'];
//		echo "<pre>";
//		print_R($object);
		if( $_GET['act'] == "audit" ){
			$id = $this->service->equAdd_d($object,true);
		}else{
			$id = $this->service->equAdd_d($object);
		}
		if ($id && $_GET['act'] == "audit") {
			msg('提交成功！该需求已转入到发货需求列表页。');
		} else{
			if ($id) {
				msg('保存成功！');
			} else {
				msg('保存失败！');
			}
		}
	}

	/**
	 * 修改物料
	 */
	function c_equEdit($isEditInfo = false) {
		//		$this->permCheck (); //安全校验
		$object = $_POST['exchange'];
		if( $_GET['act']== "audit" ){
			$flag = $this->service->equEdit_d($object,true);
		}else{
			$flag = $this->service->equEdit_d($object);

		}
		if ($flag && $_GET['act'] == "audit") {
			msg('提交成功！该需求已转入到发货需求列表页。');
		} else{
			if ($flag) {
				msg('编辑成功！');
			} else {
				msg('编辑失败！');
			}
		}
	}

	/**
	 * 变更物料
	 */
	function c_equChange($isEditInfo = false) {
		//		$this->permCheck (); //安全校验
		$rows = $_POST['exchange'];
		$id = $this->service->equChange_d($rows);
		if ($id) {
			msg('变更成功！');
		} else{
			msg('变更失败！');
		}
	}

	/**
	 * 跳转到查看物料确认tab
	 */
	function c_toViewTab() {
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('id', $_GET['id']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/***************************************物料确认   end************************************************/
	/**
	 * 物料确认 独立添加物料
	 */
	function c_getNoProductEqu(){
		$contractId = $_POST['exchangeId'];
		$this->service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$rows = $this->service->getNoProductEqu_d($contractId);
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->srocArr['equIdArr'] = $_GET['equIdArr'];
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>