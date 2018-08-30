<?php
/**
 * @author Administrator
 * @Date 2011年5月9日 15:19:33
 * @version 1.0
 * @description:借试用 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.remarkapp,c.subTip,c.customerType,c.isproShipcondition,c.id ,c.chanceId ,c.chanceCode ,c.chanceName ,c.Code ,c.Type ,c.customerName,c.customerId" .
         		",c.limits ,c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus,l.ExaStatus as lExaStatus," .
         		"c.customTypeId,c.customTypeName,c.warnDate,c.ExaDT,c.createName,c.createId,c.createTime,c.DeliveryStatus,c.reason,c.createSection,c.createSectionId,c.shipaddress," .
         		"c.deliveryDate,c.objCode,c.isSubAppChange,c.equEstimate,c.equEstimateTax,c.status,c.tostorage,c.timeType,c.renew,c.isship,c.initTip,c.rdprojectName,c.rdprojectId,c.rdprojectCode,c.backStatus,c.module,c.moduleName,c.newProLineStr,c.needSalesConfirm,c.salesConfirmId,c.isDelayApply,
         		c.checkFile, if(
         		    (sum.allNum is null or sum.exeNum = 0 or sum.exeNum = sum.backNum),1,
         		    if((sum.exeNum > 0 and sum.backNum <> 0),2,
         		    if((sum.exeNum > 0 and sum.backNum = 0),0,''))) as backStatusCode" .
         		" from oa_borrow_borrow c left join user u on c.createId=u.LogName 
         		left join (select borrowId,SUM(number) AS allNum, SUM(executedNum) AS exeNum, SUM(backNum) AS backNum
         		from oa_borrow_equ where isDel = 0 group by borrowId)sum on c.id = sum.borrowId
         		left join oa_borrow_equ_link l on (c.id = l.borrowId and l.isTemp = 0)
         		where 1=1 and c.isTemp =  0",

	'select_shipments'=>"select c.subTip,c.changeTips,c.standardDate,c.isproShipcondition,c.id ,c.chanceId ,c.chanceCode ,c.chanceName ,c.Code ,c.Type ,c.customerName
         		,c.limits ,c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus ,
         		c.customTypeId,c.customTypeName,c.warnDate,c.ExaDT,c.createName,c.createId,c.createTime,c.DeliveryStatus,c.reason,c.createSection,c.createSectionId,c.shipaddress,
         		c.deliveryDate,c.objCode,c.status,c.tostorage,c.timeType,c.renew,c.isship,c.initTip,c.rdprojectName,c.rdprojectId
         		,c.rdprojectCode,c.makeStatus,c.dealStatus,c.isSubAppChange,c.equEstimate,c.equEstimateTax,
				l.id as lid ,l.ExaStatus as lExaStatus,l.ExaDTOne as lExaDTOne, l.id as linkId
         		from oa_borrow_borrow c left join oa_borrow_equ_link l on (c.id=l.borrowId and l.isTemp=0)
         	     left join oa_sale_chance ce on c.chanceId=ce.id
         	     left join user u on c.createId=u.LogName
         		where (select count(*) from oa_borrow_equ e where  e.isTemp=0 and e.isDel=0 and e.borrowId=c.id )>0 and
         		c.isTemp=0 " ,

		'select_assignment'=>"select c.subTip,c.changeTips,c.standardDate,c.isproShipcondition,c.id ,c.chanceId ,c.chanceCode ,c.chanceName ,c.Code ,c.Type ,c.customerName
         		,c.limits ,c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus ,
         		c.customTypeId,c.customTypeName,c.warnDate,c.ExaDT,c.createName,c.createId,c.createTime,c.DeliveryStatus,c.reason,c.createSection,c.createSectionId,c.shipaddress,
         		c.deliveryDate,c.objCode,c.status,c.tostorage,c.timeType,c.renew,c.isship,c.initTip,c.rdprojectName,c.rdprojectId
         		,c.rdprojectCode,c.makeStatus,c.dealStatus,c.isSubAppChange,c.equEstimate,c.equEstimateTax,c.needSalesConfirm,c.salesConfirmId,c.isDelayApply,
				l.id as lid ,l.ExaStatus as lExaStatus,l.ExaDTOne as lExaDTOne, l.id as linkId
         		from oa_borrow_borrow c left join oa_borrow_equ_link l on (c.id=l.borrowId and l.isTemp=0)
         	     left join oa_sale_chance ce on c.chanceId=ce.id
         	     left join user u on c.createId=u.LogName
         	    where c.isTemp=0 ",

		"select_equ" => "SELECT * FROM ( SELECT
								c.limits,
								ce.borrowId AS id,
								ce.productId,
								ce.productNo,
								ce.productNo AS productCode,
								ce.productName,
								SUM(ce.number) AS number,
								SUM(ce.executedNum) AS executedNum,
								SUM(ce.onWayNum) AS onWayNum
							FROM
								oa_borrow_equ ce
							RIGHT JOIN oa_borrow_borrow c ON (c.id = ce.borrowId)
							WHERE
								1 = 1
							AND c.ExaStatus = '完成' AND c.dealStatus IN(1,3) AND c.isTemp=0
							AND c.DeliveryStatus != 'TZFH'
							AND ce.isTemp = 0
							AND ce.isDel = 0
							GROUP BY productId,limits ) ce WHERE 1 = 1 AND ce.number-ce.executedNum>0 "
	,"select_cont" => "select * from (select
						c.id,
						c.code,
						c.type,
						c.limits,
						ce.number,
						ce.onWayNum,
						ce.executedNum,
						ce.productId,
						ce.productNo,
						ce.productName
					from
						oa_borrow_borrow c left join oa_borrow_equ ce
							on ( c.id=ce.borrowId )
					where
						1 = 1
					and (ce.number-ce.executedNum>0) and c.dealStatus in(1,3) and c.isTemp=0
					and c.ExaStatus = '完成' and c.DeliveryStatus != 'TZFH' and ce.isTemp=0 and ce.isDel=0 ) ce where 1=1 "


     //借试用转销售物料
     ,'borrowequ_choose'=>"select c.id,c.borrowId,c.productName,c.productId,c.productNo,c.warrantyPeriod,c.productModel,
     		                   c.number,c.executedNum,c.backNum,c.price,c.money,c.customerName,c.customerId,c.salesName,
     					       c.salesNameId,c.ExaStatus,c.limits,c.Code,c.conProductId,c.conProductName,c.businessBelong
     		         from(
     		            	select e.id,e.borrowId,e.productName,e.productId,e.productNo,e.warrantyPeriod,e.productModel,
     		                	e.number,e.executedNum,e.backNum,e.price,e.money,b.customerName,b.customerId,b.salesName,
     							b.salesNameId,b.ExaStatus,b.limits,b.Code,b.businessBelong,e.conProductId,p.conProductName
                     		from oa_borrow_equ e left join oa_borrow_borrow b on e.borrowId=b.id
     						left join oa_borrow_product p on e.conProductId = p.id
                            where 1=1 and  b.limits='客户' and b.ExaStatus='完成' and b.isTemp=0 and e.executedNum > 0 and e.isTemp=0 and e.executedNum-e.backNum>0
                         ) c where 1=1",
     //借试用报表sql
    'borrowReport_master' => "select REPLACE(REPLACE(c.createId,'.','_'),' ','_') as id,c.deptName as dept,c.createName as user,c.createId as userId,c.allmoney as allMoney
			from
			(
			select (select DEPT_NAME from user u left join department d on u.DEPT_ID = d.DEPT_ID where u.USER_ID = rs.createId) as deptName,rs.createName,rs.createId,sum(rs.money) as allmoney
			from
			(select b.createName,b.createId,b.renew,e.borrowId,e.productNo,e.productName,(e.executedNum - if(e.backNum is null,0,e.backNum)) as num,e.price,(e.executedNum * e.price) as money,b.beginTime,b.closeTime from
			oa_borrow_equ e
			left join oa_borrow_borrow b on b.id=e.borrowId where e.executedNum <> 0) rs  group by createId
			) c where 1=1",

    //借试用报表sql -- 从表
    'borrowReport_table' => "select c.createName,c.createId,c.renew,c.borrowId,c.equid,c.productNo,c.productName,c.num as number,c.price,c.money,c.beginTime,c.closeTime as endTime,c.isOvertime,c.overtimeNum,c.renewNum,c.renewDate
		    from
		    (
		    select b.createId as id,b.createName,b.createId,b.renew,e.borrowId,e.id as equid,e.productNo,e.productName,(e.executedNum - if(e.backNum is null,0,e.backNum)) as num,e.price,(e.executedNum * e.price) as money,
		       b.beginTime,b.closeTime,if(datediff(now(),b.closeTime) < 0 ,'否','是') as isOvertime,if(datediff(now(),b.closeTime) < 0,0,datediff(now(),b.closeTime)) as overtimeNum,
		       ( select re.number from oa_borrow_renew_equ re left join oa_borrow_renew r on r.id = re.renewId where r.ExaStatus = '完成' and re.equid = e.id order by r.id desc limit 1) as renewNum,
		       ( select r.reendDate from oa_borrow_renew_equ re left join oa_borrow_renew r on r.id = re.renewId where r.ExaStatus = '完成' and re.equid = e.id order by r.id desc limit 1) as renewDate
		    from
		    oa_borrow_equ e
		    left join oa_borrow_borrow b on b.id=e.borrowId where e.executedNum <> 0
		    )c
		where 1=1 and c.num <> 0",
     'select_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as borrowId ,c.chanceId ,c.chanceCode ,c.chanceName ,c.Code ,c.Type ,c.customerName ,c.limits ," .
		"c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus ,c.ExaDT
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_borrow_borrow c
		where
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'select_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as borrowId ,c.chanceId ,c.chanceCode ,c.chanceName ,c.Code ,c.Type ,c.customerName ,c.limits ," .
		"c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus ,c.ExaDT
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_borrow_borrow c where
		p.Flag='1' and
		w.Pid =c.id ",
     //加上续借时间
     'select_borrow_renew'=>"select c.subTip,c.customerType,c.isproShipcondition,c.id ,c.chanceId ,c.chanceCode ,c.chanceName ,c.Code ,c.Type ,c.customerName,c.customerId" .
         		",c.limits ,c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus ,l.ExaStatus as lExaStatus, " .
         		"c.customTypeId,c.customTypeName,c.warnDate,c.ExaDT,c.createName,c.createId,c.createTime,c.DeliveryStatus,c.reason,c.createSection,c.createSectionId,c.shipaddress," .
         		"c.deliveryDate,c.objCode,c.status,c.tostorage,c.timeType,c.renew,c.isship,c.initTip,c.rdprojectName,c.rdprojectId,c.rdprojectCode,ifnull(p.endDate,c.closeTime) as endDate," .
         		"c.checkFile, if(
         		    (sum.allNum is null or sum.exeNum = 0 or sum.exeNum = sum.backNum),1,
         		    if((sum.exeNum > 0 and sum.backNum <> 0),2,
         		    if((sum.exeNum > 0 and sum.backNum = 0),0,''))) as backStatusCode,c.needSalesConfirm,c.salesConfirmId,c.isDelayApply".
                " from oa_borrow_borrow c LEFT JOIN (SELECT max(reendDate) as endDate,c.borrowId  FROM oa_borrow_renew c group by c.borrowId) p ON c.id=p.borrowId
         		left join (select borrowId,SUM(number) AS allNum, SUM(executedNum) AS exeNum, SUM(backNum) AS backNum
         		from oa_borrow_equ where isDel = 0 group by borrowId)sum on c.id = sum.borrowId
         		 left join oa_borrow_equ_link l on (c.id = l.borrowId and l.isTemp = 0)
         		where 1=1  and c.isTemp = 0 ",
     'select_borrowTosale' => "select c.subTip,c.customerType,c.isproShipcondition,c.id ,c.chanceId ,c.chanceCode ,c.chanceName ,
				c.Code ,c.Type ,c.customerName,c.customerId,c.limits ,c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,
				c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus ,c.customTypeId,c.customTypeName,c.warnDate,c.ExaDT,c.createName,
				c.createId,c.createTime,c.DeliveryStatus,c.reason,c.createSection,c.createSectionId,c.shipaddress,c.deliveryDate,c.objCode,
				c.status,c.tostorage,c.timeType,c.renew,c.isship,c.initTip,c.rdprojectName,c.rdprojectId,c.rdprojectCode,c.backStatus
				from oa_borrow_borrow c LEFT JOIN oa_borrow_equ e on c.id = e.borrowId where 1=1 and c.isTemp =  0 and (( e.executedNum-e.backNum ) > 0) ",
	//加上商机状态
     "select_withChance"=>"select c.remarkapp,c.subTip,c.customerType,c.isproShipcondition,c.id ,c.chanceId ,c.chanceCode ,c.chanceName ,c.Code ,c.Type ,c.customerName,c.customerId" .
		     ",c.limits ,c.beginTime ,c.closeTime ,c.salesName ,c.salesNameId ,c.scienceName ,c.scienceNameId ,c.remark,c.ExaStatus ," .
		     "c.customTypeId,c.customTypeName,c.warnDate,c.ExaDT,c.createName,c.createId,c.createTime,c.DeliveryStatus,c.reason,c.createSection,c.createSectionId,c.shipaddress," .
		     "c.deliveryDate,c.objCode,c.status,c.tostorage,c.timeType,c.renew,c.isship,c.initTip,c.rdprojectName,c.rdprojectId,c.rdprojectCode,c.backStatus,c.module,c.moduleName,c.newProLineStr," .
		     "e.status as chanceStatus from oa_borrow_borrow c left join oa_sale_chance e on c.chanceId = e.id where 1=1 and c.isTemp = 0 ",
);

$condition_arr = array (
	array (
		"name" => "lExaStatus",
		"sql" => " and l.ExaStatus like CONCAT('%',#,'%') "
	),
    array (
		"name" => "changingExaStatus",
		"sql" => " and (c.ExaStatus like CONCAT('%',#,'%') or l.ExaStatus like CONCAT('%',#,'%')) "
	),
	array (
		"name" => "lExaStatusArr",
		"sql" => " and l.ExaStatus in(arr) "
	),
	array (
		"name" => "customTypeId",
		"sql" => " and c.customTypeId=# "
	),
	array (
		"name" => "borrowId",
		"sql" => " and c.borrowId=# "
	),
	array (
		"name" => "subBorrowId",
		"sql" => " and c.subBorrowId=# "
	),
	array (
		"name" => "customTypeName",
		"sql" => " and c.customTypeName=# "
	),
	array (
		"name" => "warnDate",
		"sql" => " and c.warnDate=# "
	),
    array(
        "name" => "ids",
        "sql" => "and c.id in(arr)"
        ),
    array(
        "name" => "standardDate",
        "sql" => "and c.standardDate=#"
        ),
    array(
        "name" => "customerId",
        "sql" => "and c.customerId=#"
        ),
    array(
        "name" => "productNametoCon",
        "sql" => "and c.productName like CONCAT('%',#,'%')"
        ),
    array(
        "name" => "productCodetoCon",
        "sql" => "and c.productNo like CONCAT('%',#,'%')"
        ),
	array(
   		"name" => "isproShipcondition",
   		"sql" => " and c.isproShipcondition=# "
        ),
    array(
   		"name" => "isproShipconditionAs",
   		"sql" => " and (c.isproShipcondition=# or c.isship=#) "
        ),
	array(
   		"name" => "inSea",
   		"sql" => " and c.createSectionId not in(arr) "
        ),
	array(
   		"name" => "outSea",
   		"sql" => " and c.createSectionId in(arr) "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
    array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "initTip",
   		"sql" => " and c.initTip=# "
        ),
   array(
        "name" => "ExaStatusArr",
        "sql" => "and c.ExaStatus in(arr)"
        ),
   array(
        "name" => "dealStatusArr",
        "sql" => "and c.dealStatus in(arr) "
        ),
   array(
        "name" => "dealStatus",
        "sql" => "and c.dealStatus=#"
        ),
   array(
        "name" => "ExaStatus",
        "sql" => "and c.ExaStatus=#"
        ),
   array(
        "name" => "ExaStatusArr",
        "sql" => "and c.ExaStatus in(arr)"
        ),
   array(
        "name" => "makeStatus",
        "sql" => "and c.makeStatus=#"
        ),
   array(
        "name" => "DeliveryStatus",
        "sql" => "and c.DeliveryStatus=#"
        ),
   array(
        "name" => "DeliveryStatus2",
        "sql" => "and c.DeliveryStatus in(arr)"
        ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
   	  ),
   array(
   		"name" => "chanceCode",
   		"sql" => " and c.chanceCode=# "
   	  ),
   array(
   		"name" => "chanceName",
   		"sql" => " and c.chanceName=# "
   	  ),
   array(
        "name" => "createId",
        "sql" => "and c.createId=#"
      ),
   array(
   		"name" => "Code",
   		"sql" => " and c.Code like CONCAT('%',#,'%') "
   	  ),
   array(
        "name" => "ajaxCode",
        "sql" => "and c.Code = #"
   ),
   	array(
   		"name" => "objCode",
   		"sql" => " and c.objCode like CONCAT('%',#,'%') "
   	  ),
   array(
        "name" => "statusArr",
        "sql" => "and c.status in(arr)"
   ),
   array(
        "name" => "status",
        "sql" => "and c.status =#"
   ),
   array(
        "name" => "statusNo",
        "sql" => "and c.status <>#"
   ),
   array(
        "name" => "tostorage",
        "sql" => "and c.tostorage =#"
   ),
   array(
        "name" => "sto",
        "sql" => "$"
   ),
   array(
        "name" => "isship",
        "sql" => "and c.isship =#"
   ),
   array(
        "name" => "isshipments",
        "sql" => "and (c.isship =# or c.isproShipcondition = 1)"
   ),
   array(
   		"name" => "Type",
   		"sql" => " and c.Type=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "limits",
   		"sql" => " and c.limits=# "
   	  ),
   array(
   		"name" => "beginTime",
   		"sql" => " and c.beginTime=# "
   	  ),
   array(
   		"name" => "closeTime",
   		"sql" => " and c.closeTime=# "
   	  ),
   array(
   		"name" => "salesName",
   		"sql" => " and c.salesName LIKE CONCAT('%', #, '%') "
   	  ),
   array(
   		"name" => "salesNameId",
   		"sql" => " and c.salesNameId=# "
   	  ),
	array(
		"name" => "salesNameIds",
		"sql" => "and c.salesNameId in(arr)"
	),
   array(
   		"name" => "scienceName",
   		"sql" => " and c.scienceName=# "
   	  ),
   array(
   		"name" => "scienceNameId",
   		"sql" => " and c.scienceNameId=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
      ),
  array(
	"name" => "rdprojectName",
	"sql" => " and c.rdprojectName=# "
      ),
  array(
	"name" => "rdprojectId",
	"sql" => " and c.rdprojectId=# "
      ),
  array(
	"name" => "rdprojectCode",
	"sql" => " and c.rdprojectCode=# "
      ),
   //审核工作流
	array(
		"name" => "findInName",//审批人ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlowCode",//业务表
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "Flag",//业务表
		"sql"=>" and p.Flag= # "
	),
    array(
        "name" => "createIdOr",
        "sql" => " or c.createId=# )"
	),
	array(
		"name" => "createSections",
		"sql"=>" and ( c.createSectionId in(arr) "
	),
	array(
	    "name" => "createName",
	    "sql" => "and c.createName like CONCAT('%',#,'%') "
	),
	array(
	    "name" => "pageUser",
	    "sql" => "$"
	),
	array(
	    "name" => "subTip",
	    "sql" => "c.subTip =#"
	),
	array(
	    "name" => "user",
	    "sql" => "and c.createName like CONCAT('%',#,'%')"
	),
	array(
	    "name" => "dept",
	    "sql" => "and c.deptName like CONCAT('%',#,'%')"
	),
	array(
	    "name" => "createTime",
	    "sql" => "and c.createTime like binary(concat('%',#,'%'))"
	)
	,
	array(
	    "name" => "productNameKS",
	    "sql" => "and c.id in (select borrowId from oa_borrow_equ where productNameKS like CONCAT('%',#,'%'))"
	)
	,
	array(
	    "name" => "productName",
	    "sql" => "and c.id in (select borrowId from oa_borrow_equ where productName like CONCAT('%',#,'%'))"
	)
	,
	array(
	    "name" => "productNo",
	    "sql" => "and c.id in (select borrowId from oa_borrow_equ where productNo like CONCAT('%',#,'%'))"
	)
	,
	array(
	    "name" => "productNoKS",
	    "sql" => "and c.id in (select borrowId from oa_borrow_equ where productNoKS like CONCAT('%',#,'%'))"
	)
	,
	array(
	    "name" => "serialName",
	    "sql" => "and c.id in (select borrowId from oa_borrow_equ where serialName like CONCAT('%',#,'%'))"
	)
	,
	array(
	    "name" => "serialName2",
	    "sql" => "and c.id in (select relDocId from oa_stock_product_serialno where relDocType='oa_borrow_borrow' and sequence like CONCAT('%',#,'%') )"
	)
	,
	array(
		"name" => "serialName3",
		"sql" => "and c.id in (select relDocItemId from oa_stock_product_serialno where relDocType='oa_borrow_borrow' and sequence like CONCAT('%',#,'%') )"
	)
	,
	array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	)
	/**********设备汇总表**********/
	,array (
		"name" => "productIdEqu",
		"sql" => " and ce.productId=# "
	),array (
		"name" => "productCodeEqu",
		"sql" => " and ce.productNo like CONCAT('%',#,'%')  "
	),array (
		"name" => "productNameEqu",
		"sql" => " and ce.productName like CONCAT('%',#,'%')  "
	),array (
		"name" => "limits2",
		"sql" => " and ce.limits=# "
	),
	array (
	    "name" => "areaCodeSql",
	    "sql" => "$"
	),array(
        "name" => "backStatu",
	    "sql" => "and if(
         		    (sum.allNum is null or sum.exeNum = 0 or sum.exeNum = sum.backNum),1,
         		    if((sum.exeNum > 0 and sum.backNum <> 0),2,
         		    if((sum.exeNum > 0 and sum.backNum = 0),0,''))) =#"
	),
    array (
        "name" => "isNotDelayApply",
        "sql" => " and c.isDelayApply <> 1"
    )
)
?>