<?php
/**
 * @author Show
 * @Date 2012��2��21�� ���ڶ� 15:37:22
 * @version 1.0
 * @description:ǩԼ��˾ Model��
 */
 class model_contract_signcompany_signcompany  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_signcompany";
		$this->sql_map = "contract/signcompany/signcompanySql.php";
		parent::__construct ();
	}

	/**
	 * ����ǩԼ��˾
	 */
	function saveCompanyInfo_d($object){
		//��������
		$conditionArr = array('signCompanyName' => $object['signCompanyName']);
		//��ѯ�Ƿ����һ��������
		$rs = $this->find($conditionArr,null,'id');
		if(is_array($rs)){
			$object['id'] = $rs['id'];
			$this->edit_d($object,true);
		}else{
			$this->add_d($object,true);
		}
		return true;
	}
}
?>