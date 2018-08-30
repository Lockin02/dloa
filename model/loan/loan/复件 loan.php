<?php

/**
 * �ͻ�model����
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
     *  ���ý������
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
     *  ���ý������
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
     * ��дadd_d����
     */
    function add_d($object) {
        try {
            $this->start_d();

            $object['Debtor'] = $_SESSION['USER_ID'];
            $object['CreateDT'] = date("Y-m-d H:i:s");
            $object['ApplyDT'] = date("Y-m-d H:i:s");
            $object['belongcom'] = $_SESSION['USER_COM_NAME'];
            $object['belongcomcode'] = $_SESSION['USER_COM'];

            //����������Ϣ
            $newId = parent:: add_d($object);

            //���������ƺ�Id
            $this->updateObjWithFile($newId);

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��д�༭����
     */
    function edit_d($object) {
        try {
            $this->start_d();
            //�޸�������Ϣ
            parent:: edit_d($object, true);

            $id = $object['id'];

            //���������ƺ�Id
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
     * user ת��
     */
    function getUserName_d($userId){
        $userDao = new model_deptuser_user_user();
        $userName = $userDao->getUserName_d($userId);
        return $userName['USER_NAME'];
    }

    /**
     * ��ȡ�軹����
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
     * �����û�ID��ȡ��صĸ�����Ϣ
     * @param $debtor
     * @return array
     */
    function getPersonalLoanInfo($debtor){
        $backData = array();
        //Ƿ���ܶ(���½�����=��֧��״̬+�����еĽ��)
        $sql="select sum(Amount) as amounts from loan_list where Debtor='".$debtor."' and Status in ('������','��֧��') and isTemp = 0";
        $backData['preLoanBalanceSql1'] = $sql;
        $preLoanPay = $this->_db->getArray($sql);
        $preLoanPay = ($preLoanPay)? $preLoanPay[0]['amounts'] : '';
        $preLoanPay = ($preLoanPay == '')? 0 : $preLoanPay;

        $sql="select sum(r.Money) as rm from loan_list l , loan_repayment r  where l.ID=r.loan_ID and  l.Debtor='".$debtor."' and l.Status in('������' ) and l.isTemp = 0";
        $backData['preLoanBalanceSql2'] = $sql;
        $preloanReping = $this->_db->getArray($sql);
        $preloanReping = ($preloanReping)? $preloanReping[0]['rm'] : '';
        $preloanReping = ($preloanReping == '')? 0 : $preloanReping;
        $backData['preLoanBalance'] =$preLoanPay-$preloanReping;

        //���ڽ���ܶ� [Ŀǰ����Ա���³���Ԥ�ƹ黹ʱ�����н�δ�黹���]
        $sql="select
                l.debtor , sum(  IFNULL(  l.amount - IFNULL(r.repam,0)   , l.amount ) ) as lam 
            from
                loan_list l
                left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id ) 
    
            where
                l.status in ('������','��֧��') and l.isTemp = 0
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

        //�ⷿѺ�����ܶ�
        $sql="select
                l.debtor , sum(  IFNULL(  l.amount - IFNULL(r.repam,0)   , l.amount ) ) as lam 
            from
                loan_list l
                left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id ) 
    
            where
                l.status in ('������','��֧��') and l.isTemp = 0 ".
//               and l.no_writeoff = '1'
                "and l.loanNature = 1
                and l.debtor = '".$debtor."'
            group by l.debtor";
        $backData['cqjkamSql'] = $sql;
        $cqjkam = $this->_db->getArray($sql);
        $cqjkam = ($cqjkam)? $cqjkam[0]['lam'] : '';
        $backData['cqjkam'] = ($cqjkam == '')? 0 : $cqjkam;

        // �����еķ���
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
                            t. NAME = '��������'
                        AND p.SmallID = 1
                    ) t ON t.pid = c.id
                    WHERE
                        c. STATUS IN (
                            '���ż��',
                            '��������',
                            '�������',
                            '���ɸ���'
                        )
                    AND (
                        (c.isProject = '1')
                        OR (
                            if(c.STATUS = '��������',(
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

        //�Ͻ���Ʊ�ܶ�
        $sql = "select sum(Amount) AS amount  from cost_summary_list where CostMan = '".$debtor."' and Status not in('�༭','���','���') and IsFinRec = 1 and ( (isNotReced='0' and isProject='1') or isProject='0')";
        $backData['addUpNotCostSql'] = $sql;
        $addUpNotCost = $this->_db->getArray($sql);
        $addUpNotCost = ($addUpNotCost)? $addUpNotCost[0]['amount'] : '';
        $backData['addUpNotCost'] = ($addUpNotCost == '')? 0 : $addUpNotCost;

        // ���﷢Ʊ�ܶ�
        $sql = "select sum(Amount) AS amount from cost_summary_list where CostMan = '".$debtor."' and Status in('�༭','���ż��') and IsFinRec = 0 and ( (isNotReced='1' and isProject='1') or isProject='0')";
        $backData['handCostSql'] = $sql;
        $handCost = $this->_db->getArray($sql);
        $handCost = ($handCost)? $handCost[0]['amount'] : '';
        $backData['handCost'] = ($handCost == '')? 0 : $handCost;

        // ����һ�ʽ����������(ֻ��δ�����,�ѻ���ļ�ʹ�����ڻ���Ҳ����)
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
				AND Status in ('���������','��֧��','������')
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
     * ��ȡ��������
     */
    function getReportRow($param,$tip=0){
        $type = isset($param['searchType'])?$param['searchType']:"Debtor";
        if($type == "Debtor"){
            $typeAs = "userName";
        }else if($type == "deptName"){
            $typeAs = "debtorDeptName";
            $type = "deptCode";
            if($param['searchVal'] != ''){
                //���ݲ������ƻ�ȡid���Ӳ���id��
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
            }else{// ����һ������
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
        //ƴ�Ӳ�ѯ����
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

        $this->start = ($param['page'] == 1) ? 0 : ($param['page'] - 1) * $param['pageSize']; //��ҳ��ʼ��
        $this->page  = $param['page'];
        $this->pageSize = $param['pageSize'];

        if($tip==0){
            $limit = " limit " . $this->start . "," . $this->pageSize;
        }else{
            $limit = " ";
        }
        //����Դsql
        $rowSql = "select ".$typeAs." as type,c.ID,c.Debtor,c.Status,c.Amount,c.XmFlag,c.PrepaymentDate,c.debtorDeptName as deptName,c.debtorDeptCode as deptCode,".
             "p.companyName,p.companyId,p.divisionName,p.divisionCode from loan_list c left join oa_hr_personnel p on c.Debtor=p.userAccount".
            " where c.Debtor <> '' AND c.isTemp = 0 AND (Status='��֧��' or Status='������' or Status='�ѻ���') ".$condition ." order by debtorDeptCode";

        //���ݲ�ѯsql
        $type = ($type == "divisionName")? 'c.type' : $type;
          $sql = "SELECT type,".
            "sum(IF((`Status`='��֧��' or `Status`='������' or `Status`='�ѻ���'), Amount, 0)) AS amount,".
            "sum(IF((`Status`='��֧��' or `Status`='������' or `Status`='�ѻ���'), Amount, 0))-sum(IF(`Status`='�ѻ���', Amount, 0)) as unamount,".
            "sum(IF((`Status`='��֧��' or `Status`='������') and date_format(PrepaymentDate, '%Y%m%d') < date_format(NOW(), '%Y%m%d'), Amount, 0)) AS beamount,".
            "sum(IF(XmFlag = 0 and (`Status`='��֧��' or `Status`='������' or `Status`='�ѻ���'), Amount, 0)) AS deptamount,".
            "sum(IF(XmFlag = 1 and (`Status`='��֧��' or `Status`='������' or `Status`='�ѻ���'), Amount, 0)) AS proamount".
            " FROM".
            " (".$rowSql.")c".
            " GROUP BY ".$type . " order by type " ;

        $rowCount = $this->_db->getArray($sql);
        $this->count = (count($rowCount[0]) > 0)? count($rowCount) : 0;
        $row = $this->_db->getArray($sql.$limit);
        return $row;
    }

    //���ݲ������ƻ�ȡ����id ���ظ����Զ��Ÿ���
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

    // ��ȡ�Ӳ�����Ϣ
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

    //������ϼ�
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
        $allArrp['type'] = "�ϼ�";
        $allArrp['amount'] = $amount;
        $allArrp['unamount'] = $unamount;
        $allArrp['beamount'] = $beamount;
        $allArrp['deptamount'] = $deptamount;
        $allArrp['proamount'] = $proamount;
        $rows[] = $allArrp;
        return $rows;
    }

    /**
     * ����Ŀ��
     * ��ȡ��Ŀ�Ź�����������֧��/������/�ѻ���״̬�����ϼ�
     * @param $projId
     * @return int
     */
    function getInLoanAmountByProjId($projId){
        $amountSum = 0;
        $this->searchArr = array();
        $this->searchArr['projectId'] = $projId;
        $this->searchArr['StatusArr'] = "��֧��,������,�ѻ���";
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
     * �������
     * ��ȡ��Ŀ�Ź�����������֧��/������״̬�����ϼ�
     * @param $projId
     * @return int
     */
    function getNoPayLoanAmountByProjId($projId){
        $amountSum = 0;
        $this->searchArr['projectId'] = $projId;
        $this->searchArr['StatusArr'] = "��֧��,������";
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
     * ����������Ƿ񳬹���ĿԤ��
     * @param $projId
     * @param $amountVal
     * @return int
     */
    function chkBeyondBudget($projId,$amountVal){
        if($projId != ''){
            $esmprojectDao = new model_engineering_project_esmproject();
            $projectData = $esmprojectDao->get_d($projId);
            $budgetField = ($projectData)? $projectData['budgetField'] : 0;// ����֧�����
            $historyAmountSum = $this->getInLoanAmountByProjId($projId);
            if(($amountVal+$historyAmountSum) > $budgetField){
                return 1;// ��
            }else{
                return 0;// ��
            }
        }else{
            return 0;// ��
        }
    }

    /**
     * ͨ���������ƻ�ȡ�����������
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
     * ������ɺ�����
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
        // ����ʱ��Ҫ���µ��ݵĲ���
        $updateInfo = array();
        switch ($object['ExaStatus']) {
            case AUDITED: // �������
                $updateInfo['id'] = $objId;
                $updateInfo['ApplyDT'] = date("Y-m-d H:i:s");
                $updateInfo['ExaDT'] = date("Y-m-d H:i:s");
                $updateInfo['Status'] = '����֧��';
                break;
            case BACK:
                $updateInfo['id'] = $objId;
                $updateInfo['IsFinRec'] = 0;
                $updateInfo['Status'] = '���';
                $updateInfo['ExaDT'] = date("Y-m-d H:i:s");
                break;
        }

        if(!empty($updateInfo)){
            $this->updateById($updateInfo);
        }
        return true;
    }

    /**
     * �������
     */
    function change_d($object){
        try{
            $this->start_d();

            // �Ȱ�ԭ����������ļ�¼�ı�ʾ����Ϊ 0 : �Ǳ���м�¼
            $this->update(array("isTemp"=>1,"changeTip"=>1,"originalId"=>$object['oldId']), array("changeTip"=>0));

            // ����ԭ���ı����ʾΪ 1 : �����
            $updateInfo['id'] = $object['oldId'];
            $updateInfo['changeTip'] = 1;
            $this->updateById($updateInfo);

            if(isset($object['id']) && isset($object['oldId']) && $object['id'] != ''){
                // ɾ����������ʱ��¼��Ϣ
                $sql = "delete from loan_list where id = {$object['id']};";
                $this->query($sql);

                // ɾ������ʱ�����¼��Ϣ
                $sql = "delete from loan_list_change where tempId = {$object['id']} and objId = {$object['oldId']};";
                $this->query($sql);
            }
            unset($object['id']);

            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            //ʵ���������
            $changeLogDao = new model_common_changeLog ( 'loanList' );

            //��������
            $object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

            //���������Ϣ
            $tempObjId = $changeLogDao->addLog ( $object );

            $this->commit_d();
            return $tempObjId;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���������ɺ�����
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
        // ����ʱ��Ҫ���µ��ݵĲ���
        try{
            $this->start_d();

            $originalId = $object ['originalId'];
            $originalObj = $this->find(array("id"=>$originalId));

            // �ѱ�������ļ�¼��ʾ����Ϊ 0, �����������ʱ��, �ѱ��ʱ��ԭ��״̬���»�ȥ�������ڱ����,ԭ��״̬�������ʱ��¼��һ�£�
            $today = date("Y-m-d H:i:s");
            $this->update("ID in ({$objId},{$originalId})", array("changeTip"=>0,"ExaDT"=>$today,"Status"=>$originalObj['Status']));

            //�����Ϣ����
            $changeLogDao = new model_common_changeLog ( 'loanList' );
            $changeLogDao->confirmChange_d ( $object );

            switch ($object['ExaStatus']) {
                case AUDITED: // �������
                    $this->chkLoanChargeAgainst($originalId);
                    break;
                case BACK: // ����ûͨ��
                    break;
            }

            $this->commit_d();
        }catch(Exception $e){
            $this->rollBack();
        }
        return true;
    }

    /**
     * �������ĵ�����ûδ�ύ�����¼(changeTip Ϊ����еı�ʾ,�����ɺ�ָ���0)
     *
     * @param $objId
     * @return array|bool
     */
    function chkChangeTipRcl($objId) {
        $row = $this->findAll(array('originalId'=>$objId,'isTemp'=>1,'changeTip'=>1,'ExaStatus'=>'���ύ'));
        return ($row)? $row : array();
    }

    /**
     * ���ݴ���ID��������״̬
     *
     * @param string $objId ���objId��Ϊ��,ֻ�Դ����ݽ��м����²���,�������������ͨ�������ݽ��м����²���
     */
    function chkLoanChargeAgainst($objId = ''){
        $chkArr = array();// �����������
        if($objId != ''){
            $chkArr = $this->findAll(array("ID"=>$objId));
            $this->updateLoanChargeAgainst_d($chkArr);
        }else{
            $this->chkLoanChargeAgainstTimer_d();
        }
    }

    /**
     * ������Ԥ�ƻ�����60����ʾ
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
                    concat_ws(',',u.user_name , '�촿��'),
                    concat_ws(',',u.user_name  )
                ),concat_ws(',',u.user_name  )
            ) AS sendName
        from  loan_list l 
            left join user u on (l.debtor = u.user_id )
            left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id ) 
            LEFT JOIN (
                select ws.pid,fsp.User from flow_step fs left join wf_task ws on ws.task = fs.Wf_task_ID left join flow_step_partent fsp on fs.ID = fsp.StepID where fs.Item = '������' and ws.code = 'loan_list'
            ) t ON l.ID = t.pid
            left join department d on (u.dept_id=d.dept_id)
            left join user u1 on (find_in_set(u1.user_id , t.user) )
        where 
            l.isTemp = 0 
            AND l.status in ('������','��֧��')  
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
                $mailContent = "���ã������µĽ�{$v['id']}������{$v['lam']}��δ�黹��������������60�컹δ�黹���뾡��黹������������лл��";

                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','������Ԥ�ƻ�����60��֪ͨ','{$mailContent}','{$addresses}','',NOW(),'','','1')";
                $this->_db->query($sql);
                $updateSql = "update loan_list set overSixtyDayMailTimes = overSixtyDayMailTimes+1 where ID = {$v['id']};";
                $this->_db->query($updateSql);
            }
        }
    }

    /**
     * ��ʱ��鲢���½��ĳ���״̬
     */
    function chkLoanChargeAgainstTimer_d($val = ''){
        ini_set('memory_limit', '1024M');
        $chkArr = $this->_db->getArray("select ID,PayDT,no_writeoff,hasFilesNum,Status,PrepaymentDate,loanNature from loan_list where isTemp = 0 and Status not in ('��������','�༭','���','�ѻ���') AND ( ExaStatus <> '���������' ) order by ID desc;");

        $this->updateLoanChargeAgainst_d($chkArr);
    }

    /**
     * ��鲢���½��ĳ���״̬
     *
     * @param $row
     */
    function updateLoanChargeAgainst_d($row){
        set_time_limit(0);
        $todayTimes = strtotime(date("Y-m-d"));// ����ĵ�ʱ���
        $tenDaysTimes= (10*60*60*24);// 10���ʱ���

        // �Բ������������м�鲢���³���״̬
        foreach($row as $val){
            $objId = $val['ID'];
            $PrepaymentDateTimes = strtotime($val['PrepaymentDate']);// Ԥ�ƻ������ڵ�ʱ���

            // ���ݶ�Ӧ�ĸ��� (��������д洢�� hasFilesNum ��������,�������������ȡ�ϴ�����������)
            if($val['hasFilesNum'] == 0){
                $file = $this->getFilesByObjId($objId, false, 'Loan_list');
                $hasFilesNum = count($file);
                $updateArr = array("id"=> $objId,"hasFilesNum" => $hasFilesNum);
                $this->updateById($updateArr);
            }else{
                $hasFilesNum = $val['hasFilesNum'];
            }

            // ���ɸ�������
            $PayDT = explode(" ",$val['PayDT']);
            $PayDT = ($val['PayDT'] != '' || $val['PayDT'] != '0000-00-00 00:00:00')? strtotime($PayDT[0]) : 0;
            $saveTimesRange = ($PayDT == 0)? 0 : $PayDT + $tenDaysTimes;// ���ɸ���10���ڣ�����10�죩Ϊ��ȫ��

            $noWriteOff = '';// �Ƿ��������״̬, 1:��ֹ����; 0:�������

            if($PrepaymentDateTimes < $todayTimes && $val['Status'] != '�ѻ���'){// ���ڻ�δ����ĵ���,������Ϊ�ɳ���
                $noWriteOff = 0;
            }else if ($saveTimesRange > 0 && $saveTimesRange > $todayTimes){// δ����,���ڳ��ɸ���10���ڵĶ�����Ϊ���ɳ���
                $noWriteOff = 1;
            }else if($hasFilesNum > 0 && ($saveTimesRange > 0 && $saveTimesRange < $todayTimes)){// δ����,���ڳ��ɸ���10�������и����Ķ��ǲ��ɳ���
                $noWriteOff = 1;
            }else if($hasFilesNum <= 0 && ($saveTimesRange > 0 && $saveTimesRange < $todayTimes)){// δ����,���ڳ��ɸ���10������û�и����Ķ��ǿɳ���
                $noWriteOff = 0;
            }
            // $this->write_log("loanNature: ".$val['loanNature'].";\n hasFilesNum: ".$hasFilesNum.";\n noWriteOff: ".$noWriteOff.";\n Status: ".$val['Status']);
            if($noWriteOff !== '' && $val['loanNature'] == 1){// ֻ���ⷿѺ�����͵ĸ���
                $this->update(array("ID"=>$objId),array("no_writeoff"=>$noWriteOff));
            }
        }
    }

    // ==================================== ���˵����� (start) ==================================== //
    /**
     * �յ����÷���
     * @param $id
     * @return bool
     * @throws Exception
     */
    function receiveForm($id) {
        try {
            //ʵ�����ʼ���
            $mailDao = new model_common_mail();
            //����
            $updateResult = $this->update(array('id' => $id), array('IsFinRec' => '1', 'FinRecDT' => date('Y-m-d H:i:s')));

            if($updateResult){
                $Subject = "OA-�����յ�֪ͨ:".$id;
                $ebody = "���ã�<br />���񲿻�ƣ�".$_SESSION["USERNAME"]."�Ѿ�������Ľ���Ϊ��".$id."�ĵ��ݣ�<br />��������oaϵͳ�鿴��лл��";
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
     * �˵����÷���
     * @param $id
     * @return bool
     * @throws Exception
     */
    function backForm($id) {
        try {
            //ʵ�����ʼ���
            $mailDao = new model_common_mail();
            //����
            $updateResult = $this->update(array('id' => $id), array('IsFinRec' => '0', 'FinRecDT' => date('Y-m-d H:i:s')));

            if($updateResult){
                $Subject = "OA-�����յ�֪ͨ:".$id;
                $ebody = "���ã�<br />���񲿻�ƣ�".$_SESSION["USERNAME"]."�Ѿ��˻���Ľ���Ϊ��".$id."�ĵ��ݣ�<br />��������oaϵͳ�鿴��лл��";
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

    // ==================================== ���˵����� (end) ==================================== //

    // ��¼��־
    function write_log( $content ){
        $logTime = date("Y-m-d H:i:s", time());

        // --- ��¼���ô˺���������Ϣ [start] --- //
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
        // --- ��¼���ô˺���������Ϣ [end] --- //

        error_log ( "[{$logTime}] LogBy [Loan-Model] PREV_CLASSPATH: [backClassInfo => ".$backClassInfo."]\nContent{ ". $content ."\n}\n\n", 3, './test_log.log' );
    }
}
?>