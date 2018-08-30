<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = '';
if($_GET['ids']){
	$condition .= ' and c.id in(' .$_GET['ids'] . ')' ;
}
//if($_GET['objId']){
//	$condition .= ' and d.objId in(' .$_GET['objId'] . ')' ;
//}
//if($_GET['sourceType']){
//	$condition .= ' and d.objType = "'.$_GET['sourceType'].'"';
//}
$QuerySQL = <<<QuerySQL
select c.id as id,c.`hwapplyNumb`,
		date_format(c.`createTime`,'%Y-%m-%d') as purchaseDate,c.`suppName`,
       p.`productNumb`,p.`productName`,
		p.`pattem`,p.`units`,
        p.`amountAll`,p.`amountIssued`,p.`dateHope`,
       p.`dateIssued`,p.`applyPrice`,p.`moneyAll`,p.`applyDeptName`,
        p.`sourceNumb`,p.`remark`
	from oa_purch_apply_basic c LEFT join oa_purch_apply_equ p on(c.id=p.basicId)
    	where c.isTemp=0 $condition
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
