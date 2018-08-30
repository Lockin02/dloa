<?php
@session_start( );
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
set_time_limit(0);
$condition = " and l.isTemp = 0";
$loanIds = isset($_GET['ids'])? $_GET['ids'] : '';
$loanIds = base64_decode($loanIds);
$payBegin  = isset($_GET['payBegin'])? $_GET['payBegin'] : '';
$payEnd  = isset($_GET['payEnd'])? $_GET['payEnd'] : '';
$company  = isset($_GET['company'])? $_GET['company'] : '';
$compantId  = isset($_GET['compantId'])? $_GET['compantId'] : '';
$typeCode  = isset($_GET['typeCode'])? $_GET['typeCode'] : '';
$searchType  = isset($_GET['searchType'])? $_GET['searchType'] : '';

switch ($typeCode){
    case 'debtorDeptName':
        $extOrderStr = ",c.debtorName,c.ID";
        break;
    case 'debtorName':
        $extOrderStr = ',c.ID';
        break;
    case 'divisionName':
        $extOrderStr = ',c.debtorName,c.ID';
        break;
    default:
        $extOrderStr = '';
}

// 添加主要条件
if($loanIds == ''){
    // 如果非直接指定借款单ID查询的需要添加对应报表列表的付款日期条件
    if(!empty($payEnd)){
        $condition = " and (l.PayDT between '".$payBegin."' and '".$payEnd."') ";
    }
    // 如果非直接指定借款单ID查询的需要添加对应报表列表的所属公司条件
    if(!empty($company)){
        $condition .= " and l.belongcom='".$company."'";
    }else if(!empty($compantId)){
        $condition .= " and l.belongcomcode ='".$compantId."'";
    }
    if($searchType != ''){
        switch ($searchType){
            case 'emptyDebtorName';
                $condition .= " AND (p.userName is null or p.userName = '') AND (l.debtorName is null or l.debtorName = '')";
                break;
            case 'emptyDebtorDeptName';
                $condition .= " AND (l.debtorDeptName is null or l.debtorDeptName = '')";
                break;
            case 'emptyDivisionName';
                $condition .= " AND (p.divisionName is null or p.divisionName = '')";
                break;
            case 'divisionNameLong';
                $loanIds = base64_decode($_SESSION['searchIds']);
                $condition .= " and l.ID IN ($loanIds)";
                break;
            default:
                $condition = " and l.isTemp = 5";// 无数据的条件
        }
    }else{
        $condition = " and l.isTemp = 5";// 无数据的条件
    }
}else{
    $condition .= " and l.ID IN ($loanIds)";
}

$QuerySQL = <<<QuerySQL
SELECT * FROM(
SELECT
	l.debtorDeptName,
	if(l.debtorName is null or l.debtorName = '',p.userName,l.debtorName) as debtorName,
	IF(p.userNo <> '' and p.userNo is not null,p.userNo,'') AS debtorUserNo,
	l.ID,
	IF((l.`Status`='已支付' or l.`Status`='还款中' or l.`Status`='已还款'), l.Amount, 0) AS amount,
	IF((l.`Status`='已支付' or l.`Status`='还款中'), l.Amount, 0) AS unamount,
	IF((l.`Status`='已支付' or l.`Status`='还款中') and date_format(l.PrepaymentDate, '%Y%m%d') < date_format(NOW(), '%Y%m%d'), l.Amount, 0) AS beamount,
	IF(l.XmFlag = 0 and (l.`Status`='已支付' or l.`Status`='还款中' or l.`Status`='已还款'), l.Amount, 0) AS deptamount,
	IF(l.XmFlag = 1 and (l.`Status`='已支付' or l.`Status`='还款中' or l.`Status`='已还款'), l.Amount, 0)  AS proamount,
	l.projectCode,
	l.Reason AS reason,
	IF(l.loanNature = 1, '租房押金', IF(l.loanNature = 2,'短期借款','')) AS loanNature,
	IF(l.XmFlag = 1, '项目借款', IF(l.XmFlag = 0,'部门借款','')) AS loanType
FROM
	loan_list l
LEFT JOIN oa_hr_personnel p on l.Debtor=p.userAccount  
WHERE 1=1 $condition
ORDER BY l.debtorName DESC)c
ORDER BY c.debtorDeptName DESC $extOrderStr

QuerySQL;

//echo $QuerySQL;exit();
GenAttrXmlData($QuerySQL, false);
?>
