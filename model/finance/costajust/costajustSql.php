<?php
/**
 * @author Show
 * @Date 2011��5��31�� ���ڶ� 10:30:13
 * @version 1.0
 * @description:�ɱ������� sql�����ļ� ��������
                                  ����ɱ�������(���ڳ����������)
                                  ���ɱ�������
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.stockbalId,c.formNo ,c.formType ,c.outAdjustType ,c.formDate ,c.stockId ,c.stockCode ,c.stockName ,c.deptName ,c.deptId ,c.salesman ,c.salesmanId ,c.createTime ,c.createName ,c.createId ,c.updateName ,c.updateId ,c.updateTime  from oa_finance_costajust c where 1=1 "
);

$condition_arr = array (
	array(
       "name" => "id",
   		"sql" => " and c.Id=# "
    ),
   array(
   		"name" => "formNo",
   		"sql" => " and c.formNo=# "
    ),
   array(
   		"name" => "formType",
   		"sql" => " and c.formType=# "
    ),
   array(
   		"name" => "outAdjustType",
   		"sql" => " and c.outAdjustType=# "
    ),
   array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
    ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
    ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode=# "
    ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
    ),
   array(
   		"name" => "salesmanId",
   		"sql" => " and c.salesmanId=# "
    ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
    ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
    )
)
?>