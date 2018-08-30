<?php
/*
 * Created on 2011-10-29
 * 源单查询功能，即金蝶所谓的上查下查功能
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_common_search_searchSource extends model_base {
	function __construct(){
		include (WEB_TOR."model/common/search/searchSourceRegister.php");

		//对应源单匹配对象
		$this->sourceArr = isset($sourceArr) ? $sourceArr : null;

		parent::__construct ();
	}
}
?>
