<?php
/**
 * ÆÀ¹À·½°¸sql
 * @author fengxw
 */
$sql_arr = array (
	"select_scheme" => "select c.id,c.schemeCode,c.schemeTypeCode,c.schemeTypeName," .
			"c.schemeName,c.schemeTotal,c.schemePass,c.formDate,c.formManId,c.formManName," .
			"c.ExaStatus,c.ExaDT,c.createId,c.createName,c.createTime,c.updateName,c.updateId," .
			"c.updateTime from oa_supp_scheme c where  1=1 "
);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
	array(
   		"name" => "schemeCode",
   		"sql" => " and c.schemeCode like CONCAT('%',#,'%')"
        ),
    array(
   		"name" => "schemeTypeCode",
   		"sql" => " and c.schemeTypeCode like CONCAT('%',#,'%') "
   	  ),
    array(
   		"name" => "schemeType",
   		"sql" => " and c.schemeTypeCode=#"
   	  ),
   array(
   		"name" => "schemeTypeName",
   		"sql" => " and c.schemeTypeName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "schemeNameEq",
   		"sql" => " and c.schemeName=# "
   	  ),
   array(
   		"name" => "schemeCodeEq",
   		"sql" => " and c.schemeCode=# "
   	  ),
     array(
   		"name" => "schemeTotal",
   		"sql" => " and c.schemeTotal =# "
   	  ),
     array(
   		"name" => "schemePass",
   		"sql" => " and c.schemePass =# "
   	  ),
     array(
   		"name" => "formDate",
   		"sql" => " and c.formDate =# "
   	  ),
     array(
   		"name" => "formManId",
   		"sql" => " and c.formManId =# "
   	  ),
     array(
   		"name" => "formManName",
   		"sql" => " and c.formManName =# "
   	  ),
     array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus like CONCAT('%',#,'%')"
   	  ),
     array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT =# "
   	  ),
     array(
   		"name" => "createId",
   		"sql" => " and c.createId =# "
   	  ),
     array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%')"
   	  ),
     array(
   		"name" => "createTime",
   		"sql" => " and c.createTime =# "
   	  ),
     array(
   		"name" => "updateId",
   		"sql" => " and c.updateId =# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime =# "
   	  )
)
?>