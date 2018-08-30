<?php
/**
 * @author zengqin
 * @Date 2015-2-12
 * @version 1.0
 * @description: 费用预算明细表 记录各个区域以及省份的预算信息
 */
class model_finance_budget_budgetDetail extends model_base{

	function __construct() {
		$this->tbl_name = "oa_finance_budget_detail";
		$this->sql_map = "finance/budget/budgetDetailSql.php";
		parent::__construct ();
	}

	/**
	 * 根据areaId，年份获取预算信息
	 * @param year e.g:2015
	 * @param areaId
	 */
	 function getByParam($year,$areaId){
	 	$areaId = $this->checkArea($areaId);
		$conditions = array("year"=>$year,"areaId"=>$areaId);
		return $this->find($conditions);
	 }

	 /**
	 * 获取当前季度
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
	 * 判断区域是否归属广州贝讯，如果是则决算统一算入广州贝讯
	 * @param areaId
	 * @return if(区域属于贝讯)return '999' else return areaId原区域ID
	 */
	 function checkArea($areaId){
		// 如果是广州贝讯的区域则区域Id为999，广州贝讯统一做费用预算
		if(!empty($areaId)){
			$regionDao = new model_system_region_region();
			$region = $regionDao->get_d($areaId);
			if($region['businessBelongName']=='广州贝讯'){
				return '999';
			}
		}
		return $areaId;
	 }
}
 ?>