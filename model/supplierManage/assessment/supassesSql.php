<?php
/**
 * @author Administrator
 * @Date 2012年1月12日 15:55:18
 * @version 1.0
 * @description:供应商评估 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.assesYear,c.suppName ,c.formCode,c.formDate ,c.assessType,c.assessTypeName ,c.assessId ,c.assessName ,c.assessCode ,c.suppLinkName ,c.suppAddress ,c.suppTel ,c.mainProduct ,c.suppSource ,c.isFirst ,c.parentId,c.assesManId ,c.assesManName ,c.totalNum ,c.assesState ,c.suppGrade ,c.purchManId ,c.purchManName ,c.approvalRemark ,c.approvalDate ,c.yearTotal,c.yearSupGrade,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.ExaStatus,c.ExaDT  from oa_supp_suppasses c where 1=1 ",
         "select_assesInfo"=>"select c.id ,c.suppId ,c.suppName ,c.assesYear,c.formCode,c.formDate ,c.assessType,c.assessTypeName ,c.assessId ,c.assessName ,c.assessCode ,c.suppLinkName ,c.suppAddress ,c.suppTel ,c.mainProduct ,c.suppSource ,c.isFirst ,c.parentId,c.assesManId ,c.assesManName ,c.totalNum ,c.assesState ,c.suppGrade ,c.ExaStatus,c.ExaDT  from oa_supp_suppasses c where 1=1 ",
        "select_assesList"=>"select c.id ,c.suppId ,c.assesYear,c.suppName ,c.formCode,c.formDate ,c.assessType,c.assessTypeName ,
                                    c.assessId ,c.assessName ,c.assessCode ,c.suppLinkName ,c.suppAddress ,c.suppTel ,c.mainProduct ,c.suppSource ,
                                    c.isFirst ,c.parentId,c.assesManId ,c.assesManName ,c.totalNum ,c.assesState ,c.suppGrade ,c.purchManId ,c.purchManName,
                                    c.approvalRemark ,c.approvalDate ,c.yearTotal,c.yearSupGrade,c.ExaStatus,c.ExaDT,c.isDone,b.notDoneNames,
	                                IF(FIND_IN_SET('".$_SESSION['USER_ID']."',b.notDoneIds)>0,0,1) as processed
                                    from (
                                        select s.id ,s.suppId ,s.assesYear,s.suppName ,s.formCode,s.formDate ,s.assessType,s.assessTypeName ,
                                            s.assessId ,s.assessName ,s.assessCode ,s.suppLinkName ,s.suppAddress ,s.suppTel ,s.mainProduct ,s.suppSource ,
                                            s.isFirst ,s.parentId,s.assesManId ,s.assesManName ,s.totalNum ,s.assesState ,s.suppGrade ,s.purchManId ,s.purchManName ,
                                            s.approvalRemark ,s.approvalDate ,s.yearTotal,s.yearSupGrade,s.ExaStatus,s.ExaDT,1 as  isDone  from oa_supp_suppasses s
                                        left join oa_supp_suppasses_detail d on (d.parentId=s.id and find_in_set('".$_SESSION['USER_ID']."',d.assesManId) and d.affstate<>'1') group by s.id

                                    )c
                                    left join (
                                        SELECT parentId,GROUP_CONCAT(t.assesMan) as notDoneNames,GROUP_CONCAT(t.assesManId) as notDoneIds from (select parentId,assesMan,assesManId from oa_supp_suppasses_detail where affstate = 0 group by parentId,assesManId)t group by t.parentId
                                    )b on c.id = b.parentId
                                    where 1=1 "
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
   		"name" => "formCode",
   		"sql" => " and c.formCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "assessType",
   		"sql" => " and c.assessType=# "
   	  ),
   array(
   		"name" => "assessTypeArr",
   		"sql" => " and c.assessType in(arr) "
   	  ),
   array(
   		"name" => "assessId",
   		"sql" => " and c.assessId=# "
   	  ),
   array(
   		"name" => "assessName",
   		"sql" => " and c.assessName=# "
   	  ),
   array(
   		"name" => "assessCode",
   		"sql" => " and c.assessCode=# "
   	  ),
   array(
   		"name" => "suppLinkName",
   		"sql" => " and c.suppLinkName=# "
   	  ),
   array(
   		"name" => "suppAddress",
   		"sql" => " and c.suppAddress=# "
   	  ),
   array(
   		"name" => "suppTel",
   		"sql" => " and c.suppTel=# "
   	  ),
   array(
   		"name" => "mainProduct",
   		"sql" => " and c.mainProduct=# "
   	  ),
   array(
   		"name" => "suppSource",
   		"sql" => " and c.suppSource=# "
   	  ),
   array(
   		"name" => "isFirst",
   		"sql" => " and c.isFirst=# "
   	  ),
   array(
   		"name" => "assesManId",
   		"sql" => " and c.assesManId=# "
   	  ),
   array(
   		"name" => "assesManName",
   		"sql" => " and c.assesManName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "totalNum",
   		"sql" => " and c.totalNum=# "
   	  ),
   array(
   		"name" => "assesState",
   		"sql" => " and c.assesState=# "
   	  ),
   array(
   		"name" => "suppGrade",
   		"sql" => " and c.suppGrade=# "
   	  ),
   array(
   		"name" => "purchManId",
   		"sql" => " and c.purchManId=# "
   	  ),
   array(
   		"name" => "purchManName",
   		"sql" => " and c.purchManName=# "
   	  ),
   array(
   		"name" => "approvalRemark",
   		"sql" => " and c.approvalRemark=# "
   	  ),
   array(
   		"name" => "approvalDate",
   		"sql" => " and c.approvalDate=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
    array(
        "name" => "ExaStatusNot",
        "sql" => " and c.ExaStatus!=# "
    ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "myjoinId",
   		"sql" => " and c.id in (select parentId from oa_supp_suppasses_menber where assesManId=#) "
   	  ),
	array (
		"name" => "year",
		"sql" => "and YEAR(c.formDate) = #"
	),
	array (
		"name" => "assesYear",
		"sql" => "and assesYear= #"
	),
	array (
		"name" => "beginDate",
		"sql" => "and date_format(formDate,'%Y-%m') >= #"
	),
	array (
		"name" => "endDate",
		"sql" => "and date_format(formDate,'%Y-%m') <= #"
	),
    array (
        "name" => "ids",
        "sql" => "and c.id in(arr)"
    ),
    array(
        "name" => "processed",
        "sql" => " and (IF(FIND_IN_SET('".$_SESSION['USER_ID']."',b.notDoneIds)>0,0,1) = #)"
    )
)
?>