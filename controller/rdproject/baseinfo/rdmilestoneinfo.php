<?php
	/**
	 * @description: 项目里程碑-相关信息
	 * @date 2010-9-26 下午03:05:22
	 */
class controller_rdproject_baseinfo_rdmilestoneinfo extends controller_base_action{
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-9-26 下午03:06:50
	 */
	function __construct () {
		$this->objName = "rdmilestoneinfo";
		$this->objPath = "rdproject_baseinfo";
		parent::__construct();
	}

	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 默认跳转页面
	 * @param tags
	 * @date 2010-9-26 上午09:35:49
	 */
	function c_projectlist () {
		$this->display('main-list');
	}

	/*
	 * 显示左导航栏
	 */
	function c_menulist(){
		$this->display('menu-list');
	}


	/*
	 * 添加里程碑相关信息
	 */
	function c_toaddmilestone(){

		//获取添加页面中“前置里程碑”所需要的页面内容
		$service = $this -> service;
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
//		$gettablemilestone = $_GET['projectType'];
//		$getexmilestone = $service->pageExMile_d($parentId);
		//显示前置里程碑的下拉列表
//		$exMilestoneName = $service->showExMilestone($getexmilestone);

		//通过编码获取前置里程碑的名称
//		$getexMileName = $service->getexMileName($gettablemilestone);
//		$this->assign('ex',$getexMileName);

		$this->showDatadicts(array('projectType' => 'YFXMGL'));
//		$this->assign('exMilestoneName',$exMilestoneName);
		//生成唯一编码
		$this->assign('numb',$this->businessCode());
		$this->assign('parentId',$parentId);

		$this->display('add');

	}

	/*
	 * 增加里程碑
	 */
	function c_addmilestone(){
		$milObj = $_POST[$this->objName];
		$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$id = $this->service->addmilestone_d($milObj,true);
		if($id){
			msg('添加里程碑成功');
		}
	}


	/*
	 * 显示默认右页面。
	 * 默认为“项目里程碑”Tab标签下的显示列表页面
	 */
	function c_milestonelist(){
		$service = $this->service;
		//分页
		$auditArr = array(
			"createId" => $_SESSION['USER_ID'],
		);

		if(!isset($_GET['projectType'])){
			$_GET['projectType'] = null;
		}
		$rows = $service->page_d( $_GET['projectType'] );
		$this->pageShowAssign();
		//调用数据字典内容来显示项目类型的下拉框
		$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->assign('list',$service->showprojectlist($rows));
		$this->display('list');
	}


	/**
	 * @desription 对列表内容进行过滤
	 * @param tags
	 * @date 2010-9-29 下午06:33:29
	 */
	function c_milestonefilterlist () {
		//以下代码是对应“类型-里程碑点”。2010年10月26日注释。
		$service = $this->service;
		$projectType = $_GET['projectType'];
		$this->assign('projectTypeVal',$projectType);
		$service->searchArr=array("projectType"=>$projectType);
		$rows = $service->page_d();
		$this->showdatadicts(array('projectType' => 'YFXMGL'));
		$this->pageShowAssign();

		$this->assign('list',$service->showprojectlist($rows));
		$this->display('list');
	}

	/**
	 * @desription 用于提供一个接口，返回一个关于里程碑的信息数组
	 * @param tags
	 * @return return_type
	 * @date 2010-10-3 下午04:55:21
	 */
	function c_returnMilestoneInfo(){
		$service = $this->service;
		//传入的参数是"项目类型"<option>的下拉值,ID为"projectType"
		$projectType = $_GET['projectType'];

		//返回所需要的数组内容，传入的参数是通过选中“项目类型”后所对应的下拉值
		$service->returnMilestoneInfo_d($projectType);

	}

	/*
	 * 跳转到修改里程碑页面
	 */
	function c_toEdit(){
		$service = $this->service;
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$rows = $service->getEditMilestoneInfo_d($id);
		$frontRows=$service->findAll(array('numb'=>$rows['frontNumb']));
		$rows2['0'] = $rows;
		$this->arrToShow($rows2);

		$exMilestoneName = $service->milestoneSelect_d( $id );
		$this->assign('frontName',$frontRows[0][milestoneName]);
		$this->assign('exMilestoneName',$exMilestoneName );
		$this->assign('milestoneDescription',$rows['milestoneDescription']);
		$this->display('edit');
	}

	/*
	 * 修改里程碑
	 */
	function c_editMilestone(){
		$rdmile = $_POST[$this->objName];

		$id = $this->service->editMilestone_d($rdmile,true);
		if($id){
			msg('编辑里程碑成功');
		}
	}

	/**删除里程碑
	*author can
	*2011-4-8
	*/
	function c_deleteMilestone(){
		$id=isset($_GET['id'])?$_GET['id']:null;
		$flag=$this->service->deletes($id);
//		if($flag){
//			msgGo('删除成功！',"?model=rdproject_baseinfo_rdmilestoneinfo&action=milestonelist");
//		}
	}

	/**删除里程碑
	*author can
	*2011-4-8
	*/
	function c_deleteMilestone1(){
		$id=isset($_GET['id'])?$_GET['id']:null;
		$flag=$this->service->deletes($id);
		if($flag){
			echo 1;
		}else{
			echo 0;
		}
	}


	/***************************************************************************************************
	 * ------------------------------以下为ajax返回json方法---------------------------------------------*
	*************************************************************************************************/
	/**根据项目获取前置里程碑
	*author can
	*2011-4-8
	*/
	function c_getFrontMilestone(){
		$projectType=isset($_POST['projectType'])?$_POST['projectType']:null;

		//通过编码获取前置里程碑的名称
		$getexMileName = $this->service->getMilestoneByProjectType_d($projectType);
		$exMilestoneName = $this->service->showExMilestoneList($getexMileName);
		echo $exMilestoneName;


	}
}
?>
