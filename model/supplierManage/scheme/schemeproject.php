<?php
/**
 * @author Administrator
 * @Date 2012��1��7�� 14:57:01
 * @version 1.0
 * @description:������Ŀ Model��
 */
 class model_supplierManage_scheme_schemeproject  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_assesproject";
		$this->sql_map = "supplierManage/scheme/schemeprojectSql.php";
		parent::__construct ();
	}

     //��˾Ȩ�޴��� TODO
     protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

 }
 ?>