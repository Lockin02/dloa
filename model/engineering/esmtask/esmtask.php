<?php
/*
 * Created on 2010-12-2
 * ��Ŀ����
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_esmtask_esmtask extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_task";
		$this->sql_map = "engineering/esmtask/esmtaskSql.php";
		parent::__construct ();


//		$this->datadictDao = new model_system_datadict_datadict ();
//		$this->statusDao = new model_common_status ();
//		$this->statusDao->status = array (array ("statusEName" => "done", "statusCName" => "�����", "key" => "1" ), array ("statusEName" => "doing", "statusCName" => "��ִ��", "key" => "2" )
//		, array ("statusEName" => "undo", "statusCName" => "δִ��", "key" => "3" ), array ("statusEName" => "audit", "statusCName" => "�����", "key" => "4" ) );

	}

	/**
	 * @desription ����������б��ȡ���ݷ���
	 * @param tags
	 * @date 2010-12-2 ����03:39:11
	 * @qiaolong
	 */
	function getdoneinfo() {
		$this->searchArr['status'] = "TG";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
	/**
	 * @desription ��ִ�������б��ȡ���ݷ���
	 * @param tags
	 * @date 2010-12-2 ����03:39:57
	 * @qiaolong
	 */
	function getdoinginfo () {
		$this->searchArr['status'] = "JXZ";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
	/**
	 * @desription δִ�������б��ȡ���ݷ���
	 * @param tags
	 * @date 2010-12-2 ����03:41:11
	 * @qiaolong
	 */
	function getundoinfo () {
		$this->searchArr['status'] = "WQD";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
	/**
	 * @desription ����������б��ȡ���ݷ���
	 * @param tags
	 * @date 2010-12-2 ����03:41:47
	 * @qiaolong
	 */
	function getauditinfo () {
		$this->searchArr['status'] = "WFB";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
 }
?>
