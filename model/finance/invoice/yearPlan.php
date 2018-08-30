<?php
/**
 * @author Show
 * @Date 2011��3��4�� ������ 10:07:57
 * @version 1.0
 * @description:����Ʊ��ȼ�¼�� Model��
 */
 class model_finance_invoice_yearPlan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invoice_yearPlan";
		$this->sql_map = "finance/invoice/yearPlanSql.php";
		parent::__construct ();
	}

	/**
	 * ������Ȼ�ȡ��Ʊ��ȼƻ��������ȼƻ������ڣ�����һ�������ΪȫΪ0 ������
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