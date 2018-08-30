<?php
/**
 * @author Administrator
 * @Date 2011��11��22�� 17:08:26
 * @version 1.0
 * @description:���������Ͻ�����ÿ��Ʋ�
 */
class controller_projectmanagent_borrow_money extends controller_base_action {

	function __construct() {
		$this->objName = "money";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }


/***********************************�ͻ������� ������********��ʼ*********************************************/
	/**
	 * �ͻ������ý�����
	 */
	 function c_customerMoney(){
         $this->display("customermoney");
	 }
	 /*
	  * �ͻ������ý���������ҳ
	  */
	 function c_toAdd(){

        $dao = new model_system_region_region();
        $areaArr = $dao->list_d();
        $initArr = $this->service->initTable($areaArr);
        $this->assign("areaMoney" , $initArr[0] );
        $this->display('add');
	 }
     /*
      *�ͻ������ý������ ��ʼ��
      */
     function c_initMoney(){
         $object = $_POST [$this->objName];
		if ($this->service->initMoney_d ( $object )) {
			msgRF ( '��ʼ����ɣ�' );
		}
     }

     /*
      * �޸Ľ����תҳ
      */
     function c_editMoney(){
         $obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display("cus-editMoney");
     }

     /*
      * �޸Ľ��
      */
     function c_editM(){
         $object = $_POST [$this->objName];
		if ($this->service->editM_d ( $object )) {
			msgGo ( '�޸���ɣ�');
		}
     }



/***********************************�ͻ������� ������********����*********************************************/
/***********************************Ա�������� ������********��ʼ*********************************************/
	 /**
	  * Ա�������ý����� -- tabҳ
	  */
	  function c_proMoney(){
         $this->display("promoney");
	 }

	 //����
	 function c_prodept(){
	 	$this->display("prodept");
	 }
	 //��ɫ
	 function c_prorole(){
	 	$this->display("prorole");
	 }
	 //����
	 function c_propersonal(){
	 	$this->display("propersonal");
	 }

	 /*
	  * ����
	  */
	 function c_toProAdd(){
	 	$type = $_GET['type'];
	 	switch($type){
            case "dept": $this->assign("type" , "����");break;
            case "role": $this->assign("type" , "��ɫ");break;
            case "person": $this->assign("type" , "����");break;
	 	}
       $this->display('proadd');
	 }
	 /*
	  * �������淽��
	  */
	 function c_proadd(){
	        $object = $_POST [$this->objName];
			if ($this->service->add_d ( $object )) {
				msg ( '�����ɣ�');
			}
	 }
	 /**
	  * �޸���תҳ
	  */
	 function c_proeditMoney(){
	 	   $obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
           $this->display("proeditMoney");
	 }
	 /*
	  *�޸Ľ��
	  */
	 function c_proedit(){
	 	  $object = $_POST [$this->objName];
			if ($this->service->edit_d ( $object )) {
				msg ( '�޸���ɣ�');
			}
	 }

	 /**
	  * ajax ��֤���š���ɫ��Ա���Ƿ��ظ�
	  */
     function c_ajaxCheckingDept(){
         $name = $_POST['name'];
        $searchArr = array ("deptName" => $name );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
     }
     function c_ajaxCheckingRole(){
         $name = $_POST['name'];
        $searchArr = array ("roleName" => $name );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
     }
     function c_ajaxCheckingUser(){
         $name = $_POST['name'];
        $searchArr = array ("userName" => $name );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
     }
/***********************************Ա�������� ������********����*********************************************/
 }
?>