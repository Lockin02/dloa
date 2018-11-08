<?php
/**
 * @author Administrator
 * @Date 2016-11-9 14:42:39
 */
class controller_loan_loan_loan extends controller_base_action {
    private $bindId = "";
	function __construct() {
		$this->objName = "loan";
		$this->objPath = "loan_loan";
        $this->bindId = "394c197d-ea21-4702-95be-f22bca8e715c";
		parent::__construct ();
	 }

	/**
	 * 跳转列表
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * 跳转我的借款页面
     */
    function c_toListMyLoan(){
        $this->assign("myId",$_SESSION ['USER_ID']);
        $this->view('listMy');
    }

    /**
     * 跳转我的借款页面
     */
    function c_toListByProjectId(){
        $projectId = isset($_GET['projectId'])? $_GET['projectId'] : '';
        $this->assign("projectId",$projectId);
        $this->view('listProject');
    }

   /**
	 * 跳转页面
	 */
   function c_toAdd() {
       $otherdatasDao = new model_common_otherdatas();
       $docUrl = $otherdatasDao->getDocUrl($this->bindId);
       $this->assign("docUrl",$docUrl);
       // 获取常用银行账户信息
       $personnelDao = new model_hr_personnel_personnel();
       $personnelInfo = $personnelDao->find(array("userAccount"=>$_SESSION ['USER_ID']));// 申请人人事档案信息
       $oftenAccount = $oftenBank = '';
       if($personnelInfo && !empty($personnelInfo)){
           $oftenAccount = $personnelInfo['oftenAccount'];
           $oftenBank = $personnelInfo['oftenBank'];
       }

       // 显示用户部门信息
       $deptuserDao = new model_deptuser_user_user();
       $userDepts = $deptuserDao->deptusertree_d($_SESSION ['USER_ID'],null,null);
       $userName = $deptCode = $deptName = '';
       if($userDepts){
           $userDeptObj = array();
           foreach ($userDepts as $userDept){
               if($userDept['USER_ID'] == $_SESSION ['USER_ID']){
                   $userDeptObj = $userDept;
               }
           }
           $userName = isset($userDeptObj['USER_NAME'])? $userDeptObj['USER_NAME'] : '';
           $deptCode = isset($userDeptObj['DEPT_ID'])? $userDeptObj['DEPT_ID'] : '';
           $deptName = isset($userDeptObj['DEPT_NAME'])? $userDeptObj['DEPT_NAME'] : '';
       }
       $this->assign ( "debtorName", $userName );
       $this->assign ( "debtorDeptCode", $deptCode );
       $this->assign ( "debtorDeptName", $deptName );


       // 获取借款类型选项
       $loanTypesOpts = $loanTypesVal = "";
       $index = 0;
       ini_set("display_errors",1);
       $getPYDao = new model_common_getPingYing();
       if(is_array($this->service->_loanTypes)){
           foreach ($this->service->_loanTypes as $k => $v){
               if($index == 0){
                   $loanTypesVal = $k;
                   $loanTypesOpts .= '<input type="radio" name="loan[XmFlag]" value="'.$k.'" class="jkSlt" id="'.$getPYDao->getFirstPY($v).'"/> '.$v;
               }else{
                   $loanTypesOpts .= '&nbsp;&nbsp;<input type="radio" name="loan[XmFlag]" value="'.$k.'" class="jkSlt" id="'.$getPYDao->getFirstPY($v).'"/> '.$v;
               }
               $index++;
           }
       }

       // 获取借款性质选项
       $loanNatureOpts = $loanNatureVal = "";
       $index = 0;
       if(is_array($this->service->_loanNature)){
           foreach ($this->service->_loanNature as $k => $v){
               if($index == 0){
                   $loanNatureVal = $k;
               }
               $loanNatureOpts .= "<option value='{$k}'>{$v}</option>";
               $index++;
           }
       }

       $this->assign ( "todayStr", date("Y-m-d") );
       $this->assign ( "oftenAccount", $oftenAccount );
       $this->assign ( "oftenBank", $oftenBank );
       $this->assign (  "loanTypesVal", $loanTypesVal );
       $this->assign (  "loanTypesOpts", $loanTypesOpts );
       $this->assign (  "loanNatureVal", $loanNatureVal );
       $this->assign (  "loanNatureOpts", $loanNatureOpts );
       $this->view ( 'add' );
   }

    /**
     * 跳转页面
     */
   function c_toEdit() {
       $otherdatasDao = new model_common_otherdatas();
       $docUrl = $otherdatasDao->getDocUrl($this->bindId);
       $this->assign("docUrl",$docUrl);

       // 获取常用银行账户信息
       $personnelDao = new model_hr_personnel_personnel();
       $personnelInfo = $personnelDao->find(array("userAccount"=>$_SESSION ['USER_ID']));// 申请人人事档案信息
       $oftenAccount = $oftenBank = '';
       if($personnelInfo && !empty($personnelInfo)){
           $oftenAccount = $personnelInfo['oftenAccount'];
           $oftenBank = $personnelInfo['oftenBank'];
       }
       $this->permCheck (); //安全校验
       $obj = $this->service->get_d ( $_GET ['id'] );
       foreach ( $obj as $key => $val ) {
           if($key == "BankAddress" && $val == ""){
               $this->assign ( "BankAddress", $oftenBank );
           }else if($key == "BankNo" && $val == ""){
               $this->assign ( "BankNo", $oftenAccount );
           }else{
               $this->assign ( $key, $val );
           }
       }

       // 获取借款类型选项
       $loanTypesOpts = $loanTypesVal = "";
       if(is_array($this->service->_loanTypes)){
           foreach ($this->service->_loanTypes as $k => $v){
               if($k == $obj['XmFlag']){
                   $loanTypesOpts .= '<input type="radio" name="loan[XmFlag]" value="'.$k.'" checked/> '.$v;
               }else{
                   $loanTypesOpts .= '&nbsp;&nbsp;<input type="radio" name="loan[XmFlag]" value="'.$k.'"/> '.$v;
               }
           }
       }
       $this->assign (  "loanTypesOpts", $loanTypesOpts );

       // 获取借款性质选项
       $loanNatureOpts = $loanNatureVal = "";
       if(is_array($this->service->_loanNature)){
           foreach ($this->service->_loanNature as $k => $v){
               if($k == $obj['loanNature']){
                   $loanNatureVal = $k;
                   $loanNatureOpts .= "<option value='{$k}' selected>{$v}</option>";
               }else{
                   $loanNatureOpts .= "<option value='{$k}'>{$v}</option>";
               }
           }
       }
       $this->assign (  "loanNatureVal", $loanNatureVal );
       $this->assign (  "loanNatureOpts", $loanNatureOpts );

       //附件
       $file = $this->service->getFilesByObjId($obj['ID'], true);
       $this->assign('file', $file);
       $this->view ( 'edit');
   }

    /**
     * 变更借款单
     */
    function c_toChange() {
        // 获取常用银行账户信息
        $personnelDao = new model_hr_personnel_personnel();
        $personnelInfo = $personnelDao->find(array("userAccount"=>$_SESSION ['USER_ID']));// 申请人人事档案信息
        $oftenAccount = $oftenBank = '';
        if($personnelInfo && !empty($personnelInfo)){
            $oftenAccount = $personnelInfo['oftenAccount'];
            $oftenBank = $personnelInfo['oftenBank'];
        }
        $this->permCheck (); //安全校验
        $obj = $this->service->get_d ( $_GET ['id'] );
        foreach ( $obj as $key => $val ) {
            if($key == "BankAddress" && $val == ""){
                $this->assign ( "BankAddress", $oftenBank );
            }else if($key == "BankNo" && $val == ""){
                $this->assign ( "BankNo", $oftenAccount );
            }else if($key == "Debtor"){
                $debtorName = $this->service->getUserName_d($val);
                $this->assign ( "debtorName", $debtorName );
            }else{
                $this->assign ( $key, $val );
            }
        }

        // 借款类型
        if($obj['XmFlag'] == "0"){
            $this->assign('XmFlagName',"部门借款");
        }else{
            $this->assign('XmFlagName',"工程借款");
        }

        // 借款性质
        if($obj['loanNature'] == "1"){
            $this->assign('loanNatureName',"租房押金");
        }else{
            $this->assign('loanNatureName',"");
        }

        //附件
        $file = $this->service->getFilesByObjId($obj['ID'], true);
        $this->assign('file', $file);

        // 检查有无未提交变更记录
        $hasChangeRclId = '';
        $hasChangeRcl = $this->service->chkChangeTipRcl($obj['ID']);
        if(count($hasChangeRcl) > 0){
            $hasChangeRclId = $hasChangeRcl[0]['ID'];
        }
        $this->assign('hasChangeRclId', $hasChangeRclId);

        $this->view ( 'change');
    }

    /**
     * ajax获取变更记录信息
     */
    function c_getChangeRecordByAjax(){
        $obj = $this->service->get_d ( $_REQUEST ['id'] );
        $changeLogDao = new model_common_changeLog( 'loanList' );
        $changelog = $changeLogDao->getObjByTempId($_REQUEST ['id']);
        $obj['changeLog'] = ($changelog)? $changelog[0] : array();

        //附件
        $file = $this->service->getFilesByObjId($obj['ID'], true);
        $obj['file'] = $file;

        echo util_jsonUtil::encode ( $obj );
    }

    /**
     * 变更借款单
     */
    function c_change(){
        try {
            $object = $_POST[$this->objName];
            $id = $this->service->change_d($object);
            $obj = $this->service->get_d($id);
            $appArea = $this->service->getAppArea($obj['ProjectNo']);
            $Amount = (isset($obj['Amount']))? $obj['Amount'] : '';
            if($object['XmFlag'] == '0'){
                //部门借款
                succ_show("general/costmanage/loan/ewf_change_loan.php?actTo=ewfSelect&billId=" . $id . "&proSid=" . $obj['projectId'] . "&billArea=" . $appArea . "&flowMoney=".$Amount."&flowType=1&loanAttr=".$object['loanNature']);
            }else{
                //工程借款
                $chkSevProjectRst = $this->service->_db->getArray("select * from oa_esm_project where attribute in ('GCXMSS-02','GCXMSS-01','GCXMSS-05') and id = '{$object['projectId']}';");
                if($chkSevProjectRst && count($chkSevProjectRst) > 0){// 服务项目
                    succ_show("general/costmanage/loan/ewf_change_loan_sev.php?actTo=ewfSelect&billId=" . $id . "&proSid=" . $obj['projectId'] . "&billArea=" . $appArea . "&flowMoney=".$Amount."&flowType=2&loanAttr=".$object['loanNature']);
                }else{// 其他类工程项目
                    succ_show("general/costmanage/loan/ewf_change_loan.php?actTo=ewfSelect&billId=" . $id . "&proSid=" . $obj['projectId'] . "&billArea=" . $appArea . "&flowMoney=".$Amount."&flowType=2&loanAttr=".$object['loanNature']);
                }
            }
        } catch (Exception $e) {
            msgBack2("变更失败！失败原因：" . $e->getMessage());
        }
    }

    /**
     * 处理列表相关字段信息
     *
     * @param $rows
     * @param array $filtArr
     * @return mixed
     */
    function listDataProcess($rows,$filtArr = array()){
        $service = $this->service;
        $personnelDao = new model_hr_personnel_personnel();
        $deptMappingDao = new model_bi_deptFee_deptMapping();
        foreach($rows as $rk => $row){
            if($row['Status'] == "已支付" || $row['Status'] == "还款中"){
                $rows[$rk]['Status'] = "未还款";
            }
            if($row['ExaStatus'] == "变更审批中"){
                $rows[$rk]['Status'] = "变更审批中";
            }

            //需还款金额
            $rows[$rk]['reMoney'] = $service->getReMoney_d($row['id'],$rows[$rk]['Amount']);

            // 部门对应的相关信息
            $debtorDeptName = $rows[$rk]['DebtorDeptName'];
            $DebtorBusiness = $DebtorModule = '';
            if($debtorDeptName != ''){
                $mappingArr = $deptMappingDao->getBusinessByDept($debtorDeptName);
                $DebtorBusiness = $mappingArr['business'];
                $DebtorModule = $service->getBelongModuleByDept($debtorDeptName);
            }
            $rows[$rk]['DebtorBusiness'] = $DebtorBusiness;
            $rows[$rk]['DebtorModule'] = $DebtorModule;

            // 计算逾期天数
            $PrepaymentDateDays = '-';
            if($row['PrepaymentDate'] != ''){
                $PrepaymentDateTimes = strtotime($row['PrepaymentDate']);

                if($row['ReceiptDT'] != '' && $row['ReceiptDT'] != "0000-00-00 00:00:00"){
                    $ReceiptDTStr = explode(" ",$row['ReceiptDT']);
                    $todayTimes = strtotime($ReceiptDTStr[0]);

                    if($PrepaymentDateTimes < $todayTimes && $rows[$rk]['Status'] != "未还款"){
                        $diffTimes = abs($todayTimes - $PrepaymentDateTimes);
                        $PrepaymentDateDays = $diffTimes / (60*60*24);
                        $PrepaymentDateDays = $PrepaymentDateDays."天";
                    }
                }else{
                    $todayTimes = strtotime(date("Y-m-d"));

                    if($PrepaymentDateTimes < $todayTimes && ($rows[$rk]['Status'] == "未还款" || $rows[$rk]['Status'] == "变更审批中")){
                        $diffTimes = abs($todayTimes - $PrepaymentDateTimes);
                        $PrepaymentDateDays = $diffTimes / (60*60*24);
                        $PrepaymentDateDays = $PrepaymentDateDays."天";
                    }
                }

            }

            $rows[$rk]['PrepaymentDateDays'] = $PrepaymentDateDays;
        }
        return $rows;
    }

    /**
     * 数据合计加载
     * create by huanghaojin
     * create on 2017-06-22
     */
    function pageCount($object) {
        // 本页小计
//        if (is_array($object)) {
//            $newArr = array(
//                'Amount' => 0,
//                'reMoney' => 0
//            );
//            foreach ($object as $val) {
//                $newArr['Amount'] = bcadd($newArr['Amount'], $val['Amount'], 2);
//                $newArr['reMoney'] = bcadd($newArr['reMoney'], $val['reMoney'], 2);
//            }
//
//            $newArr['mark'] = 'pageSum';
//            $object[] = $newArr;
//        }

        // 总合计
        $this->service->sort = '';
        $sumAll = $this->service->listBySqlId('count_all');
        if($sumAll){
            $sumAllRow = $sumAll[0];
            $sumAllRow['reMoney'] = bcsub($sumAllRow['Amount'],$sumAllRow['Money'],2);
            $sumAllRow['mark'] = 'sum';
            $sumAllRow['sumSql'] = $this->service->listSql;
            unset($sumAllRow['Money']);
            $object[] = $sumAllRow;
        }

        return $object;
    }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
        $arr = array ();
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->searchArr['isTemp'] = 0;

        if(isset($service->searchArr['Status'])){
            $searchStatus = $service->searchArr['Status'];
            if(util_jsonUtil::iconvUTF2GB($searchStatus) == '未还款'){
                $service->searchArr['StatusArr'] = "已支付,还款中";
//                $service->searchArr['notExaStatus'] = "变更审批中";
                unset($service->searchArr['Status']);
            }
//            else if(util_jsonUtil::iconvUTF2GB($searchStatus) == '出纳支付'){
//                $service->searchArr['notExaStatus'] = "变更审批中";
//            }
            else if(util_jsonUtil::iconvUTF2GB($searchStatus) == '变更审批中'){
                $service->searchArr['ExaStatus'] = "变更审批中";
                unset($service->searchArr['Status']);
            }else{
//                $service->searchArr['notExaStatus'] = "变更审批中";
            }
        }

        // 添加权限过滤
        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('loan_loan_loan', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
        $deptLimit = isset($limitArr['部门权限'])? $limitArr['部门权限'] : '';
        $companyLimit = isset($limitArr['公司权限'])? $limitArr['公司权限'] : '';
        $businessLimit = isset($limitArr['事业部权限'])? $limitArr['事业部权限'] : '';
        $XmFlagLimit = isset($limitArr['借款类型权限'])? $limitArr['借款类型权限'] : '';

        if($deptLimit != '' || $companyLimit != '' || $businessLimit != '' || $XmFlagLimit != ''){
            if(strstr($deptLimit, ';;') || strstr($companyLimit, ';;') || strstr($businessLimit, ';;') || strstr($XmFlagLimit, ';;')){
                $limitSql = array();
            }else{
                $limitSql = array();
                // 部门权限
                if($deptLimit != ''){
                    $deptLimitArr = explode(",",$deptLimit);
                    $deptLimitStr = "'".implode($deptLimitArr,"','")."'";
                    if($deptLimitStr != ''){
                        $limitSql[] = "c.debtorDeptCode in ({$deptLimitStr})";
                    }
                }

                // 公司权限
                if($companyLimit != ''){
                    $companyLimitArr = explode(",",$companyLimit);
                    $companyLimitStr = "'".implode($companyLimitArr,"','")."'";
                    if($companyLimitStr != ''){
                        $limitSql[] = "c.belongcomcode in ({$companyLimitStr})";
                    }
                }

                // 借款类型权限
                if($XmFlagLimit != ''){
                    $XmFlagLimitArr = explode(",",$XmFlagLimit);
                    $XmFlagLimitStr = "'".implode($XmFlagLimitArr,"','")."'";
                    if($XmFlagLimitStr != ''){
                        $limitSql[] = "c.XmFlag in ({$XmFlagLimitStr})";
                    }
                }

                // 事业部权限 [p.deviceCode,p.deviceName]
//            if($businessLimit != ''){
//                $businessLimitArr = explode(",",$businessLimit);
//                $businessLimitStr = "'".implode($businessLimitArr,"','")."'";
//                if($businessLimitStr != ''){
//                    $limitSql[] = "bri.namePT in ({$businessLimitStr})";
//                }
//            }
            }

            $service->searchArr['limitSql'] = "sql:and (";
            if(!empty($limitSql)){
                foreach ($limitSql as $k => $v){
                    if($k == 0){
                        $service->searchArr['limitSql'] .= "(".$v.")";
                    }else{
                        $service->searchArr['limitSql'] .= " or (".$v.")";
                    }
                }
            }else{
                $service->searchArr['limitSql'] .= "1=1";
            }
            $service->searchArr['limitSql'] .= ")";

            //$service->asc = false;
            $rows = $service->page_d ();
            $_SESSION ['listLoanSql'] = $arr ['listSql'] = $service->listSql;

            // 单据相关字段处理
            $rows = $this->listDataProcess($rows);

            foreach ($rows as $k => $v){
                $rows[$k]['loanNatureName'] = $this->service->_loanNature[$v['loanNature']];
            }
        }else{
            $rows = array();
        }

        //单页小计加载
        $rows = empty($rows)? $rows : $this->pageCount($rows);

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}


    /**
     * 返回 json 个人借款单数据
     */
    function c_listMyLoanJson(){
        $arr = array ();
        $service = $this->service;

        $service->getParam ( $_REQUEST );

        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        $service->searchArr['isTemp'] = 0;
        //$service->asc = false;
        if(isset($service->searchArr['Status'])){
            $searchStatus = $service->searchArr['Status'];
            if(util_jsonUtil::iconvUTF2GB($searchStatus) == '未还款'){
                $service->searchArr['StatusArr'] = "已支付,还款中";
//                $service->searchArr['notExaStatus'] = "变更审批中";
                unset($service->searchArr['Status']);
            }
//            else if(util_jsonUtil::iconvUTF2GB($searchStatus) == '出纳支付'){
//                $service->searchArr['notExaStatus'] = "变更审批中";
//            }
            else if(util_jsonUtil::iconvUTF2GB($searchStatus) == '变更审批中'){
                $service->searchArr['ExaStatus'] = "变更审批中";
                unset($service->searchArr['Status']);
            }else{
//                $service->searchArr['notExaStatus'] = "变更审批中";
            }
        }

        $rows = $service->page_d ();
        $_SESSION ['listLoanSql'] = $arr ['listSql'] = $service->listSql;

        // 单据相关字段处理
        $rows = $this->listDataProcess($rows);

        //单页小计加载
        $rows = empty($rows)? $rows : $this->pageCount($rows);

        foreach ($rows as $k => $v){
            $rows[$k]['loanNatureName'] = $this->service->_loanNature[$v['loanNature']];
        }

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 跳转查看页面
     */
    function c_toViewTab()
    {
        if(isset($_GET['isTemp']) && $_GET['isTemp'] == 1){
            $mainObj = $this->service->find(array('id'=>$_GET['id']));
            $id = (isset($mainObj['originalId']) && $mainObj['originalId'] > 0)? $mainObj['originalId'] : '';
            $this->assign('id', $id);
        }else{
            $this->assign('id', $_GET['id']);
        }
//        $isTemp = $this->service->isTemp($_GET['id']);

        $this->display('viewTab');
    }

    /**
     * 跳转查看页面
     */
    function c_toView() {
        $this->permCheck (); //安全校验
        $obj = $this->service->get_d ( $_GET ['id'] );
        foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
        }
        if($obj['XmFlag'] == "0"){
            $this->assign('XmFlag',"部门借款");
        }else{
            $this->assign('XmFlag',"工程借款");
        }

        // 借款性质
        $rendDatesStr = $nameStr = "";
        if($obj['loanNature'] == "1"){
            if(is_array($this->service->_loanNature)){
                $nameStr = $this->service->_loanNature[1];
            }
            $this->assign('loanNature',$nameStr);
            $rendDatesStr = "
            <tr>
                <td class=\"form_text_left\">租房开始</span></td>
                <td class=\"form_text_right\">
                     {$obj['rendHouseStartDate']}
                </td>
                <td  class=\"form_text_left\">租房结束</td>
                <td class=\"form_view_right\">
                    {$obj['rendHouseEndDate']}
                </td>
            </tr>";
        }else if($obj['loanNature'] == "2"){
            if(is_array($this->service->_loanNature)){
                $nameStr = $this->service->_loanNature[2];
            }
            $this->assign('loanNature',$nameStr);
        }
        $this->assign('rendDatesStr',$rendDatesStr);

        //附件
        $file = $this->service->getFilesByObjId($obj['ID'], false);
        $this->assign('file', $file);

        $this->view ( 'view' );
    }

    /**
     *  跳转审批查看页面
     */
    function c_toAudit(){
        $this->service->getParam ( $_REQUEST );
        $obj = $this->service->list_d();
        $obj = (!empty($obj))? $obj[0] : array();
        foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
            if($key == "Debtor"){
                $debtorName = $this->service->getUserName_d($val);
                $this->assign ( "debtorName", $debtorName );
            }else if($key == "Amount"){
                $globalDao = new includes_class_global();
                $AmountBig = $globalDao->num2Upper($val);
                $this->assign ( "AmountBig", $AmountBig );
            }
        }

        // 个人借款信息
        $personalLoanInfo = $this->service->getPersonalLoanInfo($obj['Debtor']);
//        echo"<pre>";print_r($personalLoanInfo);exit();
        foreach ($personalLoanInfo as $k => $v){
            $this->assign ( $k, $v );
        }

        if($obj['projectId'] != ''){
            $inLoanAmount = $this->service->getInLoanAmountByProjId($obj['projectId']);
            $this->assign ( 'inLoanAmount', $inLoanAmount );
        }

        // 借款类型
        if($obj['XmFlag'] == "0"){
            $this->assign('XmFlag',"部门借款");
        }else{
            $this->assign('XmFlag',"工程借款");
        }

        // 借款性质
        $rendDatesStr = $nameStr = "";
        if($obj['loanNature'] == "1"){
            if(is_array($this->service->_loanNature)){
                $nameStr = $this->service->_loanNature[1];
            }
            $this->assign('loanNature',$nameStr);
            $rendDatesStr = "
                    <tr bgcolor=\"#FBFBFB\">
                        <td align=\"right\" height=\"23\">
                            <span class=\"labelStr\"> 租房开始 : &nbsp;</span>
                        </td>
                        <td>{$obj['rendHouseStartDate']}</td>
                    
                        <td align=\"right\" style='text-align: right !important;' height=\"23\">
                            <span class=\"labelStr\"> 租房结束 : &nbsp;</span>
                        </td>
                        <td colspan=\"2\">{$obj['rendHouseEndDate']}</td>
                    </tr>";
        }else if($obj['loanNature'] == "2"){
            if(is_array($this->service->_loanNature)){
                $nameStr = $this->service->_loanNature[2];
            }
            $this->assign('loanNature',$nameStr);
        }

        $this->assign('rendDatesStr',$rendDatesStr);

        //附件
        $file = $this->service->getFilesByObjId($obj['id'], false);
        $this->assign('file', $file);

        $this->view ( 'auditView' );
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = false) {
        $row = $_POST [$this->objName];
        $act = isset ($_GET ['act']) ? $_GET ['act'] : null;
        $id = $this->service->add_d($row);

        $object = $this->service->find(array("id"=>$id));
        $workArea = $this->service->getWorkArea_d();
        $appArea = $this->service->getAppArea($object['ProjectNo']);
        if ($id) {
            if ($act == 'app') {
            	 if($row['XmFlag'] == '0'){
            	     //部门借款
                     //succ_show("general/costmanage/loan/select_wflow.php?xmFlag=0&ID=".$id."&appArea=$appArea&workArea=".$workArea);
                     succ_show("general/costmanage/loan/ewf_index_loan.php?actTo=ewfSelect&billId=" . $id . "&proSid=" . $object['projectId'] . "&billArea=" . $appArea . "&flowMoney=".$object['Amount']."&flowType=1&loanAttr=".$object['loanNature']);
            	 }else{
                     //工程借款
            	     $chkSevProjectRst = $this->service->_db->getArray("select * from oa_esm_project where attribute in ('GCXMSS-02','GCXMSS-01','GCXMSS-05') and id = '{$row['projectId']}';");
                     if($chkSevProjectRst && count($chkSevProjectRst) > 0){// 服务项目
                         succ_show("general/costmanage/loan/ewf_index_loan_sev.php?actTo=ewfSelect&billId=" . $id . "&proSid=" . $object['projectId'] . "&billArea=" . $appArea . "&flowMoney=".$object['Amount']."&flowType=2&loanAttr=".$object['loanNature']);
                     }else{// 其他类工程项目
                         //succ_show("general/costmanage/loan/select_wflow.php?ID=".$id."&xmFlag=1&appArea=$appArea");
                         succ_show("general/costmanage/loan/ewf_index_loan.php?actTo=ewfSelect&billId=" . $id . "&proSid=" . $object['projectId'] . "&billArea=" . $appArea . "&flowMoney=".$object['Amount']."&flowType=2&loanAttr=".$object['loanNature']);
                     }
            	 }
            } else {
                msg('添加成功！');
            }
        }
    }

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
//		$this->checkSubmit();
		$object = $_POST [$this->objName];
		$act = isset ($_GET ['act']) ? $_GET ['act'] : null;
		$workArea = $this->service->getWorkArea_d();
        $appArea = $this->service->getAppArea($object['ProjectNo']);
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			if ($act == 'app') {
                $billDept = "";
				if($object['XmFlag'] == '0'){
            	 	//部门借款
                    //succ_show("general/costmanage/loan/select_wflow.php?xmFlag=0&ID=".$object['id']."&appArea=$appArea&workArea=".$workArea);
                    succ_show("general/costmanage/loan/ewf_index_loan.php?actTo=ewfSelect&billId=" . $object['id'] . "&proSid=" . $object['projectId'] . "&billArea=" . $appArea . "&billDept=".$billDept."&flowMoney=".$object['Amount']."&flowType=1&loanAttr=".$object['loanNature']);
            	 }else{
                    //工程借款
                    $chkSevProjectRst = $this->service->_db->getArray("select * from oa_esm_project where attribute in ('GCXMSS-02','GCXMSS-01','GCXMSS-05') and id = '{$object['projectId']}';");
                    if($chkSevProjectRst && count($chkSevProjectRst) > 0){// 服务项目
                        succ_show("general/costmanage/loan/ewf_index_loan_sev.php?actTo=ewfSelect&billId=" . $object['id'] . "&proSid=" . $object['projectId'] . "&billArea=" . $appArea . "&billDept=".$billDept."&flowMoney=".$object['Amount']."&flowType=2&loanAttr=".$object['loanNature']);
                    }else{// 其他类工程项目
                        //succ_show("general/costmanage/loan/select_wflow.php?ID=".$object['id']."&xmFlag=1&appArea=$appArea");
                        succ_show("general/costmanage/loan/ewf_index_loan.php?actTo=ewfSelect&billId=" . $object['id'] . "&proSid=" . $object['projectId'] . "&billArea=" . $appArea . "&billDept=".$billDept."&flowMoney=".$object['Amount']."&flowType=2&loanAttr=".$object['loanNature']);
                    }

            	 }
			} else {
                msg('编辑成功！');
            }
		}
	}

    /**
     * ajax 修改借款金额
     */
	function c_ajaxChangeAmount(){
	    $mainId = isset($_REQUEST['id'])? $_REQUEST['id'] : '';
        $newAmount = isset($_REQUEST['amount'])? $_REQUEST['amount'] : '';
        $globalDao = new includes_class_global();
        $AmountBig = $globalDao->num2Upper($newAmount);
        $AmountBig = iconv('gb2312','utf-8',$AmountBig);
        $newArr = array("id"=>$mainId,"Amount"=>$newAmount);
        $result = $this->service->edit_d($newArr);
        if($result){
            $resultArr = $this->service->find(array("id"=>$mainId));
            if($resultArr['Amount'] == $newAmount){
                $backArr['result'] = "ok";
                $backArr['msg'] = iconv('gb2312','utf-8',"更新成功!");
                $backArr['data'] = array("AmountBig" => $AmountBig,"newAmount"=>$newAmount);
            }else{
                $backArr['result'] = "fail";
                $backArr['msg'] = iconv('gb2312','utf-8',"更新失败!");
            }
        }else{
            $backArr['result'] = "fail";
            $backArr['msg'] = iconv('gb2312','utf-8',"更新失败!");
            $backArr['data'] = array("AmountBig" => $AmountBig,"newAmount"=>$newAmount);
        }
        echo json_encode($backArr);
    }

    /**
     * ajax 检查租房日期是否在项目的预计工期范围
     */
    function c_ajaxChkRendDateRange(){
        $esmprojectDao = new model_engineering_project_esmproject();
        $projId = isset($_REQUEST['projId'])? $_REQUEST['projId'] : '';
        $rendHouseStartDate = isset($_REQUEST['rendHouseStartDate'])? $_REQUEST['rendHouseStartDate'] : '';
        $rendHouseEndDate = isset($_REQUEST['rendHouseEndDate'])? $_REQUEST['rendHouseEndDate'] : '';
        $rendHouseStartTimes = strtotime($rendHouseStartDate);
        $rendHouseEndTimes = strtotime($rendHouseEndDate);

        $returnArr['startDate'] = '';
        $returnArr['endDate'] = '';
        if($projId != ''){
            $projectData = $esmprojectDao->get_d($projId);
            if($projectData){
                $planBeginTimes = isset($projectData['planBeginDate'])? strtotime($projectData['planBeginDate']) : 0;
                $planEndTimes = isset($projectData['planEndDate'])? strtotime($projectData['planEndDate']) : 0;
                if($rendHouseStartTimes >= $planBeginTimes && $rendHouseEndTimes <= $planEndTimes){
                    $returnArr['result'] = 'ok';
                }else{
                    $returnArr['result'] = 'fail';
                    $returnArr['startDate'] = $projectData['planBeginDate'];
                    $returnArr['endDate'] = $projectData['planEndDate'];
                }
            }else{
                $returnArr['result'] = 'fail';
            }
        }else{
            $returnArr['result'] = 'fail';
        }
        echo json_encode($returnArr);
    }

    /**
     * ajax 检查申请金额是否超出了项目预算金额
     */
    function c_ajaxChkBeyondBudget(){
        $projId = isset($_REQUEST['projId'])? $_REQUEST['projId'] : '';
        $amountVal = isset($_REQUEST['amountVal'])? $_REQUEST['amountVal'] : 0;

        $returnArr['result'] = 'ok';
        $returnArr['isBeyondBudget'] = $this->service->chkBeyondBudget($projId,$amountVal);

        echo json_encode($returnArr);
    }

    /**
     * 借款报表
     */
    function c_loanReport(){
        $this->view ( 'loanreport' );
    }
    //借款报表-json
    function c_reportPageJson() {
        $service = $this->service;
        $rows = $service->getReportRow_1($_REQUEST); //PMS866

        //统计金额
        $rows = $service->getRowsallMoney_d($rows);
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['totalSize'] = ($service->count === 0)? $service->count : $arr ['totalSize'];
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 审批完成后调用方法
     */
    function c_dealAfterAudit()
    {
        $spid = $_GET['spid'];
        $this->service->dealAfterAudit_d($spid);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 变更审批查看页面
     */
    function c_changeAudit(){
        $actType = isset($_GET['actType'])? $_GET['actType'] : '';
        $TempId = isset($_GET['id'])? $_GET['id'] : '';

        $changeLogDao = new model_common_changeLog ( 'loanList' );

        $changeLog = $changeLogDao->getObjByTempId($TempId);
        $changeReason = ($changeLog && isset($changeLog[0]['changeReason']))? $changeLog[0]['changeReason'] : '';
        $this->assign ( 'changeReason' , $changeReason );
        $this->assign ( 'loanId' , $changeLog[0]['objId'] );

        $changeLogObj = $changeLogDao->getChangeInformation_d($TempId,'loanList');
        $changeLogInfo = array();
        foreach ($changeLogObj as $v){
            $changeLogInfo[$v['changeField']]['oldValue'] = $v['oldValue'];
            $changeLogInfo[$v['changeField']]['newValue'] = $v['newValue'];
        }

        $this->service->getParam ( $_REQUEST );
        $obj = $this->service->list_d();
        $obj = (!empty($obj))? $obj[0] : array();

        foreach ( $obj as $key => $val ) {

            if(isset($changeLogInfo[$key])){
                $this->assign ( $key."Old", $changeLogInfo[$key]['oldValue'] );
                $this->assign ( $key, '&nbsp;&nbsp;->&nbsp;&nbsp;'.$val );
            }else{
                // 变更时候会修改的的字段
                $changeField = array("ProjectNo","PrepaymentDate","rendHouseStartDate","rendHouseEndDate");
                if(in_array($key,$changeField)){
                    $this->assign ( $key."Old", $val );
                    $this->assign ( $key, '');
                }else{
                    $this->assign ( $key, $val);
                }
            }

            if($key == "Debtor"){
                $debtorName = $this->service->getUserName_d($val);
                $this->assign ( "debtorName", $debtorName );
            }else if($key == "Amount"){
                $globalDao = new includes_class_global();
                $AmountBig = $globalDao->num2Upper($val);
                $this->assign ( "AmountBig", $AmountBig );
            }
        }

        if(isset($changeLogInfo['ProjectNo']) && !empty($changeLogInfo['ProjectNo'])){
            if($changeLogInfo['ProjectNo']['oldValue'] != ''){
                $sql = "select id from oa_esm_project where projectCode = '{$changeLogInfo['ProjectNo']['oldValue']}' limit 1;";
                $arr = $this->service->_db->getArray($sql);
                $oldProjectId = ($arr[0]['id'] != '')? $arr[0]['id'] : '';
                $this->assign ( "oldProjectId", $oldProjectId );
            }
        }else{
            $this->assign ( "oldProjectId", $obj['projectId'] );
        }

        // 个人借款信息
        $personalLoanInfo = $this->service->getPersonalLoanInfo($obj['Debtor']);

        foreach ($personalLoanInfo as $k => $v){
            $this->assign ( $k, $v );
        }


        // 借款类型
        $this->assign('XmFlagCode',$obj['XmFlag']);
        if($obj['XmFlag'] == "0"){
            $this->assign('XmFlag',"部门借款");
        }else{
            $this->assign('XmFlag',"工程借款");
        }

        if($obj['projectId'] != ''){
            $inLoanAmount = $this->service->getInLoanAmountByProjId($obj['projectId']);
            $this->assign ( 'inLoanAmount', $inLoanAmount );
        }

        // 借款性质
        $rendDatesStr = $nameStr = "";
        if($obj['loanNature'] == "1"){
            if(is_array($this->service->_loanNature)){
                $nameStr = $this->service->_loanNature[1];
            }
            $this->assign('loanNature',$nameStr);
            $rendHouseStartDateOld = isset($changeLogInfo['rendHouseStartDate'])? $changeLogInfo['rendHouseStartDate']['oldValue'] : $obj['rendHouseStartDate'];
            $rendHouseEndDateOld = isset($changeLogInfo['rendHouseEndDate'])? $changeLogInfo['rendHouseEndDate']['oldValue'] : $obj['rendHouseEndDate'];
            $rendHouseStartDateNew = isset($changeLogInfo['rendHouseStartDate'])? '&nbsp;&nbsp;->&nbsp;&nbsp;'.$changeLogInfo['rendHouseStartDate']['newValue'] : '';
            $rendHouseEndDateNew = isset($changeLogInfo['rendHouseEndDate'])? '&nbsp;&nbsp;->&nbsp;&nbsp;'.$changeLogInfo['rendHouseEndDate']['newValue'] : '';

            $rendDatesStr = "
                    <tr bgcolor=\"#FBFBFB\">
                        <td align=\"right\" height=\"23\">
                            <span class=\"labelStr\"> 租房开始 : &nbsp;</span>
                        </td>
                        <td>{$rendHouseStartDateOld}<font color='red'>{$rendHouseStartDateNew}</font></td>
                    
                        <td align=\"right\"  style='text-align: right !important;' height=\"23\">
                            <span class=\"labelStr\"> 租房结束 : &nbsp;</span>
                        </td>
                        <td colspan=\"2\">{$rendHouseEndDateOld}<font color='red'>{$rendHouseEndDateNew}</font></td>
                    </tr>";
        }else if($obj['loanNature'] == "2"){
            if(is_array($this->service->_loanNature)){
                $nameStr = $this->service->_loanNature[2];
            }
            $this->assign('loanNature',$nameStr);
        }

        $this->assign('rendDatesStr',$rendDatesStr);

        //附件
        $file = $this->service->getFilesByObjId($obj['id'], false);
        $this->assign('file', $file);

        switch ($actType){
            case 'audit':
                $this->view ( 'auditChangeView' );
                break;
            default:
                $this->view ( 'auditChangeView' );
                break;
        }
    }

    /**
     * 变更审批完成后调用方法
     */
    function c_dealAfterAuditChange(){
        $spid = $_GET['spid'];
        $this->service->dealAfterAuditChange_d($spid);
        succ_show('?model=common_workflow_workflow&action=auditingList');
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
        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);

        $paramArr['searchType'] = $_GET['searchType'];
        $paramArr['searchVal'] = $_GET['searchVal'];
        $paramArr['company'] = $_GET['company'];
        $paramArr['payBegin'] = $_GET['payBegin'];
        $paramArr['payEnd'] = $_GET['payEnd'];

        $rows = $service->getReportRow($paramArr,1);
        //匹配导出列
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);
        foreach ($rows as $key => $row) {
            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }
            array_push($dataArr, $colIdArr);
        }
        return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);
    }

    /**
     * 预先把页面的列表字段信息加入Session中
     */
    function c_setColInfoToSession(){
        $_SESSION['exportCol'] = array();
        $_SESSION['exportCol']['ColId'] = isset($_REQUEST['ColId'])? $_REQUEST['ColId'] : '';
        $_SESSION['exportCol']['ColName'] = isset($_REQUEST['ColName'])? $_REQUEST['ColName'] : '';

        if($_SESSION['exportCol']['ColId'] !='' && $_SESSION['exportCol']['ColName'] != ''){
            echo 1;
        }else{
            unset($_SESSION['exportCol']);
            echo 0;
        }
    }

    /**
     * 检验导出数据的权限
     */
    function c_chkExcelOutLimit(){
        if(isset($this->service->this_limit['导出权限']) && $this->service->this_limit['导出权限'] == 1){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 导出页面列表数据
     */
    function c_excelOut(){
        $service = $this->service;
        set_time_limit(0);
        $XmFlagArr = $service->_loanTypes;// 借款类型;

        $IsFinRecArr = array(// 财务收单
            "1" => "已收单",
            "0" => "未收单"
        );

        $writeOffArr = array(// 是否允许冲销
            "0" => "允许冲销",
            "1" => "禁止冲销"
        );

        $loanNature = array(
            "1" => "租房押金",
            "2" => "短期借款"
        );

        $listLoanSql = isset($_SESSION ['listLoanSql'])? $_SESSION ['listLoanSql'] : '';
        if(isset($_SESSION['exportCol'])){
            $colIdStr = $_SESSION['exportCol']['ColId'];
            $colNameStr = $_SESSION['exportCol']['ColName'];
            unset($_SESSION['exportCol']);
        }else{
            $colIdStr = '';
            $colNameStr = '';
        }
        ini_set('memory_limit', '1024M');
        $rows = $service->_db->getArray($listLoanSql);
        if($rows){
            $rows = $this->listDataProcess($rows);
            foreach ($rows as $k => $v){
                $rows[$k]['XmFlag'] = $XmFlagArr[$v['XmFlag']];
                $rows[$k]['IsFinRec'] = $IsFinRecArr[$v['IsFinRec']];
                $rows[$k]['no_writeoff'] = $writeOffArr[$v['no_writeoff']];
                $rows[$k]['isBackMoney'] = $v['Amount'] - $v['reMoney'];
                $rows[$k]['loanNatureName'] = $loanNature[$v['loanNature']];
                foreach ($v as $vk =>$vv){
                    if( $vv == "0000-00-00 00:00:00" ){
                        $rows[$k][$vk] = '';
                    }
                }
            }
        }else{
            $rows = array();
        }
        $dataArr = $rows;

        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        unset($colIdArr[0]);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        unset($colNameArr[0]);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);
        $colArr = util_jsonUtil::iconvUTF2GBArr($colArr);
        unset($colArr['payStatus']);
        
        return model_loan_common_loanExcelUtil::export2ExcelUtil($colArr, $dataArr,"借款单列表");
        // return model_loan_common_loanExcelUtil::exportCSV($colArr, $dataArr,"借款单列表");
    }

    function c_toSeeTheAuditRecord(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        if($id != ''){
            $obj = $this->service->get_d ( $id );
            foreach ( $obj as $key => $val ) {
                $this->assign ( $key, $val );
            }

            $this->view ( 'auditRecord' );
        }else{
            echo "传入数据有误,无ID值。";
        }
    }

    /**
     * 跳转到报表借款单明细列表
     */
    function c_loanReportDetail(){
        $payBegin = $payEnd = $company = $compantId = '';
        $type = isset($_GET['type'])? $_GET['type'] : 'NULL';
        $deptCode = isset($_GET['deptCode'])? $_GET['deptCode'] : 'NULL';
        $typeCode = isset($_GET['typeCode'])? $_GET['typeCode'] : 'NULL';
        $extParamStr = isset($_GET['extParam'])? $_GET['extParam'] : '';

        $matchArr = array();
        if(isset($_SESSION['loanReportArr']) && !empty($_SESSION['loanReportArr'])){
            foreach ($_SESSION['loanReportArr'] as $k => $v){
                if($typeCode == 'debtorDeptName'){
                    if($v['type'] == $type && $v['deptCode'] == $deptCode){
                        $matchArr = $v;
                        break;
                    }
                }else if($v['type'] == $type){
                    $matchArr = $v;
                    break;
                }
            }
        }
//
        if(isset($matchArr['sltIdsSqlForDept']) && $matchArr['sltIdsSqlForDept'] != '' && $matchArr['typeCode'] == 'debtorDeptName'){
            $idsArr = $this->service->_db->getArray($matchArr['sltIdsSqlForDept']);
            if($idsArr){
                $matchArr['ids'] = base64_encode($idsArr[0]['ids']);
            }
        }

        $typeCode = $searchParam = $ids = '';
        if(isset($matchArr['typeCode'])){
            $typeCode = $matchArr['typeCode'];
            if($type == 'NULL' || $type == ''){
                switch ($matchArr['typeCode']){
                    case 'debtorName';
                        $searchParam = "emptyDebtorName";
                        break;
                    case 'debtorDeptName';
                        $searchParam = "emptyDebtorDeptName";
                        break;
                    case 'divisionName';
                        $searchParam = "emptyDivisionName";
                        break;
                }
            }else{
                $ids = $matchArr['ids'];
            }
        }

        // 如果ID串过长的时候,转用脚本查询ID
        if($matchArr['idsNum'] > 200 && ($type != 'NULL' && $type != '')){
            $_SESSION['searchIds'] = $matchArr['ids'];
            $ids = "";
            $searchParam = $matchArr['typeCode']."Long";
        }

        // 附加搜索条件
        if($extParamStr != ''){
            $extParamArr = explode("|",$extParamStr);
            if(is_array($extParamArr)){
                foreach ($extParamArr as $paramStr){
                    $paramArr = explode(":",$paramStr);
                    switch ($paramArr[0]){
                        case'payBegin':
                            $payBegin = $paramArr[1];
                            break;
                        case'payEnd':
                            $payEnd = $paramArr[1];
                            break;
                        case'companyName':
                            $company = $paramArr[1];
                            break;
                        case'companyId':
                            $compantId = $paramArr[1];
                            break;
                    }
                }
            }
        }

        $this->assign ( "payBegin", $payBegin );
        $this->assign ( "payEnd", $payEnd );
        $this->assign ( "company", $company );
        $this->assign ( "compantId", $compantId );
        $this->assign ( "searchType", $searchParam );
        $this->assign ( "typeCode", $typeCode );
        $this->assign ( "ids", $ids );
        $this->view ( 'loanReportDetail' );
    }

    /**
     * 对接NC接口
     */
    function c_sendToNC(){
        $loanId = isset($_REQUEST['loanId'])? $_REQUEST['loanId'] : '';

        if(!empty($loanId)){
            $ncApiDao = new model_common_ncApi();
            $LoanInfoSql = <<<EOT
        select l.id,l.Amount,l.Debtor,l.belongcom,l.belongcomcode,i.nc_pk_org as ncComCode,DATE_FORMAT(l.payDT,"%Y-%m-%d") as payDate,DATE_FORMAT(l.PrepaymentDate,"%Y-%m-%d") as PrepaymentDate,IF(l.XmFlag = 0,'DEPT','PROJECT') AS typeCode 
        from loan_list l 
        left join branch_info i on i.NamePT = l.belongcomcode
        where l.ID in ($loanId) order by id desc
EOT;
            $LoanInfoArr = $this->service->_db->getArray($LoanInfoSql);
            $data = array(
                "loan" => array(),
                "type" => "LOAN"
            );
            foreach ($LoanInfoArr as $loanInfo){
                // 获取 NC 用户ID
                $NcUserId = '';
                $UserawsInfo = util_curlUtil::getDataFromAWS('mobile', 'getNCIdByUid', array("uid" => $loanInfo['Debtor']), array(), true, 'com.youngheart.apps.');
                $UserawsInfo = util_jsonUtil::decode($UserawsInfo['data']);
                if($UserawsInfo['result'] == 'ok' && isset($UserawsInfo['data']) && isset($UserawsInfo['data']['result']) && $UserawsInfo['data']['result'] == 0){
                    $UserawsInfo = $UserawsInfo['data'];
                    $NcUserId = (isset($UserawsInfo['NCId']))? $UserawsInfo['NCId'] : '';
                }

                // 整合借款单的相关信息
                $data['loan'][] = array(
                    'userId' => $NcUserId,
                    'money' => $loanInfo['Amount'],
                    'typeCode' => $loanInfo['typeCode'],
                    'repaymentDate' => $loanInfo['PrepaymentDate'],
                    'payDate' => $loanInfo['payDate'],
                    'pk_org' => $loanInfo['ncComCode']
                );
            }

            // 向接口推送数据,注意参数的key名需要跟接口报文里面对应参数的name值一致,否则对方接受不到传过去的参数
            $sendData = array();
            $dataJson = util_jsonUtil::encode($data['loan']);
            $dataJson = "{loan:{$dataJson}}";
            $sendData["string"] = $dataJson;
            $sendData["string1"] = $data['type'];
            $ncApiDao->sendLoanPayToNC($sendData);
        }

         msgGo("支付成功！","general/costmanage/teller/loan_list.php");
    }
 }
?>