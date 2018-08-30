<?php
/**
 * @author Show
 * @Date 2012年7月29日 16:32:12
 * @version 1.0
 * @description:费用明细控制层
 */
class controller_engineering_cost_esmcostdetail extends controller_base_action {

	function __construct() {
		$this->objName = "esmcostdetail";
		$this->objPath = "engineering_cost";
		parent :: __construct();
	}

    /******************* 列表部分 *****************************/

	/**
	 * 跳转到费用明细列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 列表显示任务费用明细
	 */
	function c_feeListJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.createId,c.costTypeId having costMoney <> 0';
		$rows = $service->list_d ('select_fee2');

//		print_r($rows);
		if(!empty($rows)){
			$newArr = array();
			$cacheArr = array();
			foreach($rows as $key => $val){
				if(!isset($cacheArr[$val['createId']])){
					//缓存数组索引
					$cacheArr[$val['createId']] = count($newArr);

					//生成数组人员信息
					$newArr[$cacheArr[$val['createId']]]['createId'] = $val['createId'];
					$newArr[$cacheArr[$val['createId']]]['createName'] = $val['createName'];
				}
				$newArr[$cacheArr[$val['createId']]][$val['costTypeId']] = $val['costMoney'];
			}
		}

		echo util_jsonUtil::encode ( $newArr );
	}

	/**
	 * 获取费用表头
	 */
	function c_getFeeTitle(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.costTypeId';
		$service->asc = false;
		$rows = $service->list_d ('select_feetitle');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/******************* 项目经理费用审核部分  *********************/
	/**
	 * 项目经理费用审核
	 */
	function c_projectManageList(){
	    $weekDao = new model_engineering_baseinfo_week();
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		$showType = empty($_GET['showType']) ? 1 : $_GET['showType'];

		//获取当前周
        $weekTimes = $weekDao->getWeekNoByDayTimes();
		$this->assign('weekTimes',$weekTimes);

		//周日期设置
		if($_GET['beginDate']){
			$beginDate = $_GET['beginDate'];
			$endDate = $_GET['endDate'];
		}else{
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
        $this->assign('beginDate',$beginDate);
        $this->assign('endDate',$endDate);

		//列表内容渲染
		$pageInfo = $this->service->projectManage_d($projectId,$showType,$beginDate,$endDate);
		$this->assignFunc($pageInfo);

        $this->assign('projectId',$projectId);

        //是否是查看页面
        $this->assign('isView',0);

		$this->display('listmanage');
	}

	/**
	 * 项目经理费用审核
	 */
	function c_ajaxManageList(){
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		$showType = empty($_POST['showType']) ? 1 : $_POST['showType'];

		//周日期设置
		if($_POST['beginDate']){
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		}else{
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}

		//列表内容渲染
		$pageInfo = $this->service->projectManage_d($projectId,$showType,$beginDate,$endDate);
		$pageInfo['beginDate'] = $beginDate;
		$pageInfo['endDate'] = $endDate;

		echo util_jsonUtil::encode($pageInfo);
	}

	/**
	 * 项目经理费用审核 - 传入年周
	 */
	function c_ajaxManageListYW(){
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		$weekTimes = $_POST['weekTimes'] ? $_POST['weekTimes'] : die();
		$showType = empty($_POST['showType']) ? 1 : $_POST['showType'];

		//周日期设置
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekTimes);
        $weekBEArr = (!empty($weekInfo))? $weekDao->getWeekRange($weekInfo['week'],$weekInfo['year']) : array();
//		print_r($weekBEArr);exit();
		$beginDate = (!empty($weekInfo) && !empty($weekBEArr))? $weekBEArr['beginDate'] : '';
		$endDate = (!empty($weekInfo) && !empty($weekBEArr))? $weekBEArr['endDate'] : '';

		//列表内容渲染
		$pageInfo = $this->service->projectManage_d($projectId,$showType,$beginDate,$endDate);
		$pageInfo['beginDate'] = $beginDate;
		$pageInfo['endDate'] = $endDate;
		$pageInfo['weekTimes'] = $weekInfo['weekTimes'];

		echo util_jsonUtil::encode($pageInfo);
	}

	/**
	 * 项目成员报销页面查看的费用名称
	 */
	function c_pageJsonCostMoney() {
		$service = $this->service;

		$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$service->groupBy = 'c.executionDate';
		$rows = $service->page_d ('count_projectdate');
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

	/**
	 * 项目查看费用信息
	 */
	function c_projectViewList(){
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		$showType = empty($_GET['showType']) ? 1 : $_GET['showType'];

		//获取当前周
        $weekDao = new model_engineering_baseinfo_week();
        $weekTimes = $weekDao->getWeekNoByDayTimes();
		$this->assign('weekTimes',$weekTimes);

		//周日期设置
		if($_GET['beginDate']){
			$beginDate = $_GET['beginDate'];
			$endDate = $_GET['endDate'];
		}else{
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
        $this->assign('beginDate',$beginDate);
        $this->assign('endDate',$endDate);

		//列表内容渲染
		$pageInfo = $this->service->projectView_d($projectId,$showType,$beginDate,$endDate);
		$this->assignFunc($pageInfo);

        $this->assign('projectId',$projectId);

        //是否是查看页面
        $this->assign('isView',1);

		$this->display('listmanage');
	}

	/**
	 * 获取含有未审核费用的周 (最前的一次)
	 */
	function c_getUnconfirmWeek(){
		$projectId = $_POST['projectId'];
		if(empty($projectId)){
			echo -1;
		}
		$weekTimes = $this->service->getUnconfirmWeek_d($projectId);
		if($weekTimes){
			echo $weekTimes;
		}else{
			echo 0;
		}
		exit();
	}

    /******************* 增删改查 *****************************/
	/**
	 * 跳转到新增费用明细页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

    /**
     * 日志中新增费用信息
     */
    function c_toAddInWorklog(){
        $rs = $this->service->checkConfig_d();
        if($rs != 1){
            echo $rs;
            die();
        }

        $worklogId = $_GET['worklogId'];
        //获取日志中的其他信息
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //获取模板信息
        $templateArr = $this ->service->initTemplateAdd_d($worklogObj);

        $this->assign('templateName',$templateArr['templateName']);
        $this->assign('detail',$templateArr['str']);

    	$this->view('addinworklog');
    }

    /**
     * 日志中新增费用信息
     */
    function c_addInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->addBatch_d($object);
        if($countMoney){
           msg('保存成功');
        }else{
           msg('保存失败');
        }
    }

    /**
     * 日志中新增费用信息
     */
    function c_toEditInWorklog(){
        $worklogId = $_GET['worklogId'];
        //获取日志中的其他信息
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //获取模板信息
        $templateArr = $this ->service->initTemplateEdit_d($worklogObj);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);

        $this->view('editinworklog');
    }

    /**
     * 编辑费用
     */
    function c_editInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->editBatch_d($object);
        if($countMoney){
           msg('保存成功');
        }else{
           msg('保存失败');
        }
    }

	/**
	 * 跳转到编辑费用明细页面
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
	 * 跳转到查看费用明细页面
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
     * 日志中新增费用信息
     */
    function c_toViewInWorklog(){
        $worklogId = $_GET['worklogId'];
        //获取日志中的其他信息
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //获取模板信息
        $templateArr = $this ->service->initTemplateView_d($worklogObj);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);
        $this->assign('invoiceNumber',$templateArr['invoiceNumber']);
        $this->assign('invoiceMoney',$templateArr['invoiceMoney']);

        $this->view('viewinworklog');
    }

	/**
	 * 确认费用功能
	 */
	function c_toConfirm(){
        $worklogId = $_GET['worklogId'];
        //获取日志中的其他信息
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //获取模板信息
        $templateArr = $this ->service->initTemplateConfirm_d($worklogObj);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);
        $this->assign('invoiceNumber',$templateArr['invoiceNumber']);
        $this->assign('invoiceMoney',$templateArr['invoiceMoney']);

        $this->view('confirm');
	}

	/**
	 * 确认费用
	 */
	function c_confirm(){
        $object = $_POST[$this->objName];
        $rs = $this->service->confirm_d($object);
        if($rs){
           msgRf('确认成功');
        }else{
           msgRf('确认失败');
        }
	}

	/**
	 * 重新编辑 - 打回后
	 */
	function c_toReeditInWorklog(){
        $worklogId = $_GET['worklogId'];
        //获取日志中的其他信息
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //获取模板信息
        $templateArr = $this ->service->initTemplateEdit_d($worklogObj,true);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);

        $this->view('reeditinworklog');
	}

    /**
     * 编辑费用
     */
    function c_reeditInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->reeditBatch_d($object);
        if($countMoney){
           msg('保存成功');
        }else{
//           msg('保存失败');
        }
    }

	/********************* 费用录入界面模板部分 *********************/
    /**
     * 费用报销模版渲染
     */
    function c_initTempAdd(){
		$modelType = $_POST['modelType'];
		if($modelType){
			$modelStr =  $this->service->initTempAdd_d($modelType);
			echo util_jsonUtil::iconvGB2UTF ( $modelStr['str'] );
		}else{
			echo "";
		}
		exit();
    }

	/******************** 票据整理部分 ***************************/
	/**
	 * 票据组整理报销单列表
	 */
	function c_manageExpenseList(){
		//渲染报销单信息
		$expenseId = $_GET['expenseId'];
		$expenseObj = $this->service->getExpenseInfo_d($expenseId);
		$this->assignFunc($expenseObj);

		//加载报销单对应日志明细
		$costdetail = $this->service->getCostdetail_d($expenseObj['esmCostdetailId']);
		$this->assignFunc($this->service->initCostdetail_d($costdetail));

		$this->view('listmanageexpense');
	}
	/**
	 * 跳转到详细费用报销
	 */
	function c_costDetail(){
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		$this->assign('projectId',$projectId);
	
		$this->view('costDetai');
	}
	/**
	 * 详细费用报销
	 */
	function c_listHtml() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		$status = $_POST['status'] ? $_POST['status'] : die();
		//列表内容渲染
		$listInfo = $this->service->listHtml_d($projectId,$status);

		echo util_jsonUtil::iconvGB2UTF($listInfo);
	}
}