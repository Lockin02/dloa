<?php
/**
 *供应商联系人model层类
 */
class model_supplierManage_temporary_stcontact extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_cont_temp";
		$this->sql_map = "supplierManage/temporary/stcontactSql.php";
		parent::__construct ();
	}

	/**
	 * 根据主表的id（即从表的parentId）获取对象
	 */
	function getByid_d($parentId) {
		$parentId = isset($parentId)?$parentId:'';
		$sql = "select c.id,c.parentId,c.name,c.mobile1,c.mobile2,c.fax,c.plane,c.email,c.busiCode from  oa_supp_cont_temp c where c.parentId=" . "'" . $parentId . "'";
		$rows = $this->pageBySql($sql);
		return $rows;

	}


	/**
	 * @desription 对列表页的数据进行过渡显示。查看联系人。
	 * @param tags
	 * @date 2010-11-17 下午06:59:42
	 */
	function pageParentId_d () {
//		return $this->searchArr('parentId');
		return $this->pageBySqlId('select_default');
	}
}
?>
