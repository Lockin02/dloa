<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$beginDT = $_GET['beginDT'];
$endDT = $_GET['endDT'];
$contractType = $_GET['contractType'];
$area = $_GET['area'];
$principal = $_GET['principal'];
$customerName = $_GET['customerName'];
$customerType = $_GET['customerType'];
$complete = $_GET['complete'];
$limit = "统计范围:";
//查询条件
$time = " and createTime between '$beginDT' and '$endDT'";
if($contractType !=''){
	  	$contractTypeArr = explode(",",$contractType);
        foreach($contractTypeArr as $k => $v){
                  $type .= "'$v',";
        }
        $type = rtrim($type,",");
		$contractTypeSql = " and contractTypeName in($type)";
		$limit .= "  合同类型（".$contractType."）";
}else { $contractTypeSql = "";}
if($area != ''){
	    $areaSql = " and areaName = '$area'";
        $limit .= "  合同所属区域（".$area."）";
}else { $areaSql = "";}
if($principal !=''){
	    $principalSql = " and prinvipalName = '$principal'";
	    $limit .= "  合同负责人（.$principal.）";
}else{  $principalSql = "";}
if($customerName != ''){
	    $customerNameSql = " and customerName = '$customerName'";
	    $limit .= "  客户名称（$customerName）";
}else{  $customerNameSql = "";}
if($customerType != ''){
	    $customerTypeSql = " and customerType = '$customerType'";
	    $limit .= "  客户类型（$customerType）";
}else{  $customerTypeSql = "";}

if($complete == "是"){
	$completeSql = " and state = '4'";
	$limit .= "  是否完工（是）";
}else if($complete == "否"){
	$completeSql = " and state <> 4 ";
	$limit .= "  是否完工（否）";
}else{ $completeSql = ""; }

$whereSql = $contractTypeSql.$areaSql.$principalSql.$customerNameSql.$customerTypeSql.$completeSql.$time;

$QuerySQL = <<<QuerySQL
select "$limit" as lim,"$beginDT" as beginDT,"$endDT" as endDT,date_format(c.createTime,'%Y-%m-%d') as createTime,c.contractCode,
  (select dataName from oa_system_datadict where dataCode = c.customerType) as customerType,c.customerName,c.contractNatureName,
  c.contractTypeName,c.winRate,c.contractMoney,c.incomeMoney,c.invoiceMoney,
  (case c.state when '4' then '√'  else '' end) as completeY,(case c.state when '4' then ''  else '√' end) as completeN,
  c.prinvipalName,c.areaPrincipal,c.areaName from oa_contract_contract c where 1=1 $whereSql
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
