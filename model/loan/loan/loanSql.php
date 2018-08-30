<?php
$sql_arr = array (
    "select_default" => "select c.id,c.Debtor,c.CreateDT,DATE_FORMAT(c.CreateDT,'%Y-%m-%d') as createDate,c.ApplyDT,c.AccountTitle,c.Reason,c.Amount,c.Status," .
    		" c.Teller,c.PayDT,c.ReceiptDT,c.Payee,c.ProjectNo,c.XmSid,c.XmFlag,c.PayType,c.IsFinRec,c.FinRecDT,c." .
    		" Area,c.BankNo,c.FileNames,c.BankAddress,c.appArea,c.loanNature,c.PrepaymentDate,c.Remark,c.belongcom," .
    		" c.belongcomcode,c.no_writeoff,c.projectId,c.projectCode,c.projectName,c.productLine,c.productArea,c.productCity,c.ExaStatus," .
    		" c.groupArea,c.productLineName,c.productAreaName,c.productCityName,c.prints,c.workArea,h.account as acccard,c.rendHouseStartDate,c.rendHouseEndDate,c.debtorDeptName,c.debtorDeptName as DebtorDeptName,c.debtorDeptCode,".
			" u.USER_NAME as DebtorName,c.belongcom as DebtorCompany,c.belongcomcode as DebtorCompanyCode,p.userNo as DebtorPersnlNum,p.deviceCode,p.deviceName".
			" from loan_list c".
			" left join hrms h on (c.debtor = h.user_id)" .
			" left join user u on (c.debtor = u.user_id)" .
			" left join oa_hr_personnel p on (c.debtor = p.userAccount)" .
    		"where 1=1 ",
	"count_all" => "select sum(c.Amount) AS Amount,sum(T.Money) as Money from loan_list c ".
		"LEFT JOIN (
				SELECT
					loan_ID AS id,
					sum(Money) AS Money
				FROM
					loan_repayment
				GROUP BY
					loan_ID
			) T ON T.id = c.id ".
		"left join hrms h on (c.debtor = h.user_id) ".
		"left join user u on (c.debtor = u.user_id) ".
		"left join oa_hr_personnel p on (c.debtor = p.userAccount) " .
		"where 1=1"
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id = #"
	),
	array (
		"name" => "idArr",
		"sql" => " and c.ID in(arr)"
	),
	array (
		"name" => "isTemp",
		"sql" => " and c.isTemp = #"
	),
	array (
		"name" => "Debtor",
		"sql" => " and c.Debtor = #"
	),
	array (
		"name" => "DebtorName",
		"sql" => " and u.USER_NAME = #"
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId = #"
	),array (
		"name" => "ProjectNo",
		"sql" => " and c.ProjectNo = #"
	),
	array (
		"name" => "Status",
		"sql" => " and c.Status = #"
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus = #"
	),
	array (
		"name" => "loanNature",
		"sql" => " and c.loanNature = #"
	),
	array(
		"name" => "XmFlag",
		"sql" => " and c.XmFlag = #"
	),
	array (
		"name" => "StatusArr",
		"sql" => " and c.Status in(arr)"
	),
	array (
		"name" => "notExaStatus",
		"sql" => " and c.ExaStatus <> #"
	),
	array (
		"name" => "DebtorNameSch",
		"sql" => " and u.USER_NAME like CONCAT('%',#,'%') "
	),
	array (
		"name" => "Reason",
		"sql" => " and c.Reason like CONCAT('%',#,'%') "
	),
	array (
		"name" => "ProjectNoSch",
		"sql" => " and c.ProjectNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "DebtorCompany",
		"sql" => " and c.belongcom = '#'"
	),
	array (
		"name" => "DebtorPersnlNum",
		"sql" => " and p.userNo = '#'"
	),
	array (
		"name" => "DebtorCompanySch",
		"sql" => " and c.belongcom like CONCAT('%',#,'%')"
	),
	array (
		"name" => "DebtorPersnlNumSch",
		"sql" => " and p.userNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "IDSch",
		"sql" => " and c.id like CONCAT('%',#,'%')"
	),
	array (
		"name" => "PrepaymentDateSch",
		"sql" => " and c.PrepaymentDate like CONCAT('%',#,'%')"
	),
	// 借款人归属部门（取最底层的部门）
	array(
		"name" => "DebtorDeptName",
		"sql" => " and c.debtorDeptName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "limitSql",
		"sql" => "$"
	),array (
		"name" => "emptyDebtorName",
		"sql" => " AND (c.debtorName is null or c.debtorName = '')"
	),array (
		"name" => "emptyDebtorDeptName",
		"sql" => " AND (c.debtorDeptName is null or c.debtorDeptName = '')"
	),array (
		"name" => "emptyDivisionName",
		"sql" => " AND (p.divisionName is null or p.divisionName = '')"
	),
);
?>
