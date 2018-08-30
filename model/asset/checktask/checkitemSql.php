<?php
/**

 * @description sqlеДжцнд╪Ч
 */
$sql_arr = array (
         "select_checkitem"=>"select c.id ,c.checkId ,c.taskId ,c.assetCode ,c.assetName ,c.brand ," .
         		"c.patten,c.machineCode ,c.belongId ,c.belongName,c.belongDeptId,c.belongDept,c.belongAreaId,c.belongArea,c.unit," .
         		"c.registNum,c.checkNum,c.overageNum,c.shortageNum,c.remark   from oa_asset_checkitem c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "checkId",
   		"sql" => " and c.checkId=# "
   	  ),
   array(
   		"name" => "taskId",
   		"sql" => " and c.taskId=# "
   	  ),
   array(
   		"name" => "assetCode",
   		"sql" => " and c.assetCode=# "
   	  ),
   array(
   		"name" => "assetName",
   		"sql" => " and c.assetName=# "
   	  ),
   array(
   		"name" => "brand",
   		"sql" => " and c.brand=# "
   	  ), array(
   		"name" => "patten",
   		"sql" => " and c.patten=# "
   	  ),
   array(
   		"name" => "machineCode",
   		"sql" => " and c.machineCode=# "
   	  ),
   array(
   		"name" => "belongId",
   		"sql" => " and c.belongId=# "
   	  ),
   array(
   		"name" => "belongName",
   		"sql" => " and c.belongName=# "
   	  ),array(
   		"name" => "belongDeptId",
   		"sql" => " and c.belongDeptId=# "
        ),
   array(
   		"name" => "belongDept",
   		"sql" => " and c.belongDept=# "
   	  ),
   array(
   		"name" => "belongAreaId",
   		"sql" => " and c.belongAreaId=# "
   	  ),
   array(
   		"name" => "belongArea",
   		"sql" => " and c.belongArea=# "
   	  ),
   array(
   		"name" => "unit",
   		"sql" => " and c.unit=# "
   	  ),
   array(
   		"name" => "registNum",
   		"sql" => " and c.registNum=# "
   	  ), array(
   		"name" => "checkNum",
   		"sql" => " and c.checkNum=# "
   	  ),
   	    array(
   		"name" => "overageNum",
   		"sql" => " and c.overageNum=# "
   	  ),
   array(
   		"name" => "shortageNum",
   		"sql" => " and c.shortageNum=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>