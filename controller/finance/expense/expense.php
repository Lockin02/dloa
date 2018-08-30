<?php

/**
 * @author Show
 * @Date 2012年9月21日 星期五 13:47:38
 * @version 1.0
 * @description:费用管理控制层
 */
class controller_finance_expense_expense extends controller_base_action
{
    private $unSltDeptFilter = "";// PMS68 费用归属部门禁止选择的部门ID配置
    private $DenyFegsdeptId = ""; // PMS772 费用归属部门禁止选择的部门ID,通过配置端配置
    private $unDeptExtFilter = "";// PMS377 此模块需要单独隐藏的部门选项
    private $bindId = "";
	function __construct() {
		$this->objName = "expense";
		$this->objPath = "finance_expense";
		parent:: __construct();

        $otherDataDao = new model_common_otherdatas();
        $subsidyArr = $otherDataDao->getConfig('unSltDeptFilter');
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unSltDeptFilter = $subsidyArr;
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");
        $DenyFegsdept = $otherDataDao->getDenyFegsdept();
        if(isset($DenyFegsdept['0']) && !empty($DenyFegsdept['0'])){
            $this->DenyFegsdeptId = $DenyFegsdept['0']['belongDeptIds'];
        }
        $this->bindId = "ba12e822-b58e-43ae-a4f9-431c9e0dfe6e";
	}

	/*
	 * 跳转到付款申请费用分摊明细表列表
	 */
	function c_page() {
		//渲染打单权限
		$printLimit = isset($this->service->this_limit['打单权限']) ? $this->service->this_limit['打单权限'] : 0;
		$this->assign('printLimit', $printLimit);
		//渲染明细权限
		$detailLimit = isset($this->service->this_limit['明细权限']) ? $this->service->this_limit['明细权限'] : 0;
		$this->assign('detailLimit', $detailLimit);

        $funType = isset($_GET['funType'])? $_GET['funType'] : '';
        $userId = isset($_GET['userId'])? $_GET['userId'] : '';
        $this->assign("funType",$funType);
        $this->assign("userId",$userId);

		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json - 默认
	 */
	function c_pageJson() {
		$service = $this->service;

		//特殊状态处理
		if (isset($_POST['checkList']) && $_POST['checkList'] == 1) {
			$thisStaus = util_jsonUtil::iconvUTF2GB($_POST['Status']);
			if ($thisStaus == '财务审核') {
				$_POST['StatusFin'] = '部门审批';
				unset($_POST['Status']);
			} elseif ($thisStaus == '部门审批') {
				$_POST['StatusNor'] = $_POST['Status'];
				unset($_POST['Status']);
			}
		}

		$service->getParam($_POST);
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 工程项目成员报销记录表json，包含选择合计、合计列
	 */
	function c_myEsmmemberPageJson() {
		$service = $this->service;

		//特殊状态处理
		if (isset($_POST['checkList']) && $_POST['checkList'] == 1) {
			$thisStaus = util_jsonUtil::iconvUTF2GB($_POST['Status']);
			if ($thisStaus == '财务审核') {
				$_POST['StatusFin'] = '部门审批';
				unset($_POST['Status']);
			} elseif ($thisStaus == '部门审批') {
				$_POST['StatusNor'] = $_POST['Status'];
				unset($_POST['Status']);
			}
		}

		$service->getParam($_POST);
		$rows = $service->page_d();

		if (!empty($rows)) {
			//数据加入安全码
			$rows = $this->sconfig->md5Rows($rows);

			$rsArr['CostBelongDeptName'] = '选择合计';
			$rsArr['Amount'] = 0;
			$rsArr['id'] = 'noId2';
			$rows[] = $rsArr;

			//总计栏加载
			$objArr = $service->listBySqlId('select_amount');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['CostBelongDeptName'] = '合计';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 报销单查询json,包含权限和合计列
	 */
	function c_pageJsonAll() {
		$service = $this->service;
		$rows = array();

        // 审批页面查看最近5笔报销时,参数重组
        if(isset($_POST['funType']) && $_POST['funType'] == "seeLastFive"){
            $service->setCompany(0);
            $_POST['costUser'] = isset($_POST['costMan'])? $_POST['costMan'] : '';
            $_POST['pageSize'] = 5;
            $_POST['sort'] = "c.id";
            $_POST['dir'] = "DESC";
            unset($_POST['costMan']);
            $service->getParam($_POST); //设置前台获取的参数信息
            unset($service->searchArr['Status']);

            $rows = $service->page_d();
        }else{
            $deptLimit = isset($service->this_limit['部门权限']) ? $service->this_limit['部门权限'] : "";
            //判断部门权限
            if (!empty($deptLimit)) {
                if (strstr($deptLimit, ';;')) {
                    $service->getParam($_POST); //设置前台获取的参数信息
                    $rows = $service->page_d();
                } else {
                    $service->getParam($_POST); //设置前台获取的参数信息
                    $service->searchArr['CostBelongDeptIds'] = $deptLimit;
                    $rows = $service->page_d();
                }

                //如果有查询数据，则加密
                if ($rows) {
                    //数据加入安全码
                    $rows = $this->sconfig->md5Rows($rows);

                    //总计栏加载
                    $objArr = $service->listBySqlId('count_all');
                    if (is_array($objArr)) {
                        $rsArr = $objArr[0];
                        $rsArr['CostManName'] = '全部合计';
                        $rsArr['id'] = 'noId';
                        $rows[] = $rsArr;
                    }
                }
            }
        }

		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
		$arr['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/* ====================== 阿里商旅数据查询 PMS 661【START】====================== */
    /**
     * 查看对应用户的阿里商旅费用明细列表
     */
	function c_seeAliTripCostRecords(){
	    $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
        $CostDateBegin = isset($_REQUEST['CostDateBegin'])? $_REQUEST['CostDateBegin'] : '';
        $CostDateEnd = isset($_REQUEST['CostDateEnd'])? $_REQUEST['CostDateEnd'] : '';

        $this->assign("userId",$userId);
        $this->assign("beginDate",$CostDateBegin);
        $this->assign("endDate",$CostDateEnd);
        $this->view('listAliTripCostRecords');
    }

    /**
     * 将线上的阿里商旅的数据拉取到本地
     */
    function c_autoSaveAliTripDate(){
        $aliDao = new model_finance_expense_alibusinesstrip();
        $aliDao->saveAliTripDateToLocal_d();
    }

    /**
     * 查看数据
     */
    function c_searchAliGridJson(){
        $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
        $CostDateBegin = isset($_REQUEST['beginDate'])? $_REQUEST['beginDate'] : '';
        $CostDateEnd = isset($_REQUEST['endDate'])? $_REQUEST['endDate'] : '';
        $dateRange = array("CostDateBegin" => $CostDateBegin,"CostDateEnd" => $CostDateEnd);
        $aliDao = new model_finance_expense_alibusinesstrip();

//        $jsonUtilObj = new util_jsonUtil();// 必须在调用Ali的SDK之前先实例化一下本地的util,否则会出错
//        $dataRows = $aliDao->getAliTripHotelOrder($userId,$dateRange);// 酒店记录
//        $flightDataRows = $aliDao->getAliTripFlightOrder($userId,$dateRange);// 机票记录
//        $trainDataRows = $aliDao->getAliTripTrainOrder($userId,$dateRange);// 火车票记录
//        if(!empty($flightDataRows)){// 合并机票记录
//            foreach ($flightDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }
//        if(!empty($trainDataRows)){// 合并火车票记录
//            foreach ($trainDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }

        $dataRows = $aliDao->searchLocalAliDataForGrid_d($userId,$dateRange);
        $dataRows = util_jsonUtil::iconvGB2UTFArr($dataRows);

        // 合计
        $countRow = array(
            "id" => "totalSum",
            "useNname" => "<span style='font-weight: bolder;font-size: 13px'>".util_jsonUtil::iconvGB2UTF("合计")."</span>",
            "beginDate" => "",
            "endDate" => "",
            "category" => "",
            "description" => "",
            "cost" => 0,
        );

        foreach ($dataRows as $row){
            $countRow['cost'] = round(bcadd($row['cost'],$countRow['cost'],3),2);
        }
        $dataRows[] = $countRow;
        $htmlStr = $aliDao->searchAliGridHtml_d($dataRows);
        echo $htmlStr;
    }

    /**
     * 导出数据
     */
    function c_exportAliGridJson(){
        $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
        $CostDateBegin = isset($_REQUEST['beginDate'])? $_REQUEST['beginDate'] : '';
        $CostDateEnd = isset($_REQUEST['endDate'])? $_REQUEST['endDate'] : '';
        $dateRange = array("CostDateBegin" => $CostDateBegin,"CostDateEnd" => $CostDateEnd);
        $aliDao = new model_finance_expense_alibusinesstrip();

        // 必须在调用Ali的SDK之前先实例化一下本地的util,否则会出错
//        $jsonUtilObj = new util_jsonUtil();
//        $esmexcelutilObj = new model_engineering_util_esmexcelutil();
//
//        $dataRows = $aliDao->getAliTripHotelOrder($userId,$dateRange);// 酒店记录
//        $flightDataRows = $aliDao->getAliTripFlightOrder($userId,$dateRange);// 机票记录
//        $trainDataRows = $aliDao->getAliTripTrainOrder($userId,$dateRange);// 火车票记录
//        if(!empty($flightDataRows)){// 合并机票记录
//            foreach ($flightDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }
//        if(!empty($trainDataRows)){// 合并火车票记录
//            foreach ($trainDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }
//        $dataRows = util_jsonUtil::iconvUTF2GBArr($dataRows);

        $dataRows = $aliDao->searchLocalAliDataForGrid_d($userId,$dateRange);
        set_time_limit(0);

        //定义表头
        $thArr = array('useNname' => '姓名', 'beginDate' => '开始日期','endDate' => '结束日期', 'category' => '类别', 'cost' => '金额','description' => '详细内容');
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $dataRows, '阿里商旅数据统计');
    }

    /**
     * 检查在该报销单的费用期间内阿里商旅的消费记录中是否存在与报销单一样的费用项
     */
    function c_checkAliTripCostRecord(){
        $type = isset($_REQUEST['type'])? $_REQUEST['type'] : '';

        switch ($type){
            case 'byBillNo':
                $billNo = isset($_REQUEST['billNo'])? $_REQUEST['billNo'] : '';

                $record = ($billNo != "")? $this->service->_db->get_one("select T.*,l.CostMan,l.CostDateBegin,l.CostDateEnd from cost_summary_list l left join (
select billno,GROUP_CONCAT(t.CostTypeID) as CostTypeIDs from (select * from cost_detail group by billno,CostTypeID)t group by t.billno
)T on T.Billno = l.billNo where l.billNo = '{$billNo}';") : array();
                $userId = isset($record['CostMan'])? $record['CostMan'] : '';
                $startDate = isset($record['CostDateBegin'])? $record['CostDateBegin'] : '';
                $endDate = isset($record['CostDateEnd'])? $record['CostDateEnd'] : '';
                $costTypeIds = isset($record['CostTypeIDs'])? $record['CostTypeIDs'] : '';
                break;
            default:
                $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
                $startDate = isset($_REQUEST['startDate'])? $_REQUEST['startDate'] : '';
                $endDate = isset($_REQUEST['endDate'])? $_REQUEST['endDate'] : '';
                $costTypeIds = isset($_REQUEST['costTypeIds'])? $_REQUEST['costTypeIds'] : '';
                break;
        }

        $matchCostType = $this->service->chkAliTripRecord($userId,$startDate,$endDate,$costTypeIds);

        $backArr = array(
            "msg" => "ok",
            "error" => "0",
            "result" => $matchCostType
        );
        echo util_jsonUtil::encode($backArr);
    }
    /* ====================== 阿里商旅数据查询 PMS 661【END】 ====================== */

	/**
	 * 我的报销明细单
	 */
	function c_myList() {
		$this->view('listmy');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;
		$service->setCompany(0); # 个人列表,不需要进行公司过滤

		$_POST['costUser'] = $_SESSION['USER_ID'];
		$service->getParam($_POST); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 报销单明细-用于费用报销审批页面的费用统计
	 */
	function c_statistictList() {
        //    	$this->assign('userId',$_GET['userId']);
//    	$this->assign('areaId',$_GET['areaId']);
//    	$this->assign('year',date('Y'));
//
//    	$this->view('liststatistict');
        $this->assign('userId',(isset($_GET['userId'])? $_GET['userId'] : ''));
        $this->assign('areaId',(isset($_GET['areaId'])? $_GET['areaId'] : ''));
        $this->assign('year',(isset($_GET['year'])? $_GET['year'] : date('Y')));

        if( (isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId'])) && (isset($_GET['view_type']) && !empty($_GET['view_type'])) ){
            $this->assign('view_type',(isset($_GET['view_type'])? $_GET['view_type'] : ''));
            if($_GET['view_type'] == 'view_all'){
                $this->view('listallstatistict');
            }
        }
        else if((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId']))){
            $this->view('liststatistict');
        }
        else{
            $this->view('listallstatistict');
        }
	}
	
	/**
	 * 获取分页数据转成Json - 费用统计
	 */
	function c_statistictPageJson() {
		$service = $this->service;
		$service->_isSetCompany = 0;//不区分公司权限

        // 获取配置项的销售部门信息
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('SALEDEPT');
        if($matchConfigItem && is_array($matchConfigItem)){
            $CostBelongDeptIds = isset($matchConfigItem[0]['belongDeptIds'])? $matchConfigItem[0]['belongDeptIds'] : '';
            if($CostBelongDeptIds != ''){
                $_POST['CostBelongDeptIds'] = $CostBelongDeptIds;
            }
        }
        $_POST['Status'] = '完成';

		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->page_d();
        $arr = array();
        $arr['getSql'] = $service->listSql;
		//如果有查询数据，则加密
		if ($rows) {
			//数据加入安全码
			$rows = $this->sconfig->md5Rows($rows);

            if(isset($_POST['needCountCol']) && $_POST['needCountCol'] == 'true'){
                //总计栏加载
                $objArr = $service->listBySqlId('count_all');
                if (is_array($objArr)) {
                    $rsArr = $objArr[0];
                    $rsArr['CostManName'] = '全部合计';
                    $rsArr['id'] = 'noId';
                    $rows[] = $rsArr;
                }
            }
		}

		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
        $arr['sumSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}
	
	/**
	 * 部门检查列表
	 */
	function c_checkList() {
		//渲染打单权限
		$checkLimit = isset($this->service->this_limit['检查人']) ? $this->service->this_limit['检查人'] : 0;
		$this->assign('checkLimit', $checkLimit);
		$this->view('listcheck');
	}

	/**
	 * 离职人员查看报销单
	 */
	function c_listForLeave() {
		$userAccount = isset($_GET['userAccount']) ? $_GET['userAccount'] : '';
		if (empty($userAccount)) {
			exit("请传入用户信息");
		} else {
			$this->assign('userAccount', $_GET['userAccount']);
			$this->view('list-forleave');
		}
	}

	/**
	 * 跳转到工程项目成员报销单列表
	 */
	function c_listForEsmmember() {
		$userAccount = isset($_GET['userAccount']) ? $_GET['userAccount'] : '';
		if (empty($userAccount)) {
			exit("请传入用户信息");
		} else {
			$this->assign('userAccount', $_GET['userAccount']);
			$this->assign('projectNo', $_GET['projectCode']);
			$this->view('list-foresmmember');
		}
	}

	/************************** S 项目费用报销检查部分 *******************/
	/**
	 * 项目检查列表
	 */
	function c_checkEsmList() {
		$this->view('listcheckesm');
	}

	/**
	 * 项目检查json
	 */
	function c_checkEsmJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		# 默认指向表的别称是
		$service->setComLocal(array(
			"c" => $service->tbl_name,
			"p" => 'oa_esm_project'
		));
		$rows = $service->page_d('select_projectlist');
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 单个项目检查列表
	 */
	function c_checkProjectList() {
		$this->assign('projectId', $_GET['projectId']);
		$this->view('listcheckproject');
	}

	/************************** E 项目费用报销检查部分 *******************/

	/*************************** 列表部分 *****************************/

	/**
	 * 跳转到新增付款申请费用分摊明细表页面
	 */
	function c_toAdd() {
		//模版渲染
		$modelTypeArr = $this->service->getModelType_d();

		//如果查询到模板，则进去报销单，否则进入模板配置页面
		if ($modelTypeArr) {
			//渲染模版
			$this->assignFunc($modelTypeArr);

//            $deptArr = explode(',',unDeptFilter.$this->unDeptExtFilter);
            $deptArr = explode(',',$this->DenyFegsdeptId.$this->unDeptExtFilter);
			$comCode = '';
			if(in_array($_SESSION['DEPT_ID'],$deptArr)){
				//申请信息加载
				$this->assign('deptTempName','');
				$this->assign('deptTempId','');
			}else{
				//申请信息加载
				$this->assign('deptTempName',$_SESSION['DEPT_NAME']);
				$this->assign('deptTempId',$_SESSION['DEPT_ID']);
				if(!empty($_SESSION['DEPT_ID'])){
					$sql = "select comCode from department where DEPT_ID = ".$_SESSION['DEPT_ID'];
					$rs = $this->service->_db->getArray($sql);
					if(!empty($rs)){
						$comCode = $rs[0]['comCode'];
					}
				}
			}
			$this->assign('comCode',$comCode);
			$this->assign('comCodeDefault',$comCode);
			//申请信息加载
			$this->assign('deptName',$_SESSION['DEPT_NAME']);
			$this->assign('deptId',$_SESSION['DEPT_ID']);
			$this->assign('thisDate', day_date);
			$this->assign('applyName', $_SESSION['USERNAME']);
			$this->assign('applyId', $_SESSION['USER_ID']);
			$this->assign('CostBelongComId', $_SESSION['USER_COM']);
			$this->assign('CostBelongCom', $_SESSION['USER_COM_NAME']);

			//获取下载
            $otherdatasDao = new model_common_otherdatas();
            $docUrl = $otherdatasDao->getDocUrl($this->bindId);
            $this->assign('downloadUrl', $docUrl);

			//判断报销人的报销单是否要进行部门检查
			$this->assign('needExpenseCheck', $this->service->needExpenseCheck_d());

			//判断当前部门是否需要省份
			$this->assign('deptIsNeedProvince', $this->service->deptIsNeedProvince_d($_SESSION['DEPT_ID']));

			//可选择其他报销人以及公司的权限
			$this->assign('allApply', $this->service->this_limit['所有报销人']);
			$this->assign('allCompany', $this->service->this_limit['所有公司']);
            $this->assign('allCompanyForSQ', $this->service->this_limit['售前公司修改权限']);

			//获取定义的销售部门id
			$this->assign('saleDeptId', expenseSaleDeptId);

			//获取定义的过滤部门id
//            $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//			$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
            $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);
			$unSltDeptFilterStr = $this->unSltDeptFilter;// PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
            $this->assign('unSltDeptFilter', $unSltDeptFilterStr);

            // PMS613 费用归属部门为系统商销售只能选的费用承担人
            $feemansForXtsSales = $this->service->getFeemansForXtsSales();
            $this->assign('feemansForXtsSales', $feemansForXtsSales);

			$this->display('add');
		} else {
			$this->assign('userName', $_SESSION['USERNAME']);
			$this->assign('userId', $_SESSION['USER_ID']);
			$this->view('createtemplate');
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		$rtObj = $this->service->add_d($object);
		if (!is_array($rtObj)) {
			msgRf($rtObj);
		}
		if ($rtObj['id']) {
			if ($object['thisAuditType'] == 'audit') {
				$rangeId = '';
				//工程项目
				if ($object['projectType'] == 'esm' && $object['projectId']) {
					$esmprojectDao = new model_engineering_project_esmproject();
					$rangeId = $esmprojectDao->getRangeId_d($object['projectId']);
					$rangeId = "&billArea=" . $rangeId;
				}else if($object['DetailType'] == '4' && $object['CostBelongDeptId']){//售前费用选择了费用归属部门后，根据办事处中的归属部门带出服务经理
					$officeDao = new model_engineering_officeinfo_officeinfo();
					$rs = $officeDao->find(array('feeDeptId' => $object['CostBelongDeptId'],
							'module' => $object['module']),null,'managerCode');
					if(!empty($rs)){
						$rangeId = "&billArea=" . $rs['managerCode'];
					}
				}
				//判断是否进入延期报销单流程
				if ($rtObj['isLate'] == 1) {
					succ_show('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId=' . $rtObj['id'] . '&flowMoney=' . $object['Amount'] .
						'&billDept=' . $object['CostBelongDeptId'] . '&billCompany=' . $object['CostBelongComId'] . $rangeId);
				} else {
					succ_show('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId=' . $rtObj['id'] . '&flowMoney=' . $object['Amount'] .
						'&billDept=' . $object['CostBelongDeptId'] . '&billCompany=' . $object['CostBelongComId'] . $rangeId);
				}
			} elseif ($object['thisAuditType'] == 'check') {
				msgRf('提交成功！');
			} else {
				msgRf('保存成功！');
			}
		}
	}

	/**
	 * 跳转到编辑付款申请费用分摊明细表页面
	 */
	function c_toEdit() {
		$this->assignFunc($_GET);
		$obj = $this->service->getInfo_d($_GET['id']);
		$this->assignFunc($obj);

//		$deptArr = explode(',',unDeptFilter.$this->unDeptExtFilter);
        $deptArr = explode(',',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        //费用归属部门公司标识
		$comCode = '';
		if(in_array($_SESSION['DEPT_ID'],$deptArr)){
			//申请信息加载
			$this->assign('deptTempName','');
			$this->assign('deptTempId','');
		}else{
			//申请信息加载
			$this->assign('deptTempName',$obj['CostBelongDeptName']);
			$this->assign('deptTempId',$obj['CostBelongDeptId']);
			if(!empty($obj['CostBelongDeptId'])){
				$sql = "select comCode from department where DEPT_ID = ".$obj['CostBelongDeptId'];
				$rs = $this->service->_db->getArray($sql);
				if(!empty($rs)){
					$comCode = $rs[0]['comCode'];
				}
			}
		}
		$this->assign('comCode',$comCode);
		$this->assign('comCodeDefault',$comCode);
		//获取下载
		// $this->assign('downloadUrl', $this->service->getFile_d());
        $otherdatasDao = new model_common_otherdatas();
        $docUrl = $otherdatasDao->getDocUrl($this->bindId);
        $this->assign('downloadUrl', $docUrl);

		//附件添加{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

		//判断报销人的报销单是否要进行部门检查
		$this->assign('needExpenseCheck', $this->service->needExpenseCheck_d($obj['CostMan']));

		//判断当前部门是否需要省份
		$this->assign('deptIsNeedProvince', $this->service->deptIsNeedProvince_d($obj['CostBelongDeptId']));

		//获取定义的销售部门id
		$this->assign('saleDeptId', expenseSaleDeptId);

		//获取定义的过滤部门id
//        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//		$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);
        $unSltDeptFilterStr = $this->unSltDeptFilter;// PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
        $this->assign('unSltDeptFilter', $unSltDeptFilterStr);

        // PMS613 费用归属部门为系统商销售只能选的费用承担人
        $feemansForXtsSales = $this->service->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

		//根据条件判断加载方法
		if ($obj['isPush']) {
			//实例化查看页面
			$this->assignFunc($this->service->initEsmEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));

			if ($obj['DetailType'] == 4) {
				$this->view('edittrp');
			} else {
				$this->view('editesm');
			}
		} else {
			//可选择其他报销人以及公司的权限
			$this->assign('allApply', $this->service->this_limit['所有报销人']);
			$this->assign('allCompany', $this->service->this_limit['所有公司']);
            $this->assign('allCompanyForSQ', $this->service->this_limit['售前公司修改权限']);

			// 所属板块
			$this->assign('module', $obj['module']);

			//实例化查看页面
			$this->assignFunc($this->service->initTempEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));
			$this->display('edit');
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
		$rtObj = $this->service->edit_d($object);
		if (!is_array($rtObj)) {
			msgRf($rtObj);
		}
		if ($rtObj) {
			if ($object['thisAuditType'] == 'audit') {
				$rangeId = '';
				//工程项目
				if ($object['projectType'] == 'esm' && $object['projectId']) {
					$esmprojectDao = new model_engineering_project_esmproject();
					$rangeId = $esmprojectDao->getRangeId_d($object['projectId']);
					$rangeId = "&billArea=" . $rangeId;
				}
				//判断是否进入延期报销单流程
				if ($rtObj['isLate'] == 1) {
					succ_show('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId=' . $rtObj['id'] .
						'&flowMoney=' . $object['Amount'] . '&billDept=' . $object['CostBelongDeptId'] .
						'&billCompany=' . $object['CostBelongComId'] .
						$rangeId);
				} else {
					succ_show('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId=' . $rtObj['id'] .
						'&flowMoney=' . $object['Amount'] . '&billDept=' . $object['CostBelongDeptId'] .
						'&billCompany=' . $object['CostBelongComId'] .
						$rangeId);
				}
			} elseif ($object['thisAuditType'] == 'check') {
				msgRf('提交成功！');
			} else {
				msgRf('保存成功！');
			}
		}
	}

	/**
	 * 跳转到查看付款申请费用分摊明细表页面
	 */
	function c_toView() {
		$obj = $this->service->getInfo_d($_GET['id']);
		$this->assignFunc($obj);
		//渲染报销类型
		$this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

		//附件添加{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

		//实例化查看页面
		$expensedetailArr = $this->service->initTempView_d($obj['expensedetail']);
		$this->assignFunc($expensedetailArr);

		$this->view('view');
	}

	/**
	 *  部门检查 - ajax提交单据
	 */
	function c_ajaxHand() {
		echo $this->service->ajaxHand_d($_POST['id']) ? 1 : 0;
	}

	/**
	 *  部门检查 -ajax打回单据
	 */
	function c_ajaxBack() {
		echo $this->service->ajaxBack_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * 检查列表 - 编辑
	 */
	function c_toEditCheck() {
		$this->assignFunc($_GET);
		$obj = $this->service->getInfo_d($_GET['id']);
		$this->assignFunc($obj);

		$this->showDatadictsByName(array('CustomerTypeView' => 'KHLX'), $obj['CustomerType'], true);
		//$deptArr = explode(',',unDeptFilter.$this->unDeptExtFilter);
        $deptArr = explode(',',$this->DenyFegsdeptId.$this->unDeptExtFilter);
        //费用归属部门公司标识
		$comCode = '';
		if(in_array($_SESSION['DEPT_ID'],$deptArr)){
			//申请信息加载
			$this->assign('deptTempName','');
			$this->assign('deptTempId','');
		}else{
			//申请信息加载
			$this->assign('deptTempName',$obj['CostBelongDeptName']);
			$this->assign('deptTempId',$obj['CostBelongDeptId']);
			if(!empty($obj['CostBelongDeptId'])){
				$sql = "select comCode from department where DEPT_ID = ".$obj['CostBelongDeptId'];
				$rs = $this->service->_db->getArray($sql);
				if(!empty($rs)){
					$comCode = $rs[0]['comCode'];
				}
			}
		}
		$this->assign('comCode',$comCode);
		$this->assign('comCodeDefault',$comCode);
		//获取下载
		// $this->assign('downloadUrl', $this->service->getFile_d());
        $otherdatasDao = new model_common_otherdatas();
        $docUrl = $otherdatasDao->getDocUrl($this->bindId);
        $this->assign('downloadUrl', $docUrl);

		//附件添加{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

		//判断当前部门是否需要省份
		$this->assign('deptIsNeedProvince', $this->service->deptIsNeedProvince_d($obj['CostBelongDeptId']));

		//获取定义的销售部门id
		$this->assign('saleDeptId', expenseSaleDeptId);

		//获取定义的过滤部门id
        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
		//$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);
        $unSltDeptFilterStr = $this->unSltDeptFilter;// PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
        $this->assign('unSltDeptFilter', $unSltDeptFilterStr);

		//根据条件判断加载方法
		if ($obj['isPush']) {
			//实例化查看页面
			$this->assignFunc($this->service->initEsmEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));

			if ($obj['DetailType'] == 4) {
				$this->view('edittrpcheck');
			} else {
				$this->view('editesmcheck');
			}
		} else {
			//可选择其他报销人以及公司的权限
			$this->assign('allApply', $this->service->this_limit['所有报销人']);
			$this->assign('allCompany', $this->service->this_limit['所有公司']);
            $this->assign('allCompanyForSQ', $this->service->this_limit['售前公司修改权限']);

			// 所属板块
			$this->assign('module', $obj['module']);

			//实例化查看页面
			$this->assignFunc($this->service->initTempEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));
			$this->display('editcheck');
		}
	}

	/**
	 * 部门检查列表 - 部门收单
	 */
	function c_ajaxDeptRec() {
		echo $this->service->ajaxDeptRec_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * 部门检查列表 - 提交财务
	 */
	function c_ajaxHandFinance() {
		echo $this->service->ajaxHandFinance_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * 提交单据确认
	 */
	function c_handConfirm() {
		echo $this->service->handConfirm_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * 确认单据
	 */
	function c_confirmCheck() {
		echo $this->service->confirmCheck_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * 否认单据
	 */
	function c_unconfirmCheck() {
		echo $this->service->unconfirmCheck_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * ajax更新字段 - 单字段
	 */
	function c_ajaxUpdate() {
		$myValue = util_jsonUtil::iconvUTF2GB($_POST['myValue']);//修改的字段内容
		echo $this->service->update(array('id' => $_POST['id']), array($_POST['myKey'] => $myValue)) ? 1 : 0;
	}

	/*************************** 费用报销录入 - 工程部分 ***************/
	/**
	 * 费用报销录入
	 */
	function c_toEsmExpenseAdd() {
		$this->assign('thisDate', day_date);
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyId', $_SESSION['USER_ID']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('CostBelongCom', $_SESSION['USER_COM_NAME']);
		$this->assign('CostBelongComId', $_SESSION['USER_COM']);
		$this->assign('relDocType', $_GET['relDocType']);
		$this->assign('relDocId', $_GET['relDocId']);

		//项目id
		$projectId = isset($_GET['projectId']) ? $_GET['projectId'] : die();
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : 0;
		$days = isset($_GET['days']) ? $_GET['days'] : '';
		$memberId = isset($_GET['memberId']) ? $_GET['memberId'] : $_SESSION['USER_ID'];

		//获取工程项目报销内容
		$projectObj = $this->service->getEsmInfo_d($projectId);
		$this->assignFunc($projectObj);

		//日期处理
		$dayArr = $this->dayDeal($days);
		$this->assignFunc($dayArr);

		//获取下载
		// $this->assign('downloadUrl', $this->service->getFile_d());
        $otherdatasDao = new model_common_otherdatas();
        $docUrl = $otherdatasDao->getDocUrl($this->bindId);
        $this->assign('downloadUrl', $docUrl);

		// 源单数据获取
		if ($relDocType) {
			$registerDao = new model_outsourcing_vehicle_register();
			$expenseCostTypeArr = $registerDao->getAllKindsFeeById_d($_GET['relDocId']);
			$expenseCostTypeArr = $expenseCostTypeArr['fee'];
		} else {
			$esmcostdetailDao = new model_engineering_cost_esmcostdetail();
			$expenseCostTypeArr = $esmcostdetailDao->getCostForExpense_d($projectId, $dayArr['dayPeriod'], $memberId);
		}

		//实例化工程新增页面
		$expensedetailArr = $this->service->initEsmAdd_d($expenseCostTypeArr, $relDocType);
		if ($expensedetailArr) {
			$this->assignFunc($expensedetailArr);

			//进入项目判断 -- 如果是试用项目，则进入售前费用报销
			if ($projectObj['contractType'] == 'GCXMYD-04') {
				$this->view('addtrp');
			} else {
				$this->view('addesm');
			}
		} else {
			msgRf('没有可报销的费用，请选择其他日期的费用进行报销');
		}
	}

	/**
	 * 日期处理
	 * @param $days
	 * @return array
	 */
	function dayDeal($days) {
		$dayArr = explode(',', $days);
		$dayRtArr = array();
		foreach ($dayArr as $key => $val) {
			if (empty($dayRtArr)) {
				$dayRtArr['beginDate'] = $val;
				$dayRtArr['endDate'] = $val;
				$dayRtArr['dayPeriod'] = $days;
			} else {
				$dayRtArr['beginDate'] = min($dayRtArr['beginDate'], $val);
				$dayRtArr['endDate'] = max($dayRtArr['endDate'], $val);
				$dayRtArr['dayPeriod'] = $days;
			}
		}
		$dayRtArr['days'] = count($dayArr);
		return $dayRtArr;
	}

	/*************************** 模板 **************************/

	/**
	 * 费用信息模板渲染
	 */
	function c_initTempAdd() {
		if ($_POST['modelType']) {
			echo util_jsonUtil::iconvGB2UTF($this->service->initTempAdd_d($_POST['modelType']));
		} else {
			echo "";
		}
	}

	/**
	 * 分摊信息模板渲染
	 */
	function c_initCostshareTempAdd() {
		if ($_POST['modelType']) {
			echo util_jsonUtil::iconvGB2UTF($this->service->initCostshareTempAdd_d($_POST['modelType']));
		} else {
			echo "";
		}
	}

	/************************* 费用类型部分 ************************/
	/**
	 * 获取费用表
	 */
	function c_getCostType() {
		echo util_jsonUtil::iconvGB2UTF($this->service->getCostType_d());
	}

	/************************* 费用说明文档上传 *******************/
	/**
	 * 费用说明文档上传
	 */
	function c_setTypeDesc() {
		$this->assign('file', $this->service->getFilesByObjId(1, true, 'expenseselect'));
		$this->view('settypedesc');
	}

	/**
	 * 帮助旧报销跳转
	 */
	function c_toSubOldExpense() {
		$obj = $this->service->find(array('id' => $_GET['id']), null, 'ProjectNo');
		if ($obj['ProjectNo']) {
			succ_show("general/costmanage/reim/summary_check_list_pro.php?pro=" . $obj['ProjectNo']);
		} else {
			exit('跳转路径有误');
		}
	}

	/************************** 售前售后部门获取 ******************/
	/**
	 * 获取售前售后部门
	 */
	function c_getSaleDept() {
		echo util_jsonUtil::encode($this->service->getSaleDept_d($_GET['detailType']));
	}

	/**
	 * 判断部门是否需要省份信息
	 */
	function c_deptIsNeedProvince() {
		echo $this->service->deptIsNeedProvince_d($_POST['deptId']) ? 1 : 0;
	}

	/**
	 * ajax获取报销单信息
	 */
	function c_ajaxGet() {
		$obj = $this->service->find(array(
			'id' => $_POST['id']
		));
		echo util_jsonUtil::encode($obj);
	}

    /****************************** 用于录入预警的部分 ****************************/
    /**
     * 获取费用预警
     */
    function c_getWarning() {
        $k = $_POST['k'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $projectNos = $_POST['projectNos'];
        $projectIds = $_POST['projectIds'];
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;

        // 获取当前周期的报销费用
        $thisPeriodFee = $this->service->getPeriodFee_d($year, $month, $projectNos);

        // 获取当前周期的报销费用
        $prevPeriodFee = $this->service->getPeriodFee_d($prevYear, $prevMonth, $projectNos);

        echo json_encode(array(
            'thisPeriodFee' => $thisPeriodFee,
            'prevPeriodFee' => $prevPeriodFee,
            'changeRate' => $prevPeriodFee == 0 ? '--' : round(($thisPeriodFee - $prevPeriodFee)/$prevPeriodFee * 100, 2),
            'k' => $k,
            'projectNos' => $projectNos,
            'projectIds' => $projectIds
        ));
    }

    /**
     * 维护报销模板
     */
    function c_toModifyModel(){
        $this->view('toModifyModel');
    }
    function c_modifyModel(){
        $obj = $_POST['expenseModel'];
        $act = $obj['act'];
        unset($obj['act']);

        $obj['updateTime'] = date("Y-m-d H:i:s");
        $customtemplateDao = new model_finance_expense_customtemplate();
        $result = false;
        $optStr = "操作";
        switch ($act){
            case 'add':
                $optStr = "新增";
                $obj['userId'] = $_SESSION['USER_ID'];
                $obj['userName'] = $_SESSION['USER_NAME'];
                $result = $customtemplateDao->add_d($obj);
                break;
            case 'edit':
                $optStr = "编辑";
                $result = $customtemplateDao->edit_d($obj);
                break;
        }

        if($result){
            echo "<script>alert('".$optStr."成功!');parent.cleanPage();parent.loadList();parent.reloadParentModelList();</script>";
        }else{
            echo "<script>alert('".$optStr."失败!'); window.history.back();</script>";
        }
    }

    /**
     * 新增报销模板
     */
    function c_toAddModel(){
        $this->view('toAddModel');
    }

    /**
     * 编辑报销模块
     */
    function c_toEditModel(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $customtemplateDao = new model_finance_expense_customtemplate();
        $row = $customtemplateDao->get_d($id);
        $this->assignFunc($row);

        $this->view('toEditModel');
    }

    /**
     * 删除报销模块
     */
    function c_deleteModel(){
        $id = isset($_REQUEST['modelId'])? $_REQUEST['modelId'] : '';
        if($id != ""){
            $customtemplateDao = new model_finance_expense_customtemplate();
            $result = $customtemplateDao->delete(array("id" => $id));
            echo ($result)? "ok" : "fail";
        }else{
            echo "fail";
        }
    }

    /**
     * 获取所有报销单中存在的项目省份信息
     */
    function c_getAllProProvince(){
        $result = $this->service->getAllProProvince_d();
        $backArr['optsArr'] = array();
        foreach ($result as $row){
            if(isset($row['proProvinceId']) && $row['proProvinceId'] > 0){
                $backArr['optsArr'][$row['proProvinceId']] = $row['proProvince'];
            }
        }
        echo util_jsonUtil::encode($backArr);
    }
}