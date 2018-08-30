<?php
/**
 * @author Show
 * @Date 2012��3��29�� ������ 9:41:06
 * @version 1.0
 * @description:�������ñ� Model��
 */
 class model_system_stamp_stampconfig  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_stamp_config";
		$this->sql_map = "system/stamp/stampconfigSql.php";
		parent::__construct ();
	}

	//������״̬
	function rtStampStatus_d($val){
		if($val == 1){
			return '����';
		}else{
			return '�ر�';
		}
	}

	/**
	 * ���ظ�������
	 */
	function getStampType_d(){
		$this->searchArr['status'] = 1;
		$this->sort = "c.stampName";
		$this->asc = false;
		$rs = $this->listBySqlId('select_forOption');
		return $rs;
	}

	/**
	 * ���ظ����˸������� - ���ڸ���������벻ͬ��ɫ������
	 */
	function getStampTypeList_d($userId){
		$this->searchArr['findPrincipalId'] = $userId;
//		$this->searchArr['status'] = 1; (��Ϊ���������б�,���������ݹرպ�����Ӧ�ĸ��¼�¼�޷���ʾ,������������鿴�����ѹرո��µĸ��¼�¼)
		return $this->listBySqlId('select_forOption');
	}
}
?>