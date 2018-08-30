<?php

class model_asset_report_assetreport extends model_base {

	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * ��ȡ�ʲ���Ƭ���Ȩ��
	 */
	function getDeptLimit(){
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('asset_assetcard_assetcard',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);

		return $sysLimit;
	}
	
	/**
	 * ��ȡ�ʲ���Ƭ���繺�����ڻ���������
	 * @param $dateTypeΪ��������
	 */
	function getEarliestDate($dateType){
		$sql = "SELECT MIN(".$dateType.") AS ".$dateType." FROM oa_asset_card";
		$rs = $this->findSql($sql);
		
		return $rs[0][$dateType];
	}
}