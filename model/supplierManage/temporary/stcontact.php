<?php
/**
 *��Ӧ����ϵ��model����
 */
class model_supplierManage_temporary_stcontact extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_cont_temp";
		$this->sql_map = "supplierManage/temporary/stcontactSql.php";
		parent::__construct ();
	}

	/**
	 * ���������id�����ӱ��parentId����ȡ����
	 */
	function getByid_d($parentId) {
		$parentId = isset($parentId)?$parentId:'';
		$sql = "select c.id,c.parentId,c.name,c.mobile1,c.mobile2,c.fax,c.plane,c.email,c.busiCode from  oa_supp_cont_temp c where c.parentId=" . "'" . $parentId . "'";
		$rows = $this->pageBySql($sql);
		return $rows;

	}


	/**
	 * @desription ���б�ҳ�����ݽ��й�����ʾ���鿴��ϵ�ˡ�
	 * @param tags
	 * @date 2010-11-17 ����06:59:42
	 */
	function pageParentId_d () {
//		return $this->searchArr('parentId');
		return $this->pageBySqlId('select_default');
	}
}
?>
