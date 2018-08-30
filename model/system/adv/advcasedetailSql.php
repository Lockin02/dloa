<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.caseId,c.logic,c.searchField,c.compare,c.value,c.leftK,c.rightK  from oa_adv_case_detail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "caseId",
   		"sql" => " and c.caseId=# "
   	  )
);
?>