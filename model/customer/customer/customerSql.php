<?php
$sql_arr = array (
    "select_customer" => "select c.ID as id,c.objectCode,c.Name,c.AreaLeader,c.AreaLeaderId,c.SellMan,c.SellManId,c.TypeOne,
      c.ProvId,c.Prov,c.CountryId,c.Country,c.City,c.CityId,c.CreateDT,c.PostalCode,c.AreaName,c.AreaId,c.Address,c.Tell,c.CreateUser,c.CreateUserId,
      r.areaPrincipal as AreaLeaderNow,c.isUsing

      from customer c
      left join oa_system_region r on c.AreaId = r.id
       where 1=1 ",
	"select_customer1" => "select c.ID as id,c.objectCode,c.Name,c.AreaLeader,c.AreaLeaderId,c.SellMan,c.SellManId,c.TypeOne,
      c.ProvId,c.Prov,c.CountryId,c.Country,c.City,c.CityId,c.CreateDT,c.PostalCode,c.AreaName,c.AreaId,c.Address,c.Tell,c.CreateUser,c.CreateUserId,
      r.id as AreaIdNow,r.areaPrincipalId as AreaLeaderIdNow,r.areaPrincipal as AreaLeaderNow,c.isUsing

      from customer c
      left join oa_system_region r on c.AreaName = r.areaName and r.isStart = 0
       where 1=1 " );

$condition_arr = array (
   array (
		"name" => "id",
		"sql" => "and c.id = #"
	),
    array (
		"name" => "ajaxCusName",
		"sql" => "and c.Name = #"
	),
	array (
		"name" => "Name",
		"sql" => "and c.Name like CONCAT('%',#,'%')"
	),
	array (
		"name" => "nName",
		"sql" => "and c.Name =#"
	),
	array (
		"name" => "TypeOne",
		"sql" => "and c.TypeOne =#"
	),array (
		"name" => "isUsing",
		"sql" => "and c.isUsing =#"
	),
	array (
		"name" => "objectCode",
		"sql" => "and c.objectCode =#"
	),
	array (
		"name" => "objectCodeLike",
		"sql" => "and c.objectCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "customerIds",
		"sql" => "and c.id in(arr)"
	),
	array (
		"name" => "SellManId",
		"sql" => "and c.SellManId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "AreaId",
		"sql" => "and (c.AreaId in(arr)"
	),
	array (
		"name" => "SellManIdO",
		"sql" => "or c.SellManId like CONCAT('%',#,'%') )"
	)

);
?>
