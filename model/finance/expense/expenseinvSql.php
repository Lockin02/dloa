<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:18:08
 * @version 1.0
 * @description:目测是发票金额汇总表 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.ID ,c.BillTypeID ,c.BillDept ,c.CostTypeID ,c.ProName ,c.ProNo ,c.ContName ,
            c.ContNo ,c.UnitPrice ,c.Days ,c.Amount ,c.BillNo ,c.BillDetailID ,c.BillAssID,c.invoiceNumber,c.isSubsidy
        from bill_detail c where 1=1 ",
    "select_count" => "select c.BillTypeID ,sum(c.Days) as Days ,sum(c.Amount) as Amount,
            sum(c.invoiceNumber) as invoiceNumber
        from bill_detail c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "ID",
        "sql" => " and c.ID=# "
    ),
    array(
        "name" => "BillTypeID",
        "sql" => " and c.BillTypeID=# "
    ),
    array(
        "name" => "BillDept",
        "sql" => " and c.BillDept=# "
    ),
    array(
        "name" => "CostTypeID",
        "sql" => " and c.CostTypeID=# "
    ),
    array(
        "name" => "ProName",
        "sql" => " and c.ProName=# "
    ),
    array(
        "name" => "ProNo",
        "sql" => " and c.ProNo=# "
    ),
    array(
        "name" => "ContName",
        "sql" => " and c.ContName=# "
    ),
    array(
        "name" => "ContNo",
        "sql" => " and c.ContNo=# "
    ),
    array(
        "name" => "UnitPrice",
        "sql" => " and c.UnitPrice=# "
    ),
    array(
        "name" => "Days",
        "sql" => " and c.Days=# "
    ),
    array(
        "name" => "Amount",
        "sql" => " and c.Amount=# "
    ),
    array(
        "name" => "AmountNo",
        "sql" => " and c.Amount <> # "
    ),
    array(
        "name" => "BillNo",
        "sql" => " and c.BillNo=# "
    ),
    array(
        "name" => "BillDetailID",
        "sql" => " and c.BillDetailID=# "
    ),
    array(
        "name" => "BillDetailIDArr",
        "sql" => " and c.BillDetailID in(arr)"
    ),
    array(
        "name" => "BillAssID",
        "sql" => " and c.BillAssID=# "
    ),
    array(
        "name" => "isSubsidy",
        "sql" => " and c.isSubsidy=# "
    )
);