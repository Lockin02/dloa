<?php

$sql_arr = array (
         "select_default"=>"select c.id ,c.borrowId ,c.borrowEquId ,c.allocatId ,c.allocatNum  from oa_borrow_r_allocat c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "borrowId",
   		"sql" => " and c.borrowId=# "
   	  ),
   array(
   		"name" => "borrowEquId",
   		"sql" => " and c.borrowEquId=# "
   	  ),
   array(
   		"name" => "allocatId",
   		"sql" => " and c.allocatId=# "
   	  )
)
?>