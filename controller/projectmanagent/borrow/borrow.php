<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author Administrator
 * @Date 2011年5月9日 15:19:33
 * @version 1.0
 * @description:借试用控制层
 */
class controller_projectmanagent_borrow_borrow extends controller_base_action
{

    function __construct() {
        $this->objName = "borrow";
        $this->objPath = "projectmanagent_borrow";
        parent::__construct();
    }

    /**
     * 跳转到新增页面
     */
    function c_toAdd() {
        $this->assign('borrowInput', BORROW_INPUT);
        $chanceId = isset($_GET['id']) ? $_GET['id'] : null;
        if ($chanceId) {
            $this->permCheck($chanceId, 'projectmanagent_chance_chance');
            $chanceDao = new model_projectmanagent_chance_chance();
            $rows = $chanceDao->get_d($chanceId);
            //复制license数据
            $licenseDao = new model_yxlicense_license_tempKey();
            $rows = $licenseDao->copyLicense($rows);

            foreach ($rows as $key => $val) {
                $this->assign($key, $val);
            }
            //用于新增也 源单类型
            $chanceType = "chance";
        }
		//获取个人申请累计金额，并格式化为千分位
		$rs = $this->service->getPersonalEquMoney($_SESSION['USERNAME']);
		$equMoney = number_format($rs[0]['equMoney'],2);

		$this->assign('equMoney', $equMoney);
		$this->assign('chanceId', $chanceId);
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->assign('salesName', $_SESSION['USERNAME']);
        $this->assign('salesNameId', $_SESSION['USER_ID']);
        $this->assign('createTime', day_date);
        $this->assign('customerName', isset($rows['customerName']) ? $rows['customerName'] : null);
        $this->assign('customerId', isset($rows['customerId']) ? $rows['customerId'] : null);
        $this->assign('customerType', isset($rows['customerType']) ? $rows['customerType'] : null);
        $this->assign('SingleType', isset($chanceType) ? $chanceType : null);
        /*************商机下推借试用冗余信息*******************/
        $this->assign('chanceCode', isset($rows['chanceCode']) ? $rows['chanceCode'] : null);
        $this->assign('chanceId', isset($rows['id']) ? $rows['id'] : null);
        /***************************************************/
        $this->view('add');
    }

    /*
	 * 跳转到借试用
	 */
    function c_toBorrowList() {
        $this->assign('returnLimit', $this->service->this_limit['设备归还'] ? 1 : 0);
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_borrowGridJson() {
        $service = $this->service;

        if(util_jsonUtil::iconvUTF2GB($_REQUEST['ExaStatus']) == "变更审批中"){
            $_REQUEST['changingExaStatus'] = "变更审批中";
            unset($_REQUEST['ExaStatus']);
        }

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $privlimit = isset ($sysLimit ['部门']) ? $sysLimit ['部门'] : null;
        if (!empty($privlimit)) {
            $service->searchArr['mySearchCondition'] = "sql: and ( u.DEPT_ID in(".$privlimit.") or c.createId='".$_SESSION['USER_ID']."')";
        } else {
            $service->searchArr['createId'] = $_SESSION['USER_ID'];
        }

        //过滤型权限设置
//		$limit = $this->initLimit();
        $limit = true;
        if ($limit == true) {
            //$service->asc = false;
            $rows = $service->page_d();
            //归还状态
            foreach ($rows as $k => $v) {
                $backStatus = $service->backStatus($v['id']);
                $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
            }
        }

        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $_SESSION['kjy_borrowJsonSql'] = $service->listSql;
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    function c_ajaxChkExportLimit(){
//        echo "<pre>";print_r($this->service->this_limit);exit();
        if(isset($this->service->this_limit['导出权限']) && $this->service->this_limit['导出权限'] == 1){
            echo 1;
        }else{
            echo 0;
        }
    }

    function c_exportExcel(){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $colIdStr = isset($_GET['colId'])? $_GET['colId'] :'';
        $colNameStr = isset($_GET['colName'])? $_GET['colName'] :'';

        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);

        $sql = isset($_SESSION['kjy_borrowJsonSql'])? $_SESSION['kjy_borrowJsonSql'] : '';
        $dataArr = $this->service->_db->getArray($sql);

        foreach ($dataArr as $k => $v){
            $dataArr[$k]['checkFile'] = ($v['checkFile'] == '有')? '有' : '否';

            // 发货状态转义
            switch($v['DeliveryStatus']){
                case 'WFH':
                    $dataArr[$k]['DeliveryStatus'] = '未发货';
                    break;
                case 'YFH':
                    $dataArr[$k]['DeliveryStatus'] = '已发货';
                    break;
                case 'BFFH':
                    $dataArr[$k]['DeliveryStatus'] = '部分发货';
                    break;
                case 'TZFH':
                    $dataArr[$k]['DeliveryStatus'] = '停止发货';
                    break;
            }

            // 归还状态转义
            switch($v['backStatusCode']){
                case '0':
                    $dataArr[$k]['backStatus'] ='未归还';
                    break;
                case '1':
                    $dataArr[$k]['backStatus'] ='已归还';
                    break;
                case '2':
                    $dataArr[$k]['backStatus'] ='部分归还';
                    break;
            }
        }

        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr, "客户借试用导出");
    }

    /**
     * 权限设置
     * 权限返回结果如下:
     * 如果包含权限，返回true
     * 如果无权限,返回false
     */
    function initLimit() {
        $service = $this->service;
        //权限配置数组
        $limitConfigArr = array(
            'customerTypeLimit' => 'customerType'
        );
        //权限数组
        $limitArr = array();
        //权限系统
        if (isset ($this->service->this_limit['客户类型']) && !empty ($this->service->this_limit['客户类型']))
            $limitArr['customerTypeLimit'] = $this->service->this_limit['客户类型'];
        if (strstr($limitArr['customerTypeLimit'], ';;')) {
            return true;
        } else {
            if (empty ($limitArr)) {
                return false;
            } else {
                //配置混合权限
                $i = 0;
                $sqlStr = "sql:and ( ";
                $k = 0;
                foreach ($limitArr as $key => $val) {
                    $arr = explode(',', $val);
                    if (is_array($arr)) {
                        $val = "";
                        foreach ($arr as $v) {
                            $val .= "'" . $v . "',";
                        }
                        $val = substr($val, 0, -1);
                    }
                    if ($i == 0) {
                        $sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
                    } else {
                        $sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
                    }
                    $i++;
                }
                $sqlStr .= ")";
                if (empty($limitArr)) {
                    $sqlStr = "";
                }
                if (!empty($goodsLimitStr)) {
                    $sqlStr .= $goodsLimitStr;
                }
                $service->searchArr['mySearchCondition'] = $sqlStr;
                return true;
            }
        }
    }

    /**
     * 跳转员工个人借试用-tab
     */
    function c_toProBorrowListTab() {
    	$this->display('proborrowlist-tab');
    }

    /**
     * 跳转员工个人借试用
     */
    function c_toProBorrowList() {
        $this->assign('returnLimit', $this->service->this_limit['设备归还'] ? 1 : 0);
        $this->display('proborrowlist');
    }

    /**
     * 跳转员工个人借试用设备清单
     */
    function c_toProBorrowEquList() {
    	$this->display('proborrowequlist');
    }

    /**
     * 员工借试用新增页面
     */
    function c_toProAdd() {
        $this->assign('borrowInput', BORROW_INPUT);
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('createId', $_SESSION['USER_ID']);

        $dept = new model_common_otherdatas();
        $this->assign('createSection', $dept->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'));
        $this->assign('createSectionId', $_SESSION['DEPT_ID']);
        //判断员工借试用是否需要走发货流程
        $isProShipcondition = isproShipcondition;
        $isProShipcondition = explode(",", $isProShipcondition);
        if (in_array($_SESSION['DEPT_ID'], $isProShipcondition)) {
            $this->assign('isShipTip', "1");
        }
        $this->assign('createTime', day_date);

        //获取默认发送人
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('tostorageName', $mailUser['oa_borrow_borrow']['tostorageName']);
        $this->assign('tostorageNameId', $mailUser['oa_borrow_borrow']['tostorageNameId']);
        $this->view('proadd');
    }

    /**
     * 员工借试用汇总列表
     */
    function c_toProBorrowAll() {
        $this->assign('returnLimit', $this->service->this_limit['设备归还'] ? 1 : 0);
        $this->display('proborrowall');
    }

    /**
     * 员工借试用---仓管确认列表
     */
    function c_toStorage() {
        $this->display('tostorage');
    }

    /**
     * 我的审批 - tab
     */
    function c_auditTab() {
        $this->display('audittab');
    }

    /**
     * 我的审批 － 未审批页面
     */
    function c_toAuditNo() {
        $this->display('auditno');
    }

    /**
     * 我的审批 － 已审批的页面
     */
    function c_toAuditYes() {
        $this->display('audityes');
    }

    /**
     * 我的借试用-tab
     */
    function c_toMyBorrowListTab() {
    	$this->display('mylist-tab');
    }

    /**
     * 我的借试用
     */
    function c_toMyBorrowList() {
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->assign('salesNameId', $_SESSION['USER_ID']);
        $this->assign('deptName',$_SESSION['DEPT_NAME']);
        $this->display('mylist');
    }

    /**
     * 我的借试用-设备清单
     */
    function c_toMyBorrowEquList() {
    	$this->display('myequlist');
    }

    /**
     * 合同查看tab ---转销售源单信息
     */
    function c_toBocomeCon() {
        $contractId = $_GET['contractId'];
        $conDao = new model_contract_contract_equ();
        $borrowIdarr = $conDao->getBorrowIds($contractId);
        foreach ($borrowIdarr as $k => $v) {
            $borrowIds .= $v['toBorrowId'] . ",";
        }
        $borrowIds = Trim($borrowIds, ",");
        $this->assign("ids", $borrowIds);
        $this->display("tobecomecon");
    }

    /****************************************借用发货列表start*******************************************************/

    /**
     * 个人借试用发货tab
     */
    function c_personShipTab() {
        $this->assign('limits', $_GET['limits']);
        $this->display('person-shiptab');
    }


    /**
     * 借试用物料确认需求
     */
    function c_assignment() {
        $this->assign('limits', $_GET['limits']);
        $this->display('assignments');
    }

    /**
     * 借试用发货
     */
    function c_toBorrowShipments() {
        if (isset($_GET['finish']) && $_GET['finish'] == 1) {
            $this->assign('listJS', 'borrow-shipped.js');
        } else {
            $this->assign('listJS', 'borrow-shipments.js');
        }
        $this->assign('inSeaIds', isproShipcondition);
        $this->assign('limits', $_GET['limits']);
        $this->display('shipments');
    }


    /**
     * 借试用发货
     */
    function c_toSeaShipments() {
        $this->assign('outSeaIds', isproShipcondition);
        $this->assign('limits', '员工');
        $this->display('seashipments');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_shipmentsPageJson() {
        $rateDao = new model_stock_outplan_contractrate();
        $service = $this->service;
        // 参数处理  -【添加新流程】罗权洲：填写+物料确认-下达发货计划-销售关联商机-提交审批 By weijb 2015.10.20
        $ExaStatus = $_REQUEST['ExaStatus'];
        unset($_REQUEST['ExaStatus']);
        $ExaStatusArr = $_REQUEST['ExaStatusArr'];
        unset($_REQUEST['ExaStatusArr']);
        $lExaStatusArr = $_REQUEST['lExaStatusArr'];
        unset($_REQUEST['lExaStatusArr']);
        $DeliveryStatus2 = $_REQUEST['DeliveryStatus2'];
        unset($_REQUEST['DeliveryStatus2']);
        $limits = $_REQUEST['limits'];
        unset($_REQUEST['limits']);
        $isproShipcondition = $_REQUEST['isproShipcondition'];
        unset($_REQUEST['isproShipcondition']);
        $isproShipconditionAs = $_REQUEST['isproShipconditionAs'];
        unset($_REQUEST['isproShipconditionAs']);
        $service->getParam($_REQUEST);
        $mySql = "sql:";
        if(!empty($ExaStatus)){
        	$mySql .= " and ((c.ExaStatus ='".$ExaStatus."'";
        }
        if(!empty($ExaStatusArr)){
        	$mySql .= " and ((c.ExaStatus in(".util_jsonUtil::strBuild($ExaStatusArr).")";
        }
        if(!empty($lExaStatusArr)){
        	$mySql .= " and l.ExaStatus in(".util_jsonUtil::strBuild($lExaStatusArr).")";
        }
        if(!empty($DeliveryStatus2)){
            // 是延期申请的
            if(isset($_REQUEST['isDelayApply']) && $_REQUEST['isDelayApply'] == 1){
                $mySql .= " and ( c.DeliveryStatus in (".util_jsonUtil::strBuild($DeliveryStatus2).") or (c.isDelayApply = 1))";
            }else{
                $mySql .= " and c.DeliveryStatus in (".util_jsonUtil::strBuild($DeliveryStatus2).")";
            }
        }
        if(empty($DeliveryStatus2) && (isset($_REQUEST['isDelayApply']) && $_REQUEST['isDelayApply'] == 1)){
            $mySql .= " and (c.isDelayApply = 0 or c.isDelayApply = 1)";
        }
        if(!empty($limits)){
        	$mySql .= " and c.limits='".$limits."'";
        }
        if(!empty($isproShipcondition)){
        	$mySql .= " and c.isproShipcondition='".$isproShipcondition."'";
        }
        if(!empty($isproShipconditionAs)){
        	$mySql .= " and (c.isproShipcondition='".$isproShipconditionAs."' or c.isship='".$isproShipconditionAs."') ";
        }
        //PMS 164 去掉该条件
//        $mySql .= ") or c.createId = 'quanzhou.luo') ";// 由罗权洲新增的单据,这里写死
        $mySql .= ") ) ";

        $service->searchArr['mySearchCondition'] = $mySql;
        //$service->asc = false;

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $sysLimit2 = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $sqlId = '';
        if(!empty($sysLimit['销售区域']) && !empty($sysLimit2['申请人部门权限'])){
            if(!strstr($sysLimit['销售区域'],";;") || !strstr($sysLimit2['申请人部门权限'],";;")){
                $applyDeptLimit = ($sysLimit2['申请人部门权限'] == '')? "" :  "or u.DEPT_ID IN(".$sysLimit2['申请人部门权限'].")";
                $areaCodeSql = "sql: and (ce.areaCode IN(".$sysLimit['销售区域'].") ".$applyDeptLimit.")";
                $areaCodeSql = str_replace(',*,',',',$areaCodeSql);
                $areaCodeSql = str_replace(',,',',',$areaCodeSql);
                $service->searchArr['areaCodeSql'] = $areaCodeSql;
            }
            $sqlId = 'select_assignment';
        }else if(!empty($sysLimit['销售区域'])){
            if(!strstr($sysLimit['销售区域'],";;")){
                $service->searchArr['areaCodeSql'] = "sql: and (ce.areaCode IN(".$sysLimit['销售区域'].") or limits='员工')";
            }
            $sqlId = 'select_assignment';
        }else if(!empty($sysLimit2['申请人部门权限'])){
            if(!strstr($sysLimit2['申请人部门权限'],";;")){
                $areaCodeSql = "sql: and u.DEPT_ID IN(".$sysLimit2['申请人部门权限'].")";
                $areaCodeSql = str_replace(',*,',',',$areaCodeSql);
                $areaCodeSql = str_replace(',,',',',$areaCodeSql);
                $service->searchArr['areaCodeSql'] = $areaCodeSql;
            }
            $sqlId = 'select_assignment';
        }else{
        	$rows = "";
        }
        if($sqlId != ''){
            $rows = $service->pageBySqlId($sqlId);
        }

        $rows = $this->sconfig->md5Rows($rows);
        //发货需求进度备注
        $orderIdArr = array();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $orderIdArr[$key] = $rows[$key]['id'];
            }
        }
        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $rows[$key]['rate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_borrow_borrow' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['rate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
            }
        }
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }


    /**
     * 获取分页数据转成Json
     */
    function c_assignmentJson() {
        //权限系统
        $rateDao = new model_stock_outplan_assignrate();
        $service = $this->service;
		// 参数处理  -【添加新流程】罗权洲：填写+物料确认-下达发货计划-销售关联商机-提交审批 By weijb 2015.10.20
        $ExaStatusArr = $_REQUEST['ExaStatusArr'];
        unset($_REQUEST['ExaStatusArr']);
        $limits = $_REQUEST['limits'];
        unset($_REQUEST['limits']);
        $service->getParam($_REQUEST);
        $mySql = "sql: ";
        if(!empty($ExaStatusArr)){
        	$mySql .= " and ((c.ExaStatus in(".util_jsonUtil::strBuild($ExaStatusArr).")";
        }
        if($_REQUEST['dealStatusArr'] == '0,2'){//未处理列表，不显示打回及关闭的单据
        	$mySql .= " and c.`status` not in('2','3')";
        }else{//已处理列表，不显示打回的单据
        	$mySql .= " and c.`status` not in('2','3')";
        }
        if(!empty($limits)){
        	$mySql .= " and c.limits='".$limits."'";
        }
        $mySql .= ") or c.createId = 'quanzhou.luo') ";// 由罗权洲新增的单据,这里写死
        $service->searchArr['mySearchCondition'] = $mySql;
        if (isset ($this->service->this_limit['借用类型']) && !empty ($this->service->this_limit['借用类型']))
            $limit = $this->service->this_limit['借用类型'];
        $limitsSql = "sql: and (";
        $limitArr = explode(',', $limit);
        if (is_array($limitArr) && count($limitArr) > 0 && $limit != '') {
            $flag = true;
            foreach ($limitArr as $key => $val) {
                if ($val == '1' && $flag) {
                    $limitsSql .= " limits='客户' ";
                    $flag = false;
                } else if ($val == '1' && !$flag) {
                    $limitsSql .= " or limits='客户' ";
                }
                if ($val == '2' && $flag) {
                    $limitsSql .= " limits='员工' and isproShipcondition='0'";
                    $flag = false;
                } else if ($val == '2' && !$flag) {
                    $limitsSql .= " or (limits='员工' and isproShipcondition='0') ";
                }
                if ($val == '3' && $flag) {
                    $limitsSql .= " limits='员工' and isproShipcondition='1' ";
                    $flag = false;
                } else if ($val == '3' && !$flag) {
                    $limitsSql .= " or (limits='员工' and isproShipcondition='1')";
                }
            }
            $limitsSql .= ") ";
            $service->searchArr['advSql'] = $limitsSql;
        }
        //$service->asc = false;
//		$rows = $service->pageBySqlId('select_shipments');

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $sysLimit2 = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        if(!empty($sysLimit['销售区域']) && !empty($sysLimit2['申请人部门权限'])){
            if(!strstr($sysLimit['销售区域'],";;") || !strstr($sysLimit2['申请人部门权限'],";;")){
                $applyDeptLimit = ($sysLimit2['申请人部门权限'] == '')? "" :  "or FIND_IN_SET(u.DEPT_ID,'".$sysLimit2['申请人部门权限']."')";
                $service->searchArr['areaCodeSql'] = "sql: and (FIND_IN_SET(ce.areaCode,'".$sysLimit['销售区域']."') ".$applyDeptLimit.")";
            }
            $rows = $service->pageBySqlId('select_assignment');
        }else if(!empty($sysLimit['销售区域'])){
            if(!strstr($sysLimit['销售区域'],";;")){
                $service->searchArr['areaCodeSql'] = "sql: and (FIND_IN_SET(ce.areaCode,'".$sysLimit['销售区域']."') or limits='员工')";
            }
            $rows = $service->pageBySqlId('select_assignment');
        }else if(!empty($sysLimit2['申请人部门权限'])){
            if(!strstr($sysLimit2['申请人部门权限'],";;")){
                $service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(u.DEPT_ID,'".$sysLimit2['申请人部门权限']."')";
            }
            $rows = $service->pageBySqlId('select_assignment');
        }
        else{
            $rows = "";
        }


//		echo "<pre>";
//		print_R($rows);

        //发货需求进度备注
        $orderIdArr = array();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $orderIdArr[$key] = $rows[$key]['id'];
                //判断如果是变更的单据，查找并替换单据ID
                if ($val['isSubAppChange'] == '1') {
                    $mid = $this->service->findChangeId($val['id']);
                    $rows[$key]['id'] = $mid;
                    $rows[$key]['linkId'] = "";
                    $rows[$key]['oldId'] = $val['id'];
                }
            }
        }
        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->searchArr['relDocType'] = 'oa_borrow_borrow';
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $rows[$key]['rate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_borrow_borrow' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['rate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
            }
        }

        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }


    /**
     * 客户借试用发货tab
     */
    function c_customerShipTab() {
        $this->assign('limits', $_GET['limits']);
        $this->display('person-shiptab');
    }

    /**
     * 借试用发货
     */
    function c_toBorrowShipped() {
        $this->assign('limits', $_GET['limits']);
        $this->display('shipped');
    }


    /****************************************借用发货列表end*******************************************************/

    /**
     * 商机--查看--借试用
     */
    function c_toListForChance() {

        $this->assign('chanceId', $_GET['chanceId']);
        $this->display('listforchance');
    }

    /************************************************************************************************/

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = false) {
        $borrowInfo = $_POST [$this->objName];
        $act = isset ($_GET ['act']) ? $_GET ['act'] : null;
        $sto = isset ($_GET ['sto']) ? $_GET ['sto'] : null;
        $con = isset ($_GET ['con']) ? $_GET ['con'] : null;
        //员工借试用仓管确认标示
        if ($sto == 'sto') {
            $borrowInfo['tostorage'] = '1';
        }
        if ($borrowInfo ['borrowInput'] == "1") {
            $chanceCodeDao = new model_common_codeRule();
            if ($borrowInfo['limits'] == '客户') {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "cus");
            } else {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "pro");
            }
            $id = $this->service->add_d($borrowInfo,$act);
        } else if ($borrowInfo ['borrowInput'] == "0") {
            $id = $this->service->add_d($borrowInfo,$act);
        } else {
            msgGo('请找管理员确认控制编号生成规则的"BORROW_INPUT"值是否正确');
        }
        if ($id) {
            if ($act == 'app') {
                $result = $this->service->updateById(array("id"=> $id,"status"=> "0","dealStatus"=> "0","ExaStatus" => "物料确认"));
                if($result){
                    msgRF('已提交物料确认!');
                }else{
                    msgRF('提交失败,请重试!');
                }
                //if ($borrowInfo['limits'] == '员工') {
                    //succ_show('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId=' . $id);
                //} else {
                    //succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $id);
                //}
            } else if ($con == 'con') {
                $result = $this->service->updateById(array("id"=> $id,"status"=> "0","dealStatus"=> "0","ExaStatus" => "物料确认"));
                if($result){
                    msgRF('已提交物料确认!');
                }else{
                    msgRF('提交失败,请重试!');
                }
//                $this->service->shortBorrowSub($id);
//                msgRF('添加成功！');
            } else {
                msgRF('添加成功！');
            }
        }

        //$this->listDataDict();
    }

    /**
     * 修改对象
     */
    function c_edit($isEditInfo = false) {
        $object = $_POST [$this->objName];
        $act = isset ($_GET ['act']) ? $_GET ['act'] : null;
        $sto = isset ($_GET ['sto']) ? $_GET ['sto'] : null;
        //员工借试用仓管确认标示
        if ($sto == 'sto') {
            $object['tostorage'] = '1';
        }
        if ($act == 'act') {
            $object['tostorage'] = '2';
        }
        if ($this->service->edit_d($object, $isEditInfo, $_GET['act'])) {
            if ($_GET['act'] == 'app') {
                $result = $this->service->updateById(array("id"=>$object['id'],"status"=> "0","dealStatus"=> "0","ExaStatus" => "物料确认"));
                if($result){
                    msgRF('已提交物料确认!');
                }else{
                    msgRF('提交失败,请重试!');
                }
//                if ($object['limits'] == '员工') {
//                    succ_show('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId=' . $object['id']);
//                } else {
//                    succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $object['id']);
//                }
            }
            if ($act == 'act') {
                msgRF('确认完成！');
            } else {
                msgRF('编辑成功！');
            }

        }
    }

    /**
     * 列表直接提交申请
     */
    function c_ajaxSubForm(){
        $id = isset($_POST['id'])? $_POST['id'] : '';
        if($id != ""){
            $result = $this->service->updateById(array("id"=>$id,"status"=> "0","dealStatus"=> "0","ExaStatus" => "物料确认"));
            if($result){
                //给交付推送邮件
                $rows = $this->service->get_d($id);
                $infoArr = array(
                    'code' => $rows['Code'],
                    'type' => '申请'
                );
                //通用邮件发送暂不支持业务处理，按类型写死邮件发送人 （暂定）
                $otherdatas = new model_common_otherdatas ();
                $objdeptName = $otherdatas->getUserDatas($rows['createId'], 'DEPT_NAME');
                $toUser = $rows['createId'];
                // 海外的发送给杨贤贞,其他的统一发送给罗权洲
                if ($objdeptName == '海外业务部') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",xianzhen.yang";
                    $this->service->mailDeal_d("borrowToShip_HY", $toUser, $infoArr);
                } else if ($rows['limits'] == '员工') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",quanzhou.luo";
                    $this->service->mailDeal_d("borrowToShip_YG", $toUser, $infoArr);
                } else if ($rows['limits'] == '客户') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",quanzhou.luo";
                    $this->service->mailDeal_d("borrowToShip_KH", $toUser, $infoArr);
                }
            }
            echo '已提交物料确认!';
        }else{
            echo '提交失败,请重试!';
        }
    }

    /**
     * 借试用查看Tab页
     */
    function c_viewTab() {
        $this->assign("borrowId", $_GET['id']);
        $this->display("viewTab");
    }

    /**
     * 借试用变更 查看tab（变更历史）
     */
    function c_toViewTab() {
        $change = isset($_GET['change']) ? $_GET['change'] : null;
        $rows = $this->service->get_d($_GET ['id']);
        if ($change == '1') {
            $this->assign('change', "1");
        } else {
            $this->assign('change', "2");
        }
        $this->assign('id', $_GET ['id']);
        $this->assign('originalId', $rows ['originalId']);
        $this->assign("borrowId", $_GET['id']);
        $this->assign("initTip", $rows['initTip']);
        $this->display('view-tab');
    }

    /**
     * 重写int
     */
    function c_init() {
//		$this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $rows = $this->service->get_d($_GET['id']);

        $file3 = $this->service->getFilesByObjId($_GET['id'], false, 'oa_borrow_borrow3');
        $this->assign('file3', $file3);

        //获取个人申请累计金额，并格式化为千分位
        $rs = $this->service->getPersonalEquMoney($rows['createName']);
        $equMoney = number_format($rs[0]['equMoney'],2);
        //审批查看页面
        if($perm == 'view' && $actType == 'audit'){
        	$equMoney = $equMoney."--<a href='javascript:void(0)' onclick='showModalWin(\"?model=projectmanagent_orderreport_orderreport&action=borrowDetailReport&searchType=sales&countType=money&searchKey=" . $rows['createName'] . '",1,'. $rows['id'].')\'>' . "查看明细" ."</a>";
        }
        $this->assign('equMoney', $equMoney);

        //渲染从表
        if ($perm == 'view') {
            $rows = $this->service->initView($rows);
        } else {
            $rows = $this->service->initEdit($rows);
        }
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($perm == 'view') {
            $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
            $this->assign('actType', $actType);
            if ($rows['limits'] == '员工') {
            	$this->assign('module', $this->getDataNameByCode($rows['module']));
                $this->view('proview');
            } else {
                $SingleType = ($rows['SingleType'] == "NULL")? "" : $rows['SingleType'];
                switch ($SingleType) {
                    case "" :
                        $this->assign('SingleType', "无");
                        $this->assign('singleCode', "无");
                        break;
                    case "chance" :
                        $this->assign('SingleType', "商机");
                        $chacneId = $rows['chanceId'];
                        $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $rows['chanceCode'] . '</span>';
                        $this->assign('singleCode', $code);
                        break;
                    case "order" :
                        $this->assign('SingleType', "合同");
                        $orderId = $rows['contractId'];
                        $orderCode = $rows['contractNum'];
                        $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';

                        $this->assign('singleCode', $code);
                        break;
                }
                $turnCon = $this->service->getTurnCon($_GET['id']);
                $this->assign('turnContract',$turnCon);
                $this->assign('module', $this->getDataNameByCode($rows['module']));

                if($rows['isTemp'] == 1){// 如果是变更页面的话,获取上次变更修改的内容
                    $changeLogFields = array('beginTime','closeTime','deliveryDate','salesName','salesNameId','scienceName','scienceNameId','shipaddress','status','reason','remark','remarkapp','module');
                    $changeLogDao = new model_common_changeLog();
                    $changeValArr = $changeLogDao->getChangeInformation_d($rows['id'],"borrow");
                    if(is_array($changeValArr) && count($changeValArr) > 0){
                        foreach ($changeLogFields as $fieldCode){
                            $catchCode = $changeShowStr = '';
                            foreach ($changeValArr as $changeRecord){
                                if($fieldCode == $changeRecord['changeField']){
                                    $catchCode = $fieldCode;
                                    $changeShowStr = "{$changeRecord['oldValue']} => {$changeRecord['newValue']}";
                                    break;
                                }
                            }
                            if($catchCode != ""){
                                $this->assign("{$catchCode}_changeShow",$changeShowStr);
                            }else{
                                $this->assign("{$fieldCode}_changeShow",$changeShowStr);
                            }
                        }
                    }
                }

                $this->view('view');
            }
        } else {
            $SingleType = $rows['SingleType'];
            switch ($SingleType) {
                case "" :
                    $this->assign('SingleType', "无");
                    $this->assign('singleCode', "无");
                    break;
                case "chance" :
                    $this->assign('SingleType', "商机");
                    $chacneId = $rows['chanceId'];
                    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $rows['chanceCode'] . '</span>';
                    $this->assign('singleCode', $code);
                    break;
                case "order" :
                    $this->assign('SingleType', "合同");
                    $orderId = $rows['contractId'];
                    $orderCode = $rows['contractNum'];
                    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';

                    $this->assign('singleCode', $code);
                    break;
            }
            $this->assign('createId', $_SESSION['USER_ID']);
            $this->showDatadicts(array ('module' => 'HTBK'), $rows['module']);
            $this->view('edit');
        }
    }

    /**
     * 物料修改
     */
    function c_productEdit() {
        if (!isset($this->service->this_limit['物料修改']) || $this->service->this_limit['物料修改'] != 1) {
            echo "没有物料修改的权限，请联系OA管理员开通";
            exit();
        }
        $icon = isset($_GET['icon']) ? $_GET['icon'] : null;
        $this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $rows = $this->service->get_d($_GET['id']);
        $rows = $this->service->editProduct($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($icon == "Pro") {
            $this->display('producteditPro');
        } else {
            $this->display('productedit');
        }

    }

    /**
     * 员工借试用查看页面
     */
    function c_proView() {
//          $this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        //echo $_GET['id'];
        $rows = $this->service->get_d($_GET['id']);

        $rows = $this->service->initView($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        $this->assign('actType', $actType);
        $this->assign('module', $this->getDataNameByCode($rows['module']));
        $this->view('proview');
    }

    /**
     * 员工试用查看tab页
     */
    function c_proviewTab() {
        $rows = $this->service->get_d($_GET ['id']);
        $this->assign('id', $_GET ['id']);
        $this->assign('originalId', $rows ['originalId']);
        $this->assign("borrowId", $_GET['id']);
        $this->assign("initTip", $rows['initTip']);
        $this->assign("isproShipcondition", $rows['isproShipcondition']);
        $this->display('proviewTab');
    }

    /**
     * 借试用处理列表 查看tab页
     */
    function c_proDisViewTab() {
        $this->assign("borrowId", $_GET['id']);
        $this->display('prodisviewTab');
    }

    /**
     * 员工借试用编辑
     */
    function c_proEdit() {
        $this->permCheck();
        $this->assign('borrowInput', BORROW_INPUT);
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $rows = $this->service->get_d($_GET['id']);
        $rows = $this->service->initEdit($rows);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
//		//获取默认发送人
//	   include (WEB_TOR."model/common/mailConfig.php");
//	    $this->assign('tostorageName' ,$mailUser['oa_borrow_borrow']['tostorageName']);
//	    $this->assign('tostorageNameId' ,$mailUser['oa_borrow_borrow']['tostorageNameId']);
        $this->showDatadicts(array ('module' => 'HTBK'), $rows['module']);
        $this->view('proedit');
    }

    /**
     * 员工借试用-仓管确认编辑页
     */
    function c_storageEdit() {
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $rows = $this->service->get_d($_GET['id']);
        $rows = $this->service->initEdit($rows);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('storageedit');
    }

    /**
     * 员工借试用 库存不足处理页
     */
    function c_borrowDispose() {
        $this->assign('borrowId', $_GET['id']);

        $rows = $this->service->get_d($_GET['id']);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取默认发送人
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('executeName', $mailUser['borrow_execute']['executeName']);
        $this->assign('executeNameId', $mailUser['borrow_execute']['executeNameId']);
        $this->assign('proposer', $rows['createName']);
        $this->assign('proposerId', $rows['createId']);
        $this->display('borrowdispose');
    }

    /**
     * 员工借试用 仓管处理 方法
     */
    function c_executeBorrow() {
        $object = $_POST [$this->objName];
        if ($object['type'] == "back") {
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->toExeBackEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "backBorrowInfo", $object['Code'], "通过", $object['proposerId'], $object['remark']);
            $this->service->ajaxBorrowBackR($object['borrowId'],$object['tempId']);
        } else if ($object['type'] == "exe") {
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->toExeEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "borrowToExedeptInfo", $object['Code'], "通过", $object['executeNameId'], $object['exeRemark']);
            $this->service->ajaxBorrowShipR($object['borrowId']);
        }
        msg('处理完成！');
    }

    /**
     * 员工借试用 续借申请
     */
    function c_borrowRenew() {
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $dao = new model_projectmanagent_borrow_borrowequ();
        $borrowequ = $dao->renewTableEdit($rows['borrowequ']);
        $this->assign('borrowequ', $borrowequ[1]);
        $this->assign('productNumber', $borrowequ[0]);
        $this->assign("date", date("Y-m-d"));
        $this->display('borrowrenew');
    }

    /**
     * 物料修改
     */
    function c_pedit($isEditInfo = false) {
        $object = $_POST [$this->objName];
        $id = $this->service->proedit_d($object, $isEditInfo);

        if ($id) {
            $this->service->updateOrderShipStatus_d($id);
            msgRF('编辑成功');
        }
    }

    /***********************************************************************************************/

    /**
     * 批量删除对象
     */
    function c_deletesInfo() {
        $deleteId = isset($_GET['id']) ? $_GET['id'] : exit;
        $delete = $this->service->deletesInfo_d($deleteId);
        if ($delete) {
            echo 1;
        } else {
            echo 0;
        }
        exit();
    }

    /***********************************************************************************************/

    /**
     * 未审批Json
     */
    function c_pageJsonAuditNo() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr['workFlowCode'] = $service->tbl_name;
        $service->asc = true;
        $rows = $service->pageBySqlId('select_auditing');
        $rows = $this->sconfig->md5Rows($rows, "borrowId");
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 已审批Json
     */
    function c_pageJsonAuditYes() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr ['workFlowCode'] = $this->service->tbl_name;
        $rows = $service->pageBySqlId('select_audited');
        $rows = $this->sconfig->md5Rows($rows, "borrowId");
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 我的借试用PageJson
     */

    function c_MyBorrowPageJson() {
        $service = $this->service;
        if(util_jsonUtil::iconvUTF2GB($_POST['ExaStatus']) == "变更审批中"){
            $_POST['changingExaStatus'] = "变更审批中";
            unset($_POST['ExaStatus']);
        }
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->setCompany(0);//不启用公司过滤
        $userId = $_SESSION['USER_ID'];
        $service->searchArr['pageUser'] = "sql:and (c.createId =  '$userId' or c.salesNameId = '$userId')";
//		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
        $rows = $service->pageBySqlId('select_borrow_renew');
        //归还状态
        foreach ($rows as $k => $v) {
            $backStatus = $service->backStatus($v['id']);
            $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
        }
        //判断是否超期
        foreach ($rows as $key => $val) {
            $newDate = date("Y-m-d");
            if ($newDate > $rows[$key]['closeTime']) {
                $rows[$key]['isExceed'] = '1';
            } else {
                $rows[$key]['isExceed'] = '0';
            }
        }

        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil :: encode($arr);
    }

    function c_listForChance() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['chanceId'] = $_GET['chanceId'];
//		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
        $rows = $service->pageBySqlId('select_default');
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 所有借试用(员工)
     */
    function c_pageJsonStaff() {
        $service = $this->service;
        $rows = array();
        if(util_jsonUtil::iconvUTF2GB($_POST['ExaStatus']) == "变更审批中"){
            $_POST['changingExaStatus'] = "变更审批中";
            unset($_POST['ExaStatus']);
        }
        $service->getParam($_POST);
        $otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $privlimit = isset ($sysLimit ['部门']) ? $sysLimit ['部门'] : null;
        if (!empty($privlimit)) {
//            $service->searchArr['createSections'] = $privlimit;
//            $service->searchArr['createIdOr'] = $_SESSION['USER_ID'];
            $service->searchArr['mySearchCondition'] = "sql: and ( u.DEPT_ID in(".$privlimit.") or c.createId='".$_SESSION['USER_ID']."')";
        } else {
            $service->searchArr['createId'] = $_SESSION['USER_ID'];
        }
        //$service->asc = false;
        $rows = $service->page_d();
        //归还状态
        foreach ($rows as $k => $v) {
            $backStatus = $service->backStatus($v['id']);
            $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
        }
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 员工借试用发货pagejson
     */
    function c_toStoragePageJson() {
        $service = $this->service;
        $rows = array();

        $service->getParam($_POST);
        $service->searchArr['sto'] = "sql:and c.initTip <> '1' and c.dealStatus in (1,2,3) and ((c.limits = '员工' and (c.ExaStatus = '完成' or c.ExaStatus = '免审')) or (c.limits = '员工' and c.toStorage = '1') or (c.limits = '客户' and c.subtip = '1'))";

        $rows = $service->pageBySqlId('select_default');
        //归还状态
        foreach ($rows as $k => $v) {
            $backStatus = $service->backStatus($v['id']);
            $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
        }

        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 从表选择物料处理配置
     */
    function c_ajaxorder() {
        $isEdit = isset($_GET['isEdit']) ? $_GET['isEdit'] : null;
        $configInfo = $this->service->c_configuration($_GET['id'], $_GET['Num'], $_GET['trId'], $isEdit);
        echo $configInfo[0];
    }

    /**
     * 短期借试用不用审批-直接提交 改审批状态
     */
    function c_ajaxShortBorrowSub() {
        try {
            $this->service->shortBorrowSub($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 列表页 提交仓管确认
     */
    function c_ajaxCounSub() {
        try {
            $this->service->ajaxCounSubS($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 员工借试用 退回
     */
    function c_borrowBack() {
        try {
            $this->service->ajaxBorrowBackR($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * y员工借试用  转至执行部
     */
    function c_borrowShip() {
        try {
            $this->service->ajaxBorrowShipR($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 执行部 回致仓库 跳转页
     */
    function c_toBackStorage() {
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取默认发送人
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('exeName', $mailUser['exeBackStorage']['exeName']);
        $this->assign('exeNameId', $mailUser['exeBackStorage']['exeNameId']);
        $this->assign('borrowId', $_GET['id']);

        $this->display("backstorage");
    }

    /**
     * 执行部 回致仓库 处理
     */
    function c_toBackStorageDis() {
        $object = $_POST [$this->objName];
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->toshipbackEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "exedpptToStorageInfo", $object['Code'], "通过", $object['exeNameId'], $object['exeRemark']);
        $this->service->BackStorageDisR($object['borrowId']);

        msg('处理完成！');
    }

    /**
     * 跳转到借试用金额配置页面
     */
    function c_toConfig() {
        $this->view("config");
    }

    /**
     * 借试用 ajax 获取下拉从表物料信息
     */
    function c_ajaxSingle() {
        $id = $_GET['id'];
        $type = $_GET['type'];
        $orderType = isset($_GET['orderType']) ? $_GET['orderType'] : null;
        switch ($type) {
            case "chance" :
                $dao = new model_projectmanagent_chance_chanceequ();
                $chanceequ = $dao->getDetail_d($id);
                $chance = $dao->borrowTableEdit($chanceequ);
                echo $chance[1];
                break;
            case "order"  :
                switch ($orderType) {
                    case "oa_sale_order" :
                        $orderDao = new model_projectmanagent_order_orderequ();
                        break;
                    case "oa_sale_service" :
                        $orderDao = new model_engineering_serviceContract_serviceequ();
                        break;
                    case "oa_sale_lease" :
                        $orderDao = new model_contract_rental_tentalcontractequ();
                        break;
                    case "oa_sale_rdproject" :
                        $orderDao = new model_rdproject_yxrdproject_rdprojectequ();
                        break;
                        break;
                }

                $orderequ = $orderDao->getDetail_d($id);
                $order = $orderDao->borrowTableEdit($orderequ);
                echo $order[1];
                break;
        }
    }

    /**
     * ajax 更新发货状态
     */
    function c_ajaxUpdateDeliveryStatus() {
        try {
            $this->service->ajaxUpdateDeliveryStatus_d();
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 借试用物料 领料提醒 发送邮件
     */
    function c_toRemindMail() {
        try {
            $borrowId = $_GET['id'];
            $borrowInfo = $this->service->get_d($borrowId);
            $this->service->toremindMail_d($borrowInfo['createId'], $borrowInfo['Code'], $borrowInfo);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 验证编号的唯一性
     */
    function c_ajaxCode() {
        $service = $this->service;
        $Code = isset ($_GET ['ajaxCode']) ? $_GET ['ajaxCode'] : false;
        $Id = isset ($_GET ['id']) ? $_GET ['id'] : false;
        $searchArr = array("ajaxCode" => $Code);
        $isRepeat = $service->isRepeat($searchArr, $Id);

        if ($isRepeat) {
            echo "1";
        } else {
            echo "0";
        }
    }

    /*********************************借试用转销售 开始*******************************************************************************/

    /**
     * 客户借试用 转销售 处理页
     */
    function c_borrowToOrder() {
        $this->assign('borrowId', $_GET['id']);
        $row = $this->service->get_d($_GET['id']);
        $isNum = array(); //用于验证借试用是否有 物料可以转为销售
        foreach ($row['borrowequ'] as $k => $v) {
//            $dao = new model_stock_allocation_allocation();
//		    $broNum = $dao->getApplyDocNotBackNum($v['borrowId'],$v['productId'],"DBDYDLXFH");
            $contractDao = new model_contract_contract_equ();
            $exeNum = $contractDao->getBorrowToContractNum($v['borrowId']);
            $Num = $v['executedNum'] - $v['backNum'] - $exeNum;
            if ($Num != 0) {
                $isNum[$k] = $v['productId'];
            }
        }
        if (!empty($isNum)) {
            $this->assign('isNum', '1');
        } else {
            $this->assign('isNum', '0');
        }
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->display('toorder');
    }

    /*
       * 借试用转销售 --无关联合同 新增处理
       */
    function c_ToOrder() {
        $borrowId = $_GET['borrowId'];
        $this->assign('createTime', date('Y-m-d'));
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('contractSigner', $_SESSION['USERNAME']);
        $this->assign('contractSignerId', $_SESSION['USER_ID']);
        $this->assign('prinvipalName', $_SESSION['USERNAME']);
        $this->assign('prinvipalId', $_SESSION['USER_ID']);
        $this->assign('prinvipalDept', $_SESSION['DEPT_NAME']); //没有后续在加
        $this->assign('prinvipalDeptId', $_SESSION['DEPT_ID']);
        $this->assign('borrowId', $borrowId);
        $customerIdarr = $this->service->find(array("id" => $borrowId), null, 'customerId');
        $this->assign('customerId', $customerIdarr['customerId']);
        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr); //盖章类型

        //合同编号是否手工输入
        $this->assign('contractInput', ORDER_INPUT);
        $this->view('to-contract');
    }

    /**
     * 借试用转销售---新增销售合同方法
     */
    function c_toOrderAdd() {
        $contractInfo = $_POST ['contract'];
        $contractDao = new model_contract_contract_contract();
        $contractId = $contractDao->add_d($contractInfo);
        if (!empty($contractInfo['borrowtoCon'])) {
//		//储存 中间表信息
//        $toOrderDao = new model_projectmanagent_borrow_toorder();
//        $toOrderId = $toOrderDao->createBatch($contractInfo['borrowtoCon'],array('contractId' => $contractId,'contractType' => $contractInfo['contractType']));
            foreach ($contractInfo['borrowtoCon'] as $k => $v) {
                $contractInfo['borrowtoCon'][$k]['toBorrowequId'] = $v['id'];
                $contractInfo['borrowtoCon'][$k]['productCode'] = $v['productNo'];
                unset($contractInfo['borrowtoCon'][$k]['id']);
                unset($contractInfo['borrowtoCon'][$k]['productNo']);
            }
            //储存 合同表内的物料信息
            $orderequDao = new model_contract_contract_equ();
            $orderequDao->createBatch($contractInfo['borrowtoCon'], array('contractId' => $contractId, 'isBorrowToorder' => '1', 'toBorrowId' => $contractInfo['borrowId']));
        }
        //判断是否直接提交审批
        if ($contractId && $_GET ['act'] == "app") {
            if ($contractId == "confirm") {
                msg("合同已提交确认成本概算");
            }
        } else if ($contractId) {
            msg('添加成功！');
        } else {
            msg('无转销售物料信息');
        }
    }

    /**
     * 借试用转销售 --有关联合同 修改或变更处理
     */
    function c_toOrderBecome() {
        $contractId = isset ($_GET ['contractId']) ? $_GET ['contractId'] : null;
        $borrowId = isset ($_GET['borrowId']) ? $_GET['borrowId'] : null;
        //合同审批状态
        $contractExaType = $this->service->getOrderExaType($contractId);
        $Dao = new model_contract_contract_contract();
        if ($contractExaType == '未审批') {
            //获取合同信息
            $obj = $Dao->get_d($contractId);
            //数据渲染
            $this->assignFunc($obj);
            $this->assign('borrowId', $borrowId);
            if ($obj['sign'] == 1) {
                $this->assign('signYes', 'checked');
            } else {
                $this->assign('signNo', 'checked');
            }
            //附件
            $file = $this->service->getFilesByObjId($obj['id'], true);
            $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
            $this->assign('file', $file);
            $this->assign('file2', $file2);
            $this->showDatadicts(array(
                'contractType' => 'HTLX'
            ), $obj['contractType']);
            switch ($obj['contractType']) {
                case 'HTLX-XSHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'XSHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-FWHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'FWHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-ZLHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'ZLHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-YFHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'YFHTSX'
                    ), $obj['contractNature']);
                    break;
            }
            $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
            $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);
            $this->showDatadicts(array('signSubject' => 'QYZT'), $obj['signSubject']);
//					//设置盖章类型
//					$stampConfigDao = new model_system_stamp_stampconfig();
//					$stampArr = $stampConfigDao->getStampType_d();
//					$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//盖章类型
            $this->view('tocontract-edit');
        } else if ($contractExaType == '完成') {
            $obj = $Dao->get_d($contractId);
            foreach ($obj as $key => $val) {
                $this->assign($key, $val);
            }
            if ($obj['sign'] == 1) {
                $this->assign('signYes', 'checked');
            } else {
                $this->assign('signNo', 'checked');
            }
            //附件
            $file = $this->service->getFilesByObjId($obj['id'], true);
            $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
            $this->assign('file', $file);
            $this->assign('file2', $file2);
            $this->showDatadicts(array(
                'contractType' => 'HTLX'
            ), $obj['contractType']);
            switch ($obj['contractType']) {
                case 'HTLX-XSHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'XSHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-FWHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'FWHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-ZLHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'ZLHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-YFHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'YFHTSX'
                    ), $obj['contractNature']);
                    break;

            }
            $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
            $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);
            $this->showDatadicts(array('signSubject' => 'QYZT'), $obj['signSubject']);

            $this->assign('borrowId', $borrowId);
            $this->view('tocontract-change');
        }
    }

    /**
     * 借试用转销售 --修改
     */
    function c_toOrderEdit($isEditInfo = false) {
        $object = $_POST['contract'];
        $editDao = new model_contract_contract_contract();

//		//储存 中间表信息
//        $toOrderDao = new model_projectmanagent_borrow_toorder();
//        $toOrderId = $toOrderDao->createBatch($object['borrowequ'],array('contractId' => $object['id'],'contractType' => $orderType));
        //储存 合同表内的物料信息
        foreach ($object['borrowtoCon'] as $k => $v) {
            $object['borrowtoCon'][$k]['toBorrowequId'] = $v['id'];
            $object['borrowtoCon'][$k]['productCode'] = $v['productNo'];
            unset($object['borrowtoCon'][$k]['id']);
            unset($object['borrowtoCon'][$k]['productNo']);
        }
        $orderequDao = new model_contract_contract_equ();
        $orderequDao->createBatch($object['borrowtoCon'], array('contractId' => $object['id'], 'isBorrowToorder' => '1', 'toBorrowId' => $object['borrowId']));

        if ($editDao->edit_d($object, $isEditInfo)) {
            msg('编辑成功！');
        }
    }

    /**
     * 将诶试用转销售--变更
     */
    function c_toOrderChange() {
        $changeDao = new model_contract_contract_contract;
        $object = $_POST['contract'];

        try {
////			//储存 中间表信息
//	        $toOrderDao = new model_projectmanagent_borrow_toorder();
//	        $toOrderId = $toOrderDao->createBatch($object['borrowequ'],array('contractId' => $object['oldId'],'contractType' => $orderType,'contractChangeId' => $id));

            foreach ($object['borrowtoCon'] as $k => $v) {
                $object['borrowtoCon'][$k]['toBorrowequId'] = $v['id'];
                $object['borrowtoCon'][$k]['isBorrowToorder'] = "1";
                $object['borrowtoCon'][$k]['toBorrowId'] = $object['borrowId'];
                $object['borrowtoCon'][$k]['productCode'] = $v['productNo'];
                unset($object['borrowtoCon'][$k]['id']);
                unset($object['borrowtoCon'][$k]['productNo']);
            }
            $object['equ'] = $object['borrowtoCon'];
            $id = $changeDao->change_d($object);
            if ($id) {
                echo "<script>this.location='controller/contract/contract/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
            }

        } catch (Exception $e) {
            msgBack2("变更失败！失败原因：" . $e->getMessage());
        }
    }


    /**
     * 借试用转销售 选择页面
     */
    function c_borrowTurnChoose() {
        $customerId = $_GET['customerId'];
        $salesNameId = $_GET['salesNameId'];
        $this->assign('customerId', $customerId);
        $this->assign('salesNameId', $salesNameId);
        $this->view('borrowturn-choose');
    }

    /**
     * 借试用转销售信息页
     */
    function c_borrowTurnInfo() {
        $customerId = $_GET['customerId'];
        $salesNameId = $_GET['salesNameId'];
        $checkIdsArr = explode(",",$_GET['checkIds']);
        $chanceId = $_GET['chanceId'];
        $i = 0;
        foreach($checkIdsArr as $k=>$v){
        	if($v != "undefined" && !empty($v)){
        		$newCheckIdsArr[$i] = $v;
        	}
        	$i++;
        }
        $newCheckIdsStr = implode(",",$newCheckIdsArr);
        $this->assign('customerId', $customerId);
        $this->assign('salesNameId', $salesNameId);
        $this->assign("checkIds",$newCheckIdsStr);
        $this->assign("chanceId",$chanceId);
        //显示所有借试用单标识
        $this->assign("showAll",isset($_GET['showAll']) ? 1 : 0);
//     	 $this->view('borrowturn-info');
        $this->view('borrowturn-choose');
    }

    //借试用转销售获取数据json
    function c_borrowequJson() {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
        //$service->asc = false;
        $service->setCompany(0);//不启用公司过滤
        $rows = $service->pageBySqlId('borrowequ_choose');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 借试用转销售获取数据id串
     * 用于配合分页的全选功能
     */
    function c_borrowequIds() {
    	$service = $this->service;
    	$service->getParam ( $_REQUEST );
        $service->setCompany(0);//不启用公司过滤
    	$rows = $service->list_d ('borrowequ_choose');
    	if($rows){
    		$ids = '';
    		foreach ($rows as $v){
    			$ids .= $v['id'].',';
    		}
    		$ids = rtrim($ids,',');
    		echo $ids;
    	}
    	echo '';
    }

    //借试用转销售获取数据json
    function c_borrowequJsons() {
    	$service = $this->service;
    	$service->getParam($_REQUEST);
        $service->setCompany(0);//不启用公司过滤
        if($_REQUEST['objType']){
            $service->searchArr['chanceId']=$_REQUEST['objType'];
        }elseif($_REQUEST['showAll'] && $_REQUEST['showAll'] == '1'){//如果显示所有借试用单，则不区分客户
        	unset($service->searchArr['customerId']);
        }
    	$service->searchArr['limits']="客户";
    	$service->searchArr['ExaStatus']="完成";
    	//$service->getParam ( $_POST ); //设置前台获取的参数信息


    	//$service->asc = false;
    	$service->groupBy = "c.id";
    	$rows = $service->pageBySqlId('select_borrowTosale');
    	//数据加入安全码
    	$rows = $this->sconfig->md5Rows($rows);
    	$arr = array();
    	$arr ['collection'] = $rows;
    	//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
    	$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
    	$arr ['page'] = $service->page;
    	echo util_jsonUtil::encode($arr);
    }

    /**
     * 借试用转销售 选择客户、物料页
     */
    function c_borrowTurnList() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!empty($id)) {
            //获取借试用单据信息
            $borrowArr = $this->service->get_d($id);
            $this->assign('customerId', $borrowArr['customerId']);
            $this->assign('customerName', $borrowArr['customerName']);
            $this->assign('borrowId', $id);
            $this->assign('borrowCode', $borrowArr['Code']);
        } else {
            $this->assign('customerId', "");
            $this->assign('customerName', "");
            $this->assign('borrowId', "");
            $this->assign('borrowCode', "");
        }
        $this->assign('salesName', $_SESSION['USERNAME']);
        $this->assign('salesNameId', $_SESSION['USER_ID']);
        $this->view("borrowturnlist");
    }

    //选择是否关联合同
    function c_tochooseCon() {
        $ids = $_GET['ids'];
        $this->assign('ids', $ids);
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view("tochooseCon");
    }

    /*
      * 获取借试用原单物料信息
      */
    function c_getOriginalBorrowEquInfo() {
        $id = $_POST['id'];
        $sql = "select id,borrowId,executedNum,backNum from oa_borrow_equ where id = '{$id}';";
        $equData = $this->service->_db->getArray($sql);
        if($equData){
            echo util_jsonUtil::encode($equData);
        }else{
            echo '';
        }
    }

    /*
      * 获取借试用转销售物料
      */
    function c_getBorrowequInfo() {
        $ids = $_POST['ids'];
        $service = $this->service;
        $service->searchArr['ids'] = $ids;
        $service->setCompany(0);//不启用公司过滤
        $rows = $service->list_d('borrowequ_choose');
        echo util_jsonUtil::encode($rows);
    }

    /*
      * 判断合同状态
      */
    function c_toconExastatusType() {
        $contractId = $_POST['contractId'];
        //合同审批状态
        $contractExaType = $this->service->getOrderExaType($contractId);
        echo $contractExaType;
    }

    /*********************************借试用转销售 结束*******************************************************************************/

    /**
     * 借试用 申请审批 判断 创建人与跟踪人
     */
    function c_borrowExa() {
        if (!empty ($_GET ['spid'])) {
        	$this->service->workflowCallBack($_GET ['spid']);
        }
        if ($_GET['urlType']) {
            echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

        } else {
            //防止重复刷新,审批后的跳转页面
            echo "<script>this.location='?model=projectmanagent_borrow_borrow&action=toProBorrowAll'</script>";
        }
    }

    /*******************************变更   开始***************************************************/
    function c_toChange() {
        $change = isset($_GET['change']) ? $_GET['change'] : null;
        $changeLogDao = new model_common_changeLog ('borrow');
        if ($changeLogDao->isChanging($_GET['id'])) {
            msgGo("该合同已在变更审批中，无法变更.");
        }
        $sql = "select count(id) as num from oa_borrow_order_equ where businessId = " . $_GET['id'] . "";
        $toOrder = $this->service->_db->getArray($sql);
        if ($toOrder[0]['num'] != 0) {
            msgGO("该条借试用申请已经提交转销售申请，无法变更.");
        }
        //临时记录id
        $tempId = isset($_GET['tempId']) ? $_GET['tempId'] : '';
        //判断是否存在临时保存的记录
        if(empty($tempId)){
        	$sql = "select tempId,ExaStatus from oa_borrow_changlog where id = (select max(id) as id from oa_borrow_changlog " .
        			"where objType = 'borrow' and objId = ". $_GET['id'] ." and changeManId = '" . $_SESSION['USER_ID'] . "')";
        	$rs = $this->service->_db->getArray($sql);
        	$tempId = !empty($rs) && $rs[0]['ExaStatus'] != AUDITED ? $rs[0]['tempId'] : '';
        }
        $this->assign('tempId', $tempId);
        $borrowId = isset($_GET['tempId']) ? $_GET['tempId'] : $_GET['id'];
        $this->assign('borrowId', $borrowId);
        $rows = $this->service->get_d($borrowId);
        //附件
        $rows ['file'] = $this->service->getFilesByObjId($rows ['id'], true);
        $rows = $this->service->initChange($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //id始终为源单id
        if(isset($_GET['tempId'])){
        	$this->assign('id', $_GET['id']);
        }

        $SingleType = ($rows['SingleType'] == "NULL")? "" : $rows['SingleType'];
        switch ($SingleType) {
            case "" :
                $this->assign("SingleType", "无");
                $this->assign("singleCode", "无");
                break;
            case "chance" :
                $this->assign("SingleType", "商机");
                $chacneId = $rows['chanceId'];
                $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $rows['chanceCode'] . '</span>';
                $this->assign('singleCode', $code);
                break;
            case "order" :
                $this->assign('SingleType', "合同");
                $orderId = $rows['contractId'];
                $orerType = $rows['contractType'];
                $orderCode = $rows['contractNum'];
                switch ($orerType) {
                    case "oa_sale_order" :
                        $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_order_order&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_service" :
                        $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=engineering_serviceContract_serviceContract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_lease":
                        $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_rdproject" :
                        $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                }
                $this->assign('singleCode', $code);
                break;
        }


//		$this->showDatadicts ( array ('customerType' => 'KHLX' ), $rows ['customerType'] );
//		$this->showDatadicts ( array ('invoiceType' => 'FPLX' ), $rows ['invoiceType'] );
//		$this->showDatadicts ( array ('orderNature' => 'XSHTSX' ), $rows ['orderNature'] );
        $this->showDatadicts(array ('module' => 'HTBK'), $rows['module']);
        if ($change == "proChange") {
            $this->view('prochange');
        } else {
            $this->view('change');
        }


    }

    /**
     * 变更方法
     */
    function c_change() {
        try {
            $change = isset($_GET['change']) ? $_GET['change'] : null;
            $row = $_POST ['borrow'];
            //保存
            if($row['isSub'] == '0'){
            	$this->service->change_d($row);
            	msgGo("保存成功！",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
            }else{
            	$oldrow = $this->service->getBorrowInfo($row['oldId'],array(0 => 'product'));
            	//比较变更差异
            	$isDeff = $this->service->getDeff($row,$oldrow);
            	if($isDeff == 1){
            		//只更新备注
            		$f = $this->service->changeNoApp($row);
            		msgGo("变更成功！",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
            	}else if($isDeff == 2){
            		msgGo("无任何变更！",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
            	}else{
            		$id = $this->service->change_d($row,'audit');
                    if(!empty($id)){
                        if ($change == "prochange") {
                            msgGo("已提交物料确认！",'?model=projectmanagent_borrow_borrow&action=toProBorrowList');
//            			echo "<script>this.location='controller/projectmanagent/borrow/ewf_prochange_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
                        } else {
                            msgGo("已提交物料确认！",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
//            			echo "<script>this.location='controller/projectmanagent/borrow/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
                        }
                    }
            	}
            }
        } catch (Exception $e) {
            msgBack2("变更失败！失败原因：" . $e->getMessage());
        }
    }

    /**
     * 变更审批通过后 处理方法
     */
    function c_confirmChangeToApprovalNo() {
        if (!empty ($_GET ['spid'])) {
        	//$this->service->workflowCallBack_change($_GET ['spid']);
            $this->service->workflowCallBack_changeNew($_GET ['spid']);
        }
        $urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
        //防止重复刷新
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /*******************************变更  end***************************************************/

    /**
     * 根据客户获取区域已借金额
     */
    function c_getAreaMoneyByCustomerId() {
        $areaMoney = $this->service->getAreaMoneyByCustomerId($_POST['customerId']);
        echo util_jsonUtil::encode($areaMoney);
    }

    /**
     * 根据员工获取已借金额信息
     */
    function c_getUserDeptMoneyByUserId() {
        $moneyArr = $this->service->getUserMoney($_POST['userId']);
        echo util_jsonUtil::encode($moneyArr);
    }

    /*******************************序列号  处理***************************************************/

    /**
     * 借试用归还处理 -序列号
     */
    function c_serialNumBorrowReturn() {
        $borrowLimit = $_GET['borrowLimit'];
        $serialnoNameStr = $_GET['serialName'];
        $serialnoIdStr = $_GET['serialId'];
        $serNameArr = array(); //序列号名称
        $serIdArr = array(); //序列号ID
        $nameArr = explode(",", $serialnoNameStr);
        $IdArr = explode(",", $serialnoIdStr);
        foreach ($nameArr as $k1 => $v1) {
            array_push($serNameArr, $v1);
        }
        foreach ($IdArr as $k2 => $v2) {
            array_push($serIdArr, $v2);
        }
        foreach ($serNameArr as $key => $val) {
            $serialArr[$key]['name'] = $val;
            $serialArr[$key]['id'] = $serIdArr[$key];
        }
        $serial = $this->service->serialNum_d($serialArr);
        $this->assign("num", $_GET['num']);
        $this->assign("amount", $_GET['num']);
        $this->assign("serial", $serial);
        $this->assign("inputId", $_GET['inputId']);
        $this->assign("sid", $_GET['sid']);
        $this->display("serialNum");

    }

    //客户转销售
    function c_serialNum() {
        $borrowLimit = $_GET['borrowLimit'];
        if ($borrowLimit == "员工") {
            $type = "DBDYDLXJY";
        } else {
            $type = "DBDYDLXFH";
        }
        $allocationDao = new model_stock_allocation_allocation();
        $loanArr = $allocationDao->findLendDoc($type, $_GET['borrowId']);
        $serNameArr = array(); //序列号名称
        $serIdArr = array(); //序列号ID
        foreach ($loanArr as $k => $v) {
            foreach ($v['items'] as $key => $val) {
                if ($val['productId'] == $_GET['productId']) {
                    $nameArr = explode(",", $val['serialnoName']);
                    $IdArr = explode(",", $val['serialnoId']);
                    foreach ($nameArr as $k1 => $v1) {
                        array_push($serNameArr, $v1);
                    }
                    foreach ($IdArr as $k2 => $v2) {
                        array_push($serIdArr, $v2);
                    }
                }

            }
        }
        foreach ($serNameArr as $key => $val) {
            $serialArr[$key]['name'] = $val;
            $serialArr[$key]['id'] = $serIdArr[$key];
        }
        $serial = $this->service->serialNum_d($serialArr);
        $this->assign("num", $_GET['num']);
        $this->assign("amount", $_GET['num']);
        $this->assign("serial", $serial);
        $this->assign("inputId", $_GET['inputId']);
        $this->assign("sid", $_GET['sid']);
        $this->display("serialNum");

    }

    //显示所选序列号
    function c_serialShow() {
        $serialArr = $_GET['serial'];
        $serialArr = explode(",", $serialArr);
        $serial = $this->service->serialshow_d($serialArr);
        $this->assign("serial", $serial);
        $this->display("serialShow");
    }

    //查看页 查看借出物料的 序列号
    function c_serialNo() {
        $borrowId = $_GET['borrowId'];
        $ItemId = $_GET['itemId'];
        $productId = $_GET['productId'];
        $renew = isset($_GET['renew']) ? $_GET['renew'] : null;
        $allocationDao = new model_stock_allocation_allocation();
        $findSerSql = "select serialName,serialId from oa_borrow_equ where borrowId = " . $borrowId . " and id= " . $ItemId . "";
        $serialInfo = $this->service->_db->getArray($findSerSql);
        $serNameArr = array(); //序列号名称
        $serIdArr = array(); //序列号ID
        if ($serialInfo[0]['serialName'] != '') {
            $nameArr = explode(",", $serialInfo[0]['serialName']);
            $IdArr = explode(",", $serialInfo[0]['serialId']);
            foreach ($nameArr as $k1 => $v1) {
                array_push($serNameArr, $v1);
            }
            foreach ($IdArr as $k2 => $v2) {
                array_push($serIdArr, $v2);
            }
        } else {
            $loanArr = $allocationDao->findLendDoc("DBDYDLXJY", $borrowId);
            if (empty($loanArr)) {
                $loanArr = $allocationDao->findLendDoc("DBDYDLXFH", $borrowId);
            }
            foreach ($loanArr as $k => $v) {
                foreach ($v['items'] as $key => $val) {
                    if ($val['productId'] == $productId) {

                        $nameArr = explode(",", $val['serialnoName']);
                        $IdArr = explode(",", $val['serialnoId']);
                        foreach ($nameArr as $k1 => $v1) {
                            array_push($serNameArr, $v1);
                        }
                        foreach ($IdArr as $k2 => $v2) {
                            array_push($serIdArr, $v2);
                        }
                    }

                }
            }
        };
        foreach ($serNameArr as $key => $val) {
            $serialArr[$key]['name'] = $val;
            $serialArr[$key]['id'] = $serIdArr[$k];
        }
        $serial = $this->service->serialNum_d($serialArr);

        $this->assign("num", $_GET['num']);
        $this->assign("amount", $_GET['amount']);
        $this->assign("renew", $renew);
        $this->assign("serial", $serial);
        $this->display("serialNo");
    }

    /***********************************转借  开始*******************************************************/
    /**
     * 转借申请页
     */
    function c_subtenancyApply() {
        $proBorrowId = $_GET['borrowId'];
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $dao = new model_projectmanagent_borrow_borrowequ();
        $borrowequ = $dao->subtenancyTable($rows['borrowequ']);
        $this->assign('borrowequ', $borrowequ[1]);
        $this->assign('productNumber', $borrowequ[0]);

        $this->assign('ApplyBeginTime', day_date);
        $this->assign('borrowInput', BORROW_INPUT);

        $this->display('subtenancyapply');
    }

    /**
     * 转借新增方法
     */
    function c_subtenancyAdd() {
        $borrowInfo = $_POST [$this->objName];
        if ($borrowInfo ['borrowInput'] == "1") {
            $chanceCodeDao = new model_common_codeRule();
            if ($borrowInfo['limits'] == '客户') {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "cus");
                $borrowInfo['createName'] = $borrowInfo['salesName'];
                $borrowInfo['createId'] = $borrowInfo['salesNameId'];
            } else {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "pro");
            }
            $id = $this->service->add_d($borrowInfo);
        } else if ($borrowInfo ['borrowInput'] == "0") {
            $id = $this->service->add_d($borrowInfo);
        } else {
            msgGo('请找管理员确认控制编号生成规则的"BORROW_INPUT"值是否正确');
        }
        if ($id) {
            succ_show('controller/projectmanagent/borrow/ewf_subtenancyBorrow.php?actTo=ewfSelect&billId=' . $id);
        } else {
//				msgRF ( '添加失败！');
        }
    }

    /**
     * 转借 修改
     */
    function c_subtenancyEdit() {
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $dao = new model_projectmanagent_borrow_borrowequ();
        $borrowequ = $dao->subtenancyTable($rows['borrowequ']);
        $this->assign('borrowequ', $borrowequ[1]);
        $this->assign('productNumber', $borrowequ[0]);
        $this->display('subtenancyedit');
    }

    /**
     * 转借查看页
     */
    function c_subtenancyView() {
        $rows = $this->service->get_d($_GET['id']);

        //渲染从表
        $rows = $this->service->initView($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('subtenancyView');
    }

    /**
     * 转借修改处理
     */
    function c_subEdit($isEditInfo = false) {
        $object = $_POST [$this->objName];
        if ($this->service->edit_d($object)) {
            msgRF('修改成功！');
        }
    }

    /**
     * 转借确认
     */
    function c_subtenancyAff() {
        $rows = $this->service->get_d($_GET['id']);

        //渲染从表
        $rows = $this->service->initView($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $this->display('subtenancyAff');
    }

    //转借 确认方法
    function c_subAff() {
        $borrowId = $_POST ["borrowId"];
        $this->service->updateSubAff($borrowId); //确认后改变单据状态

        $rows = $this->service->get_d($borrowId);
        $exaStatus = $rows['ExaStatus']; //获取转借单据审批状态

        if ($exaStatus == '完成') {
            $this->service->updateExaTomail($borrowId);
        }
        msgRF('确认成功！');
    }


    /**
     * 转借申请审批通过后 调用的方法
     */
    function c_updateSubtenancy() {
    	$this->service->workflowCallBack_sub($_GET ['spid']);
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * 转借Tab 查看页
     */
    function c_subtenancyViewList() {
        $this->assign("subBorrowId", $_GET['id']);
        $this->display('subtenancyViewList');
    }

    /***********************************转借  结束*******************************************************/
    /***********************************借试用报表******BEGIN*************************************************/
    function c_borrowReport() {
        $this->display('borrowReport');
    }

    //报表json-主表
    function c_borrowReportJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
//		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
//		$service->searchArr['workFlowCode'] = $service->tbl_name;
        $service->asc = false;
        $rows = $service->pageBySqlId('borrowReport_master');
        foreach ($rows as $k => $v) {
            //获取员工借用金额额度
            $moneyLimit = $service->moneyConfig($v['userId']);
            $rows[$k]['moneyLimit'] = $moneyLimit;
            $money = $rows[$k]['allMoney'] - $rows[$k]['moneyLimit'];
            if ($money > 0) {
                $rows[$k]['isOverrun'] = "是";
                $rows[$k]['overrunMoeny'] = $money;
            } else {
                $rows[$k]['isOverrun'] = "否";
                $rows[$k]['overrunMoeny'] = 0;
            }
        }
        //获取并处理借试用报表的初始化数据
        $rows = $service->getInitializeInfo($rows);
        foreach ($rows as $key => $val) {
            if (empty($val['id'])) {
                unset($rows[$key]);
            }
        }

        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    //报表json--从表
    function c_borrowreportTable() {
        $service = $this->service;

        $service->getParam($_REQUEST);

        $rows = $service->pageBySqlId('borrowReport_table');

        //获取并处理借试用报表的初始化数据
        $rows = $service->getInitializeInfoTable($rows, $service->searchArr['createId']);

        $arr = array();
        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }

    /***********************************借试用报表******END*************************************************/

    /**********************************借试用导入**********************************************************/
    /**
     * 上传EXCEL
     */
    function c_upExcel() {
        set_time_limit(0);
        $objNameArr = array(
            0 => 'beginTime', //借出时间（借用开始日期）
            1 => 'closeTime', //归还时间
            2 => 'useType', //用途
            3 => 'K3Code', //K3编码
            4 => 'productName', //物料名称
            5 => 'productModel', //型号
            6 => 'number', //数量
            7 => 'deptName', //部门名称
            8 => 'customerName', //客户名称
            9 => 'userName', //借用人
            10 => 'seriesNumber', //产品序列号
            11 => 'remark', //备注
            23 => 'rowsIndex'
        );

        $this->c_addExecel($objNameArr);

    }

    /**
     * 上传EXCEl并导入其数据
     */
    function c_addExecel($objNameArr) {
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $customerDao = new model_customer_customer_customer ();
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $upexcel = new model_contract_common_allcontract ();
            $excelData = $upexcel->upExcelData($filename, $temp_name);
            spl_autoload_register('__autoload'); //改变加载类的方式

            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }

//                echo "<pre>";print_r($objectArr);exit;

                $arrinfo = array(); //导入信息
                $cusArr = array(); //客户借试用数组
                $proArr = array(); //员工借试用数组
                foreach ($objectArr as $val) {
                    if (empty($val['K3Code'])) {
                        array_push($arrinfo, array("rowsIndex" => $val['rowsIndex'], "orderCode" => "物料名称：" . $val['productName'], "cusName" => $val['userName'], "result" => "导入失败，K3物料编码为空"));
                        continue;
                    }
                    if (empty($val['userName'])) {
                        array_push($arrinfo, array("rowsIndex" => $val['rowsIndex'], "orderCode" => "物料名称：" . $val['productName'], "cusName" => $val['userName'], "result" => "导入失败，没有员工姓名"));
                        continue;
                    }
                    if (!empty($val['customerName'])) { //有客户的客户借试用
                        $cusArr[] = $val;
                    }
                    if (empty($val['customerName'])) { // 没有客户的员工借试用
                        $proArr[] = $val;
                    }
                }
                $cusBorrowArr = $this->CusdisposeData($cusArr);
                $proBorrowArr = $this->ProdisposeData($proArr);
                //处理客户借试用并保存信息
                foreach ($cusBorrowArr as $val) {
                    //判断客户是否存在
                    $customerId = $customerDao->findCid($val ['customerName']);
                    if (empty($customerId)) {
                        $rowIndexStr = $val['rowIndexStr'];
                        $rowsIndexArr = explode(",", $rowIndexStr);
                        foreach ($rowsIndexArr as $rowsIndex) {
                            if (!empty($rowsIndex)) {
                                array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "客户名称： " . $val['customerName'] . " , " . "借出时间：" . $val['beginTime'], "cusName" => $val['applyUserName'], "result" => "导入失败，客户为空或不存在"));
                            }
                        }
                    } else {
                        $tempIndexArr = array();
                        foreach ($val['borrowequ'] as $k => $v) {
                            if (empty($v['productId']) || empty($v['productNoKS'])) {
                                $rowsIndexArr = explode(",", $v['rowsIndex']);
                                foreach ($rowsIndexArr as $rowsIndex) {
                                    if (!empty($rowsIndex)) {
                                        array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "物料编码: " . $v['productNoKS'], "cusName" => $val['applyUserName'], "result" => "导入失败，物料信息不存在或K3物料代码错误"));
                                        unset($val['borrowequ'][$k]);
                                    }
                                }
                            }else{
                                array_push($tempIndexArr,$v['rowsIndex']);
                            }
                        }
                        if (empty($val['borrowequ'])) {
                            continue;
                        }
                        $val['rowIndexStr'] = implode(',',$tempIndexArr);
                        if (!empty($val)) {
                            //判断借用人是否离职或重复
                            $userId = $this->borrower($val['applyUserName']);
                            $cusBorrowAdd = $this->cusBorrowInfo($val, $userId, $customerId);
                            $id = $this->service->add_d($cusBorrowAdd);
                            if ($id) {
                                $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                                $rowsIndexArr = explode(",", $rowIndexStr);
                                foreach ($rowsIndexArr as $rowsIndex) {
                                    if (!empty($rowsIndex)) {
                                        array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "保存成功", "cusName" => $val['applyUserName'], "result" => "导入成功"));
                                    }
                                }
                            } else {
                                $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                                $rowsIndexArr = explode(",", $rowIndexStr);
                                foreach ($rowsIndexArr as $rowsIndex) {
                                    if (!empty($rowsIndex)) {
                                        array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "申请时间：" . $val['createTime'], "cusName" => $val['applyUserName'], "result" => "导入失败，未知原因"));
                                    }
                                }
                            }
                        }
                    }
                }
                //处理员工借试用并保存信息
                foreach ($proBorrowArr as $val) {
                    $tempIndexArr = array();
                    foreach ($val['borrowequ'] as $k => $v) {
                        if (empty($v['productId']) || empty($v['productNoKS'])) {
                            $rowsIndexArr = explode(",", $v['rowsIndex']);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "物料编码: " . $v['productNoKS'], "cusName" => $val['applyUserName'], "result" => "导入失败，物料信息不存在或K3物料代码错误"));
                                    unset($val['borrowequ'][$k]);
                                }
                            }
                        }else{
                            array_push($tempIndexArr,$v['rowsIndex']);
                        }
                    }
                    if (empty($val['borrowequ'])) {
                        continue;
                    }
                    $val['rowIndexStr'] = implode(',',$tempIndexArr);
                    if (!empty($val)) {
                        // 查询用户id
                        $userId = $this->borrower($val['applyUserName']);
                        $cusBorrowAdd = $this->proBorrowInfo($val, $userId, $customerId);
                        $id = $this->service->add_d($cusBorrowAdd);
                        if ($id) {
                            $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "保存成功", "cusName" => $val['applyUserName'], "result" => "导入成功"));
                                }
                            }
                        } else {
                            $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "申请时间：" . $val['createTime'], "cusName" => $val['applyUserName'], "result" => "导入失败，未知原因"));
                                }
                            }
                        }
                    }
                }
                $result = array();
                foreach ($arrinfo as $value) {
                    $result[$value['rowsIndex']] = $value;
                }
                sort($result);
                if ($result) {
                    echo util_excelUtil::showResultBorrow($this->sysSortArray($arrinfo, "rowsIndex"), "导入结果", array("行号", "相关信息", "借用人", "结果"));
                }
            } else {
                echo "文件不存在可识别数据!";
            }
        } else {
            echo "上传文件类型不是EXCEL!";
        }
    }

    function sysSortArray($ArrayData, $KeyName1, $SortOrder1 = "SORT_ASC", $SortType1 = "SORT_REGULAR") {
        if (!is_array($ArrayData)) {
            return $ArrayData;
        }

        // Get args number.
        $ArgCount = func_num_args();

        // Get keys to sort by and put them to SortRule array.
        for ($I = 1; $I < $ArgCount; $I++) {
            $Arg = func_get_arg($I);
            if (!eregi("SORT", $Arg)) {
                $KeyNameList[] = $Arg;
                $SortRule[] = '$' . $Arg;
            } else {
                $SortRule[] = $Arg;
            }
        }

        // Get the values according to the keys and put them to array.
        foreach ($ArrayData AS $Key => $Info) {
            foreach ($KeyNameList AS $KeyName) {
                ${$KeyName}[$Key] = $Info[$KeyName];
            }
        }

        // Create the eval string and eval it.
        $EvalString = 'array_multisort(' . join(",", $SortRule) . ',$ArrayData);';
        eval ($EvalString);
        return $ArrayData;
    }

    /**
     * 转换时间戳
     * @param $timestamp
     * @return bool|string
     */
    function transitionTime($timestamp) {
        $time = "";
        if (!empty($timestamp)) {
            if (mktime(0, 0, 0, 1, $timestamp - 1, 1900) > '2000-01-01') {
                $time = date("Y-m-d", mktime(0, 0, 0, 1, $timestamp - 1, 1900));
            } else {
                $time = $timestamp;
            }
        }
        return $time;
    }

    /**
     * 判断借用人是否重复或离职
     * @param $name
     * @return string
     */
    function borrower($name) {
        if($name == '阮明艳') return 'ruan.mingyan';
        if($name == '王星') return 'xing.wang';
        $Dao = new model_common_otherdatas();
        $userId = $Dao->getUserID($name);
        $userInfoArr = $Dao->getUserDatas($userId[0]['USER_ID']);
        if ($userInfoArr['HAS_LEFT'] == 1) {
            return "left";
        } else {
            return $userId[0]['USER_ID'];
        }
    }

    /**
     * 处理客户借试用数据
     * @param $cusBorrow
     * @param $userId
     * @param $customerId
     * @return array
     */
    function cusBorrowInfo($cusBorrow, $userId, $customerId) {
        $userId = $userId ? $userId : $cusBorrow['applyUserName'];
        $addArr = array();
        $chanceCodeDao = new model_common_codeRule();

        $addArr['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "cus");
        $addArr['Type'] = $cusBorrow['Type'];
        $addArr['customerName'] = $cusBorrow['customerName'];
        $addArr['limits'] = "客户";
        $addArr['beginTime'] = $cusBorrow['beginTime'];
        $addArr['closeTime'] = $cusBorrow['closeTime'];
        $addArr['salesName'] = $cusBorrow['applyUserName'];
        $addArr['salesNameId'] = $userId;
        $addArr['remark'] = $cusBorrow['remark'];
        $addArr['reason'] = $cusBorrow['reason'];
        $addArr['createName'] = $cusBorrow['applyUserName'];
        $addArr['createId'] = $userId;
        $addArr['createTime'] = $cusBorrow['createTime'];
        $addArr['ExaStatus'] = "完成";
        if (empty($customerId[0]['id'])) {
            $addArr['customerId'] = 0;
        } else {
            $addArr['customerId'] = $customerId[0]['id'];
        }

        $addArr['DeliveryStatus'] = "YFH";
        $addArr['initTip'] = "1";
        $addArr['rowIndexStr'] = $cusBorrow['rowIndexStr'];
        $addArr['borrowequ'] = $cusBorrow['borrowequ'];

        return $addArr;
    }

    /**
     * 处理员工借试用数据
     * @param $proBorrow
     * @param $userId
     * @param $customerId
     * @return array
     */
    function proBorrowInfo($proBorrow, $userId, $customerId) {
        $userId = $userId ? $userId : $proBorrow['applyUserName'];
        $Dao = new model_common_otherdatas();
        $userInfoArr = $Dao->getUserDatas($userId);
        $chanceCodeDao = new model_common_codeRule();

        $addArr = array();
        $addArr['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "pro");
        $addArr['Type'] = $proBorrow['Type'];
        $addArr['limits'] = "员工";
        $addArr['beginTime'] = $proBorrow['beginTime'];
        $addArr['closeTime'] = $proBorrow['closeTime'];
        $addArr['remark'] = $proBorrow['remark'];
        $addArr['createName'] = $proBorrow['applyUserName'];
        $addArr['createId'] = $userId;
        $addArr['createTime'] = $proBorrow['createTime'];
        $addArr['ExaStatus'] = "完成";
        $addArr['customerId'] = $customerId[0]['id'];
        $addArr['reason'] = $proBorrow['reason'];
        $addArr['createSection'] = $userInfoArr['DEPT_NAME'];
        $addArr['createSectionId'] = $userInfoArr['DEPT_ID'];
        $addArr['DeliveryStatus'] = "YFH";
        $addArr['initTip'] = "1";
        $addArr['rowIndexStr'] = $proBorrow['rowIndexStr'];
        $addArr['borrowequ'] = $proBorrow['borrowequ'];

        return $addArr;
    }

    /**
     * 借试用编号唯一性判断
     * @param $Code
     * @return mixed
     */
    function borrowCodeOne($Code) {
        return $this->service->_db->getArray("select id from oa_borrow_borrow where Code = '$Code'");
    }

    /**
     * 处理数据
     * @param $objectArr
     * @return array
     */
    function CusdisposeData($objectArr) {
        $codeArr = array(); //单据数组
        $codeInfoArr = array();
        foreach ($objectArr as $key => $val) {
            $yearMonth = date("Ym", strtotime($val['beginTime']));
            $codeArr[$key] = $yearMonth . $val['userName'] . $val['customerName'];
        }
        $codeArr = array_flip($codeArr);

        //所有物料信息
        $productInfoTempArr = $this->service->findProductInfo();
        $productInfoArr = array();
        foreach ($productInfoTempArr as $val) {
            $productInfoArr[$val['ext2']] = $val;
        }
        foreach ($codeArr as $k => $v) {
            $seriesNumberArr = array();
            $remarkArr = array();
            foreach ($objectArr as $val) {
                $yearMonth = date("Ym", strtotime($val['beginTime']));
                if ($yearMonth . $val['userName'] . $val['customerName'] == $k) {
                    $codeInfoArr[$k]['KCode'] = "";
                    $codeInfoArr[$k]['createTime'] = date("Y-m-d"); //单据创建日期，暂定导入当天
                    $codeInfoArr[$k]['Type'] = $val['useType'];
                    $codeInfoArr[$k]['applyUserName'] = $val['userName'];
                    $codeInfoArr[$k]['beginTime'] = $this->transitionTime($val['beginTime']);
                    $codeInfoArr[$k]['closeTime'] = $this->transitionTime($val['closeTime']);
                    $codeInfoArr[$k]['closeTimeTrue'] = ""; //导入模板内没有，暂时为空
                    $codeInfoArr[$k]['dept'] = $val['deptName'];
                    $codeInfoArr[$k]['customerName'] = $val['customerName'];
                    if ($val['seriesNumber'] && !in_array($val['seriesNumber'], $seriesNumberArr)) {
                        $seriesNumberArr[] = mysql_real_escape_string($val['seriesNumber']);
                    }
                    if ($val['remark'] && !in_array($val['remark'], $remarkArr)) {
                        $remarkArr[] = mysql_real_escape_string($val['remark']);
                    }
                    if (!isset($codeInfoArr[$k]['rowIndexStr'])) {
                        $codeInfoArr[$k]['rowIndexStr'] = $val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['rowIndexStr'] .= ",".$val['rowsIndex'];
                    }

                    if (!empty($codeInfoArr[$k]['borrowequ'][$val['K3Code']])) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['number'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['executedNum'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['rowsIndex'] .= ",".$val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']] = array(
                            "productNoKS" => $val['K3Code'],
                            "productNameKS" => $val['productName'],
                            "productId" => $productInfoArr[$val['K3Code']]['id'],
                            "productName" => $productInfoArr[$val['K3Code']]['productName'],
                            "productNo" => $productInfoArr[$val['K3Code']]['productCode'],
                            "productModel" => $productInfoArr[$val['K3Code']]['pattern'],
                            "number" => $val['number'],
                            "executedNum" => $val['number'],
                            "lentDate" => $val['beginTime'],
                            "lentType" => $val['useType'],
                            "rowsIndex" => $val['rowsIndex']
                        );
                    }

                    if ($val['seriesNumber']) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] .=
                            $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] ? '/' . $val['seriesNumber'] :
                                $val['seriesNumber'];
                    }
                }
            }
            if (!empty($seriesNumberArr)) {
                $codeInfoArr[$k]['reason'] = implode('/', $seriesNumberArr);
            }
            if (!empty($remarkArr)) {
                $codeInfoArr[$k]['remark'] = implode('/', $remarkArr);
            }
        }
        return $codeInfoArr;
    }

    function ProdisposeData($objectArr) {
        $codeArr = array(); //单据数组
        $codeInfoArr = array();
        foreach ($objectArr as $key => $val) {
            $yearMonth = date("Ym", strtotime($val['beginTime']));
            $codeArr[$key] = $yearMonth . $val['userName'];
        }
        $codeArr = array_flip($codeArr);
        //所有物料信息
        $productInfoTempArr = $this->service->findProductInfo();
        $productInfoArr = array();
        foreach ($productInfoTempArr as $val) {
            $productInfoArr[$val['ext2']] = $val;
        }

        foreach ($codeArr as $k => $v) {
            $seriesNumberArr = array();
            $remarkArr = array();
            foreach ($objectArr as $val) {
                $yearMonth = date("Ym", strtotime($val['beginTime']));
                if ($yearMonth . $val['userName'] == $k) {
                    $codeInfoArr[$k]['KCode'] = "";
                    $codeInfoArr[$k]['createTime'] = date("Y-m-d"); //单据创建日期，暂定导入当天
                    $codeInfoArr[$k]['Type'] = $val['useType'];
                    $codeInfoArr[$k]['applyUserName'] = $val['userName'];
                    $codeInfoArr[$k]['beginTime'] = $this->transitionTime($val['beginTime']);
                    $codeInfoArr[$k]['closeTime'] = $this->transitionTime($val['closeTime']);
                    $codeInfoArr[$k]['closeTimeTrue'] = ""; //导入模板内没有，暂时为空
                    $codeInfoArr[$k]['dept'] = $val['deptName'];
                    $codeInfoArr[$k]['customerName'] = $val['customerName'];
                    if ($val['seriesNumber'] && !in_array($val['seriesNumber'], $seriesNumberArr)) {
                        $seriesNumberArr[] = mysql_real_escape_string($val['seriesNumber']);
                    }
                    if ($val['remark'] && !in_array($val['remark'], $remarkArr)) {
                        $remarkArr[] = mysql_real_escape_string($val['remark']);
                    }
                    if (!isset($codeInfoArr[$k]['rowIndexStr'])) {
                        $codeInfoArr[$k]['rowIndexStr'] = $val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['rowIndexStr'] .= ",".$val['rowsIndex'];
                    }
                    if (!empty($codeInfoArr[$k]['borrowequ'][$val['K3Code']])) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['number'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['executedNum'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['rowsIndex'] .= ",".$val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']] = array(
                            "productNoKS" => $val['K3Code'],
                            "productNameKS" => $val['productName'],
                            "productId" => $productInfoArr[$val['K3Code']]['id'],
                            "productName" => $productInfoArr[$val['K3Code']]['productName'],
                            "productNo" => $productInfoArr[$val['K3Code']]['productCode'],
                            "productModel" => $productInfoArr[$val['K3Code']]['pattern'],
                            "remark" => $val['remark'],
                            "number" => $val['number'],
                            "executedNum" => $val['number'],
                            "lentDate" => $val['beginTime'],
                            "lentType" => $val['useType'],
                            "rowsIndex" => $val['rowsIndex']
                        );
                    }

                    if ($val['seriesNumber']) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] .=
                            $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] ? '/' . $val['seriesNumber'] :
                                $val['seriesNumber'];
                    }
                }
            }
            if (!empty($seriesNumberArr)) {
                $codeInfoArr[$k]['reason'] = implode('/', $seriesNumberArr);
            }
            if (!empty($remarkArr)) {
                $codeInfoArr[$k]['remark'] = implode('/', $remarkArr);
            }
        }
        return $codeInfoArr;
    }

    /**********************************借试用导入*******END*************************************************/

    /**
     * 初始化借试用数据
     */
    function c_initializeBorrowData() {
        $this->service->initializeBorrowData_d();
    }

    /*************************************设备总汇表 start **************************************/
    /**
     * 合同发货设备-计划统计列表
     */
    function c_shipEquList() {
        $limits = isset($_GET['limits']) ? $_GET['limits'] : "";
        $equNo = isset($_GET['productCode']) ? $_GET['productCode'] : "";
        $equName = isset($_GET['productName']) ? $_GET['productName'] : "";
        $searchArr = array();
        if ($equNo != "") {
            $searchArr['productCodeEqu'] = $equNo;
        }
        if ($equName != "") {
            $searchArr['productNameEqu'] = $equName;
        }
        $searchArr['limits2'] = $_GET['limits'];
        $service = $this->service;
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
        $service->__SET('groupBy', "p.productId,p.productNumb");
        $rows = $service->pageEqu_d();
        $this->pageShowAssign();

        $this->assign('equNumb', $equNo);
        $this->assign('equName', $equName);
        $this->assign('limits', $limits);
        $this->assign('list', $this->service->showEqulist_s($rows));
        $this->display('list-equ');
        unset($this->show);
        unset($service);
    }

    /***********************************设备总汇表 end *********************************/


    /**
     * 员工借试用 打回
     */
    function c_rollBack() {
        $rows = $this->service->get_d($_GET['id']);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }//echo"<pre>";print_r($rows);exit();
        $tempId = '';
        if($rows['isTemp'] == 1){
            $tempId = $rows['id'];
            $this->assign('borrowId', $rows['originalId']);
        }else{
            if($rows['isSubAppChange'] == 1 && $rows['dealStatus'] == 2){
                $tempId = $this->service->findChangeId($rows['id']);
            }
            $this->assign('borrowId', $_GET['id']);
        }

        //获取默认发送人
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('executeName', $mailUser['borrow_execute']['executeName']);
        $this->assign('executeNameId', $mailUser['borrow_execute']['executeNameId']);
        $this->assign('proposer', $rows['createName']);
        $this->assign('proposerId', $rows['createId']);
        $this->assign('tempId', $tempId);
        $this->display('rollback');
    }

    /**
     * 高级搜索
     */
    function c_search() {
        $this->assign('gridName', $_GET['gridName']);
        $this->assign('gridType', $_GET['gridType']);
        $this->view('search');
    }

    /**
     * 借试用设备列表从表
     */
    function c_listPageJson(){
    	$service = $this->service;
    	$service->getParam ( $_POST );
    	$rows=$service->list_d();
    	$arr ['collection'] = $rows;
    	echo util_jsonUtil::encode ( $arr);
    }

    /**
     * 关联商机(用于罗权洲新建单据,销售后面关联商机)
     */
    function c_toRelateChance() {
    	$service = $this->service;
    	$rows = $service->get_d($_GET['id']);
    	//获取个人申请累计金额，并格式化为千分位
    	$rs = $service->getPersonalEquMoney($rows['createName']);
    	$equMoney = number_format($rs[0]['equMoney'],2);
    	$this->assign('id', $_GET['id']);
    	$this->assign('equMoney', $equMoney);
    	$this->assign('SingleType', $rows['SingleType']);
    	$this->assign('singleCode', $rows['singleCode']);
    	$this->assign('chanceCode', $rows['chanceCode']);
    	$this->assign('chanceId', $rows['chanceId']);
    	$this->view('relatetochance');
    }

    /**
     * 关联商机(用于罗权洲新建单据,销售后面关联商机)
     */
    function c_relateChance() {
        $object = $_POST [$this->objName];
        $act = isset ($_GET ['act']) ? $_GET ['act'] : null;
        if ($this->service->updateById($object)) {
            if ($_GET['act'] == 'app') {
 				succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $object['id']);
            }
            if ($act == 'app') {
                msg('提交成功！');
            } else {
                msg('保存成功！');
            }

        }
    }

    /**
     * 判断是否存在发货记录
     */
    function c_isExistOutplan() {
    	$outplan = new model_stock_outplan_outplan();
    	$rs = $outplan->find(array('docId' => $_POST ['id'],'docType' => 'oa_borrow_borrow'));
    	if(!empty($rs)){
    		echo 1;
    	}else{
    		echo 0;
    	}
    }

    /**
     * 重写ajax方式批量删除对象（应该把成功标志跟消息返回）
     */
    function c_ajaxdeletes() {
    	//$this->permDelCheck ();
    	try {
    		$this->service->deletes_d ( $_POST ['id'] );
    		echo 1;
    	} catch ( Exception $e ) {
    		echo 0;
    	}
    }

    /**
     * 带上商机信息
     */
    function c_pageJsonWithChance() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ('select_withChance');
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
     * 判断是否能关闭
     */
    function c_isCanClose() {
		echo $this->service->isCanClose_d($_POST ['id']);
    }

    /**
     *  销售助理 操作处理（附件上传，申请盖章）
     */
    function c_handleDispose()
    {
        $handleType = $_GET['handleType'];
        if ($handleType == "YSWJ") {
            $this->assign("handle", "1");
            $this->c_toCheckFile();
        }
    }

    function c_toCheckFile()
    {
        $id = isset($_GET['id'])? str_replace("K","",$_GET['id']) : '';
        $this->assign("serviceId", $id);
        $text = $_GET['type'] . "3";
        $this->assign("serviceType3", $text);
        $this->display('checkfile');
    }

    /**
     * 单独附件上传方法
     */
    function c_uploadfile()
    {
        $row = $_POST[$this->objName];
        if(isset($row['checkFile'])){
            $upSql = "update oa_borrow_borrow set checkFile = '有' where id= ".$row['serviceId'];
            $this->service->_db->query($upSql);
        }
        $id = $this->service->uploadfile_d($row);
        if ($id && $_GET['handle'] == "1") {
            $dao = new model_contract_contract_aidhandle();
            $dao->add_d(array("contractId" => $row['serviceId'], "handleType" => "KJYYSWJ"));
            msg('添加成功！');
        } else {
            if ($id) {
                msg('添加成功！');
            } else {
                msg('添加失败！');
            }
        }
    }

    /**
     * 跳转销售确认物料页面
     */
    function c_toConfirmEqu(){
//        ini_set("display_errors",1);
        $needSalesConfirm = isset($_GET['needSalesConfirm'])? $_GET['needSalesConfirm'] : '';
        $salesConfirmId = isset($_GET['salesConfirmId'])? $_GET['salesConfirmId'] : '';
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $borrowequDao = new model_projectmanagent_borrow_borrowequ();
        $borrowOldId = 0;

        switch($needSalesConfirm){
            case '1':
                $obj = $this->service->getBorrowInfo($id);
                $obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $id . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
                $singleArr = $this->service->getSingleCodeURL($obj);
                $obj['SingleType'] = $singleArr['SingleType'];
                $obj['singleCode'] = $singleArr['singleCode'];
                $products = $borrowequDao->showItemView($obj['product']);
                $this->assign('docType', 'oa_borrow_borrow');
                $this->assign("products", $products);
                break;
            case '2':
                $borrowOldId = $id;
                $id = $salesConfirmId;
                $obj = $this->service->getBorrowInfoWithTemp($id);
                $obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $id . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
                $products = $borrowequDao->showItemView($obj['product']);
                $singleArr = $this->service->getSingleCodeURL($obj);
                $obj['SingleType'] = $singleArr['SingleType'];
                $obj['singleCode'] = $singleArr['singleCode'];
                $this->assign('docType', 'oa_borrow_borrow');
                $this->assign("products", $products);
                break;
            case '3':
                $obj = $this->service->getBorrowInfo($id);
                $obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $id . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
                $singleArr = $this->service->getSingleCodeURL($obj);
                $obj['SingleType'] = $singleArr['SingleType'];
                $obj['singleCode'] = $singleArr['singleCode'];
                $products = $borrowequDao->showItemView($obj['product']);
                $this->assign('docType', 'oa_borrow_borrow');
                $this->assign("products", $products);

                unset($obj['equEstimateTax']);
                $costConfirmSql = "select * from oa_borrow_cost where borrowId = '{$id}' and linkId = '{$salesConfirmId}'";
                $costConfirm = $this->service->_db->get_one($costConfirmSql);
                $confirmMoneyTax = $costConfirm['confirmMoneyTax'];
                $this->assign("equEstimateTax", $confirmMoneyTax);
                break;
        }

        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if(isset($obj['borrowequ'])){
            $linkId = "";
            foreach ($obj['borrowequ'] as $k => $equ){
                if(isset($equ['linkId']) && !empty($equ['linkId']) && isset($equ['isTemp']) && $equ['isTemp'] == 0){
                    $linkId = $equ['linkId'];
                }else{
                    if(!empty($salesConfirmId) && $needSalesConfirm == 3){
                        $linkId = $salesConfirmId;
                    }
                }
            }
            $this->assign("linkId", $linkId);
        }

        $this->assign("borrowOldId",$borrowOldId);
        $this->assign("needSalesConfirm",$needSalesConfirm);
        $this->assign("salesConfirmId",$salesConfirmId);
        $this->view('toConfirmEqu');
    }

    /**
     * 销售确认发货物料
     */
    function c_confirmEqu(){
        $service = $this->service;
        $postForm = $_POST['equConfirm'];
        $act = $postForm['confirmAct'];
        $needSalesConfirm = $postForm['needSalesConfirm'];// 物料确认类型
        $salesConfirmId = $postForm['salesConfirmId'];// 主要关联的业务数据ID（申请:原单ID, 变更:临时单ID, 交付变更:临时linkId）
        switch($act){
            case 'audit':// 提交
                $resultArr = $service->salesConfirmEqu($needSalesConfirm,$salesConfirmId);
                if($resultArr['result'] && isset($resultArr['url'])){
                    succ_show($resultArr['url']);
                }
                break;
            case 'back':// 打回
                $result = $service->salesBackEqu($needSalesConfirm,$salesConfirmId);
                if($result){
                    msgRF('已打回, 等待交付重新确认物料!');
                }else{
                    msg('打回失败,请重试！');
                }
                break;
        }
    }

    /**
     * 跳转到发送延期提醒页面
     */
    function c_toNoticeDelayApply(){
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        // 默认邮件信息
        $mailTitle = $rows['Code'].'延期提醒';
        $mailContent = "各位好！【{$rows['salesName']}】负责的借试用：【{$rows['Code']}】【{$rows['customerName']}】已申请延期，截止日期更新为：{$rows['closeTime']}。";

        // 添加物料清单信息
        $borrowEquDao = new model_projectmanagent_borrow_borrowequ();
        $borrowEqu = $borrowEquDao->findAll(array("borrowId"=>$_GET['id'],"isTemp" => 0,"isDel" => 0));
        if($borrowEqu){
            $detailStr = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center>".
                "<tr><th>序号</th><th>物料编号</th><th>物料名称</th><th>物料版本/型号</th><th>数量</th><th>已执行数量</th><th>已归还数量</th></tr>";
            foreach ($borrowEqu as $k => $v){
                $num = $k + 1;
                $detailStr .= "<tr style='text-align: center'><td>{$num}</td><td>{$v['productNo']}</td><td>{$v['productName']}</td><td>{$v['productModel']}</td><td>{$v['number']}</td><td>{$v['executedNum']}</td><td>{$v['backNum']}</td></tr>";
            }
            $detailStr .= "</table>";
            $mailContent .= "<br>详细物料如下:<br>".$detailStr;
        }

        $this->assign("mailTitle", $mailTitle);
        $this->assign("mailContent", $mailContent);

        // 默认邮件接收人
        $mailReceiverNames = $rows['salesName'].",郑娟娟,吕冬冬,林建敬,黄金华,刘艳华,罗权洲";
        $mailReceiverIds = $rows['salesNameId'].",jinhua.huang,jianjing.lin,yanhua.liu,dongdong.lv,quanzhou.luo,juanjuan.zheng";
        $this->assign("mailReceiverNames", $mailReceiverNames);
        $this->assign("mailReceiverIds", $mailReceiverIds);

        // 默认邮件抄送人
        $mailCReceiverNames = '邓永杰,刘红辉';
        $mailCReceiverIds = 'dyj,honghui.liu';
        $this->assign("mailCReceiverNames", $mailCReceiverNames);
        $this->assign("mailCReceiverIds", $mailCReceiverIds);

        $this->view("noticeDelayApply");
    }

    /**
     * 发送延期提醒页面
     */
    function c_sendNoticeDelayApply(){
        $postData = $_POST [$this->objName];

        $borrowId = isset($postData['borrowId'])? $postData['borrowId'] : '';
        $title = isset($postData['mailTitle'])? $postData['mailTitle'] : '';
        $content = isset($postData['mailContent'])? $postData['mailContent'] : '';
        $mailUser = isset($postData['mailReceiverIds'])? $postData['mailReceiverIds'] : '';
        $ccMailUser = isset($postData['mailCReceiverIds'])? $postData['mailCReceiverIds'] : '';

        // 添加邮件记录
        $mailconfigDao = new model_system_mailconfig_mailconfig();
        $mailconfigDao->addMailRecord($title,$content,$mailUser,$ccMailUser);

        // 邮件类
        $emailDao = new model_common_mail();
        $result = $emailDao->mailGeneral($title, $mailUser, $content, $ccMailUser);

        if($result){// 发送成功
            $updateObj = array(
                "id" => $borrowId,
                "isDelayApply" => 0
            );
            $result = $this->service->updateById($updateObj);
        }

        if($result) {// 发送成功
            msg('发送成功!');
        }else{// 发送失败
            msg('发送失败,请重试！');
        }
    }
}