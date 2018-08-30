<?php
/**
 *
 * уш╬и╥╫й╫sql
  * @author fengxw
 *
 */
$sql_arr = array (
	"select_deprMethod" => "select c.id,c.code,c.name,c.describes,c.expression,c.remark,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime from oa_asset_deprMethod c where  1=1 "
);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "code",
   		"sql" => " and c.code like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "describes",
   		"sql" => " and c.describes like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "expression",
   		"sql" => " and c.expression like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
   	  )

)
?>