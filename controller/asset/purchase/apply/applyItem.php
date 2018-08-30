<?php
/**
 *
 * 采购申请明细表控制层类
 * @author fengxw
 *
 */
class controller_asset_purchase_apply_applyItem extends controller_base_action {

	function __construct() {
		$this->objName = "applyItem";
		$this->objPath = "asset_purchase_apply";
		parent::__construct ();
	}

	/*
	 * 跳转到采购申请明细表
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * 交付部采购物料信息
	 */
	function c_delPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//获取采购需求明细信息
		$rows=$service->getItemByApplyId($_POST['applyId'],'1','0');
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
	 * 过滤假删除采购物料信息
	 */
	function c_IsDelPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//获取采购需求明细信息
		$rows=$service->getDelItemByApplyId($_POST['applyId'],'0');
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
	 * 过滤下达数量不为null和0的数据返回json
	 */
	function c_issuedListJson() {
		$service = $this->service;
		$service->getParam ( $_POST );
		$rows = $service->findIssuedAmount ($_POST['applyId']);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 过滤假删除标志的数据返回json
	 */
	function c_DelListJson() {
		$service = $this->service;
		$rows = $service->findIsDel ($_GET['applyId']);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_preListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if( is_array($rows)&&count($rows) ){//页面显示的物料名称改为手输的物料名称（zengzx）
			foreach( $rows as $key=>$val ){
				$rows[$key]['productName']=$val['inputProductName'];
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_purchListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 编辑时获取所有数据返回json
	 */
	function c_editListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}