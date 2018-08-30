<?php

class controller_contract_contract_confirm extends controller_base_action {

	function __construct() {
		$this->objName = "confirm";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * 跳转到销售助理操作记录列表
	 */
    function c_page() {
		$sql = "select count(id) as num from oa_contract_confirm where state='未确认'";
		$arr = $this->service->_db->getArray($sql);
		$this->assign("num",$arr[0]['num']);
      $this->view('list');
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
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_ajaxCheckTip() {
		$id = $_POST ['id'];
		$val = $_POST ['val'];
        if($val == '1'){
			$setVal = "已确认";
		}else{
			$setVal = "未确认";
		}

		$sql = "update oa_contract_confirm set state='" . $setVal . "',handleId='".$_SESSION ['USER_ID']."',handleName='".$_SESSION ['USERNAME']."',handleDate='".date("Y-m-d H:i:s")."' where id = '" . $id . "'";
		$this->service->_db->query($sql);
		echo 1;
	}

	/**
	 * 导出
	 */
	function c_exportExcel()
	{
		$service = $this->service;
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		$rows = array();

		if(!isset($_GET['colId']) && !isset($_GET['colName'])){// 如果前端没传入对应的列ID以及列名,从SESSION中获取
			if(isset($_SESSION['exportCol'])){
				$colIdStr = $_SESSION['exportCol']['ColId'];
				$colNameStr = $_SESSION['exportCol']['ColName'];
				unset($_SESSION['exportCol']);
			}else{
				$colIdStr = '';
				$colNameStr = '';
			}
		}else{
			$colIdStr = $_GET['colId'];
			$colNameStr = $_GET['colName'];
		}

		$searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
		$searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
		$searchArr[$searchConditionKey] = $searchConditionVal;

		$service->getParam($_REQUEST);
		//登录人
//         $appId = $_SESSION['USER_ID'];
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);

		if (!empty($this->service->searchArr)) {
			$this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
		} else {
			$this->service->searchArr = $searchArr;
		}

//         $rows = $service->page_d();
		ini_set('memory_limit', '1024M');
		$rows = $service->page_d ();

		$arr = array();
		$arr['collection'] = $rows;

		//匹配导出列
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);

		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}


		return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr);
	}


 }
?>