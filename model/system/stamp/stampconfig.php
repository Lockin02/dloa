<?php
/**
 * @author Show
 * @Date 2012年3月29日 星期四 9:41:06
 * @version 1.0
 * @description:盖章配置表 Model层
 */
 class model_system_stamp_stampconfig  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_stamp_config";
		$this->sql_map = "system/stamp/stampconfigSql.php";
		parent::__construct ();
	}

	//返回章状态
	function rtStampStatus_d($val){
		if($val == 1){
			return '开启';
		}else{
			return '关闭';
		}
	}

	/**
	 * 返回盖章类型
	 */
	function getStampType_d(){
		$this->searchArr['status'] = 1;
		$this->sort = "c.stampName";
		$this->asc = false;
		$rs = $this->listBySqlId('select_forOption');
		return $rs;
	}

	/**
	 * 返回负责人盖章数组 - 用于盖章申请进入不同角色的桌面
	 */
	function getStampTypeList_d($userId){
		$this->searchArr['findPrincipalId'] = $userId;
//		$this->searchArr['status'] = 1; (因为盖章配置有变,旧配置数据关闭后导致相应的盖章记录无法显示,所以现在允许查看个人已关闭盖章的盖章记录)
		return $this->listBySqlId('select_forOption');
	}
}
?>