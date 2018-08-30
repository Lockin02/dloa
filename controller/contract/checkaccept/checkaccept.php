<?php
/**
 * @author tse
 * @Date 2014��4��1�� 11:53:04
 * @version 1.0
 * @description:��ͬ���յ����Ʋ�
 */
class controller_contract_checkaccept_checkaccept extends controller_base_action {
	function __construct() {
		$this->objName = "checkaccept";
		$this->objPath = "contract_checkaccept";
		parent::__construct ();
	}

	/**
	 * ��ת����ͬ���յ��б�
	 */
	function c_page() {
		if($_GET['identify']=='contractTool'){
			$this->assign ( 'checkStatus', 'δ����' );
		}
		else{
			$this->assign ( 'checkStatus', '' );
		}
		$this->view ( 'list' );
	}

	/**
	 * ��дpageJson
	 */
	function c_pageJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ();

		/**
		 * ������ʾ
		 */
		foreach ($rows as $key => $val){
			$rows[$key]['checkFile'] = $this->service->getFilesByObjId ( $val['id'], false,$this->service->tbl_name );
            //��ȡԤ��ʱ��
            $checkDate = $this->service->handleCheckDT($rows[$key]['clause'],$rows[$key]['days'],$rows[$key]['contractId']);

			if($rows[$key]['checkDate'] == '' || $rows[$key]['checkDate'] == '0000-00-00'){
//
				$rows[$key]['checkDate'] = $checkDate;
//                if(!empty($checkDate)){
//                    $rows[$key]['realEndDateView'] = date("Y-m-d",strtotime($checkDate)-$rows[$key]['days']*24*60*60);
//                }
//
			}
//else{
//                $rows[$key]['realEndDateView'] = $val['realEndDate'];
//            }
            $rows[$key]['realEndDateView'] = $val['completeDate'];
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ���������������
	 */
	function c_editlistJson(){
		$this->service->getParam($_POST);
		$this->service->asc = false;
		$arr = $this->service->list_d("select_clause");
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��ת��������ͬ���յ�ҳ��
	 */
	function c_toAdd() {
		$this->assign ( 'contractCode', $_GET ['contractCode'] );
		$this->assign ( 'contractId', $_GET ['contractId'] );
		$this->view ( 'add' );
	}

	/**
	 * ��д��������(non-PHPdoc)
	 *
	 * @see controller_base_action::c_add()
	 */
	function c_add() {
		$msg= $this->service->add_d($_POST);
		if($msg){
			msg('��ӳɹ�!');
		}
	}

	/**
	 * ��ת���༭��ͬ���յ�ҳ��
	 */
	function c_toEdit() {
		$this->assign ( 'contractCode', $_GET ['contractCode'] );
		$this->assign ( 'contractId', $_GET ['contractId'] );
		$this->view ( 'edit' );
	}

	/**
	 * ��дedit����(non-PHPdoc)
	 * @see controller_base_action::c_edit()
	 */
	function c_edit(){
		$msg = $this->service->edit_d($_POST);
		if($msg){
			msg("���³ɹ�");
		}else{
			msg("����ʧ��");
		}
	}

	/**
	 * ��ת���鿴��ͬ���յ�ҳ��
	 */
	function c_toView() {
		$this->permCheck (); // ��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ȷ��
	 */
	function c_confirm(){
		echo $this->service->confirm_d($_POST);
	}

/**
	 * ���
	 */
	function c_change(){
		echo $this->service->change_d($_POST);
	}


	/**
	 * ��ת���ϴ�����ҳ��
	 */
	function c_toUploadFile(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file',$this->service->getFilesByObjId ( $obj['id'], true,$this->service->tbl_name )) ;
		$this->assignFunc($obj);
		$this->view('uploadfile');
	}

	/**
	 * ����
	 */
	function c_check(){
		echo $this->service->check_d(util_jsonUtil::iconvUTF2GBArr($_POST));
	}

	//��ȡ�����Ϣ
	function c_showChanceHistory(){
		$id = $_GET['id'];
		$info = $this->service->getChanceHistory_d($id);
		$this->assign("info", $info);
		$this->view("showChanceHistory");
	}

}
?>