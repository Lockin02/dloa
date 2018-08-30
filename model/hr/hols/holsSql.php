<?php
/**
 * @author Administrator
 * @Date 2012年8月25日 星期六 10:54:13
 * @version 1.0
 * @description:考勤 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.ID as id,c.UserId ,c.Type ,c.BeginDT ,c.EndDT ,c.BeginHalf ,c.EndHalf ,c.DTA ,c.Reason ,c.ApplyDT ,c.ExaStatus ,c.ExaDT ,c.Status ,c.LeaveUserId ,c.Remark ,c.RemarkChange ,c.ProExa ,c.ProExaDT ,c.ProExaSta ,c.ProExaRes,b.userNo,b.userName,b.companyName,b.deptName,b.deptNameT,b.deptNameS,b.deptNameF  from hols c left join oa_hr_personnel b on c.UserId = b.userAccount where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "ID",
   		"sql" => " and c.ID=# "
   	  ),
   array(
   		"name" => "UserId",
   		"sql" => " and c.UserId=# "
   	  ),
   array(
   		"name" => "Type",
   		"sql" => " and c.Type=# "
   	  ),
   array(
   		"name" => "BeginDT",
   		"sql" => " and c.BeginDT=# "
   	  ),
   array(
   		"name" => "EndDT",
   		"sql" => " and c.EndDT=# "
   	  ),
   array(
   		"name" => "BeginHalf",
   		"sql" => " and c.BeginHalf=# "
   	  ),
   array(
   		"name" => "EndHalf",
   		"sql" => " and c.EndHalf=# "
   	  ),
   array(
   		"name" => "DTA",
   		"sql" => " and c.DTA=# "
   	  ),
   array(
   		"name" => "Reason",
   		"sql" => " and c.Reason=# "
   	  ),
   array(
   		"name" => "ApplyDT",
   		"sql" => " and c.ApplyDT=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "Status",
   		"sql" => " and c.Status=# "
   	  ),
   array(
   		"name" => "LeaveUserId",
   		"sql" => " and c.LeaveUserId=# "
   	  ),
   array(
   		"name" => "userNoSearch",
   		"sql" => " and b.userNo=# "
   	  ),
   array(
   		"name" => "userNo",
   		"sql" => " and b.userNo like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and b.userName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "companyName",
   		"sql" => " and b.companyName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and b.deptName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "deptNameS",
   		"sql" => " and b.deptNameS like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "deptNameT",
   		"sql" => " and b.deptNameT like CONCAT('%',#,'%') "
   	  ),
    array(
        "name" => "deptNameF",
        "sql" => " and b.deptNameF like CONCAT('%',#,'%') "
    ),
   array(
   		"name" => "ProExaDT",
   		"sql" => " and c.ProExaDT=# "
   	  ),
   array(
   		"name" => "ProExaSta",
   		"sql" => " and c.ProExaSta=# "
   	  ),
   array(
   		"name" => "ProExaRes",
   		"sql" => " and c.ProExaRes=# "
   	  )
)
?>