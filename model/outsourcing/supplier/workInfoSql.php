<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:50:05
 * @version 1.0
 * @description:供应商工作经验信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.suppName ,c.suppCode ,c.experience ,c.personNum ,c.suppTotalNum ,c.proportion ,c.remark  from oa_outsourcesupp_workinfo c where 1=1 ",
          "select_sum"=>"select sum(c.personNum) as personNum,sum(c.proportion) as proportion  from oa_outsourcesupp_workinfo c where 1=1 "
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
   		"name" => "experience",
   		"sql" => " and c.experience=# "
   	  ),
   array(
   		"name" => "personNum",
   		"sql" => " and c.personNum=# "
   	  ),
   array(
   		"name" => "suppTotalNum",
   		"sql" => " and c.suppTotalNum=# "
   	  ),
   array(
   		"name" => "proportion",
   		"sql" => " and c.proportion=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>