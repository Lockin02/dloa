<?php
/**
 * @author show
 * @Date 2014��8��26�� 14:35:45
 * @version 1.0
 * @description:��Ʊ����(���) sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.invoiceId ,c.productName ,c.productId ,c.amount ,c.softMoney ,c.hardMoney ,c.serviceMoney ,c.repairMoney ,c.equRentalMoney ,c.spaceRentalMoney ,c.otherMoney ,c.psType  from oa_finance_invoice_detail_currency c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "invoiceId",
        "sql" => " and c.invoiceId=# "
    ),
    array(
        "name" => "productName",
        "sql" => " and c.productName=# "
    ),
    array(
        "name" => "productId",
        "sql" => " and c.productId=# "
    ),
    array(
        "name" => "amount",
        "sql" => " and c.amount=# "
    ),
    array(
        "name" => "softMoney",
        "sql" => " and c.softMoney=# "
    ),
    array(
        "name" => "hardMoney",
        "sql" => " and c.hardMoney=# "
    ),
    array(
        "name" => "serviceMoney",
        "sql" => " and c.serviceMoney=# "
    ),
    array(
        "name" => "repairMoney",
        "sql" => " and c.repairMoney=# "
    ),
    array(
        "name" => "equRentalMoney",
        "sql" => " and c.equRentalMoney=# "
    ),
    array(
        "name" => "spaceRentalMoney",
        "sql" => " and c.spaceRentalMoney=# "
    ),
    array(
        "name" => "otherMoney",
        "sql" => " and c.otherMoney=# "
    ),
    array(
        "name" => "psType",
        "sql" => " and c.psType=# "
    )
);