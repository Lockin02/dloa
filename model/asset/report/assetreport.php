<?php

class model_asset_report_assetreport extends model_base {

	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取资产卡片相关权限
	 */
	function getDeptLimit(){
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('asset_assetcard_assetcard',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);

		return $sysLimit;
	}
	
	/**
	 * 获取资产卡片最早购置日期或入账日期
	 * @param $dateType为日期类型
	 */
	function getEarliestDate($dateType){
		$sql = "SELECT MIN(".$dateType.") AS ".$dateType." FROM oa_asset_card";
		$rs = $this->findSql($sql);
		
		return $rs[0][$dateType];
	}
}