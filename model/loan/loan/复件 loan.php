<?php

/**
 * 客户model层类
 */
class model_loan_loan_loan extends model_base {

    public $_loanNature = '';
    public $_loanTypes = '';
	function __construct() {
		$this->tbl_name = "Loan_list";
		$this->sql_map = "loan/loan/loanSql.php";
		parent::__construct ();
        $this->setLoanTypes();
        $this->setLoanNature();
	}

    /**
     *  设置借款性质
     */
	function setLoanNature(){
        $otherDataDao = new model_common_otherdatas();
        $loanTypesStr = $otherDataDao->getConfig('loanNature');
        $arr = array();
        if($loanTypesStr != ''){
            $arr1 = explode(",",$loanTypesStr);
            foreach ($arr1 as $v){
                $arr2 = explode(":",$v);
                $arr[$arr2[0]] = $arr2[1];
            }
        }
        $this->_loanNature = $arr;
    }

    /**
     *  设置借款类型
     */
    function setLoanTypes(){
        $otherDataDao = new model_common_otherdatas();
        $loanTypesStr = $otherDataDao->getConfig('loanTypes');
        $arr = array();
        if($loanTypesStr != ''){
            $arr1 = explode(",",$loanTypesStr);
            foreach ($arr1 as $v){
                $arr2 = explode(":",$v);
                $arr[$arr2[0]] = $arr2[1];
            }
        }
        $this->_loanTypes = $arr;
    }

    /**
     * 重写add_d方法
     */
    function add_d($object) {
        try {
            $this->start_d();

            $object['Debtor'] = $_SESSION['USER_ID'];
            $object['CreateDT'] = date("Y-m-d H:i:s");
            $object['ApplyDT'] = date("Y-m-d H:i:s");
            $object['belongcom'] = $_SESSION['USER_COM_NAME'];
            $object['belongcomcode'] = $_SESSION['USER_COM'];

            //插入主表信息
            $newId = parent:: add_d($object);

            //处理附件名称和Id
            $this->updateObjWithFile($newId);

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重写编辑方法
     */
    function edit_d($object) {
        try {
            $this->start_d();
            //修改主表信息
            parent:: edit_d($object, true);

            $id = $object['id'];

            //处理附件名称和Id
            $this->updateObjWithFile($id);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    function getWorkArea_d(){
		 	$sql="SELECT
				s.salesAreaName,
				s.id
			FROM
				`oa_system_saleperson` s
			WHERE
				s.deptId = '".$_SESSION['DEPT_ID']."' or (s.deptId ='37' and '".$_SESSION['DEPT_ID']."' in (172,198))
			ORDER BY
				businessBelong DESC,
				salesAreaName";

		  $rtnArr = $this->_db->getArray($sql);
		  if(!empty($rtnArr)){
		  	 return $rtnArr[0]['id'];
		  }else{
             return "";
		  }
   }

   function getAppArea($proNo){
   	  if(empty($proNo)){
   	  	return "";
   	  }
   	  $sql = "select id from oa_esm_project where projectCode='".$proNo."'";
   	  $tempArr = $this->_db->getArray($sql);
   	  if(!empty($tempArr[0]['id'])){
         $proDao = new model_engineering_project_esmproject();
   	     $proArea = $proDao->getRangeId_d($tempArr[0]['id']);
   	     return $proArea;
   	  }else{
   	  	return "";
   	  }
   }

    /**
     * user 转换
     */
    function getUserName_d($userId){
        $userDao = new model_deptuser_user_user();
        $userName = $userDao->getUserName_d($userId);
        return $userName['USER_NAME'];
    }

    /**
     * 获取需还款金额
     *
     * @param $id
     * @param $Amount
     * @return string
     */
    function getReMoney_d($id,$Amount){
    	$sql = "select sum(Money) as reMoney from loan_repayment  where loan_ID = '".$id."'";
    	$reArr = $this->_db->getArray($sql);
    	if(empty($reArr[0]['reMoney'])){
    		return $Amount;
    	}else{
            return bcsub($Amount,$reArr[0]['reMoney']);
        }
    	return $reArr[0]['reMoney'];
    }

    /**
     * 根据用户ID获取相关的个人信息
     * @param $debtor
     * @return array
     */
    function getPersonalLoanInfo($debtor){
        $backData = array();
        //欠款总额：(上月借款余额=已支付状态+还款中的金额)
        $sql="select sum(Amount) as amounts from loan_list where Debtor='".$debtor."' and Status in ('还款中','已支付') and isTemp = 0";
        $backData['preLoanBalanceSql1'] = $sql;
        $preLoanPay = $this->_db->getArray($sql);
        $preLoanPay = ($preLoanPay)? $preLoanPay[0]['amounts'] : '';
        $preLoanPay = ($preLoanPay == '')? 0 : $preLoanPay;

        $sql="select sum(r.Money) as rm from loan_list l , loan_repayment r  where l.ID=r.loan_ID and  l.Debtor='".$debtor."' and l.Status in('还款中' ) and l.isTemp = 0";
        $backData['preLoanBalanceSql2'] = $sql;
        $preloanReping = $this->_db->getArray($sql);
        $preloanReping = ($preloanReping)? $preloanReping[0]['rm'] : '';
        $preloanReping = ($preloanReping == '')? 0 : $preloanReping;
        $backData['preLoanBalance'] =$preLoanPay-$preloanReping;

        //逾期借款总额 [目前该人员名下超过预计归还时间所有借款单未归还金额]
        $sql="select
                l.debtor , sum(  IFNULL(  l.amount - IFNULL(r.repam,0)   , l.amount ) ) as lam 
            from
                loan_list l
                left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id ) 
    
            where
                l.status in ('还款中','已支付') and l.isTemp = 0
                AND
				IF (
					l.ReceiptDT <> '',
					IF (
						l.ReceiptDT <> '0000-00-00 00:00:00',
						date_format(l.PrepaymentDate, '%Y%m%d') < date_format(l.ReceiptDT, '%Y%m%d'),
						date_format(l.PrepaymentDate, '%Y%m%d') < date_format(now(), '%Y%m%d')
					),
					date_format(l.PrepaymentDate, '%Y%m%d') < date_format(now(), '%Y%m%d')
				)".
//                and l.no_writeoff = '0'
                "and l.debtor = '".$debtor."'
            group by l.debtor";
        $backData['yqjkamSql'] = $sql;
        $yqjkam = $this->_db->getArray($sql);
        $yqjkam = ($yqjkam)? $yqjkam[0]['lam'] : '';
        $backData['yqjkam'] = ($yqjkam == '')? 0 : $yqjkam;

        //租房押金借款总额
        $sql="select
                l.debtor , sum(  IFNULL(  l.amount - IFNULL(r.repam,0)   , l.amount ) ) as lam 
            from
                loan_list l
                left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id ) 
    
            where
                l.status in ('还款中','已支付') and l.isTemp = 0 ".
//               and l.no_writeoff = '1'
                "and l.loanNature = 1
                and l.debtor = '".$debtor."'
            group by l.debtor";
        $backData['cqjkamSql'] = $sql;
        $cqjkam = $this->_db->getArray($sql);
        $cqjkam = ($cqjkam)? $cqjkam[0]['lam'] : '';
        $backData['cqjkam'] = ($cqjkam == '')? 0 : $cqjkam;

        // 报销中的费用
        $sql = "SELECT
                    sum(t.Amount) as totalAmount
                FROM
                (
                    SELECT
                        c.Amount
                    FROM
                        cost_summary_list c
                    LEFT JOIN (
                        SELECT
                            t.pid,
                            p.Result
                        FROM
                            wf_task t
                        JOIN flow_step_partent p ON t.task = p.wf_task_id
                        WHERE
                            t. NAME = '报销审批'
                        AND p.SmallID = 1
                    ) t ON t.pid = c.id
                    WHERE
                        c. STATUS IN (
                            '部门检查',
                            '部门审批',
                            '财务审核',
                            '出纳付款'
                        )
                    AND (
                        (c.isProject = '1')
                        OR (
                            if(c.STATUS = '部门审批',(
                                c.isProject <> '1'
                                AND c.isNew = '1'
                                AND t.Result = 'ok'
                            ),(1=1))
                        )
                    )
                    AND c.CostMan = '".$debtor."'
                    GROUP BY c.id
                ) t";
        $billingCost = $this->_db->getArray($sql);
        $billingCost = ($billingCost)? $billingCost[0]['totalAmount'] : '';
        $backData['billingCost'] = ($billingCost == '')? 0 : $billingCost;

        //上交发票总额
        $sql = "select sum(Amount) AS amount  from cost_summary_list where CostMan = '".$debtor."' and Status not in('编辑','完成','打回') and IsFinRec = 1 and ( (isNotReced='0' and isProject='1') or isProject='0')";
        $backData['addUpNotCostSql'] = $sql;
        $addUpNotCost = $this->_db->getArray($sql);
        $addUpNotCost = ($addUpNotCost)? $addUpNotCost[0]['amount'] : '';
        $backData['addUpNotCost'] = ($addUpNotCost == '')? 0 : $addUpNotCost;

        // 手里发票总额
        $sql = "select sum(Amount) AS amount from cost_summary_list where CostMan = '".$debtor."' and Status in('编辑','部门检查') and IsFinRec = 0 and ( (isNotReced='1' and isProject='1') or isProject='0')";
        $backData['handCostSql'] = $sql;
        $handCost = $this->_db->getArray($sql);
        $handCost = ($handCost)? $handCost[0]['amount'] : '';
        $backData['handCost'] = ($handCost == '')? 0 : $handCost;

        // 最早一笔借款逾期天数(只算未还款的,已还款的即使是逾期还的也不算)
        $sql = "SELECT
                *
            FROM
            (
                SELECT
                    id,
                    amount,
                    debtor,
                    PrepaymentDate,
					case
					when ReceiptDT <> '' then DATEDIFF(date_format(ReceiptDT, '%Y-%m-%d'),date_format(PrepaymentDate, '%Y-%m-%d'))
					else DATEDIFF(date_format(now(), '%Y-%m-%d'),date_format(PrepaymentDate, '%Y-%m-%d')) end AS diffDays,
                    now() as today,
					ReceiptDT
                FROM
                    loan_list
                WHERE
                    date_format(PrepaymentDate, '%Y%m%d') <> ''
				AND date_format(PrepaymentDate, '%Y%m%d') < date_format(now(), '%Y%m%d')
				AND Status in ('变更审批中','已支付','还款中')
                AND debtor = '".$debtor."'
            ) t order by t.diffDays desc limit 1";
        $backData['maxOverRowDifDaysSql'] = $sql;
        $maxOverRow = $this->_db->getArray($sql);
//        $maxOverRowAmount = ($maxOverRow)? $maxOverRow[0]['amount'] : '';
//        $backData['maxOverRowAmount'] = ($maxOverRowAmount == '')? 0 : $maxOverRowAmount;
        $maxOverRowDifDays = ($maxOverRow)? $maxOverRow[0]['diffDays'] : '';
        $backData['maxOverRowDifDays'] = ($maxOverRowDifDays == '')? 0 : $maxOverRowDifDays;

        foreach ($backData as $k => $v){
            if($k == 'preLoanBalance' || $k == 'yqjkam' || $k == 'cqjkam' || $k == 'handCost'){
                $backData[$k] = number_format($v,2,".","");
            }
        }
        return $backData;
    }

    /**
     * 获取借款报表数据
     */
    function getReportRow($param,$tip=0){
        $type = isset($param['searchType'])?$param['searchType']:"Debtor";
        if($type == "Debtor"){
            $typeAs = "userName";
        }else if($type == "deptName"){
            $typeAs = "debtorDeptName";
            $type = "deptCode";
            if($param['searchVal'] != ''){
                //根据部门名称获取id及子部门id串
                $deptId = util_jsonUtil::iconvUTF2GB($param['searchVal']);
                $chkSql = "select * from department where pdeptid = {$deptId} and DEPT_ID = {$deptId};";
                $isMain = $this->_db->getArray($chkSql);
                if(count($isMain[0]) > 0){
                    $allChilSql = "select DEPT_ID as deptId from department where pdeptid = {$deptId};";
                    $allChil = $this->_db->getArray($allChilSql);
                    $deptIdStr = "";
                    foreach ($allChil as $k => $child){
                        $relativeIds = $this->lonaRept_deptIdStr($child['deptId']);
                        $deptIdStr .= ($k == 0)? $relativeIds : ",".$relativeIds;
                    }
                }else{
                    $deptIdStr = $this->lonaRept_deptIdStr($deptId);
                }
            }else{// 所有一级部门
                $deptDao = new model_deptuser_dept_dept();
                $deptRow = $deptDao->getCompanyList_d();
                if(count($deptRow) > 0){
                    $comCode = $deptName = '';
                    foreach ($deptRow as $k => $dept){
                        if($dept['comCode'] != ''){
                            $comCode .= ($k == 0)? "'{$dept['comCode']}'" : ",'{$dept['comCode']}'";
                            $deptName .= ($k == 0)? "'{$dept['DEPT_NAME']}'" : ",'{$dept['DEPT_NAME']}'";
                        }
                    }
                }
                $sql = "select GROUP_CONCAT(DEPT_ID) AS ids from department where comCode IN ({$comCode}) AND DEPT_NAME IN ({$deptName}) AND Dflag = 0 AND DelFlag = '0';";
                $deptArr = $this->_db->getArray($sql);
                if(isset($deptArr[0]) && isset($deptArr[0]['ids'])){
                    $deptIdStr = $deptArr[0]['ids'];
                }else{
                    $deptIdStr = "";
                }
            }

        }else{
            $typeAs = ($type == "divisionName")? "if(p.divisionName is null,'',p.divisionName) " : $type;
        }

        if(isset($param['searchVal']) && !empty($param['searchVal'])){
            unset($param['company']);
        }
        //拼接查询条件
        $begin = isset($param['payBegin'])?$param['payBegin']:'';
        $end = isset($param['payEnd']) && !empty($param['payEnd'])?$param['payEnd']:date('Y-m-d');
        $end = date('Y-m-d',strtotime('+1 day',strtotime($end)));
        if(empty($param['payEnd'])){
            $condition = " and 1=1 ";
        }else{
            $condition = " and (PayDT between '".$begin."' and '".$end."') ";
        }
        if(isset($param['searchVal']) && !empty($param['searchVal'])){
            if($tip==0){
                $param['searchVal'] = util_jsonUtil::iconvUTF2GB($param['searchVal']);
            }
            if($param['searchType'] == "deptName"){
                $condition .= " and debtorDeptCode in (".$deptIdStr.")";
            }else{
                $condition .= " and ".$typeAs."='".$param['searchVal']."'";
            }
        }
        if(isset($param['company']) && !empty($param['company'])){
            if($tip==0){
                $param['company'] = util_jsonUtil::iconvUTF2GB($param['company']);
            }
            $condition .= " and companyName='".$param['company']."'";
        }

        $this->start = ($param['page'] == 1) ? 0 : ($param['page'] - 1) * $param['pageSize']; //分页开始数
        $this->page  = $param['page'];
        $this->pageSize = $param['pageSize'];

        if($tip==0){
            $limit = " limit " . $this->start . "," . $this->pageSize;
        }else{
            $limit = " ";
        }
        //数据源sql
        $rowSql = "select ".$typeAs." as type,c.ID,c.Debtor,c.Status,c.Amount,c.XmFlag,c.PrepaymentDate,c.debtorDeptName as deptName,c.debtorDeptCode as deptCode,".
             "p.companyName,p.companyId,p.divisionName,p.divisionCode from loan_list c left join oa_hr_personnel p on c.Debtor=p.userAccount".
            " where c.Debtor <> '' AND c.isTemp = 0 AND (Status='已支付' or Status='还款中' or Status='已还款') ".$condition ." order by debtorDeptCode";

        //数据查询sql
        $type = ($type == "divisionName")? 'c.type' : $type;
          $sql = "SELECT type,".
            "sum(IF((`Status`='已支付' or `Status`='还款中' or `Status`='已还款'), Amount, 0)) AS amount,".
            "sum(IF((`Status`='已支付' or `Status`='还款中' or `Status`='已还款'), Amount, 0))-sum(IF(`Status`='已还款', Amount, 0)) as unamount,".
            "sum(IF((`Status`='已支付' or `Status`='还款中') and date_format(PrepaymentDate, '%Y%m%d') < date_format(NOW(), '%Y%m%d'), Amount, 0)) AS beamount,".
            "sum(IF(XmFlag = 0 and (`Status`='已支付' or `Status`='还款中' or `Status`='已还款'), Amount, 0)) AS deptamount,".
            "sum(IF(XmFlag = 1 and (`Status`='已支付' or `Status`='还款中' or `Status`='已还款'), Amount, 0)) AS proamount".
            " FROM".
            " (".$rowSql.")c".
            " GROUP BY ".$type . " order by type " ;

        $rowCount = $this->_db->getArray($sql);
        $this->count = (count($rowCount[0]) > 0)? count($rowCount) : 0;
        $row = $this->_db->getArray($sql.$limit);
        return $row;
    }

    //根据部门名称获取部门id ，重复的以逗号隔开
    public function lonaRept_deptIdStr($deptId)
    {
        if($deptId != ''){
            $deptIds = array();
            $firstSql = "
            SELECT
                c.DEPT_ID AS id,
                c.DEPT_NAME,
                c.Dflag,
                c.pId,
                comCode,
                c.Depart_x,
                (
                    SELECT
            
                    IF (count(d.DEPT_ID) > 1, 1, 0)
                    FROM
                        department d
                    WHERE
                        d.Depart_x LIKE CONCAT(c.Depart_x, '%')
                    AND d.comCode = c.comCode
                ) AS hasChildren
            FROM
                department c
            WHERE
                DEPT_ID = {$deptId}
        ";
            $firstObj = $this->_db->getArray($firstSql);
            if($firstObj && count($firstObj[0] > 0)) {
                $deptIds[] = $firstObj[0]['id'];

                $children = $this->getChildren($firstObj[0]['id']);
                $deptIds = array_merge($deptIds,$children);
            }

            $deptIdsStr = implode(",",$deptIds);
//            echo"<pre>";print_r($deptIdsStr);exit();
            return  $deptIdsStr;
        }else{
            return $deptId;
        }
    }

    // 获取子部门信息
    function getChildren($pid){
        $obj = array();
        $nextSql = "select DEPT_ID AS id from department where pId = {$pid};";
        $nextObj = $this->_db->getArray($nextSql);
        if($nextObj && count($nextObj[0] > 0)){
            foreach ($nextObj as $next){
                $obj[] = $next['id'];
                $nextFlag = $this->getChildren($next['id']);
                if($nextFlag){
                    $obj = array_merge($obj,$nextFlag);
                }
            }
        }
        return $obj;
    }

    function getChildrenOld($Dflag,$comCode,$Depart_x){
        $obj = array();
        $nextSql = "
                SELECT
                    c.DEPT_ID AS id,
                    c.DEPT_NAME,
                    c.Depart_x,
                    c.Dflag,
                    c.comCode,
                    (
                        SELECT
                
                        IF (count(d.DEPT_ID) > 1, 1, 0)
                        FROM
                            department d
                        WHERE
                            d.Depart_x LIKE CONCAT(c.Depart_x, '%')
                        AND d.comCode = c.comCode
                    ) AS hasChildren
                FROM
                    department c
                WHERE
                    1 = 1
                AND (
                    (
                        c.Depart_x LIKE CONCAT('{$Depart_x}', '%')
                    )
                )
                AND ((c.Dflag = '{$Dflag}'))
                AND ((c.DelFlag = '0'))
                AND ((c.comCode = '{$comCode}'))
                ORDER BY
                    id DESC
                ";
        $nextObj = $this->_db->getArray($nextSql);
        if($nextObj && count($nextObj[0] > 0)){
            foreach ($nextObj as $next){
                $obj[] = $next['id'];
                if($next['hasChildren'] == 1){
                    $nextFlag = $this->getChildren($next['Dflag']+1,$next['comCode'],$next['Depart_x']);
                    if($nextFlag){
                        $obj = array_merge($obj,$nextFlag);
                    }
                }
            }
        }
        return $obj;
    }

    //报表金额合计
    function getRowsallMoney_d($rows){
        $amount = 0;
        $unamount= 0;
        $beamount = 0;
        $deptamount = 0;
        $proamount = 0;
        foreach($rows as $key=>$val){
            $amount += $val['amount'];
            $unamount += $val['unamount'];
            $beamount += $val['beamount'];
            $deptamount += $val['deptamount'];
            $proamount += $val['proamount'];
        }
        $allArrp['type'] = "合计";
        $allArrp['amount'] = $amount;
        $allArrp['unamount'] = $unamount;
        $allArrp['beamount'] = $beamount;
        $allArrp['deptamount'] = $deptamount;
        $allArrp['proamount'] = $proamount;
        $rows[] = $allArrp;
        return $rows;
    }

    /**
     * 【项目借款】
     * 获取项目号关联的所有已支付/还款中/已还款状态借款单金额合计
     * @param $projId
     * @return int
     */
    function getInLoanAmountByProjId($projId){
        $amountSum = 0;
        $this->searchArr = array();
        $this->searchArr['projectId'] = $projId;
        $this->searchArr['StatusArr'] = "已支付,还款中,已还款";
        $this->searchArr['isTemp'] = 0;
        $rows = $this->list_d();
        if($rows){
            foreach ($rows as $row){
                $amountSum = bcadd($amountSum,$row['Amount'],2);
            }
        }
        return $amountSum;
    }

    /**
     * 【借款余额】
     * 获取项目号关联的所有已支付/还款中状态借款单金额合计
     * @param $projId
     * @return int
     */
    function getNoPayLoanAmountByProjId($projId){
        $amountSum = 0;
        $this->searchArr['projectId'] = $projId;
        $this->searchArr['StatusArr'] = "已支付,还款中";
		 $this->searchArr['isTemp'] = 0;
        $rows = $this->list_d();
        if($rows){
            foreach ($rows as $row){
                $amountSum = bcadd($amountSum,$row['Amount'],2);
            }
        }
        return $amountSum;
    }

    /**
     * 检查申请金额是否超过项目预算
     * @param $projId
     * @param $amountVal
     * @return int
     */
    function chkBeyondBudget($projId,$amountVal){
        if($projId != ''){
            $esmprojectDao = new model_engineering_project_esmproject();
            $projectData = $esmprojectDao->get_d($projId);
            $budgetField = ($projectData)? $projectData['budgetField'] : 0;// 报销支付金额
            $historyAmountSum = $this->getInLoanAmountByProjId($projId);
            if(($amountVal+$historyAmountSum) > $budgetField){
                return 1;// 是
            }else{
                return 0;// 否
            }
        }else{
            return 0;// 否
        }
    }

    /**
     * 通过部门名称获取部门所属板块
     * @param $Dept
     * @return string
     */
    function getBelongModuleByDept($Dept){
        $datadictDao = new model_system_datadict_datadict ();
        $row = $this->_db->getArray("select module from department where DEPT_NAME = '{$Dept}';");
        if($row) {
            return $datadictDao->getDataNameByCode ( $row[0]['module'] );
        }else{
            return '';
        }
    }

    /**
     * 审批完成后处理方法
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterAudit_d($spid)
    {
        $otherdatas = new model_common_otherdatas();
        $folowInfo = $otherdatas->getStepInfo($spid);
        $objId = $folowInfo ['objId'];
        $object = $this->get_d($objId);
        // 审批时需要更新单据的部分
        $updateInfo = array();
        switch ($object['ExaStatus']) {
            case AUDITED: // 审批完成
                $updateInfo['id'] = $objId;
                $updateInfo['ApplyDT'] = date("Y-m-d H:i:s");
                $updateInfo['ExaDT'] = date("Y-m-d H:i:s");
                $updateInfo['Status'] = '出纳支付';
                break;
            case BACK:
                $updateInfo['id'] = $objId;
                $updateInfo['IsFinRec'] = 0;
                $updateInfo['Status'] = '打回';
                $updateInfo['ExaDT'] = date("Y-m-d H:i:s");
                break;
        }

        if(!empty($updateInfo)){
            $this->updateById($updateInfo);
        }
        return true;
    }

    /**
     * 变更操作
     */
    function change_d($object){
        try{
            $this->start_d();

            // 先把原来变更产生的记录的标示更新为 0 : 非变更中记录
            $this->update(array("isTemp"=>1,"changeTip"=>1,"originalId"=>$object['oldId']), array("changeTip"=>0));

            // 更新原单的变更标示为 1 : 变更中
            $updateInfo['id'] = $object['oldId'];
            $updateInfo['changeTip'] = 1;
            $this->updateById($updateInfo);

            if(isset($object['id']) && isset($object['oldId']) && $object['id'] != ''){
                // 删除借款单主表临时记录信息
                $sql = "delete from loan_list where id = {$object['id']};";
                $this->query($sql);

                // 删除借款单临时变更记录信息
                $sql = "delete from loan_list_change where tempId = {$object['id']} and objId = {$object['oldId']};";
                $this->query($sql);
            }
            unset($object['id']);

            //数据字典处理
            $object = $this->processDatadict($object);

            //实例化变更类
            $changeLogDao = new model_common_changeLog ( 'loanList' );

            //附件处理
            $object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

            //建立变更信息
            $tempObjId = $changeLogDao->addLog ( $object );

            $this->commit_d();
            return $tempObjId;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }

    /**
     * 变更审批完成后处理方法
     * @param $spid
     * @return bool|int
     */
    function dealAfterAuditChange_d($spid)
    {
        $otherdatas = new model_common_otherdatas();
        $folowInfo = $otherdatas->getStepInfo($spid);
        $objId = $folowInfo ['objId'];
        $userId = $folowInfo ['Enter_user'];
        $object = $this->get_d($objId);
        $object['id'] = $object['ID'];
        unset($object['ID']);
        // 审批时需要更新单据的部分
        try{
            $this->start_d();

            $originalId = $object ['originalId'];
            $originalObj = $this->find(array("id"=>$originalId));

            // 把变更产生的记录标示更新为 0, 补充审批完成时间, 把变更时的原单状态更新回去（避免在变更中,原单状态变得与临时记录不一致）
            $today = date("Y-m-d H:i:s");
            $this->update("ID in ({$objId},{$originalId})", array("changeTip"=>0,"ExaDT"=>$today,"Status"=>$originalObj['Status']));

            //变更信息处理
            $changeLogDao = new model_common_changeLog ( 'loanList' );
            $changeLogDao->confirmChange_d ( $object );

            switch ($object['ExaStatus']) {
                case AUDITED: // 审批完成
                    $this->chkLoanChargeAgainst($originalId);
                    break;
                case BACK: // 审批没通过
                    break;
            }

            $this->commit_d();
        }catch(Exception $e){
            $this->rollBack();
        }
        return true;
    }

    /**
     * 检查关联的单据有没未提交变更记录(changeTip 为变更中的标示,变更完成后恢复成0)
     *
     * @param $objId
     * @return array|bool
     */
    function chkChangeTipRcl($objId) {
        $row = $this->findAll(array('originalId'=>$objId,'isTemp'=>1,'changeTip'=>1,'ExaStatus'=>'待提交'));
        return ($row)? $row : array();
    }

    /**
     * 根据传入ID检查借款单冲销状态
     *
     * @param string $objId 如果objId不为空,只对此数据进行检查更新操作,否则对所有审批通过的数据进行检查更新操作
     */
    function chkLoanChargeAgainst($objId = ''){
        $chkArr = array();// 参与检测的数组
        if($objId != ''){
            $chkArr = $this->findAll(array("ID"=>$objId));
            $this->updateLoanChargeAgainst_d($chkArr);
        }else{
            $this->chkLoanChargeAgainstTimer_d();
        }
    }

    /**
     * 借款单超过预计还款日60天提示
     */
    function overSixtydaysWarning(){
        $chkSql = "
        select 
            l.id,l.debtor,l.PayDT,l.PrepaymentDate,u.user_name, FORMAT(IF(r.repam,l.amount - r.repam,l.amount ) ,2) as lam,
            IF(
                l.loanNature = 1,
                IF(l.XmFlag = 1,
                    concat_ws(',',u.user_id , 'chunjun.xu'),
                    concat_ws(',',u.user_id  )
                ),concat_ws(',',u.user_id  )
            ) AS sendId,
            IF(
                l.loanNature = 1,
                IF(l.XmFlag = 1,
                    concat_ws(',',u.user_name , '徐纯筠'),
                    concat_ws(',',u.user_name  )
                ),concat_ws(',',u.user_name  )
            ) AS sendName
        from  loan_list l 
            left join user u on (l.debtor = u.user_id )
            left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id ) 
            LEFT JOIN (
                select ws.pid,fsp.User from flow_step fs left join wf_task ws on ws.task = fs.Wf_task_ID left join flow_step_partent fsp on fs.ID = fsp.StepID where fs.Item = '财务会计' and ws.code = 'loan_list'
            ) t ON l.ID = t.pid
            left join department d on (u.dept_id=d.dept_id)
            left join user u1 on (find_in_set(u1.user_id , t.user) )
        where 
            l.isTemp = 0 
            AND l.status in ('还款中','已支付')  
            AND u.HAS_LEFT=0  
            AND l.no_writeoff=0
            AND l.overSixtyDayMailTimes < 1
            AND l.PayDT <> ''
            AND to_days(  DATE_ADD(l.PrepaymentDate , INTERVAL 60 DAY) ) <  to_days(now())
        order by l.PayDT desc";

        $errorData = $this->_db->getArray($chkSql);
        if($errorData){
            foreach ($errorData as $k => $v){
                $sendIds = rtrim($v['sendId'],",");
                $sendIds = str_replace(",","','",$sendIds);
                $sql = "select GROUP_CONCAT(EMAIL) as address  from user where USER_ID in('{$sendIds}')";
                $adrsArr = $this->_db->getArray($sql);
                $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";
                $mailContent = "您好！您名下的借款单{$v['id']}，还有{$v['lam']}金额（未归还金额）超过还款日期60天还未归还，请尽快归还或者申请变更，谢谢！";

                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','借款单超过预计还款日60天通知','{$mailContent}','{$addresses}','',NOW(),'','','1')";
                $this->_db->query($sql);
                $updateSql = "update loan_list set overSixtyDayMailTimes = overSixtyDayMailTimes+1 where ID = {$v['id']};";
                $this->_db->query($updateSql);
            }
        }
    }

    /**
     * 定时检查并更新借款单的冲销状态
     */
    function chkLoanChargeAgainstTimer_d($val = ''){
        ini_set('memory_limit', '1024M');
        $chkArr = $this->_db->getArray("select ID,PayDT,no_writeoff,hasFilesNum,Status,PrepaymentDate,loanNature from loan_list where isTemp = 0 and Status not in ('部门审批','编辑','打回','已还款') AND ( ExaStatus <> '变更审批中' ) order by ID desc;");

        $this->updateLoanChargeAgainst_d($chkArr);
    }

    /**
     * 检查并更新借款单的冲销状态
     *
     * @param $row
     */
    function updateLoanChargeAgainst_d($row){
        set_time_limit(0);
        $todayTimes = strtotime(date("Y-m-d"));// 今天的的时间戳
        $tenDaysTimes= (10*60*60*24);// 10天的时间戳

        // 对参与检测的数组进行检查并更新冲销状态
        foreach($row as $val){
            $objId = $val['ID'];
            $PrepaymentDateTimes = strtotime($val['PrepaymentDate']);// 预计还款日期的时间戳

            // 单据对应的附件 (如果数据中存储的 hasFilesNum 数据有误,则用这个方法获取上传附件数量。)
            if($val['hasFilesNum'] == 0){
                $file = $this->getFilesByObjId($objId, false, 'Loan_list');
                $hasFilesNum = count($file);
                $updateArr = array("id"=> $objId,"hasFilesNum" => $hasFilesNum);
                $this->updateById($updateArr);
            }else{
                $hasFilesNum = $val['hasFilesNum'];
            }

            // 出纳付款日期
            $PayDT = explode(" ",$val['PayDT']);
            $PayDT = ($val['PayDT'] != '' || $val['PayDT'] != '0000-00-00 00:00:00')? strtotime($PayDT[0]) : 0;
            $saveTimesRange = ($PayDT == 0)? 0 : $PayDT + $tenDaysTimes;// 出纳付款10天内（含第10天）为安全期

            $noWriteOff = '';// 是否允许冲销状态, 1:禁止冲销; 0:允许冲销

            if($PrepaymentDateTimes < $todayTimes && $val['Status'] != '已还款'){// 逾期还未还款的单据,都更新为可冲销
                $noWriteOff = 0;
            }else if ($saveTimesRange > 0 && $saveTimesRange > $todayTimes){// 未逾期,还在出纳付款10天内的都更新为不可冲销
                $noWriteOff = 1;
            }else if($hasFilesNum > 0 && ($saveTimesRange > 0 && $saveTimesRange < $todayTimes)){// 未逾期,还在出纳付款10天外且有附件的都是不可冲销
                $noWriteOff = 1;
            }else if($hasFilesNum <= 0 && ($saveTimesRange > 0 && $saveTimesRange < $todayTimes)){// 未逾期,还在出纳付款10天外且没有附件的都是可冲销
                $noWriteOff = 0;
            }
            // $this->write_log("loanNature: ".$val['loanNature'].";\n hasFilesNum: ".$hasFilesNum.";\n noWriteOff: ".$noWriteOff.";\n Status: ".$val['Status']);
            if($noWriteOff !== '' && $val['loanNature'] == 1){// 只对租房押金类型的更新
                $this->update(array("ID"=>$objId),array("no_writeoff"=>$noWriteOff));
            }
        }
    }

    // ==================================== 收退单处理 (start) ==================================== //
    /**
     * 收单调用方法
     * @param $id
     * @return bool
     * @throws Exception
     */
    function receiveForm($id) {
        try {
            //实例化邮件类
            $mailDao = new model_common_mail();
            //更新
            $updateResult = $this->update(array('id' => $id), array('IsFinRec' => '1', 'FinRecDT' => date('Y-m-d H:i:s')));

            if($updateResult){
                $Subject = "OA-财务收单通知:".$id;
                $ebody = "您好！<br />财务部会计：".$_SESSION["USERNAME"]."已经接收你的借款单号为：".$id."的单据！<br />详情请上oa系统查看，谢谢！";
                $sql = "select Debtor from loan_list where ID={$id};";
                $Debtor = $this->_db->getArray($sql);
                $mailUser = ($Debtor)? $Debtor[0]['Debtor'] : '';
                $mailDao->mailClear($Subject, $mailUser, $ebody);
            }

            return $updateResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 退单调用方法
     * @param $id
     * @return bool
     * @throws Exception
     */
    function backForm($id) {
        try {
            //实例化邮件类
            $mailDao = new model_common_mail();
            //更新
            $updateResult = $this->update(array('id' => $id), array('IsFinRec' => '0', 'FinRecDT' => date('Y-m-d H:i:s')));

            if($updateResult){
                $Subject = "OA-财务收单通知:".$id;
                $ebody = "您好！<br />财务部会计：".$_SESSION["USERNAME"]."已经退还你的借款单号为：".$id."的单据！<br />详情请上oa系统查看，谢谢！";
                $sql = "select Debtor from loan_list where ID={$id};";
                $Debtor = $this->_db->getArray($sql);
                $mailUser = ($Debtor)? $Debtor[0]['Debtor'] : '';
                $mailDao->mailClear($Subject, $mailUser, $ebody);
            }

            return $updateResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // ==================================== 收退单处理 (end) ==================================== //

    // 记录日志
    function write_log( $content ){
        $logTime = date("Y-m-d H:i:s", time());

        // --- 记录调用此函数的类信息 [start] --- //
        $backtrace = debug_backtrace();
        array_shift($backtrace);
        $backClassInfo = '';
        if(!empty($backtrace)){
            $arr['file'] = $backtrace[0]['file'];
            $arr['function'] = $backtrace[0]['function'];
            $arr['class'] = $backtrace[0]['class'];
            $arr['file'] = $backtrace[0]['file'];
            $backClassInfo = json_encode($arr);
        }
        // --- 记录调用此函数的类信息 [end] --- //

        error_log ( "[{$logTime}] LogBy [Loan-Model] PREV_CLASSPATH: [backClassInfo => ".$backClassInfo."]\nContent{ ". $content ."\n}\n\n", 3, './test_log.log' );
    }
}
?>