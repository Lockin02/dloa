<?php
/**
 * @author Administrator
 * @Date 2012��7��16�� 15:00:09
 * @version 1.0
 * @description:��������� Model��
 */
 class model_asset_basic_agency  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_agency";
		$this->sql_map = "asset/basic/agencySql.php";
		parent::__construct ();
	}
	/**
	 * @author ���ݸ����������
	 *
	 */
	 function getAgencyByUser_d($userId){
		$this->searchArr = array ('chargeId' => $userId );
		$row= $this->listBySqlId ();
		$areaStr='';
		$areaArr=array();
		if(is_array($row)){
			foreach($row as $key=>$val){
				$areaArr[$key]=$val['agencyCode'];
			}
			$areaStr=implode(',',$areaArr);
		}
		return $areaStr;
	 }

	function getByChargeId($userId){
		$this->searchArr['chargeId']=$userId;
		return $rows = $this->list_d();
	}
 }
?>