<?php
/**
 *
 * 余额控制层类
 * @author fengxw
 *
 */
class controller_asset_balance_balance extends controller_base_action {

	function __construct() {
		$this->objName = "balance";
		$this->objPath = "asset_balance";
		parent::__construct ();
	}

	/*
	 * 跳转到资产折旧
	 */
    function c_page() {
	  $this->assign('assetId',$_GET['assetId']);
      $this->view("list");
    }

    /**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->assign('flag',$_GET['flag']);
		$this->assign('deprTime', date("Y-m-d"));
		$this->view ( 'add' );
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

		$assetCode = isset ($_GET['assetCode']) ? $_GET['assetCode'] : null;
		$this->assign('assetCode',$assetCode);
		$assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName',$assetName);
//		$origina = isset ($_GET['origina']) ? $_GET['origina'] : null;
//		$this->assign('origina',$origina);
//		$netValue = isset ($_GET['netValue']) ? $_GET['netValue'] : null;
//		$this->assign('netValue',$netValue);

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if($id){
			if($_POST['flag']){
				msg('资产折旧成功');
			}else{
				msgGo('资产折旧成功');
			}
		}else{
			msgGo('资产折旧失败');
		}
		//$this->listDataDict();
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->pageBySqlId ('select_balance_assetcard');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}

?>