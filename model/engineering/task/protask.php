<?php
/*
 * Created on 2010-12-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * ��Ŀ���� model
 */
 class model_engineering_task_protask extends model_base {
	public $db;

	function __construct(){
		$this->tbl_name = "oa_esm_task";
		$this->sql_map = "engineering/task/protaskSql.php";
		parent::__construct();
	}
    function showlist($rows)
    {
		return '';
	}
      /*
       * ��������,������״̬��ΪWQD
      */
	function publishTask_d($id, $userId, $userName) {

		//$sql;
		$publishTask ['id'] = $id;
		$publishTask ['publishId'] = $userId;
		$publishTask ['publishName'] = $userName;
		$publishTask ['status'] = 'WQD';

		if (parent::updateById ( $publishTask ))
		{
			$tkinfo=$this->get_d($id);

			return "���񷢲��ɹ�!";
		}
		else
			return "���񷢲�ʧ��!";
	}
	  /*
       * �ύ����,������״̬��ΪDSH
       */
	function putTask_d($id, $userId, $userName) {

		//$sql;
		$putTask ['id'] = $id;
		$putTask ['publishId'] = $userId;
		$putTask ['publishName'] = $userName;
		$putTask ['status'] = 'DSH';

		if (parent::updateById ( $putTask ))
		{
			$tkinfo=$this->get_d($id);

			return "�ύ��˳ɹ�!";
		}
		else
			return "�ύ���ʧ��!";
	}
 }
?>

