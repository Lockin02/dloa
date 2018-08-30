<?php
/**
 * @author Show
 * @Date 2011年3月4日 星期五 10:07:57
 * @version 1.0
 * @description:财务开票额度记录表 Model层
 */
 class model_finance_invoice_yearPlan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invoice_yearPlan";
		$this->sql_map = "finance/invoice/yearPlanSql.php";
		parent::__construct ();
	}

	/**
	 * 根据年度获取开票额度计划，如果额度计划不存在，返回一个金额数为全为0 的数组
	 */
	function getYearPlan_d(){
		$rows = $this->find(array( 'year' => date('Y')));
		if(empty($rows)){
			$rows = array( 'year' => date('Y'),
				'salesOne' => 0 ,'salesTwo' => 0 ,
				'salesThree' => 0 ,'salesFour' => 0 ,
				'salesAll' => 0 ,'serviceAll' => 0 ,
				'serviceOne' => 0 ,'serviceTwo' => 0 ,
				'serviceThree' => 0 ,'serviceFour' => 0 ,
			);
		}
		return $rows;
	}
 }
?>