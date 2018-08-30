<?php
$sql_arr = array(

	'myResSupp' =>'select c.id ,c.busiCode ,c.systemCode ,c.objCode ,c.suppName ,c.shortName ,c.registeredFunds ,c.legalRepre ,c.registeredDate ,c.employeesNum ,c.companySize ,c.companyNature ,c.fax ,c.suppSource ,c.requestType ,c.recomComments ,c.failureDate ,c.effectDate ,c.suppLevel ,c.registMark ,c.advantages ,c.taxRegistCode ,c.businRegistCode ,c.products ,c.businessCode ,c.plantArea ,c.address ,c.status ,c.trade ,c.country ,c.provinces ,c.city ,c.suppType ,c.companyType ,c.zipCode ,c.plane ,c.manageDeptId ,c.manageDeptName ,c.manageUserId ,c.manageUserName ,c.vatTaxRate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.mainProduct ,c.advantages,c.delFlag,c.suppGrade,c.suppFailNumb,c.suppCategory,c.suppCategoryName,u.usedName
	from oa_supp_lib c
	LEFT JOIN (SELECT suppId,GROUP_CONCAT(usedName) as usedName from  oa_supp_usedname GROUP BY suppId)u on c.id=u.suppId
	where 1=1 and c.delFlag=0',
	'excel_select' => 'select
			c.id,c.busiCode,c.suppName,c.legalRepre,c.products,c.suppGrade,c.suppCategoryName,c.registeredFunds,c.address,b.bankName,b.accountNum,l.linkman,l.position,l.mobile1
		from
			oa_supp_lib c
				left join
			(
				select b.suppId,b.bankName,b.accountNum from oa_supp_bankinfo b group by b.suppId
			) b
				on c.id = b.suppId
				left join
			(
				select l.parentId as suppId,l.name as linkman,l.mobile1,l.position from oa_supp_cont l group by l.parentId
			) l
				on c.id = l.suppId where 1=1 and c.delFlag=0',
	'select_cont' => 'select
			c.id,c.busiCode,c.suppName,c.legalRepre,c.products,c.suppGrade,c.suppCategoryName,c.registeredFunds,c.address,c.createName,c.createTime,c.status,l.linkman,l.position,l.mobile1,u.usedName
		from
			oa_supp_lib c
				left join
			(
				select l.parentId as suppId,l.name as linkman,l.mobile1,l.position from oa_supp_cont l where l.name!=""    group by l.parentId
			) l
				on c.id = l.suppId
			LEFT JOIN (SELECT suppId,GROUP_CONCAT(usedName) as usedName from  oa_supp_usedname GROUP BY suppId)u on c.id=u.suppId
				where 1=1 and c.delFlag=0'
);
$condition_arr = array(
	array(
		"name" => "manageUserId",
		"sql" => " and c.manageUserId=# "
	),array(
		"name" => "id",
		"sql" => " and c.id =# "
	),array(
		"name" => "status",
		"sql" => " and c.status like CONCAT('%',#,'%') "
	),array(
		"name" => "products", //ËÑË÷×Ö¶Î
		"sql" => " and c.products like CONCAT('%',#,'%') "
	),array(
		"name" => "mainProduct", //ËÑË÷×Ö¶Î
		"sql" => " and c.mainProduct like CONCAT('%',#,'%') "
	),array(
		"name" => "busiCode", //ËÑË÷×Ö¶Î
		"sql" => " and c.busiCode like CONCAT('%',#,'%') "
	),array(
		"name" => "suppName", //ËÑË÷×Ö¶Î
		"sql" => " and c.suppName like CONCAT('%',#,'%') "
	),array(
        "name" => "usedName", //ËÑË÷×Ö¶Î
        "sql" => " and u.usedName like CONCAT('%',#,'%') "
    ),array(
		"name" => "createId", //ËÑË÷×Ö¶Î
		"sql" => " and c.createId like CONCAT('%',#,'%') "
	),array(
		"name" => "createNameSeach", //ËÑË÷×Ö¶Î
		"sql" => " and c.createId like CONCAT('%',#,'%') "
	),
	array(
		"name" => "suppNameSeach", //ËÑË÷×Ö¶Î
		"sql" => " and c.suppName like CONCAT('%',#,'%') "
	),array(
		"name" => "updateTime",
		"sql" => " and c.updateTime =# "
	),
	array(
		"name" => "ajaxSuppName",//ajaxÎ¨Ò»ÐÔÑéÖ¤
		"sql"=>" and c.suppName= # "
	),
	array(
		"name" => "suppGrade",//ajaxÎ¨Ò»ÐÔÑéÖ¤
		"sql"=>" and c.suppGrade in(arr) "
	),
	array(
		"name" => "noSuppGrade",//ajaxÎ¨Ò»ÐÔÑéÖ¤
		"sql"=>" and c.suppGrade not in(arr) "
	)
);
?>
