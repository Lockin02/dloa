<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php

$taskNo=$_GET['taskNo'];//任务编号
$deptId=$_GET['deptId'];//盘点部门

$deptIds=explode(",",$deptId);
foreach($deptIds as $key=>$val){
	$deptIds[$key]="'".$val."'";
}
$depts=implode(",",$deptIds);

$QuerySQL = <<<QuerySQL
select  o.taskNo,
        oi.assetCode,
	    oi.assetName,
	    oi.brand,
	    oi.patten,
	    oi.machineCode,
	    oi.belongName,
	    oi.belongDept,
	    oi.belongArea,
	    oi.unit,
        '1' as registNum,
        '1' as checkNum ,
        '0' as overageNum ,
        '0' as shortageNum
              from oa_asset_checkitem oi inner  join oa_asset_check o on(o.id=oi.checkId) where o.taskNo='$taskNo' and o.deptId in ($depts) and
                   oi.assetCode  in
(select    c.assetCode
           from oa_asset_card c where c.isDel='0'  and  c.isTemp='0'  and  c.isSell='0' and c.orgId in ($depts)  ) group by assetCode
           union all

select  o.taskNo,
        oi.assetCode,
	    oi.assetName,
	    oi.brand,
	    oi.patten,
	    oi.machineCode,
	    oi.belongName,
	    oi.belongDept,
	    oi.belongArea,
	    oi.unit,
        '1' as registNum,
        '1' as checkNum,
        '1' as overageNum,
        '0' as shortageNum
              from oa_asset_checkitem oi  inner  join oa_asset_check o on(o.id=oi.checkId) where o.taskNo='$taskNo' and o.deptId in ($depts) and
                   oi.assetCode  not in
(select     c.assetCode
             from oa_asset_card c where c.isDel='0'and c.isTemp='0'  and c.isSell='0' and c.orgId in ($depts))  group by assetCode
union all

  select  o.taskNo,
          c.assetCode ,
          c.assetName ,
          c.assetTypeName,
          c.spec ,
          c.machineCode,
          c.userName ,
          c.orgName ,
          c.origina  ,
          c.unit ,
          '0' as registNum,
          '0' as checkNum,
          '0' as overageNum,
          '1' as shortageNum
              from oa_asset_card c  inner join oa_asset_checkitem oi left join oa_asset_check o  on(o.id=oi.checkId)
               where c.isDel='0'and c.isTemp='0'  and  c.isSell='0' and c.orgId in ($depts) and o.taskNo='$taskNo' and c.assetCode
            not in
 ( select  oi.assetCode  from oa_asset_checkitem oi  inner join oa_asset_check o on o.id=oi.checkId  where o.taskNo='$taskNo' and o.deptId in ($depts))   group by assetCode;
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );

?>