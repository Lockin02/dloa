<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:22:42
 * @version 1.0
 * @description:��Ʒ����������Ʋ�
 */
class controller_stockup_application_application extends controller_base_action {

	function __construct() {
		$this->objName = "application";
		$this->objPath = "stockup_application";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ʒ���������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������Ʒ��������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add',true );
   }

   /**
	 * ��ת���༭��Ʒ��������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ((array) $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
	$this->assign ( "appList", $this->service->details( $_GET ['id']));
      $this->view ( 'edit',true);
   }

   /**
	 * ��ת���鿴��Ʒ��������ҳ��
	 */
	function c_toView() {
      	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "appList", $this->service->details( $_GET ['id']));

		if($_GET['actType']=='audit'){
			$this->assign ( 'button', '' );
		}else{
			$this->assign ( 'button', '<input  type="button" class="txt_btn_a" value=" ��  �� " onclick="closeFun();"/>' );
		}
      $this->view ( 'view' );
   }
    /**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object,$object['auditType'] == 'audit'?true:false);
		if ($id) {
            if($object['auditType'] == 'audit'){
            	succ_show('controller/stockup/application/ewf_index.php?actTo=ewfSelect&billId=' . $id .'&flowMoney=6&billDept='.$object['appDeptId'] );
            	}else{
                msgGo("����ɹ�","?model=stockup_apply_apply&action=appList");
            }
		} else {
			msgGo("����ʧ��");
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object);
		if ($rs) {
            if($object['auditType'] == 'audit'){
            	succ_show('controller/stockup/application/ewf_index.php?actTo=ewfSelect&billId=' .$object['id'] .'&flowMoney=6&billDept='.$object['appDeptId'] );

            }else{
                msg("����ɹ�");
            }
		} else {
			msg('����ʧ��');
		}
	}

	/**
    *���˱��
    */
   function c_personList(){
   		$this->view('personList');
   }

   /**
	 * ��񷽷�
	 */
	function c_personListJson(){
		$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('personList');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 *
	 * delete
	 */
   function c_delete(){
		$service = $this->service;
		echo $service->deletes_d($_POST);

	}
	/**
	 * ��ת�������������뵼��ҳ��
	 */
	 function c_toExport(){
		$this->view('export');
	 }
	 /**
	  * �����������뵼��
	  */
	  function c_export(){
		$row = $_POST['application'];
	   	set_time_limit(0);
	   	$service = $this->service;
	 	if(trim($row['listNo'])){ //������
			$service->searchArr['listNoS'] = $row['listNo'];
	 	}
	 	if(trim($row['createName'])){ //��������
			$service->searchArr['createNameS'] = $row['createName'];
	 	}
	 	if(trim($row['batchNum'])){ //���κ�
			$service->searchArr['batchNumS'] = $row['batchNum'];
	 	}
	 	if(trim($row['createTimeS'])){ //����ʱ��
			$service->searchArr['createTimeS'] = $row['createTimeS'];
	 	}
	 	if(trim($row['createTimeE'])){ //����ʱ��
			$service->searchArr['createTimeE'] = $row['createTimeE'];
	 	}
	 	if(trim($row['ExaStatus'])){ //����״̬
			$service->searchArr['ExaStatus'] = $row['ExaStatus'];
	 	}
	 	$rows = $service->list_d('personList');
	 	$exportDatas = array();
		foreach($rows as $key =>$val){
			$exportDatas[$key]['listNo'] = $val['listNo'];
			$exportDatas[$key]['createName'] = $val['createName'];
			$exportDatas[$key]['batchNum'] = $val['batchNum'];
			$exportDatas[$key]['createTime'] = substr($val['createTime'],0,10);
			$exportDatas[$key]['ExaStatus'] = $val['ExaStatus'];
		}
		$colArr  = array(
		);
		$modelName = '�����������뵼��';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$exportDatas, $modelName);
	  }

	 /**
	 * ��ת�����������б���ҳ��
	 */
	 function c_toExportList(){
		$this->view('exportList');
	 }
	 /**
	  * �����������뵼��
	  */
	  function c_exportList(){
		$row = $_POST['application'];
	   	set_time_limit(0);
	   	$service = $this->service;
	 	if(trim($row['listNo'])){ //������
			$service->searchArr['listNoS'] = $row['listNo'];
	 	}
	 	if(trim($row['createName'])){ //��������
			$service->searchArr['createNameS'] = $row['createName'];
	 	}
	 	if(trim($row['batchNum'])){ //���κ�
			$service->searchArr['batchNumS'] = $row['batchNum'];
	 	}
	 	if(trim($row['createTimeS'])){ //����ʱ��
			$service->searchArr['createTimeS'] = $row['createTimeS'];
	 	}
	 	if(trim($row['createTimeE'])){ //����ʱ��
			$service->searchArr['createTimeE'] = $row['createTimeE'];
	 	}
	 	if(trim($row['ExaStatus'])){ //����״̬
			$service->searchArr['ExaStatus'] = $row['ExaStatus'];
	 	}
	 	$rows = $service->list_d();
	 	$exportDatas = array();
		foreach($rows as $key =>$val){
			$exportDatas[$key]['listNo'] = $val['listNo'];
			$exportDatas[$key]['createName'] = $val['createName'];
			$exportDatas[$key]['batchNum'] = $val['batchNum'];
			$exportDatas[$key]['createTime'] = substr($val['createTime'],0,10);
			$exportDatas[$key]['ExaStatus'] = $val['ExaStatus'];
		}
		$colArr  = array(
		);
		$modelName = '���������б���';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$exportDatas, $modelName);
	  }
 }
?>