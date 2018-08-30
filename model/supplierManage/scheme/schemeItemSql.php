<?php
/**
 * ÆÀ¹À·½°¸Ã÷Ï¸sql
 * @author fengxw
 */
$sql_arr = array (
	"select_schemeItem" => "select c.id,c.parentId,c.schemeCode,c.schemeName,c.assesId,c.assesProCode,c.assesProName,c.assesProProportion,c.assesStandard,c.assesProportion,c.assesExplain,c.assesDept ,c.assesDeptId ,c.assesMan ,c.assesManId from oa_supp_scheme_detail c where  1=1 "
);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "schemeCode",
   		"sql" => " and c.schemeCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "schemeName",
   		"sql" => " and c.schemeName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "assesId",
   		"sql" => " and c.assesId like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "assesProCode",
   		"sql" => " and c.assesProCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "assesProName",
   		"sql" => " and c.assesProName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "assesProProportion",
   		"sql" => " and c.assesProProportion like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "assesStandard",
   		"sql" => " and c.assesStandard like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "assesProportion",
   		"sql" => " and c.assesProportion like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "assesExplain",
   		"sql" => " and c.assesExplain like CONCAT('%',#,'%')"
   	  )
)
?>