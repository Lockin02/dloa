<?php
/**
 * @author zengqin
 * @Date 2015-2-12
 * @version 1.0
 * @description: ����Ԥ����ϸ�� ��¼���������Լ�ʡ�ݵ�Ԥ����Ϣ
 */
class model_finance_budget_budgetDetail extends model_base{

	function __construct() {
		$this->tbl_name = "oa_finance_budget_detail";
		$this->sql_map = "finance/budget/budgetDetailSql.php";
		parent::__construct ();
	}

	/**
	 * ����areaId����ݻ�ȡԤ����Ϣ
	 * @param year e.g:2015
	 * @param areaId
	 */
	 function getByParam($year,$areaId){
	 	$areaId = $this->checkArea($areaId);
		$conditions = array("year"=>$year,"areaId"=>$areaId);
		return $this->find($conditions);
	 }

	 /**
	 * ��ȡ��ǰ����
	 */
	function currentSeason(){
		$month = date("m",time());
		if($month<=3&&$month>=1){
			return 'first';
		}else if($month<=6&&$month>=4){
			return 'second';
		}else if($month<=9&&$month>=7){
			return 'third';
		}else if($month<=12&&$month>=10){
			return 'fourth';
		}
	}
	/**
	 * �ж������Ƿ�������ݱ�Ѷ������������ͳһ������ݱ�Ѷ
	 * @param areaId
	 * @return if(�������ڱ�Ѷ)return '999' else return areaIdԭ����ID
	 */
	 function checkArea($areaId){
		// ����ǹ��ݱ�Ѷ������������IdΪ999�����ݱ�Ѷͳһ������Ԥ��
		if(!empty($areaId)){
			$regionDao = new model_system_region_region();
			$region = $regionDao->get_d($areaId);
			if($region['businessBelongName']=='���ݱ�Ѷ'){
				return '999';
			}
		}
		return $areaId;
	 }
}
 ?>