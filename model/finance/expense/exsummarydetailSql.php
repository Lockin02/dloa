<?php

/**
 * @author Show
 * @Date 2012��10��11�� ������ 10:02:05
 * @version 1.0
 * @description:���û��ܱ���ϸ��ע��Ϣ sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.ID ,c.BillNo ,c.CostTypeID ,c.CostMoney ,c.Note  from cost_summary c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ID",
		"sql" => " and c.ID=# "
	),
	array (
		"name" => "BillNo",
		"sql" => " and c.BillNo=# "
	),
	array (
		"name" => "CostTypeID",
		"sql" => " and c.CostTypeID=# "
	),
	array (
		"name" => "CostMoney",
		"sql" => " and c.CostMoney=# "
	),
	array (
		"name" => "Note",
		"sql" => " and c.Note=# "
	)
)
?>