<?php
/**
 * @author Show
 * @Date 2012年10月15日 星期一 9:44:04
 * @version 1.0
 * @description:发票汇总单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.ID ,c.BillNo ,c.InputMan ,c.PayMan ,c.TallyMan ,c.CostMan ,c.CostDept ,c.BelongtoDept ,c.Payee ,c.PayeePro ,c.PayeeTown ,c.PayeeAcc ,c.Content ,c.Amount ,c.InputDt ,c.PayDT ,c.TallyDT ,c.Status ,c.TypeFlag ,c.ConBillNo ,c.Bank ,c.BankAcc ,c.SerialType ,c.SerialNo ,c.rand_key  from bill_list c where 1=1 "
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
   		"name" => "BillNo",
   		"sql" => " and c.BillNo=# "
   	  ),
   array(
   		"name" => "InputMan",
   		"sql" => " and c.InputMan=# "
   	  ),
   array(
   		"name" => "PayMan",
   		"sql" => " and c.PayMan=# "
   	  ),
   array(
   		"name" => "TallyMan",
   		"sql" => " and c.TallyMan=# "
   	  ),
   array(
   		"name" => "CostMan",
   		"sql" => " and c.CostMan=# "
   	  ),
   array(
   		"name" => "CostDept",
   		"sql" => " and c.CostDept=# "
   	  ),
   array(
   		"name" => "BelongtoDept",
   		"sql" => " and c.BelongtoDept=# "
   	  ),
   array(
   		"name" => "Payee",
   		"sql" => " and c.Payee=# "
   	  ),
   array(
   		"name" => "PayeePro",
   		"sql" => " and c.PayeePro=# "
   	  ),
   array(
   		"name" => "PayeeTown",
   		"sql" => " and c.PayeeTown=# "
   	  ),
   array(
   		"name" => "PayeeAcc",
   		"sql" => " and c.PayeeAcc=# "
   	  ),
   array(
   		"name" => "Content",
   		"sql" => " and c.Content=# "
   	  ),
   array(
   		"name" => "Amount",
   		"sql" => " and c.Amount=# "
   	  ),
   array(
   		"name" => "InputDt",
   		"sql" => " and c.InputDt=# "
   	  ),
   array(
   		"name" => "PayDT",
   		"sql" => " and c.PayDT=# "
   	  ),
   array(
   		"name" => "TallyDT",
   		"sql" => " and c.TallyDT=# "
   	  ),
   array(
   		"name" => "Status",
   		"sql" => " and c.Status=# "
   	  ),
   array(
   		"name" => "TypeFlag",
   		"sql" => " and c.TypeFlag=# "
   	  ),
   array(
   		"name" => "ConBillNo",
   		"sql" => " and c.ConBillNo=# "
   	  ),
   array(
   		"name" => "Bank",
   		"sql" => " and c.Bank=# "
   	  ),
   array(
   		"name" => "BankAcc",
   		"sql" => " and c.BankAcc=# "
   	  ),
   array(
   		"name" => "SerialType",
   		"sql" => " and c.SerialType=# "
   	  ),
   array(
   		"name" => "SerialNo",
   		"sql" => " and c.SerialNo=# "
   	  ),
   array(
   		"name" => "rand_key",
   		"sql" => " and c.rand_key=# "
   	  )
)
?>