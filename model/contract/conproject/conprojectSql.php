<?php
/**
 * @author liub
 * @Date 2014年5月29日 15:17:49
 * @version 1.0
 * @description:合同项目表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select * @from (" .
         		"select IF (p.status IS NULL,if(c.schedule = '100','GCXMZT04','GCXMZT02'),p.status) as status,c.esmProjectId," .
         		"(c.contractMoney-c.estimates) as gross," .
         		"c.contractName,c.projectName,c.checkTip,c.id ,c.contractId ,c.contractCode ,c.contractMoney ,c.projectCode ,c.proLineCode ,c.proLineName ,c.proportion ," .
         		"c.schedule ,c.earnings ,c.earningsTypeName ,c.earningsTypeCode ,c.estimates ,c.budget ,c.cost ,c.costAct ,c.planBeginDate ," .
         		"c.planEndDate ,c.actBeginDate ,c.actEndDate ,c.state ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ," .
         		"c.createName ,c.createId,c.exgross,c.proMoney,c.proLineMoney,c.module,c.moduleName,c.officeId,c.officeName " .
         		" from oa_contract_project c" .
         		" left join oa_esm_project p on c.esmProjectId=p.id)c" .
         		" where 1=1 ",
         "select_store"=>"select c.deductMoney,c.badMoney,IF (p.status IS NULL,if(c.schedule = '100','GCXMZT04','GCXMZT02'),p.status) as status,c.esmProjectId," .
         		"(c.contractMoney-c.estimates) as gross,c.proportionTrue,c.txaRate,c.rateMoney,c.grossTrue,c.exgrossTrue," .
         		"c.contractName,c.projectName,c.checkTip,c.pid,c.id ,c.contractId ,c.contractCode ,c.contractMoney ,c.projectCode ,c.proLineCode ,c.proLineName ,c.proportion ," .
         		"c.schedule ,c.earnings ,c.earningsTypeName ,c.earningsTypeCode ,c.estimates ,c.budget ,c.cost ,c.costAct ,c.planBeginDate ," .
         		"c.planEndDate ,c.actBeginDate ,c.actEndDate ,c.state ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ," .
         		"c.createName ,c.createId,c.exgross,c.proMoney,c.proLineMoney,c.version,c.isUse,c.storeYear,c.storeMon,c.module,c.moduleName,c.officeId,c.officeName " .
         		" from oa_contract_project_record c" .
                " left join oa_esm_project p on c.esmProjectId=p.id".
         		" where 1=1 ",
         "select_defaultNew"=>"select * @from (" .
         		"select IF (p.status IS NULL,if(c.schedule = '100','GCXMZT04','GCXMZT02'),p.status) as status,c.esmProjectId," .
         		"(c.contractMoney-c.estimates) as gross," .
         		"c.contractName,c.projectName,c.checkTip,c.id ,c.contractId ,c.contractCode ,c.contractMoney ,c.projectCode ,c.proLineCode ,c.proLineName ,c.proportion ," .
         		"c.schedule ,c.earnings ,c.earningsTypeName ,c.earningsTypeCode ,c.estimates ,c.budget ,c.cost ,c.costAct ,c.planBeginDate ," .
         		"c.planEndDate ,c.actBeginDate ,c.actEndDate ,c.state ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ," .
         		"c.createName ,c.createId,c.exgross,c.proMoney,c.proLineMoney,o.module,o.moduleName, " .
         		" IF (p.productLine IS NULL,IF(t.exeDeptId IS NULL,'',t.exeDeptId),p.productLine) as officeId," .
         		" IF (p.productLineName IS NULL,IF(t.exeDeptName IS NULL,'',t.exeDeptName),p.productLineName) as officeName," .
         		" p.productLine as projectProLine,t.exeDeptId as conproExeDeptId,o.module as conModule" .
         		" from oa_contract_project c" .
         		" left join oa_esm_project p on c.esmProjectId=p.id" .
         		" left join oa_contract_contract o on c.contractId=o.id" .
				" left join (
					select contractId,newProLineCode,exeDeptId,exeDeptName
					from oa_contract_product where proTypeId = 11 and isDel = '0' group by contractId, newProLineCode
				) t on c.contractId = t.contractId and c.proLineCode = t.newProLineCode )c" .
				" where 1=1 ",
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
    array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
        ),
    array(
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
        ),
    array(
   		"name" => "checkTip",
   		"sql" => " and c.checkTip=# "
        ),
    array(
       "name" => "status",
       "sql" => " and status=# "
    ),
    array(
   		"name" => "esmProjectId",
   		"sql" => " and c.esmProjectId=# "
        ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "contractMoney",
   		"sql" => " and c.contractMoney=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "proLineCode",
   		"sql" => " and c.proLineCode=# "
   	  ),
   array(
   		"name" => "proLineName",
   		"sql" => " and c.proLineName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "proportion",
   		"sql" => " and c.proportion=# "
   	  ),
   array(
   		"name" => "schedule",
   		"sql" => " and c.schedule=# "
   	  ),
   array(
   		"name" => "earnings",
   		"sql" => " and c.earnings=# "
   	  ),
   array(
   		"name" => "earningsTypeName",
   		"sql" => " and c.earningsTypeName=# "
   	  ),
   array(
   		"name" => "earningsTypeCode",
   		"sql" => " and c.earningsTypeCode=# "
   	  ),
   array(
   		"name" => "estimates",
   		"sql" => " and c.estimates=# "
   	  ),
   array(
   		"name" => "budget",
   		"sql" => " and c.budget=# "
   	  ),
   array(
   		"name" => "cost",
   		"sql" => " and c.cost=# "
   	  ),
   array(
   		"name" => "planBeginDate",
   		"sql" => " and c.planBeginDate=# "
   	  ),
   array(
   		"name" => "planEndDate",
   		"sql" => " and c.planEndDate=# "
   	  ),
   array(
   		"name" => "actBeginDate",
   		"sql" => " and c.actBeginDate=# "
   	  ),
   array(
   		"name" => "actEndDate",
   		"sql" => " and c.actEndDate=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
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
   		"name" => "version",
   		"sql" => " and c.version=# "
   	  ),
   array(
        "name"=> "storeYear",
        "sql" => " and c.storeYear=#"
   ),
   array(
        "name"=> "storeMon",
        "sql" => " and c.storeMon=#"
   ),
   array(
        "name"=> "isUse",
        "sql" => " and c.isUse=#"
   ),
   array(
        "name"=> "exeDeptId",
        "sql" => " and c.exeDeptId=#"
    ),
   array(
		"name"=> "officeId",
		"sql" => " and c.officeId=#"
	),
   array(
		"name"=> "officeIdReal",
		"sql" => " and projectProLine = # or (projectProLine is null and conproExeDeptId = #)"
	),
   array(
		"name"=> "module",
		"sql" => " and c.module=#"
	),
   array(
		"name"=> "moduleReal",
		"sql" => " and conModule=#"
	),
   array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	),
   array (
        "name" => "warningStr",
        "sql" => "$"
   ),
	array(
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	)
);