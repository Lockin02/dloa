<?php
$sql_arr = array (
	"select" => "select id,institutionId,institutionName,bookingBusiness,institutionBusiness,agencyType from oa_flights_ticketagencies c where 1=1"
);

$condition_arr = array (
	array (
		"name" => "institutionId",
		"sql" => "and c.institutionId =#"
	),
	array (
		"name" => "institutionIdSearch",
		"sql" => " and c.institutionId like concat('%',#,'%')"
	),
	array (
		"name" => "institutionName",
		"sql" => "and c.institutionName =#"
	),
	array (
		"name" => "institutionNameSearch",
		"sql" => " and c.institutionName like concat('%',#,'%')"
	),
	array (
		"name" => "findVal",
		"sql" => " and c.agencyType =#"
	),
	array (
		"name" => "findTicketVal",
		"sql" => " and c.agencyType =#"
	),
	array (
		"name" => "agencyType",
		"sql" => " and c.agencyType =#"
	)
);
?>