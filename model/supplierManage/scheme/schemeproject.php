<?php
/**
 * @author Administrator
 * @Date 2012年1月7日 14:57:01
 * @version 1.0
 * @description:评估项目 Model层
 */
 class model_supplierManage_scheme_schemeproject  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_assesproject";
		$this->sql_map = "supplierManage/scheme/schemeprojectSql.php";
		parent::__construct ();
	}

     //公司权限处理 TODO
     protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

 }
 ?>