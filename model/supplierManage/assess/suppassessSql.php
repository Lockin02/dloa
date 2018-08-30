<?php
$sql_arr = array(
	"select_default" => "select c.id,c.assesId,c.suppId,c.suppName,c.suppTotal,c.assessLevel ".
			" from oa_supp_asses_supp c where 1=1 "
	,"select_readAll" => "select c.id,c.assesId,c.suppId,c.suppName,c.suppTotal,c.assessLevel" .
			",s.id as sId,s.objCode as sCode,s.shortName as sSName,s.manageUserId as sMId,s.manageUserName as sMName,s.trade as sTrade,s.products as sPdt ".
			" from oa_supp_asses_supp c left join oa_supp_lib s on c.suppId=s.id where 1=1 "
	,"select_page" => "select c.id,c.assesId,c.suppId,c.suppName,c.suppTotal,c.assessLevel," .
			" l.shortName as lshortName,l.manageUserName as lmanageUserName,l.companyNature as lcompanyNature,l.companySize as lcompanySize ".
			" from oa_supp_asses_supp c inner join oa_supp_lib l on c.suppId=l.id where 1=1 "
);


$condition_arr = array(
	array(
		"name" => "assesId", //方案Id
		"sql" => " and c.assesId=# "
	),
	array(
		"name" => "suppIds", //供应商id
		"sql" => " and c.suppId in(arr) "
	)
);
?>