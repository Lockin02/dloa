<?php
/**
 * @author Show
 * @Date 2012年11月6日 星期二 11:42:10
 * @version 1.0
 * @description:设备管理-库存信息 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.typeid ,c.dept_id ,c.area ,c.device_name ,c.unit ,c.total ,c.borrow ,c.surplus ,c.lastmonth ,c.thismonth ,c.lose ,c.average ,c.discount ,c.rate ,c.inventory ,c.notse ,c.description ,c.rand_key  from device_list c where 1=1 ",
    "select_select" => "select
			c.id ,c.typeid ,c.dept_id ,c.area ,c.device_name ,c.unit ,c.description ,c.rand_key,c.budgetPrice as discount,
			d.DEPT_NAME as deptName,
			a.Name as areaName,
			t.typename as deviceType,c.budgetPrice
		from
			device_list c
			left join
			department d on c.dept_id = d.DEPT_ID
			left join
			area a on c.area = a.ID
			left join
			device_type t on c.typeid = t.id
		where 1 ",
    "select_deviceType" => "select c.id as value,c.typename as text from device_type c ",
    "select_deviceTypeMy" => "select
            g.id as value,g.typename as text
        from
            device_borrow_order_info as a
            left join device_info as b on b.id=a.info_id
            left join device_borrow_order as o on o.id=a.orderid
            left join device_list c ON c.id=b.list_id
            left join device_type g ON g.id=c.typeid
        where 1 ",
    "select_projectArea" => "SELECT
              c.id,c.Name,c.del,re.amount,re.borrowNum,re.surplus
			@FROM
			   (
			   SELECT
			      i.area,i.list_id,SUM(i.amount) AS amount,SUM(i.borrow_num) AS borrowNum,(SUM(i.amount) - SUM(i.borrow_num)) AS surplus
			   FROM
			      device_info i
    		   WHERE
				  i.quit = 0
			   GROUP BY
			      area,list_id
				) re
				LEFT JOIN
			   area c
			ON c.id = re.area
			WHERE 1",
    "select_device" => "select
			f.device_name,b.coding,b.dpcoding,a.amount as borrowNum,f.unit,g.typename as deviceType,e.USER_NAME as borrowUserName,
			a.id,if(a.date = 0,'',FROM_UNIXTIME(a.date,'%Y-%m-%d')) as borrowDate,
			if(a.returndate = 0 ,'',FROM_UNIXTIME(a.returndate,'%Y-%m-%d')) as returnDate,
			bf.days as useDays,
			bf.fee as amount,
			b.fitting,d.number as projectCode,d.name as projectName,b.notes,f.description,f.budgetPrice
		from
			device_borrow_order_info as a
			left join device_info as b on b.id=a.info_id
			left join device_borrow_order as c on c.id=a.orderid
			left join project_info as d on d.id = c.project_id
			left join user as e on e.user_id=c.userid
			left join device_list f ON f.id=b.list_id
			left join device_type g ON g.id=f.typeid
			LEFT JOIN (SELECT borrowInfoId,SUM(days) AS days,SUM(fee) AS fee FROM oa_esm_resource_fee GROUP BY borrowInfoId) bf ON bf.borrowInfoId = a.id
		where 1 ",
    "select_my" => "select
			SUM(a.amount) as num,g.typename as deviceType,c.device_name,c.unit,c.description,c.id,
			de.DEPT_ID AS deptId,de.DEPT_NAME AS deptName
		from
			device_borrow_order_info as a
			left join device_info as b on b.id=a.info_id
			left join device_borrow_order as o on o.id=a.orderid
			left join device_list c ON c.id=b.list_id
			left join device_type g ON g.id=c.typeid
			LEFT JOIN department de ON b.dept_id = de.DEPT_ID
		where 1 ",
    "select_mydetail" => "select
			a.id as borrowItemId,c.id as cid,c.device_name,b.coding as coding,b.dpcoding as dpcoding,a.amount-a.return_num as amount,
			c.description,b.price as price,b.id as bid,g.typename as deviceType,p.projectId,p.number as projectCode,
			p.name as projectName,p.manager as managerId,u.USER_NAME as managerName,p.flag as flag,b.dept_id AS deptId,
			IFNULL(FROM_UNIXTIME(a.targetdate,'%Y-%m-%d'),'') as targetdate,FROM_UNIXTIME(a.date,'%Y-%m-%d') as date,
    		DATEDIFF(CURDATE(),FROM_UNIXTIME(a.targetdate, '%Y-%m-%d')) as borrowDays
        from
			device_borrow_order_info as a
			left join device_info as b on b.id=a.info_id
			left join device_borrow_order as o on o.id=a.orderid
			left join device_list c ON c.id=b.list_id
			left join device_type g ON g.id=c.typeid
			left join project_info p ON o.project_id=p.id
			left join user u ON p.manager=u.USER_ID
        where 1 ",
    "select_returninfo" => "select
			a.id as borrowItemId,a.notse,b.id as bid,b.id as resourceId,c.device_name as resourceName,c.id as resourceListId,
			g.id as resourceTypeId,g.typename as resourceTypeName,
			b.coding as coding,b.dpcoding as dpcoding,a.amount-a.return_num as number,c.description,b.price as price,
			c.unit,IFNULL(FROM_UNIXTIME(a.returndate,'%Y-%m-%d'),FROM_UNIXTIME(a.targetdate,'%Y-%m-%d')) as beginDate
        from
			device_borrow_order_info as a
			left join device_info as b on b.id=a.info_id
			left join device_borrow_order as o on o.id=a.orderid
			left join device_list c ON c.id=b.list_id
			left join device_type g ON g.id=c.typeid
        where 1 "
);

$condition_arr = array(
    array(
        "name" => "oConfirm",
        "sql" => " and o.confirm =# "
    ),
	array(
		"name" => "aClaim",
		"sql" => " and a.claim =# "
	),
    array(
        "name" => "dprojectcode",
        "sql" => " and d.number =# "
    ),
    array(
        "name" => "ids",
        "sql" => " and c.id in(arr) "
    ),
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "typeid",
        "sql" => " and c.typeid=# "
    ),
    array(
        "name" => "dept_id",
        "sql" => " and c.dept_id=# "
    ),
    array(
        "name" => "dept_id_in",
        "sql" => " and c.dept_id in(arr) "
    ),
    array(
        "name" => "fdept_id_in",
        "sql" => " and f.dept_id in(arr) "
    ),
    array(
        "name" => "deptSearch",
        "sql" => " and c.dept_id in (select DEPT_ID from department where DEPT_NAME like concat('%',#,'%')) "
    ),
    array(
        "name" => "area",
        "sql" => " and c.area=# "
    ),
    array(
        "name" => "areaSearch",
        "sql" => " and c.area in (select id from area where Name like concat('%',#,'%')) "
    ),
    array(
        "name" => "device_name",
        "sql" => " and c.device_name=# "
    ),
    array(
        "name" => "device_nameSearch",
        "sql" => " and c.device_name like concat('%',#,'%') "
    ),
    array(
        "name" => "unit",
        "sql" => " and c.unit=# "
    ),
    array(
        "name" => "total",
        "sql" => " and c.total=# "
    ),
    array(
        "name" => "borrow",
        "sql" => " and c.borrow=# "
    ),
    array(
        "name" => "surplus",
        "sql" => " and c.surplus=# "
    ),
    array(
        "name" => "lastmonth",
        "sql" => " and c.lastmonth=# "
    ),
    array(
        "name" => "thismonth",
        "sql" => " and c.thismonth=# "
    ),
    array(
        "name" => "lose",
        "sql" => " and c.lose=# "
    ),
    array(
        "name" => "average",
        "sql" => " and c.average=# "
    ),
    array(
        "name" => "discount",
        "sql" => " and c.discount=# "
    ),
    array(
        "name" => "rate",
        "sql" => " and c.rate=# "
    ),
    array(
        "name" => "inventory",
        "sql" => " and c.inventory=# "
    ),
    array(
        "name" => "notse",
        "sql" => " and c.notse=# "
    ),
    array(
        "name" => "description",
        "sql" => " and c.description=# "
    ),
    array(
        "name" => "descriptionSearch",
        "sql" => " and c.description like concat('%',#,'%') "
    ),
    array(
        "name" => "rand_key",
        "sql" => " and c.rand_key=# "
    ),
    array(
        "name" => "list_id",
        "sql" => " and re.list_id=# "
    ),
    array(
        "name" => "dnumber",
        "sql" => " and replace(d.number,'-','')=replace(#,'-','') "
    ),
    array(
        "name" => "oUserid",
        "sql" => " and o.userid=# "
    ),
    array(
        "name" => "cid",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "bid",
        "sql" => " and b.id=# "
    ),
    array(
        "name" => "rowsId",
        "sql" => " and a.id in(arr) "
    ),
    array(
        "name" => "notReturn",
        "sql" => " AND a.amount > a.return_num "
    ),
    array(
        "name" => "resourceTypeId",
        "sql" => " and t.id=# "
    ),
	//设备锁定筛选条件
    array(
        "name" => "lockCondition",
        "sql" => "$"
    ),
	//设备机身码搜索
	array(
		"name" => "bCoding",
		"sql" => " and b.coding like concat('%',#,'%') "
	)
);