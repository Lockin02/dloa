<?php
/**
 * @description: ��Ŀ����߼���ϢModel
 * @date 2010-9-14 ����02:49:24
 * @author huangzf
 * @version V1.0
 */
class model_rdproject_task_tkadvanced extends model_base {

	/**
	 * @desription ���캯��
	 * @author huangzf
	 * @date 2010-9-15 ����02:50:04
	 * @version V1.0
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_task_advanced";
		$this->sql_map = "rdproject/task/tkadvancedSql.php";
		parent::__construct ();
	}

	/* ---------------------------------ҳ��ģ����ʾ����------------------------------------------*/



	/* -----------------------------------ҵ��ӿڵ���-------------------------------------------*/
	/*
	 * ͨ��������Ŀ����id��������߼���Ϣ
	 */
	function getTkAdByPTId($tkBaseId){
		//return parent::echoSelect();
		$searchArr=array(
			"projectTaskId"=>$tkBaseId
		);
		$this->searchArr=$searchArr;
		$tkadvanceds=$this->listBySqlId();
		return $tkadvanceds[0];
	}

}
?>
