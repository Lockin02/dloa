<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:51:20
 * @version 1.0
 * @description:供应商资质证书 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.suppName ,c.suppCode ,c.typeName ,c.typeCode ,c.certifyName ,c.certifyLevel ,c.certifyCode ,c.beginDate ,c.endDate ,c.certifyCompany ,c.remark  from oa_outsourcesupp_certify c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "typeName",
   		"sql" => " and c.typeName=# "
   	  ),
   array(
   		"name" => "typeCode",
   		"sql" => " and c.typeCode=# "
   	  ),
   array(
   		"name" => "certifyName",
   		"sql" => " and c.certifyName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "certifyLevel",
   		"sql" => " and c.certifyLevel=# "
   	  ),
   array(
   		"name" => "certifyCode",
   		"sql" => " and c.certifyCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "endDate",
   		"sql" => " and c.endDate=# "
   	  ),
   array(
   		"name" => "certifyCompany",
   		"sql" => " and c.certifyCompany like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>