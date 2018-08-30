<?php
/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:07
 * @version 1.0
 * @description:项目范围变更表控制层
 */
class controller_engineering_change_esmchangeact extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangeact";
		$this->objPath = "engineering_change";
		parent :: __construct();
	}

	/**
	 * 跳转到项目范围变更表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增项目范围变更表页面
	 */
	function c_toAdd() {
        //获取任务
        $parentId = $_GET['parentId'];
        $esmactivityObj = $this->service->getActivity_d($parentId);
        $this->assignFunc($esmactivityObj);

        //获取当前等级的剩余工作占比
//		$thisWorkRate = $this->service->getWorkRateByParentId_d($_GET['id'],$parentId);
//		$lastWorkRate = bcsub(100,$thisWorkRate,2);
//		$this->assign('workRate',$lastWorkRate);

		if($parentId != PARENT_ID){
	        //工作量单位初始化workloadUnit
	        $this->showDatadicts ( array ('workloadUnit' => 'GCGZLDW' ), $esmactivityObj['workloadUnit']);
		}else{
	        //项目获取
	        $esmprojectObj = $this->service->getProject_d($_GET['projectId']);
	        $this->assignFunc($esmprojectObj);

	        //工作量单位初始化workloadUnit
	        $this->showDatadicts ( array ('workloadUnit' => 'GCGZLDW' ));
		}

		$this->view('add');
	}

    /**
     * 新增对象操作
     */
    function c_add() {
        $id = $this->service->add_d ( $_POST [$this->objName]);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
        if ($id) {
            msgRf ( $msg );
        }
    }

	/**
	 * 跳转到编辑项目范围变更表页面
	 */
	function c_toEdit() {
        //获取任务
        $esmactivityObj = $this->service->getActivity_d($_GET['activityId']);
        $this->assignFunc($esmactivityObj);

        //工作量单位初始化workloadUnit
        $this->showDatadicts ( array ('workloadUnit' => 'GCGZLDW' ), $esmactivityObj['workloadUnit']);

		$this->view('edit');
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			msgRf ( '编辑成功！' );
		}
	}

	/**
	 * 跳转到查看项目范围变更表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 计算工作占比总和
	 *
	 */
	function c_workRateCount(){
		echo $this->service->workRateCount($_GET['changeId']);
	}

	//获取工作下级任务中的占比总和与
	function c_workRateCountNew(){
		$result = $this->service->workRateCountNew($_GET['changeId'], $_GET['parentId'], null);
		echo util_jsonUtil::encode($result);
	}

	/**
	 * 更新变更申请的预计开始和预计结束
	 */
	function c_checkIsLate(){
        echo $this->service->checkIsLate_d($_POST['changeId'],$_POST['projectId']);
	}
}