<?php
/**
 * @author Administrator
 * @Date 2012��8��28�� ������ 15:54:13
 * @version 1.0
 * @description:���鿴
 */
$sql_arr = array (
         "select_default"=>"select a.id, p.userNo,p.userName, a.Debtor,a.ApplyDT,a.Reason, a.Amount, 
a.PayDT, a.ReceiptDT,a.ProjectNo,
a.XmFlag/*(1Ϊ��Ŀ��0Ϊ���Ž�� ����)*/,a.Status
from loan_list a  left join oa_hr_personnel p on a.Debtor=p.userAccount where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "Debtor",
   		"sql" => " and a.Debtor=# "
        ),
	array(
		"name" => "ProjectNo",
		"sql" => " and a.ProjectNo=# "
	)
)
?>