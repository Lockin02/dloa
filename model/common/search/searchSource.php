<?php
/*
 * Created on 2011-10-29
 * Դ����ѯ���ܣ��������ν���ϲ��²鹦��
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_common_search_searchSource extends model_base {
	function __construct(){
		include (WEB_TOR."model/common/search/searchSourceRegister.php");

		//��ӦԴ��ƥ�����
		$this->sourceArr = isset($sourceArr) ? $sourceArr : null;

		parent::__construct ();
	}
}
?>
