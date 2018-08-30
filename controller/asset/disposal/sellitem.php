<?php
    /*
	 *资产出售申请明细表控制层
	 *@linzx
	 */
class controller_asset_disposal_sellitem extends controller_base_action {
		function __construct() {
		$this->objName = "sellitem";
		$this->objPath = "asset_disposal";
		parent::__construct ();
	}

    /*
	 * 跳转到资产出售申请明细表
	 *
	 */
    function c_page() {
      $this->view('list');
    }

    /*
	 *通过select_card获取资产卡片表的字段
	 *主要为了显示	清理状态
	 */
	function c_sellCardJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->pageBySqlId ("select_assetcard");
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
