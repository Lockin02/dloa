<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:08:18
 * @version 1.0
 * @description:供应商人员信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.suppName ,c.suppCode ,c.userNo ,c.userAccount ,c.userName ,c.age ,c.mobile ,c.email ,c.highEducationName ,c.highEducation ,c.highSchool ,c.professionalName ,c.identityCard ,c.workBeginDate ,c.workYears ,c.skillTypeName ,c.skillTypeCode ,c.levelName ,c.levelCode ,c.tradeList ,c.tradeListCode ,c.certifyList ,c.certifyListCode ,c.remark ,c.isBlack ,c.blackReason ,c.isInwork  from oa_outsourcesupp_personnel c where 1=1 "
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
   		"sql" => " and c.suppName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "userNo",
   		"sql" => " and c.userNo=# "
   	  ),
   array(
   		"name" => "userAccount",
   		"sql" => " and c.userAccount=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "age",
   		"sql" => " and c.age=# "
   	  ),
   array(
   		"name" => "mobile",
   		"sql" => " and c.mobile=# "
   	  ),
   array(
   		"name" => "email",
   		"sql" => " and c.email=# "
   	  ),
   array(
   		"name" => "highEducationName",
   		"sql" => " and c.highEducationName=# "
   	  ),
   array(
   		"name" => "highEducation",
   		"sql" => " and c.highEducation=# "
   	  ),
   array(
   		"name" => "highSchool",
   		"sql" => " and c.highSchool=# "
   	  ),
   array(
   		"name" => "professionalName",
   		"sql" => " and c.professionalName=# "
   	  ),
   array(
   		"name" => "identityCard",
   		"sql" => " and c.identityCard like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "identityCardEq",
   		"sql" => " and c.identityCard=# "
   	  ),
   array(
   		"name" => "workBeginDate",
   		"sql" => " and c.workBeginDate=# "
   	  ),
   array(
   		"name" => "workYears",
   		"sql" => " and c.workYears=# "
   	  ),
   array(
   		"name" => "skillTypeName",
   		"sql" => " and c.skillTypeName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "skillTypeCode",
   		"sql" => " and c.skillTypeCode=# "
   	  ),
   array(
   		"name" => "levelName",
   		"sql" => " and c.levelName=# "
   	  ),
   array(
   		"name" => "levelCode",
   		"sql" => " and c.levelCode=# "
   	  ),
   array(
   		"name" => "tradeList",
   		"sql" => " and c.tradeList like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "tradeListCode",
   		"sql" => " and c.tradeListCode=# "
   	  ),
   array(
   		"name" => "certifyList",
   		"sql" => " and c.certifyList like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "certifyListCode",
   		"sql" => " and c.certifyListCode=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "isBlack",
   		"sql" => " and c.isBlack=# "
   	  ),
   array(
   		"name" => "blackReason",
   		"sql" => " and c.blackReason=# "
   	  ),
   array(
   		"name" => "isInwork",
   		"sql" => " and c.isInwork=# "
   	  )
)
?>