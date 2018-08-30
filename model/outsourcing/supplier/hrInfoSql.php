<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:49:55
 * @version 1.0
 * @description:供应商人力资源信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.suppName ,c.suppCode ,c.skillArea ,c.skillAreaCode ,c.primaryNum ,c.middleNum ,c.expertNum ,c.totalNum ,c.suppTotalNum ,c.proportion ,c.remark  from oa_outsourcesupp_hrinfo c where 1=1 ",
         "select_sum"=>"select sum(c.primaryNum) as primaryNum,sum(c.middleNum) as middleNum,sum(c.expertNum) as expertNum,sum(c.totalNum) as totalNum,sum(c.proportion) as proportion from oa_outsourcesupp_hrinfo c where 1=1 "
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
   		"name" => "skillArea",
   		"sql" => " and c.skillArea=# "
   	  ),
   array(
   		"name" => "skillAreaCode",
   		"sql" => " and c.skillAreaCode=# "
   	  ),
   array(
   		"name" => "primaryNum",
   		"sql" => " and c.primaryNum=# "
   	  ),
   array(
   		"name" => "middleNum",
   		"sql" => " and c.middleNum=# "
   	  ),
   array(
   		"name" => "expertNum",
   		"sql" => " and c.expertNum=# "
   	  ),
   array(
   		"name" => "totalNum",
   		"sql" => " and c.totalNum=# "
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