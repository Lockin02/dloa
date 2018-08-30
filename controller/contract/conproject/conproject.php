<?php
/**
 * @author liub
 * @Date 2014年5月29日 14:50:09
 * @version 1.0
 * @description:合同项目表控制层
 */
class controller_contract_conproject_conproject  extends controller_base_action
{

    function __construct() {
        $this->objName = "conproject";
        $this->objPath = "contract_conproject";
        parent::__construct();

    }

   /****************工程项目列表 添加 产品项目***********************************************************/
	/**
	 * 工程项目tab页 - 查看
	 */
	function c_viewTab() {
        $row = $this->service->get_d($_GET['id']);
        $this->assignFunc($row);
		$this->assign('id', $_GET['id']);
		$this->display('viewtab');
	}
	function c_viewProject(){
		$cid = $_GET ['id'];
		$row = $this->service->getProView($cid);
		$this->assignFunc($row);
		$this->display('viewProject');
	}
	//收入版本
	function c_incomeList(){
		$this->assign('id', $_GET['id']);
		$this->view('incomeList');
	}
	function c_incomeListJson()
    {
        $condition = "";
        $isMax = ($_POST['belongYear'] == 'max')? true : false;
        $service = $this->service;
        $pid = $_POST['projectId'];
        if(isset($_POST['belongYear']) && $_POST['belongYear'] != '' && $_POST['belongYear'] != 'max'){
            $isMax = false;
            $condition .= " and storeYear = {$_POST['belongYear']}";
        }

        if(isset($_POST['belongMonth']) && $_POST['belongMonth'] != '' && $_POST['belongMonth'] != 'max'){
            $isMax = false;
            $condition .= " and storeMon = {$_POST['belongMonth']}";
        }

        $rows = $service->getIncomeList($pid,$condition,$isMax);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = ($rows)? count($rows) : 0;
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }
    //成本版本
    function c_costList(){
		$this->assign('id', $_GET['id']);
		$this->view('costList');
	}
	function c_costListJson()
    {
        $service = $this->service;
        $pid = $_POST['projectId'];
        $rows = $service->getCostList($pid);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }
    //产品成本
    function c_productList(){
    	$this->assign('id', $_GET['id']);
		$this->view('productList');
    }
    function c_productListJson()
    {
		$service = $this->service;
		$projectId = $_POST['projectId'];
		$pArr = $service->get_d($projectId);
		$dao = new model_contract_contract_product();
		$rows = $dao->getPro_d($pArr['contractId'],$pArr['proLineCode']);
		if($rows){
			$equDao = new model_contract_contract_equ();
			foreach($rows as $key => $val){
                $equArr = $equDao->getByPcid_d($val['id'],$val['contractId']);
                $equCost = 0;
                foreach($equArr as $k => $v){
                	$equCost += $v['money'];
                	$equNumber += $v['number'];
                	$equExeNumber += $v['executedNum'];
                	$equBackNum += $v['backNum'];
                }
                $rows[$key]['cost'] = $equCost;
                if($equNumber - $equExeNumber + $equBackNum > 0){
                	$rows[$key]['isDone'] = "否";
                }else{
                	$rows[$key]['isDone'] = "是";
                }
			}
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}
	//发货清单
	function c_equList(){
    	$this->assign('id', $_GET['id']);
		$this->view('equList');
    }
    function c_equListJson()
    {
		$service = $this->service;
		$projectId = $_POST['projectId'];
		$pArr = $service->get_d($projectId);
		$rows = $service->getEquList($pArr['contractId'],$pArr['proLineCode']);

		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

    //更新收入版本
	function c_confirmIncome() {
		set_time_limit(0);
		echo $this->service->confirmIncome_d();
	}
	//更新成本版本
	function c_confirmCost() {
		set_time_limit(0);
		echo $this->service->confirmCost_d();
	}

	// 报销支付
    function c_feeCostMainList(){
        $cid = isset($_GET['cid'])? $_GET['cid'] : '';
        $proLineCode = isset($_GET['proLineCode'])? $_GET['proLineCode'] : '';

        $this->assign('proLineCode',$proLineCode);
        $this->assign('contractId',$cid);
        $this->assign('showType',"main");
        $this->view('feeCostList');
    }

    function c_feeCostDetailList(){
        $cid = isset($_GET['contractId'])? $_GET['contractId'] : '';
        $costTypeId = isset($_GET['costTypeId'])? $_GET['costTypeId'] : '';
        $proLineCode = isset($_GET['proLineCode'])? $_GET['proLineCode'] : '';

        $this->assign('proLineCode',$proLineCode);
        $this->assign('contractId',$cid);
        $this->assign('costTypeId',$costTypeId);
        $this->assign('showType',"detail");
        $this->view('feeCostList');
    }

    function c_feeCostListJson(){
        $cid = isset($_POST['cId'])? $_POST['cId'] : '';
        $costTypeId = isset($_POST['costTypeId'])? $_POST['costTypeId'] : '';
        $proLineCode = isset($_POST['proLineCode'])? $_POST['proLineCode'] : '';
        $arr = array();

        if($cid != ''){
            if($costTypeId != '' && $costTypeId != 'undefined'){
                $extSql = " and costTypeId = {$costTypeId} ";
                $rows = $this->service->getFeeCostCount($cid,$extSql,"detail");
            }else{
                $rows = $this->service->getFeeCostCount($cid);
            }
            $arr['collection'] = $cid;
        }else{
            $rows = array();
        }

        $proportion = $this->service->getAccBycid($cid, $proLineCode, 11);// 项目占比
        $workRate = round($proportion, 2);

        if($rows){
            foreach ($rows as $k => $v){
                $rows[$k]['costMoney'] = round($v['costMoney'] * $workRate / 100,2);
                if($rows[$k]['costMoney'] == 0){
                    unset($rows[$k]);// 所占金额为0的数据不显示
                }
            }
        }

        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil :: encode($rows);
    }

    //
    function c_listOsAndOtherCost(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $obj = $this->service->get_d($id);

        $otherCost = $this->service->getPotherCost($obj['projectCode']);
        $otherCost = bcadd($otherCost,0,2);

        $this->assign('otherCost',$otherCost);
        $this->view('listOsAndOtherCost');
    }

	/****************工程项目列表 添加 产品项目***********结束************************************************/



    /**
     * 跳转到合同项目表列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 跳转到新增合同项目表页面
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * 跳转到编辑合同项目表页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看合同项目表页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 合同项目列表(实时)
     */
    function c_conProjectList() {
        $this->view('conprojectList');
    }

    /**
     * 合同项目列表， 数据表
     */
    function c_conProjectStoreList() {
        $recordDao = new model_contract_conproject_conprojectRecord();
        $maxNum = $recordDao->getMaxVersion(1) - 1;
        $this->assign("maxVersion", $maxNum);
        $this->view('conprojectStoreList');
    }

    /**
     * 指标数据配置 tab
     */
    function c_indicatorTab() {
        $this->view('indicatorlTab');
    }

    /**
     * 合同项目汇总 tab
     */
    function c_conProjectCollectTab() {
        $this->view('conProjectCollectTab');
    }


    /**
     * 销售类 产品生成合同项目接 TEST口
     */
    function c_createProjectBySale($cid) {
        $this->service->updateEstimatesByEsmId($cid);

        //   	   $this->service->updateConProScheduleByCid(3543);

    }

    /**
     * 合同项目列表 获取数据json
     */
    function c_conprojectJson() {
        $service = $this->service;
        //执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        //权限处理
        $limitArr = array();
        if (isset ($service->this_limit['产品线权限']) && !empty ($service->this_limit['产品线权限']))
            $limitArr['proLine'] = $service->this_limit['产品线权限'];
        if (isset ($service->this_limit['执行区域权限']) && !empty ($service->this_limit['执行区域权限']))
        	$limitArr['exeDept'] = $service->this_limit['执行区域权限'];
        if (isset ($service->this_limit['板块权限']) && !empty ($service->this_limit['板块权限']))
        	$limitArr['module'] = $service->this_limit['板块权限'];
        //必须同时有3种权限才能看到数据
        if(!empty($limitArr['proLine']) && !empty($limitArr['exeDept']) && !empty($limitArr['module'])){
        	$service->getParam($_REQUEST);
        	$sqlStr = "sql: ";
        	//产品线权限处理
        	if (strstr($limitArr['proLine'], ';;') == false) {
        		$LimitArr = explode(",", $limitArr['proLine']);
        		foreach ($LimitArr as $k => $v) {
        			if ($k == 0) {
        				$sqlStr .= " AND ( FIND_IN_SET('$v',c.proLineCode)";
        			} else {
        				$sqlStr .= " OR FIND_IN_SET('$v',c.proLineCode)";
        			}
        			$k++;
        		}
        		$sqlStr .= ")";
        	}
        	//执行区域权限处理
        	if (strstr($limitArr['exeDept'], ';;') == false) {
        		$LimitArr = explode(",", $limitArr['exeDept']);
        		foreach ($LimitArr as $k => $v) {
	        		if ($k == 0) {
	        			$sqlStr .= " AND ( FIND_IN_SET('$v',projectProLine) OR (projectProLine IS NULL AND FIND_IN_SET('$v', conproExeDeptId))";
	        		} else {
	        			$sqlStr .= " OR ( FIND_IN_SET('$v',projectProLine) OR (projectProLine IS NULL AND FIND_IN_SET('$v', conproExeDeptId)))";
	        		}
        			$k++;
        		}
        		$sqlStr .= ")";
        	}
        	//板块权限处理
        	if (strstr($limitArr['module'], ';;') == false) {
        		$LimitArr = explode(",", $limitArr['module']);
        		foreach ($LimitArr as $k => $v) {
        			if ($k == 0) {
        				$sqlStr .= " AND ( FIND_IN_SET('$v',conModule)";
        			} else {
        				$sqlStr .= " OR FIND_IN_SET('$v',conModule)";
        			}
        			$k++;
        		}
        		$sqlStr .= ")";
        	}
        	$service->searchArr['mySearchCondition'] = $sqlStr;
        	$service->sort = "c.id";
        	$rows = $service->page_d("select_defaultNew");
        	// 		if($this->service->sort){
        	// 			$this->service->sort=str_replace('reserveEarnings','earnings',$this->service->sort);
        	// 			$this->service->sort=str_replace('exgrossTrue','(earnings-cost)',$this->service->sort);
        	// 			if($this->service->sort='grossTrue'){
        	// 				$this->service->sort=str_replace('grossTrue','(earnings-cost)',$this->service->sort);
        	// 			}
        	// 		}
        	//循环获取合同ID数组，并获取项目实时信息
        	$esmDao = new model_engineering_project_esmproject();
        	//        foreach($rows as $v){
        	//            $cidArr[]= $v['contractId'];
        	//        }
        	//        $esmArr = $esmDao->getProjectList_d($cidArr);
        	//循环，重构数组
        	foreach ($rows as $k => $v) {
        		if(!empty($v['contractId'])){//存在关联合同,才需获取相关实时数据
        			$esmId = $v['esmProjectId'];
        			$esmArr = $esmDao->getProjectList_d(array($v['contractId']));
        			$conArr = $service->getConPorjectNowInfoByCid($v, $esmArr[$esmId]);
        			$rows[$k]['proportion'] = $conArr['proportion'];
        			$rows[$k]['proportionTrue'] = $conArr['proportionTrue'];
        			$rows[$k]['proMoney'] = $conArr['proMoney'];
        			$rows[$k]['contractMoney'] = $conArr['contractMoney'];
        			$rows[$k]['txaRate'] = $conArr['txaRate'];
        			$rows[$k]['rateMoney'] = $conArr['rateMoney'];
        			$rows[$k]['exgross'] = $conArr['exgross'];
        			$rows[$k]['gross'] = $conArr['gross'];
        			$rows[$k]['estimates'] = $conArr['estimates'];
        			$rows[$k]['schedule'] = $conArr['schedule']; //进度
        			$rows[$k]['earnings'] = $conArr['earnings']; //收入
        			$rows[$k]['reserveEarnings'] = $conArr['earnings']*0.02; //预留营收 add by zzx 2016-1-26 17:37:59

        			$rows[$k]['deductMoney'] = $conArr['deductMoney']; //扣款
        			$rows[$k]['badMoney'] = $conArr['badMoney'];; //坏账

        			$rows[$k]['budget'] = $conArr['budget']; //预算
        			$rows[$k]['cost'] = $conArr['cost']; //决算
        			$rows[$k]['planBeginDate'] = $conArr['planBeginDate'];
        			$rows[$k]['planEndDate'] = $conArr['planEndDate'];
        			$rows[$k]['actBeginDate'] = $conArr['actBeginDate'];
        			$rows[$k]['actEndDate'] = $conArr['actEndDate'];

        			$rows[$k]['grossTrue'] = $conArr['earnings'] - $conArr['cost'] - $v['costAct']; //毛利
        			$rows[$k]['exgrossTrue'] = bcdiv($conArr['earnings'] - $conArr['cost'] - $v['costAct'], $conArr['earnings'], 2) * 100; //毛利率
        			$rows[$k]['module'] = $conArr['module']; //板块编码
        			$rows[$k]['moduleName'] = $conArr['moduleName']; //板块
        			$rows[$k]['officeId'] = $conArr['officeId']; //区域id
        			$rows[$k]['officeName'] = $conArr['officeName']; //区域
        		}else{
        			$rows[$k]['grossTrue'] = $rows[$k]['earnings'] - $rows[$k]['cost']; //毛利
        			$rows[$k]['exgrossTrue'] = bcdiv($rows[$k]['earnings'] - $rows[$k]['cost'], $rows[$k]['earnings'], 2) * 100; //毛利率
        			$rows[$k]['reserveEarnings'] = $rows[$k]['earnings']*0.02; //预留营收 add by zzx 2016-1-26 17:37:59
        		}
        	}
        }

        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 合同项目列表 -- 数据表
     */
    function c_conprojectStoreJson() {
        $service = $this->service;
        if(isset($_REQUEST['warningStr'])){
            if($_REQUEST['warningStr'] == '1'){
                $conditionSql = "1";
            }
            unset ($_REQUEST['warningStr']);
        }

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        $recordDao = new model_contract_conproject_conprojectRecord();
        $maxNum = $recordDao->getMaxVersion() - 1;

        //过滤型权限设置
        $limit = $this->service->initLimit();
        if ($limit == true) {
            //            $service->searchArr['version'] = $maxNum;
            if(!empty($conditionSql)){
                $service->searchArr['warningStr'] = $conditionSql;
            }
            $rows = $service->pageBySqlId("select_store");
        }
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }


    /**
     * 列表导出
     */
    function c_exportExcel() {
        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $rows = array();

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $version = $_GET['version'];
        $searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
        $searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
        $searchArr[$searchConditionKey] = $searchConditionVal;
        $searchArr['version'] = $version;
        if (isset($_SESSION['advSql'])) {
            $_REQUEST['advSql'] = $_SESSION['advSql'];
        }
        $service->getParam($_REQUEST);
        //		//登录人
        //		$appId = $_SESSION['USER_ID'];
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
        //过滤型权限设置
        $limit = $this->service->initLimit();
        if ($limit == true) {
            $service->sort = "c.pid";
            $rows = $service->listBySqlId("select_store");
        }
        foreach ($rows as $key => $row) {
            if(in_array("项目类型",$colArr)){
                if(empty($row['esmProjectId'])){
                    $rows[$key]['proType'] = '销售类';
                }else{
                    $rows[$key]['proType'] = '服务类';
                }
            }
            if(in_array("产线占比",$colArr)) $rows[$key]['proportionTrue'] /= 100;
            if(in_array("项目占比",$colArr)) $rows[$key]['proportion'] /= 100;
            if(in_array("项目进度",$colArr)) $rows[$key]['schedule'] /= 100;
            if(in_array("预计毛利率",$colArr)) $rows[$key]['exgross'] /= 100;
            if(in_array("毛利率",$colArr)) $rows[$key]['exgrossTrue'] /= 100;
            if(in_array("税点",$colArr)) $rows[$key]['txaRate'] /= 100;
        }
        //匹配导出列
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);
        foreach ($rows as $key => $row) {
            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }
            array_push($dataArr, $colIdArr);
        }

        foreach ($dataArr as $key => $val) {
            if (!empty($val['status'])) {
                $dataArr[$key]['status'] = $this->getDataNameByCode($val['status']);
            }
            if ($val['checkTip'] == '0') {
                $dataArr[$key]['checkTip'] = '-';
            } else {
                $dataArr[$key]['checkTip'] = '√';
            }
        }
        return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);
    }

    /**
     * 获取权限方法
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }


    /**
     * 确认收入核算方式
     */
    function c_incomeAcc() {
        $dao = new model_contract_conproject_conprojectRecord();
        $row = $dao->get_d($_GET['id']);
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign("pid", $_GET['pid']);
        $this->assign("id", $_GET['id']);
        $this->view('incomeAcc');
    }

    function c_incomeAccEdit() {
        $rows = $_POST ['acc'];
        $id = $this->service->incomeAccEdit_d($rows);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '确认成功！';
        if ($id) {
            msg($msg);
        }
    }

    /**
     * ajax 更新考核标识
     */
    function c_ajaxCheckTip() {
        $id = $_POST ['id'];
        $pid = $_POST ['pid'];
        $val = $_POST ['val'];


        $sql = "update oa_contract_project set checkTip='" . $val . "' where id = '" . $pid . "'";
        $sql2 = "update oa_contract_project_record set checkTip='" . $val . "' where id = '" . $id . "'";
        $this->service->_db->query($sql);
        $this->service->_db->query($sql2);
        echo 1;
    }

    //导入合同项目
    function c_toExcel() {
        $this->view("excel");
    }

    function c_upExcel() {
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $service = $this->service;
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $dao = new model_contract_conproject_importConprojectUtil ();
            $excelData = $dao->readExcelData($filename, $temp_name);
            spl_autoload_register('__autoload');
            $resultArr = $service->importProInfo_d($excelData);
            if ($resultArr)
                echo util_excelUtil :: finalceResult($resultArr, "导入结果", array(
                    "合同编号",
                    "结果"
                ));
            else
                echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    //收入确认方式及 考核标识导入 扩展
    function c_toExcelExtend() {
        $this->view("excelExtend");
    }

    function c_upExcelExtend(){
            set_time_limit(0);
            ini_set('memory_limit', '128M');
            $service = $this->service;
            $filename = $_FILES ["inputExcel"] ["name"];
            $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
            $fileType = $_FILES ["inputExcel"] ["type"];
            if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $dao = new model_contract_conproject_importConprojectUtil ();
                $excelData = $dao->readExcelData($filename, $temp_name);
                spl_autoload_register('__autoload');
                $resultArr = $service->importExtend_d($excelData);
                if ($resultArr)
                    echo util_excelUtil :: finalceResult($resultArr, "导入结果", array(
                        "合同编号",
                        "结果"
                    ));
                else
                    echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        }


    /**
     *  数据更新进度
     */
    function c_progressView() {
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        $service = $this->service;
        $esmDao = new model_engineering_project_esmproject();
        $arr = $service->list_d();
        foreach ($arr as $k => $v) {
        	if(!empty($v['contractId'])){//存在关联合同,才需获取相关实时数据
        		$esmId = $v['esmProjectId'];
        		$esmArr = $esmDao->getProjectList_d(array($v['contractId']));
        		$conArr = $service->getConPorjectNowInfoByCid($v, $esmArr[$esmId]);
        		$arr[$k]['proportion'] = $conArr['proportion'];
        		$arr[$k]['proportionTrue'] = $conArr['proportionTrue'];
        		$arr[$k]['proMoney'] = $conArr['proMoney'];
        		$arr[$k]['contractMoney'] = $conArr['contractMoney'];
        		$arr[$k]['txaRate'] = $conArr['txaRate'];
        		$arr[$k]['rateMoney'] = $conArr['rateMoney'];
        		$arr[$k]['exgross'] = $conArr['exgross'];
        		$arr[$k]['gross'] = $conArr['gross'];
        		$arr[$k]['estimates'] = $conArr['estimates'];
        		$arr[$k]['schedule'] = $conArr['schedule']; //进度
        		$arr[$k]['earnings'] = $conArr['earnings']; //收入
        		$rows[$k]['reserveEarnings'] = $conArr['earnings']*0.02; //预留营收 add by zzx 2016-1-26 17:37:59

        		$arr[$k]['deductMoney'] =  $conArr['deductMoney']; //扣款
        		$arr[$k]['badMoney'] =  $conArr['badMoney']; //坏账

        		$arr[$k]['budget'] = $conArr['budget']; //预算
        		$arr[$k]['cost'] = $conArr['cost']; //决算
        		$arr[$k]['planBeginDate'] = $conArr['planBeginDate'];
        		$arr[$k]['planEndDate'] = $conArr['planEndDate'];
        		$arr[$k]['actBeginDate'] = $conArr['actBeginDate'];
        		$arr[$k]['actEndDate'] = $conArr['actEndDate'];

        		$arr[$k]['grossTrue'] = $conArr['earnings'] - $conArr['cost'] - $v['costAct']; //毛利
        		$arr[$k]['exgrossTrue'] = bcdiv($conArr['earnings'] - $conArr['cost'] - $v['costAct'], $conArr['earnings'], 2) * 100; //毛利率
        		$arr[$k]['module'] = $conArr['module']; //板块编码
        		$arr[$k]['moduleName'] = $conArr['moduleName']; //板块
        		$arr[$k]['officeId'] = $conArr['officeId']; //区域id
        		$arr[$k]['officeName'] = $conArr['officeName']; //区域
        	}else{
        		$arr[$k]['grossTrue'] = $arr[$k]['earnings'] - $arr[$k]['cost']; //毛利
        		$arr[$k]['exgrossTrue'] = bcdiv($arr[$k]['earnings'] - $arr[$k]['cost'], $arr[$k]['earnings'], 2) * 100; //毛利率
        		$rows[$k]['reserveEarnings'] = $rows[$k]['earnings']*0.02; //预留营收 add by zzx 2016-1-26 17:37:59
        	}
        }
        $rows['rows'] = $arr;
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'progressView', $rows);
    }

    /**
     * 判断当前月份是否 已有数据
     */
    function c_ajaxIsUse() {
        $date = $_POST['date'];
        $year = substr($date, 0, 4);
        $mon = substr($date, -2);

        $sql = "select * from oa_contract_project_record where storeYear='" . $year . "' and storeMon='" . $mon . "' and isUse=1";
        $arr = $this->service->_db->getArray($sql);
        if (!empty($arr)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 保存版本
     */
    function c_saveVersionView() {
        $this->assign("versionNum",$_GET['versionNum']);
        $this->view("saveVersionView");
    }

    /**
     * 记录版本
     */
    function c_saveVersion() {

        $rows = $_POST ['acc'];
        $year = substr($rows['storeMon'], 0, 4);
        $mon = substr($rows['storeMon'], -2);
        $recordDao = new model_contract_conproject_conprojectRecord();
        $maxNum = $recordDao->getMaxVersion() - 1;
        if($rows['versionNum'] == 'undefined'){
            $versionNum = $maxNum;
        }else{
            $versionNum = $rows['versionNum'];
        }
        $sql = "update oa_contract_project_record set isUse='0' where storeYear='" . $year . "' and storeMon='" . $mon . "' and isUse=1";
        $sql1 = "update oa_contract_project_record set isUse='1',storeYear='" . $year . "',storeMon='" . $mon . "' where version='" . $versionNum . "'";

        $this->service->query($sql);
        $this->service->query($sql1);

        msg("保存成功！");
    }

    /**
     * 根据 年，月 获取版本数据
     */
    function c_getVarsionArr() {
        $year = $_POST['year'];
        $mon = $_POST['mon'];

        $sql = "select version,isuse from oa_contract_project_record where storeYear='" . $year . "' and storeMon='" . $mon . "' GROUP BY version order by version desc";
        $arr = $this->service->_db->getArray($sql);
        if (!empty($arr)) {
            $versionOption = <<<EOT
                  <option value="0" style="color:black">......</option>
EOT;
            foreach ($arr as $k => $v) {
                if ($v['isuse'] == '1') {
                    $tep = "red";
                    $tepC = "(存)";
                } else {
                    $tep = "black";
                    $tepC = "";
                }
                $versionOption .= <<<EOT
                  <option value="$v[version]" style="color:$tep">$v[version]$tepC</option>
EOT;
            }
            echo util_jsonUtil :: iconvGB2UTF($versionOption);
        } else {
            echo 0;
        }
    }

    /**
     * 可编辑表格 从表数据
     */
    function c_conProsubJson() {
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $lockDao = new model_stock_lock_lock ();
        $contractDao = new model_contract_contract_equ();
        $service = $this->service;
        $service->getParam($_POST);

        $pid = $_POST['pid'];
        $rows = $service->conProsubJson_d($pid);

        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }


    /****************** 产品线项目汇总表 *********************/
    /**
     * 列表显示
     */
    function c_toLineProjectView() {
        if (isset ($_GET ['thisYear'])) {
            $initArr = $_GET;
        } else {
            $thisYear = date('Y');
            $thisMonth = date('m') * 1;

            //初始化数组
            $initArr = array(
                "thisYear" => $thisYear, "beginMonth" => $thisMonth, 'endMonth' => $thisMonth,
                'company' => 'all', 'DetailType' => 'all'
            );
        }


        $this->assignFunc($initArr);
        $this->view('lineProjectView');
    }


    /**
     *  根据合同id，产品线编码，判断产品线在合同内是否存在
     * @param $cid ,$proLineCode
     *  return  0 - 存在 1 - 不存在
     */
    function c_getisExistByLine() {
        $cid = $_POST['cid'];
        $proLineCode = $_POST['productLine'];
        $reStr = $this->service->getisExistByLine($cid, $proLineCode);
        echo $reStr;
    }

    /*
     * 根据项目ID获取相关轨迹时间
     */
    function c_listProjectTrack(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $trackArr = $this->service->getTrackAndTime($id);
        $pointTrStr = "";
        if(is_array($trackArr['dateInfo'])){
            $catchArr = array();
            $pointCodeArr = array('createDate','shipFirstDate','shipFinishDate','firstInvoiceDate','invoiceCompleteDate','changeDate');
            foreach ($trackArr['dateInfo'] as $date){
                if($date['time'] != ''){
                    $catchArr[$date['time']][] = $date['key'];
                }
            }
            foreach ($catchArr as $key => $val){
                $rowStr = "<tr><td>{$key}</td>";
                foreach ($pointCodeArr as $v){
                    $rowStr .= (in_array($v,$val))? "<td><img src='images/icon/icon088.gif'></td>" : "<td></td>";
                }
                $rowStr .= "</tr>";
                $pointTrStr .= $rowStr;
            }
        }

        $this->assign('pointTrStr',$pointTrStr);
        $this->view("listProjectTrack");
    }

    /**
     * 合同项目  财务数据导入 （暂 只提供决算导入）
     */
    function c_toLeadfinanceMoney() {
        $this->assign("dateTime", date("Y-m-d"));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= $thisYear - 5; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->view("toleadfinanceMoney");
    }


    /**
     * 上传EXCEL
     */
    function c_finalceMoneyImport() {
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $import = $_POST['import'];
        $objNameArr = array(
            0 => 'projectCode', //编号
            1 => 'money', //金额
        );
        $infoArr = array(
            "importMonth" => $import['Year'] . $import['Month'], //导入月份
            "moneyType" => $import['importInfo'], //金额类型
            "importName" => $_SESSION['USERNAME'], //导入人
            "importNameId" => $_SESSION['USER_ID'], //导入人ID
            "importDate" => date("Y-m-d:h:m:s"), //导入时间
        );
        $this->service->addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $import['normType']);

        //        if ($import['importType'] == "初始化导入") {
        //            $this->service->addFinalceMoneyExecel_d($objNameArr, $import['importInfo'], $import['normType']);
        //        } else {
        //共用信息 单独
        //        }
    }

    /**
     * 验证导入的金额是否已存在
     */
    function c_getFimancialImport() {
        $importType = util_jsonUtil :: iconvUTF2GB($_POST['importType']);
        $importMonth = util_jsonUtil :: iconvUTF2GB($_POST['importMonth']);
        $importSub = util_jsonUtil :: iconvUTF2GB($_POST['importSub']);

        if ($importType == "按月导入") {
            $num = $this->service->getFimancialImport_d($importMonth, $importSub);
            echo $num;
        } else {
            echo "0";
        }
    }

    /**
     * 导入金额明细列表Tab
     */
    function c_financialDetailTab() {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetailTab");
    }

    /**
     * 导入金额信息
     */
    function c_financialDetail() {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetail");
    }

    function c_financialdetailpageJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $rows = $service->getFinancialDetailInfo($conId, $tablename, $moneyType);
        //echo "<pre>";
        //print_R($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 导入金额信息
     */
    function c_financialImportDetail() {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialImportDetail");
    }

    function c_financialImportDetailpageJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $sql = $service->getFinancialImportDetailInfo($conId, $tablename, $moneyType);
        $service->sort = false;
        $rows = $service->pageBySql($sql);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 产品线指标表  --json数据
     */
    function c_productLineReportJson() {
        $service = $this->service;
        //        $service->getParam ( $_REQUEST );
        $rows = $service->lineProjectData($_REQUEST);
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);

    }

    /**
     * 产品营收--临时  --json数据
     */
    function c_contractProReportJson() {
        $service = $this->service;
        if(isset($_REQUEST['warningStr'])){
            if($_REQUEST['warningStr'] == '1'){
                $conditionSql = "sql: and (
         sr <> 0 and cb <> 0
       )";
            }
            unset ($_REQUEST['warningStr']);
        }
                $service->getParam ( $_REQUEST );
//        $sql = $service->contractProData();
        $service->sort = false;
        $sql = $this->service->getProSql();
        if(!empty($conditionSql)){
            $service->searchArr['warningStr'] = $conditionSql;
        }
        $row = $service->pageBySql($sql);
        //计算占比，然后获取统计表数据 得到相关收入及成本（暂时处理方案）
        $rows = $service->handleProRow($row);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);

    }
    //
    function c_toViewContract(){
        $idStr = $_GET['ids'];
        $idArr = explode(",",$idStr);
        $idArr = array_flip($idArr);
        $idArr = array_flip($idArr);
        $idStr = implode(",",$idArr);
        $sql = "select * from oa_contract_contract where id in ($idStr)";
        $row = $this->service->_db->getArray($sql);
        $html = "<table><tr><td>合同编号</td></tr>";
        foreach($row as $v){
            $html .= "<tr><td>".$v['contractCode']."</td></tr>";
        }
        $html .= "</table>";
        echo $html;
    }

    /**************************图表*******************************************************************/

    // 图表 -- Echarts
    function c_conProjectEcharts() {
        //获取配置时间区间
        $dateArr = $this->service->getSection();
        $this->assign("section",$dateArr['startMonth']." ~ ".$dateArr['endMonth']);
        $this->view("conProjectEcharts");
    }

    /**********ajax 获取数据方法**********************/
    //项目数量
    function c_getConNumChart() {
        $rows = $this->service->conProEchartsJson();
        echo util_jsonUtil::encode($rows);
    }

    //项目类型-图表
    function c_getConNumChartPie() {
        $rows = $this->service->conProEchartsPieJson();
        echo util_jsonUtil::encode($rows);
    }

    //营收状况
    function c_getRevenueChart() {
        $rows = $this->service->conProRevenueChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //毛利状况
    function c_getGrossChart() {
        $rows = $this->service->conProGrossChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //毛利率对比
    function c_getRateGrossChart() {
        $rows = $this->service->conProRateGrossChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //项目数量分布
    function c_getProNumMapChart() {
        $rows = $this->service->conProNumMapChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //获取区间内的最大版本号\
    function c_getMaxNum(){
        $endMonth = $_POST['endMonth'];
        $endYear = substr($endMonth, 0, 4);
        $endMon = substr($endMonth, -2);
        $sql = "select max(version) as maxNum from oa_contract_project_record where storeYear <= '".$endYear."' and storeMon <= '".$endMon."' and isUse=1";
        $arr = $this->service->_db->getArray($sql);
        echo $arr[0]['maxNum'];
    }

    /**
     * 更新 合同产品项目冗余值
     */
    function c_updateSaleProjectVal(){
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : '';
        $this->service->updateSaleProjectVal_d($projectCode);
        echo 1;
    }

    /**
     * 更新 合同产品项目最新版本存档
     */
    function c_updateConprojectVersion(){
        set_time_limit(0);
        $service =  $this->service;
        $service->autoUpdateConprojectVersion_d();
        echo 1;
    }
}