<?php
/**
 * @author Administrator
 * @Date 2013年12月15日 星期日 22:23:01
 * @version 1.0
 * @description:外包结算人员租赁 Model层
 */
 class model_outsourcing_account_persron  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_account_personrental";
		$this->sql_map = "outsourcing/account/persronSql.php";
		parent::__construct ();
	}

	function accountListJson_d($approvalId,$verifyIds){
		$sql="select
					 c.id,
					c.mainId,
					c.personLevel,
					c.personLevelName,
					c.pesonName,
					c.userAccount,
					c.userNo,
					c.suppName,
					c.suppId,
					c.inBudgetPrice,
					c.selfPrice,
					c.outBudgetPrice,
					'' as rentalPrice,
				v.userName,v.minDate as beginDate,v.maxDate as endDate,v.feedDay as totalDay from oa_outsourcing_approval_personrental c left join oa_outsourcing_account a on(a.id=c.mainId) left join (select projectCode,projectId, userName,min(if(beginDatePM,beginDatePM,beginDate)) as minDate,max(if(endDatePM,endDatePM,endDate)) as maxDate,sum(if(feeDayPM,feeDayPM,feeDay)) as feedDay from oa_outsourcing_workverify_detail where parentId in(".$verifyIds.") group by userName) v on(c.pesonName=v.userName) where c.mainId =".$approvalId;
			return $this->_db->getArray($sql);
	}
 }
?>