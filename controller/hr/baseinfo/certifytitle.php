<?php
/**
 * @author Show
 * @Date 2012��9��7�� 17:14:21
 * @version 1.0
 * @description:��ְ�ʸ��ν����Ʋ�
 */
class controller_hr_baseinfo_certifytitle extends controller_base_action {

	function __construct() {
		$this -> objName = "certifytitle";
		$this -> objPath = "hr_baseinfo";
		parent::__construct();
	}

	/*
	 * ��ת����ְ�ʸ��ν���б�
	 */
	function c_page() {
		$this -> view('list');
	}

	/**
	 * ��ת��������ְ�ʸ��ν��ҳ��
	 */
	function c_toAdd() {
		$remarkstr =<<<EOT
		<option title="�ر�" value="0">�ر�</option>
		<option title="����" value="1">����</option>
EOT;
		$this->assign("remarkinfo",$remarkstr);
		$this -> view('add');
	}

	/**
	 * ��ת���༭��ְ�ʸ��ν��ҳ��
	 */
	function c_toEdit() {
		$this -> permCheck();
		//��ȫУ��
		$obj = $this -> service -> get_d($_GET['id']);

		foreach ($obj as $key => $val) {
			$this -> assign($key, $val);
		}

		if($obj['status']==0){
			$remarkstr =<<<EOT
		<option title="�ر�" value="0" selected>�ر�</option>
		<option title="����" value="1">����</option>
EOT;
		}else{
			$remarkstr =<<<EOT
		<option title="�ر�" value="0">�ر�</option>
		<option title="����" value="1" selected>����</option>
EOT;
		}
		$this->assign("remarkinfo",$remarkstr);
		$this->showDatadicts ( array ('careerDirection' => 'HRZYFZ' ), $obj ['careerDirection'] );
		$this->showDatadicts ( array ('baseLevel' => 'HRRZJB' ), $obj ['baseLevel'] );
		$this->showDatadicts ( array ('baseGrade' => 'HRRZZD' ), $obj ['baseGrade'] );
		$this -> view('edit');
	}

	/**
	 * ��ת���鿴��ְ�ʸ��ν��ҳ��
	 */
	function c_toView() {
		$this -> permCheck();
		//��ȫУ��
		$obj = $this -> service -> get_d($_GET['id']);
		$obj['status'] = $this -> service -> statusDao ->statusKtoC($obj['status']);
		foreach ($obj as $key => $val) {
			$this -> assign($key, $val);
		}

		$this -> view('view');
	}
	//�б�json����
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach ($rows as $key => $value) {
				$rows[$key]['statusCN'] = $this -> service -> statusDao ->statusKtoC($rows[$key]['status']);
			}

		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>