<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:12:08
 * @version 1.0
 * @description:人事管理-基础信息-资格证书 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userNo ,c.userAccount ,c.userName ,c.certificates ,c.level ,c.certifying ,c.certifyingDate ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,p.staffName ,p.highSchool ,p.professionalName ,p.companyType ,p.companyName ,p.deptName ,p.deptNameS ,p.deptNameT ,p.deptNameF ,p.personnelTypeName ,p.wageLevelName ,p.jobLevel
	from oa_hr_personnel_certificate c
	left join oa_hr_personnel p on c.userNo=p.userNo
	where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "userNo",
   		"sql" => " and c.userNo=# "
   	  ),
   array(
   		"name" => "userNoArr",
   		"sql" => " and c.userNo in(arr) "
   	  ),
   array(
   		"name" => "userNoSearch",
   		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "userAccount",
   		"sql" => " and c.userAccount=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName=# "
   	  ),
   array(
   		"name" => "userNameSearch",
   		"sql" => " and c.userName LIKE CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "certificates",
   		"sql" => " and c.certificates=# "
   	  ),
   array(
   		"name" => "certificatesSearch",
   		"sql" => " and c.certificates LIKE CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "level",
   		"sql" => " and c.level LIKE CONCAT('%',#,'%')   "
   	  ),
   array(
   		"name" => "certifying",
   		"sql" => " and c.certifying=# "
   	  ),
   array(
   		"name" => "certifyingSearch",
   		"sql" => " and c.certifying LIKE CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "certifyingDate",
   		"sql" => " and c.certifyingDate=# "
   	  ),
   array(
   		"name" => "certifyingDateSearch",
   		"sql" => " and c.certifyingDate LIKE BINARY CONCAT('%',#,'%')   "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   	  array(
   		"name" => "DateBegin",
   		"sql" => " and c.certifyingDate >= BINARY # "
   	  ),
   	  array(
   		"name" => "DateEnd",
   		"sql" => " and c.certifyingDate <= BINARY # "
   	  )
)
?>