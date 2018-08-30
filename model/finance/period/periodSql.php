<?php
/**
 * @author Show
 * @Date 2011年5月21日 星期六 14:47:06
 * @version 1.0
 * @description:财务会计期间表 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.periodNo ,c.thisYear ,c.thisMonth ,c.thisDate ,c.isCheckout ,c.isClosed ,c.isUsing ,
            				c.isCostCheckout ,c.isCostClosed ,c.isCostUsing ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName 
						from oa_finance_accountingperiod c where 1 ",
    "selectSelect" => "select c.thisDate as value,c.periodNo as text from oa_finance_accountingperiod c where 1 "
);

$condition_arr = array(
    array(
        "name" => "effective",
        "sql" => " and (c.isCheckOut = 1 OR c.isUsing = 1) "
    ),
	array(
		"name" => "effectiveCost",
		"sql" => " and (c.isCostCheckOut = 1 OR c.isCostUsing = 1) "
	),
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "periodNo",
        "sql" => " and c.periodNo=# "
    ),
    array(
        "name" => "thisYear",
        "sql" => " and c.thisYear=# "
    ),
    array(
        "name" => "thisMonth",
        "sql" => " and c.thisMonth=# "
    ),
    array(
        "name" => "thisDate",
        "sql" => " and c.thisDate=# "
    ),
    array(
        "name" => "isCheckout",
        "sql" => " and c.isCheckout=# "
    ),
    array(
        "name" => "isClosed",
        "sql" => " and c.isClosed=# "
    ),
	array(
		"name" => "isUsing",
		"sql" => " and c.isUsing = # "
	),
	array(
		"name" => "isCostCheckout",
		"sql" => " and c.isCostCheckout=# "
	),
	array(
		"name" => "isCostClosed",
		"sql" => " and c.isCostClosed=# "
	),
	array(
		"name" => "isCostUsing",
		"sql" => " and c.isCostUsing = # "
	),
    array(
        "name" => "laterDate",
        "sql" => " and DATE_FORMAT(c.thisDate,'%Y%m') > DATE_FORMAT(#,'%Y%m') "
    ),
    array(
        "name" => "businessBelong",
        "sql" => " and c.businessBelong=# "
    )
);