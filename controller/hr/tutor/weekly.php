<?php
/**
 * @author Administrator
 * @Date 2012-08-24 14:38:04
 * @version 1.0
 * @description:��Ա���ܱ�����Ʋ�
 */
class controller_hr_tutor_weekly extends controller_base_action {

	function __construct() {
		$this->objName = "weekly";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ա���ܱ����б�
	 */
    function c_page() {
       $this->assign('role' , $_GET['role']);
       $this->assign('tutorId',$_GET['tutorId']);
      $this->view('list');
    }

   /**
	 * ��ת��������Ա���ܱ���ҳ��
	 */
	function c_toAdd() {
		$this->assign('tutorId',$_GET['tutorId']);
	    $dao = new model_hr_tutor_tutorrecords();
	    $this->service->searchArr['tutorId']=$_GET['tutorId'];
	    $rows=$this->service->list_d();  // ��Ա���������ܱ�
	    $tutorInfo = $dao->get_d($_GET['tutorId']);

	    $newweek=$rows[0];  // ����ύ���ܱ�
	    $nullWeek=array("lastweekSummary"=>"","nextweekSummary"=>"","problem"=>"");   //Ա��û���ύ���ܱ�ʱ��Ϊ�˷�ֹҳ����ʾ{XXXX},Ū�˸��յ��ܱ�
	    if($rows){
	    	foreach ($newweek as $key => $val) {
				$this->assign($key, $val);
			}
	    }else{
	    	foreach ($nullWeek as $key => $val) {
				$this->assign($key, $val);
			}
	    }
	    foreach ($tutorInfo as $key => $val) {
				$this->assign($key, $val);
			}
    	 $this->view ('add' ,true);
   }

	/**
	 * ��ת������Ա���ܱ���ҳ��
	 */
	function c_toEditWeekly() {
	    $dao = new model_hr_tutor_tutorrecords();
	    $row= $this->service->get_d($_GET['id']);
	    $nullWeek=array("lastweekSummary"=>"","nextweekSummary"=>"","problem"=>"");   //Ա��û���ύ���ܱ�ʱ��Ϊ�˷�ֹҳ����ʾ{XXXX},Ū�˸��յ��ܱ�
	    if($row){
	    	foreach ($row as $key => $val) {
				$this->assign($key, $val);
			}
	    }else{
	    	foreach ($nullWeek as $key => $val) {
				$this->assign($key, $val);
			}
	    }
	    $tutorInfo = $dao->get_d($row['tutorId']);
	    unset($tutorInfo['id']);
	    foreach ($tutorInfo as $key => $val) {
				$this->assign($key, $val);
		}
    	 $this->view ('weekly-edit' ,true);
   }

	/**
	 * ��ת���༭��Ա���ܱ���ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$dao = new model_hr_tutor_tutorrecords();
		$tutorInfo = $dao->get_d($obj['tutorId']);

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
	  $this->assign('signDate',date('Y-m-d'));
	  $this->assign('studentSuperior',$tutorInfo['studentSuperior']);
      $this->view ('edit' ,true);
   }

	/**
	 * ��ת���鿴��Ա���ܱ���ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['createTime'] = date("Y-m-d",strtotime($obj['createTime']));
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
   }

	/**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$addType=isset($_GET['addType'])?$_GET['addType']:"";
		if($addType=='submit'){
			$_POST [$this->objName]['state'] = 1;
			$_POST [$this->objName]['submitDate'] = date("Y-m-d");
		}
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			if($addType=='submit'){
				msg ( '�ύ�ɹ�' );
			}else{
				msg ( '����ɹ�' );
			}
		}else{
			if($addType=='submit'){
				msg ( '�ύʧ��' );
			}else{
				msg ( '����ʧ��' );
			}

		}
	}

	/**
	 * �༭�������
	 */
	function c_editWeekly($isAddInfo = true) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$addType=isset($_GET['addType'])?$_GET['addType']:"";
		if($addType=='submit'){
			$_POST [$this->objName]['state'] = 1;
			$_POST [$this->objName]['submitDate'] = date("Y-m-d");
		}
		$id = $this->service->editWeekly_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			if($addType=='submit'){
				msg ( '�ύ�ɹ�' );
			}else{
				msg ( '����ɹ�' );
			}
		}else{
			if($addType=='submit'){
				msg ( '�ύʧ��' );
			}else{
				msg ( '����ʧ��' );
			}

		}
	}

	/**
	 * ��ȡԱ�������ܱ����� ���һ��:�Ƿ�������
	 */
	 function c_pageForRead(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->page_d();
		if(is_array($rows)){
			//��� �Ƿ������� һ��
			$rows = $service->pageForRead($rows);
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );

	 }

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�ύ�ɹ���' );
		}
	}
 }
?>