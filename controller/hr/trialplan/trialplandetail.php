<?php
/**
 * @author Show
 * @Date 2012年8月31日 星期五 14:53:12
 * @version 1.0
 * @description:员工试用计划明细控制层
 */
class controller_hr_trialplan_trialplandetail extends controller_base_action {

	function __construct() {
		$this->objName = "trialplandetail";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/************** 列表部分 *******************/

	/**
	 * 跳转到员工试用计划明细列表
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * 查看列表
     */
    function c_viewList(){
        $this->assignFunc($_GET);
    	$this->view('listview');
    }

	/**
	 * 我的任务列表
	 */
	function c_myList(){
		$this->view('listmy');
	}

    /**
     * 我的任务
     */
    function c_myJson(){
        $service = $this->service;

        $_POST['memberId'] = $_SESSION['USER_ID'];
        $service->getParam ( $_POST ); //设置前台获取的参数信息

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
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 * 我的使用评价
	 */
	function c_myManage(){
		$this->view('listmymanage');
	}

    /**
     * 我的评价
     */
    function c_myManageJson(){
        $service = $this->service;

        $_POST['managerId'] = $_SESSION['USER_ID'];
        $service->getParam ( $_POST ); //设置前台获取的参数信息

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
        echo util_jsonUtil::encode ( $arr );
    }


	/*************** 增删改查 ******************/

	/**
	 * 跳转到新增员工试用计划明细页面
	 */
	function c_toAdd() {
		//判断是否有PLANID
		if(empty($_GET['planId'])){
			msg('没有配置对应的培训计划或该计划已完成，请于列表中对该人员安排一个培训计划');
			die();
		}

		//任务类型渲染
		$this->showDatadicts(array('taskType' => 'HRSYRW'));

		//渲染计划
		$this->assign('planId',$_GET['planId']);
		$trialPlanObj = $this->service->getTrialPlanInfo_d($_GET['planId']);
		$this->assignFunc($trialPlanObj);

		//人员信息渲染
		$this->assign('memberName',$_GET['userName']);
		$this->assign('memberId',$_GET['userAccount']);

		//获取前置任务
		$beforeTaskArr = $this->service->getBeforeTask_d($_GET['planId']);
		$this->assign('beforeId',$this->service->showTaskSel_d($beforeTaskArr));

		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 跳转到编辑员工试用计划明细页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//任务类型渲染
		$this->showDatadicts(array('taskType' => 'HRSYRW'),$obj['taskType']);

        //评分渲染
		$ruleInfo = $this->service->getRuleInfo_d($obj['isRule']);
		if($ruleInfo){
			$this->assignFunc($ruleInfo);
		}else{
			$thisRuleInfo = array('upperLimit' => $obj['taskScore'],'lowerLimit' => 0);
			$this->assignFunc($thisRuleInfo);
		}

		//获取前置任务
		$beforeTaskArr = $this->service->getBeforeTask_d($obj['planId'],$obj['id']);
		$this->assign('beforeId',$this->service->showTaskSel_d($beforeTaskArr,$obj['beforeId']));

		//渲染计划
		$trialPlanObj = $this->service->getTrialPlanInfo_d($obj['planId']);
		$this->assignFunc($trialPlanObj);
		if($trialPlanObj){
			$this->assign('planScoreOther',bcsub($trialPlanObj['planScoreAll'],$obj['taskScore'],2));
		}

		$this->view('edit');
	}

    /**
     * 新增对象操作
     */
    function c_edit() {
        $id = $this->service->edit_d ( $_POST );
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '保存成功！';
        if ($id) {
            msg ( $msg );
        }
    }

	/**
	 * 跳转到查看员工试用计划明细页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//附件添加
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

        //选项值渲染
        $this->assign('isNeed',$this->service->rtIsNeed_c($obj['isNeed']));
        $this->assign('closeType',$this->service->rtCloseType_c($obj['closeType']));

        //评分渲染
        $ruleInfo = $this->service->getRuleInfo_d($obj['isRule']);
        if($ruleInfo){
            $this->assignFunc($ruleInfo);
        }else{
            $thisRuleInfo = array('upperLimit' => $obj['taskScore'],'lowerLimit' => 0);
            $this->assignFunc($thisRuleInfo);
        }

		$this->view('view');
	}

	/**
	 * 提交审核
	 */
	function c_toHandUp(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->assign('thisDate',day_date);
		$this->view('handup');
	}

	//提交
	function c_handup(){
        $object = $_POST[$this->objName];
        $rs = $this->service->handup_d($object);
        if($rs){
        	msg('提交成功');
        }
	}

	/**
	 * 审核评分
	 */
	function c_toScore(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->assign('thisDate',day_date);

        //评分渲染
		$ruleInfo = $this->service->getRuleInfo_d($obj['isRule']);
		if($ruleInfo){
			$this->assignFunc($ruleInfo);
		}else{
			$thisRuleInfo = array('upperLimit' => $obj['taskScore'],'lowerLimit' => 0);
			$this->assignFunc($thisRuleInfo);
		}

		//附件添加
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->view('score');
	}

    /**
     * 审核评分
     */
    function c_score(){
        $object = $_POST[$this->objName];
        $rs = $this->service->score_d($object);
        if($rs){
        	msg('审核成功');
        }
    }

    /**
     * 直接完成
     */
    function c_complate(){
        $rs = $this->service->complate_d($_POST['id']);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
    }

    /**
     * 启动任务
     */
    function c_begin(){
        $rs = $this->service->begin_d($_POST['id']);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
    }

    /**
     * 判断前置任务是否已经完成
     */
    function c_isComplate(){
        $rs = $this->service->isComplate_d($_POST['planId'],$_POST['taskName']);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
    }

    /**
     * 关闭任务
     */
    function c_close(){
        $rs = $this->service->close_d($_POST['id']);
        if($rs){
            echo 1;
        }else{
            echo 0;
        }
        exit();
    }
}
?>