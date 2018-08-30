<?php
/**
 * @author Administrator
 * @Date 2012年1月11日 16:58:43
 * @version 1.0
 * @description:供应商评估明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.parentCode ,c.assesId ,c.assesProCode ,c.assesProName ,c.assesProProportion ,c.assesStandard ,c.assesProportion ,c.assesExplain ,c.assesScore ,c.assesRemark,c.assesDept ,c.assesDeptId ,c.assesMan ,c.assesManId,c.affstate,c.affTime  from oa_supp_suppasses_detail c where 1=1 ",
         "select_schemeItem" => "select c.id,c.parentId,c.schemeCode,c.schemeName,c.assesId,c.assesProCode,c.assesProName,c.assesProProportion,c.assesStandard,c.assesProportion,c.assesExplain,c.assesDept ,c.assesDeptId ,c.assesMan ,c.assesManId from oa_supp_scheme_detail c where  1=1 ",
    "select_sumscore" => "select sum(c.assesScore) as sumScore from oa_supp_suppasses_detail c  where  1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "assesId",
   		"sql" => " and c.assesId=# "
   	  ),
   array(
   		"name" => "assesProCode",
   		"sql" => " and c.assesProCode=# "
   	  ),
   array(
   		"name" => "assesProName",
   		"sql" => " and c.assesProName=# "
   	  ),
   array(
   		"name" => "assesProProportion",
   		"sql" => " and c.assesProProportion=# "
   	  ),
   array(
   		"name" => "assesStandard",
   		"sql" => " and c.assesStandard=# "
   	  ),
   array(
   		"name" => "assesProportion",
   		"sql" => " and c.assesProportion=# "
   	  ),
   array(
   		"name" => "assesExplain",
   		"sql" => " and c.assesExplain=# "
   	  ),
   array(
   		"name" => "assesScore",
   		"sql" => " and c.assesScore=# "
   	  ),
   array(
   		"name" => "assesRemark",
   		"sql" => " and c.assesRemark=# "
   	  ),
    array(
        "name" => "assesManId",
        "sql" => " and (c.assesManId=# or find_in_set(#,c.assesManId))"
    )
)
?>