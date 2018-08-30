<?php
/**
 * @author huangzf
 * @Date 2011年11月1日 11:21:38
 * @version 1.0
 * @description:操作日志控制层
 */
class controller_syslog_operation_logoperation extends controller_base_action {

	function __construct() {
		$this->objName = "logoperation";
		$this->objPath = "syslog_operation";
		parent::__construct ();
	}

	/*
	 * 跳转到操作日志
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 *
	 * 通过业务信息查看操作日志记录页面
	 */
	function c_businessView() {
		$pkValue = $_GET ['pkValue'] ? $_GET ['pkValue'] : null;
		$tableName = $_GET ['tableName'] ? $_GET ['tableName'] : null;

		$this->service->searchArr = array ("tableName" => $tableName, "pkValue" => $pkValue );
		$rows = $this->service->listBySqlId ( "select_detail" );


//		foreach ( $rows [0] as $key => $val ) {
//			$this->assign ( $key, $val );
//		}

		$this->assign("logList", $this->service->showAtBusinessView($rows));
		$this->view ( "business-view" );
	}

	/**
	 *
	 * 获取操作日志详细信息
	 */
	function c_pageDetailJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ( "select_detail" );
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
	 *
	 * 通过业务信息查看操作日志记录页面
	 */
	function c_businessList() {
		$tableName = $_GET ['tableName'] ? $_GET ['tableName'] : 'oa_finance_income';
		$this->assign('tableName',$tableName);

		$this->view ( "businesslist" );
	}

	/**
	 * 高级搜索
	 */
	function c_toSearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "年</option>";
		}
		$this->assign('yearStr',$yearStr);

		$this->view ('search' );
	}


	/**
	 * 多模块操作轨迹合并
	 * 由于工期原因，为缩短研发终端开发时间，获取数据表写死，即不通用。
	 */
	function c_businessViewMore() {
//		$pkValue = $_GET ['pkValue'] ? $_GET ['pkValue'] : null;
//		$tableName = $_GET ['tableName'] ? $_GET ['tableName'] : null;
        $tableNameStr = "oa_terminal_product,oa_terminal_terminaltype,oa_terminal_terminalinfo,oa_terminal_functiontype,oa_terminal_functioninfo";

		$this->service->searchArr = array ("tableNameStr" => $tableNameStr);
		$rows = $this->service->listBySqlId ( "select_detail" );


//		foreach ( $rows [0] as $key => $val ) {
//			$this->assign ( $key, $val );
//		}

		$this->assign("logList", $this->service->showAtBusinessViewMore($rows));
		$this->view ( "business-view" );
	}
}
?>