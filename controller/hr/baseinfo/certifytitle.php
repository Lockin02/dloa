<?php
/**
 * @author Show
 * @Date 2012年9月7日 17:14:21
 * @version 1.0
 * @description:任职资格称谓表控制层
 */
class controller_hr_baseinfo_certifytitle extends controller_base_action {

	function __construct() {
		$this -> objName = "certifytitle";
		$this -> objPath = "hr_baseinfo";
		parent::__construct();
	}

	/*
	 * 跳转到任职资格称谓表列表
	 */
	function c_page() {
		$this -> view('list');
	}

	/**
	 * 跳转到新增任职资格称谓表页面
	 */
	function c_toAdd() {
		$remarkstr =<<<EOT
		<option title="关闭" value="0">关闭</option>
		<option title="启用" value="1">启用</option>
EOT;
		$this->assign("remarkinfo",$remarkstr);
		$this -> view('add');
	}

	/**
	 * 跳转到编辑任职资格称谓表页面
	 */
	function c_toEdit() {
		$this -> permCheck();
		//安全校验
		$obj = $this -> service -> get_d($_GET['id']);

		foreach ($obj as $key => $val) {
			$this -> assign($key, $val);
		}

		if($obj['status']==0){
			$remarkstr =<<<EOT
		<option title="关闭" value="0" selected>关闭</option>
		<option title="启用" value="1">启用</option>
EOT;
		}else{
			$remarkstr =<<<EOT
		<option title="关闭" value="0">关闭</option>
		<option title="启用" value="1" selected>启用</option>
EOT;
		}
		$this->assign("remarkinfo",$remarkstr);
		$this->showDatadicts ( array ('careerDirection' => 'HRZYFZ' ), $obj ['careerDirection'] );
		$this->showDatadicts ( array ('baseLevel' => 'HRRZJB' ), $obj ['baseLevel'] );
		$this->showDatadicts ( array ('baseGrade' => 'HRRZZD' ), $obj ['baseGrade'] );
		$this -> view('edit');
	}

	/**
	 * 跳转到查看任职资格称谓表页面
	 */
	function c_toView() {
		$this -> permCheck();
		//安全校验
		$obj = $this -> service -> get_d($_GET['id']);
		$obj['status'] = $this -> service -> statusDao ->statusKtoC($obj['status']);
		foreach ($obj as $key => $val) {
			$this -> assign($key, $val);
		}

		$this -> view('view');
	}
	//列表json数据
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach ($rows as $key => $value) {
				$rows[$key]['statusCN'] = $this -> service -> statusDao ->statusKtoC($rows[$key]['status']);
			}

		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>