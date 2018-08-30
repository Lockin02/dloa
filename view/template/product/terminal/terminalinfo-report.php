<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
define('CLIENT_MULTI_RESULTS', 131072);

$terminalName=trim($_GET['terminalName']);  //终端名称
$situation=trim($_GET['situation']);  //库存情况
$proType=trim($_GET['proType']);   //生产类型
$deviceType=trim($_GET['deviceType']) ; //设备类型
$supportNetwork=trim($_GET['supportNetwork']);//支持网咯
$versionStatus=trim($_GET['versionStatus']) ; //版本状态
if($terminalName)
	$condition.=" and c.terminalName like '%{$terminalName}%' ";
if($situation)
	if($situation=='1')
		$condition.=' and n.exeNum !=0 ';
	if($situation=='2')
		$condition.=' and n.exeNum = 0 || n.exeNum is null ';
if($proType){
	//$proType=str_replace(',',"','",$proType);
	$proTypeArr=explode(',',$proType);
	$append='';
	if(is_array($proTypeArr))
	foreach ($proTypeArr as $val)
		$append.=" or FIND_IN_SET($val,c.productId)  ";
	$condition.= ' and ('.ltrim($append,' or').') ';
	
}

if($deviceType){	
	$deviceType=str_replace(',',"','",$deviceType);
	$deviceType=str_replace('---空---',"",$deviceType);
	$condition.="and c.deviceType in ('$deviceType') ";
}

if($supportNetwork){
	$supportNetwork=str_replace(',',"','",$supportNetwork);
	$supportNetwork=str_replace('---空---',"",$supportNetwork);
	$condition.=" and c.supportNetwork in ( '$supportNetwork' )";
}
if($versionStatus){	
	$versionStatus=str_replace(',',"','",$versionStatus);
	$versionStatus=str_replace('---空---',"",$versionStatus);
	$condition.=" and q.dataName in ( '$versionStatus' )";
}
	
/*echo $condition;
exit;*/
/***************存储过程**********************/
$ProSQL = <<<QuerySQL
CREATE PROCEDURE my_terminal_report() 
BEGIN 
DECLARE done INT DEFAULT 0;
DECLARE str VARCHAR(100) DEFAULT ''; 
DECLARE terminalId INT DEFAULT 0; 
DECLARE cur CURSOR FOR SELECT id,materialsId FROM oa_terminal_terminalinfo ; 
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

/********临时表存放终端id及对应的物料id********/
DROP TABLE IF EXISTS splittable; 
    CREATE TEMPORARY TABLE splittable( 
        id INT AUTO_INCREMENT, 
        terminalinfoId_ int, 
				productId_ int, 
        PRIMARY KEY (`id`) 
    ) ;
    
OPEN cur; 
REPEAT 
SET str='';
FETCH cur INTO terminalId,str; 
BEGIN 
IF (str!='') THEN 

/********分隔终端物料id成多行********/
SET @sql_=CONCAT("select '",REPLACE(str,',',"' UNION ALL SELECT '"),"'");   

/********终端物料id写入临时表********/
SET @sql_=CONCAT("INSERT INTO splittable (terminalinfoId_,productId_) select * from ( select ",terminalId," ) as c ,( ",@sql_," ) p");  

PREPARE stmt from @sql_; 
EXECUTE stmt; 
END IF; 
END; 
UNTIL done END REPEAT; 
CLOSE cur; 

/*************查询开始************/
SELECT c.id,c.typeName,c.productName, c.terminalName,c.deviceType,c.os,c.supportNetwork,q.dataName as versionStatus,c.formalVersion,c.newVersion,c.remark,c.materialsId,c.productId_, 
FORMAT(p.quotedPrice,1) as quotedPrice ,p.equId,p.equCode, d.proTypeId,
IFNULL(m.outStockNum,0) AS outStockNum,IFNULL(n.exeNum,0) as actNum ,IFNULL(n.lockedNum,0) AS lockedNum  ,
IFNULL(d.arrivalPeriod,0) AS submitDay 
FROM (SELECT c.id,c.typeName,c.productName,c.productId, c.terminalName,c.deviceType,c.os,c.supportNetwork,c.versionStatus,c.formalVersion,c.newVersion,c.remark,c.materialsId,p.productId_ 
 FROM oa_terminal_terminalinfo c LEFT JOIN splittable p ON c.id=p.terminalinfoId_) c  left JOIN oa_system_datadict q ON c.versionStatus=q.dataCode
left JOIN oa_equ_budget_baseinfo p  ON c.productId_=p.equId 
LEFT JOIN 
(select c.productId, sum(c.exeNum) as outStockNum FROM oa_stock_inventory_info c,oa_stock_syteminfo p WHERE c.stockCode=p.outStockCode GROUP BY c.productId) m 
ON c.productId_=m.productId 
LEFT JOIN 
(select c.productId,ifnull(sum(c.exeNum),0) as exeNum,ifnull(sum(c.lockedNum),0) as lockedNum from oa_stock_inventory_info c,oa_stock_syteminfo o 
where stockCode<>o.outStockCode and stockCode <> o.borrowStockCode GROUP BY c.productId) n 
ON c.productId_=n.productId 
LEFT JOIN oa_stock_product_info d on d.id=c.productId_  
where 1=1 $condition 
ORDER BY c.id desc,p.updateTime  ; 

/*************查询结束************/

END; 

QuerySQL;
/***************存储过程结束**********************/
//echo $ProSQL;exit;
//调用存储过程语句
$QuerySQL = <<<SELSQL
CALL my_terminal_report(); 
SELSQL;
/*echo $ProSQL;
exit;*/
if(dbuser){
	mysql_connect(localhost, dbuser, dbpw,1,CLIENT_MULTI_RESULTS) or die("couldn't connect: ".mysql_error());
     mysql_select_db(dbname);
}else{
	mysql_connect("172.16.5.6", "develop", "123456",1,CLIENT_MULTI_RESULTS) or die("couldn't connect: ".mysql_error());
	    mysql_select_db("newcontract");
	}
 mysql_query("set names 'gbk'");
//写入存储过程
mysql_query('DROP PROCEDURE IF EXISTS `my_terminal_report`; ');
mysql_query($ProSQL);	

GenAttrXmlData ( $QuerySQL, false );
?>
