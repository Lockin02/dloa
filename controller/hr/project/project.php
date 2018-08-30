<?php
/**
 * @author Administrator
 * @Date 2012-05-30 19:25:55
 * @version 1.0
 * @description:项目经历控制层
 */
class controller_hr_project_project extends controller_base_action {

	function __construct() {
		$this->objName = "project";
		$this->objPath = "hr_project";
		parent::__construct ();
	 }

	/*
	 * 跳转到项目经历列表
	 */
    function c_page() {
      $this->view('list');
    }
	/**
	 * 基础信息Tab页列表
	 */
	function c_tabList(){
		$this->assign("userId",$_GET['userAccount']);
		$this->assign("userNo",$_GET['userNo']);
		$this->view('tablist');
	}
   /**
	 * 跳转到新增项目经历页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
   /**
	 * 跳转到编辑项目经历页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看项目经历页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   	/**
	 * 列表高级查询
	 */
	function c_toSearch(){
        $this->view('search');
	}


	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$title = '项目经验导入结果列表';
		$thead = array( '数据信息','导入结果' );
		$objNameArr = array (
			0 => 'userNo', //员工编号
			1 => 'userName', //姓名
			2 => 'projectName', //项目名称
			3 => 'projectPlace', //项目地点
		    4 => 'projectManager', //项目经理
			5 => 'beginDate', //参加项目开始时间
			6 => 'closeDate', //参加项目结束时间
			7 => 'projectRole', //在项目中的角色
			8 => 'projectContent', //在项目中的工作内容
		       );
        $resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/**
	 * 导出数据
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['project']['listSql']))));
		$this->view('excelout-select');

	}

	/**
	 * 导出数据
	 */
	function c_selectExcelOut(){
		$rows=array();//数据集
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		$colNameArr=array();//列名数组
		include_once ("model/hr/project/projectFieldArr.php");
		if(is_array($_POST['project'])){
			foreach($_POST['project'] as $key=>$val){
					foreach($projectFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
		$newColArr=array_combine($_POST['project'],$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($_POST['project']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutProject($newColArr,$dataArr);
	}
	/******************* E 导入导出系列 ************************/
 }
?>