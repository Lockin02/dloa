<?php
/**
 * @author Show
 * @Date 2012年7月13日 星期五 10:48:12
 * @version 1.0
 * @description:项目角色(oa_esm_project_role)控制层
 */
class controller_engineering_role_esmrole extends controller_base_action {

	function __construct() {
		$this->objName = "esmrole";
		$this->objPath = "engineering_role";
		parent :: __construct();
	}

	/*********************** 列表部分 *****************************/

	/*
	 * 跳转到项目角色(oa_esm_project_role)列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonOrg() {
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
		echo util_jsonUtil::encode ( $arr );
	}

	/*
	 * 项目角色编辑列表
	 */
    function c_toEditList() {
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$project = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assign('parentId',$parentId);
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('list');
    }


    /*项目人员Tab页面(查看)*/
    function c_proMemberTab(){
    	$this->assign('projectId',$_GET['projectId']);
    	$this->view('promembertab');
    }

    /*管理项目人员Tab页面*/
    function c_proMemberTreeTab(){
    	$this->assign('projectId',$_GET['projectId']);
    	$this->view('promembertreetab');
    }


    /**
     * 项目角色树表
     */
    function c_toTreeList(){
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$project = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assignFunc($project);
		$this->assign('parentId',$parentId);
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('treelist');
    }

    /**
     * 树表数据
     */
    function c_treeJson(){
		$service = $this->service;
		$arrs = array();
		$projectId = isset($_REQUEST['projectId'])? $_REQUEST['projectId']:"";
		if(empty($projectId)){
			return false;
		}
		$service->searchArr['projectId'] = $projectId;
		$service->sort = 'c.lft';
		$service->asc = false;
		$arrs = $service->listBySqlId('treelist');

		if(!empty($arrs)){
			//除去_parentId
			foreach($arrs as $key => $val){
				if($val['_parentId'] == -1){
					unset($arrs[$key]['_parentId']);
				}
			}
		}
		//数组设值
		$rows['rows'] = $arrs;
		echo util_jsonUtil :: encode ($rows);
    }

    /**
     * 项目角色树表
     */
    function c_toTreeViewList(){
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$project = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assignFunc($project);
		$this->assign('parentId',$parentId);
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('treeviewlist');
    }

	/************************* 增删改查 **************************/

	/**
	 * 跳转到新增项目角色(oa_esm_project_role)页面
	 */
	function c_toAdd() {
		$this->assignFunc($_GET);
		$this->view('add');
	}


	/**
	 * 异步新增
	 */
	function c_ajaxAdd(){
		$object = util_jsonUtil:: iconvUTF2GBArr($_POST);
		$rs = $this->service->add_d($object);
		if($rs){
			echo $rs;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * 跳转到编辑项目角色(oa_esm_project_role)页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		$leaveMember = $this->service->getLeaveMember_d($object);//查出修改前项目成员id
		if ($this->service->edit_d ( $object )) {
			if($object['orgMemberId'] != $object['memberId']){
				if(!empty($leaveMember)){
					$leaveMemberStr = implode(',', $leaveMember);
					echo '<script>if(confirm("编辑成功,你移除了'.count($leaveMember).'个已参与项目的成员,是否填写加入和离开日期？")){
							location.href = "?model=engineering_member_esmentry&action=toLeaveList&ids='.$leaveMemberStr.'";
							}else{self.parent.tb_remove();self.parent.show_page();}</script>';
				}else{
					msg ( '编辑成功！' );
				}
			}else{
				msg ( '编辑成功！' );
			}
		}
	}

	/**
	 * 重写删除方法
	 */
	function c_ajaxdeletes() {
		$arr = $this->service->find(array('id' => $_POST ['id']),null,'memberId,projectId');
		$object['orgMemberId'] = $arr['memberId'];
		$object['memberId'] = '';
		$object['projectId'] = $arr['projectId'];
		$leaveMember = $this->service->getLeaveMember_d($object);//查出修改前项目成员id
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			if(!empty($leaveMember)){
				$leaveMemberStr = implode(',', $leaveMember);
				$result = array('0' => array('count' => count($leaveMember),'ids' => $leaveMemberStr));
				echo util_jsonUtil::encode($result);
			}else{
				echo true;
			}
		} catch ( Exception $e ) {
			echo false;
		}
	}

	/**
	 * 跳转到查看项目角色(oa_esm_project_role)页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/************************* 其他部分 *****************************/
	/**
	 * 验证是否有根节点，没有则新增
	 */
	function c_checkParent(){
		if($this->service->checkParent_d()){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	//初始化项目角色以及成员
	function c_initProjectRoleAneMember(){
		$rs = $this->service->initProjectRoleAneMember_d();
		if($rs){
			echo "completed";
		}else{
			echo "error";
		}
	}

	//成员架构导入
	function c_toEportExcelIn(){
		$this->assignFunc($_GET);
		$this->view('importExcel');
	}

	//成员架构导入
	function c_importExcel() {
		$service = $this->service;
		//获取隐藏INPUT
		$projectId = $_POST["projectId"];
		$projectCode = $_POST["projectCode"];
		$projectName = $_POST["projectName"];

		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		if ($fileType == "applicationnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.ms-excel") {

			$dao = new model_engineering_role_importTesttable();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			$objNameArr =  array(
					0 => 'roleName',//角色名称
					1 => 'memberName',//成员名称
					2 => 'activityName',//工作任务
					3 => 'jobDescription',//备注说明

			);
			$objectArr = array ();
			foreach ( $excelData as $rNum => $row ) {
				foreach ( $objNameArr as $index => $fieldName ) {
					$objectArr [$rNum] [$fieldName] = $row [$index];
				}
			}

			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importExcel ( $objectArr, $projectId, $projectCode, $projectName);

			if (is_array ( $resultArr ))
				echo util_excelUtil::showResult ( $resultArr, "信息导入结果", array ("导入文件名称", "结果" ) );

			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 * 验证项目角色是否可编辑固定投入比例
	 */
	function c_isEditFixedRate(){
		echo $this->service->isEditFixedRate_d($_POST['id']);
	}
	/**
	 * 获取可填最大固定投入比例
	 */
	function c_getMaxFixedRate(){
		//构造memberId条件
		$memberIdArr = explode(',',$_POST['memberId']);
		$memberIdStrArr = array();
		foreach ($memberIdArr as $key => $val){
			array_push($memberIdStrArr, "'".$val."'");
		}
		$memberIdStr = implode(',', $memberIdStrArr);
		$maxFixedRate = $this->service->getMaxFixedRate_d($_POST['projectId'],$memberIdStr);
		//无相关记录，则可填最大固定投入比例为100
		if(empty($maxFixedRate)){
			echo 100;
		}else{
			echo $maxFixedRate;
		}
	}
}