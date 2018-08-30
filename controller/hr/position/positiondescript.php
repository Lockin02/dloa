<?php
/**
 * @author Administrator
 * @Date 2012��7��9�� ����һ 14:15:37
 * @version 1.0
 * @description:ְλ˵������Ʋ�
 */
class controller_hr_position_positiondescript extends controller_base_action {

	function __construct() {
		$this->objName = "positiondescript";
		$this->objPath = "hr_position";
		parent::__construct ();
	 }

	/**
	 * ��ת��ְλ˵�����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���Լ������ְλ˵�����б�
	 */
    function c_mypage() {
      $this->view('mylist');
    }

   /**
	 * ��ת������ְλ˵����ҳ��
	 */
	function c_toAdd() {
		//����������
		if (empty ( $_GET ['valPlus'] )) {
			$this->assign ( 'valPlus', '' );
		} else {
			$this->assign ( 'valPlus', $_GET ['valPlus'] );
		}
	     $this->view ( 'add' );
   }
	/**
	 * ��ת���鿴����ְλ˵����ҳ��
	 */
   function c_toRead() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
     $this->view ( 'read' );
   }
   /**
	 * ��ת���༭ְλ˵����ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
	$this->showDatadicts ( array ('rewardGradeCode' => 'HRGZJB' ),$obj['rewardGradeCode']);
	$this->showDatadicts ( array ('education' => 'HRJYXL' ),$obj['education']);
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴ְλ˵����ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

	/**
	 * ְλ˵�������
	 */
	function c_add($isAddInfo = false){
		$id = $this -> service -> add_d($_POST[$this -> objName], $isAddInfo);
		$position ['name']=$_POST[$this -> objName]['positionName'];
		$position ['id']=$id;
		if ($id) {
			if($position['valPlus']!=null)
				echo "<script>window.opener.jQuery('#valHidden" . $_POST ['valPlus'] . "').val('" . util_jsonUtil::encode ( $position ) . "');</script>";
			msg ( '����ɹ���' );
			echo "<script>window.close();</script>";
		} else {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312'' /><script>alert('����ʧ��!');window.close();</script></html>";
		}
	}
	/**
	 * ְλ˵�����޸�
	 */
	function c_edit() {
		$object = $_POST[$this -> objName];
		$id = $this -> service -> edit_d($object, true);
		if ($id) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312'' /><script >alert('�޸ĳɹ�!');window.close();</script></html>";
		} else {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312' /><script>alert('�޸�ʧ��!');</script></html>";
		}
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['createId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * �������Ƿ��ظ�
	 */
	function c_checkRepeat() {
		$checkId = "";
		$service = $this->service;
		if (isset ( $_REQUEST ['id'] )) {
			$checkId = $_REQUEST ['id'];
			unset ( $_REQUEST ['id'] );
		}
		if(!isset($_POST['validateError'])){
			$service->getParam ( $_REQUEST );
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			echo $isRepeat;
		}else{
			//����֤���
			$validateId=$_POST['validateId'];
			$validateValue=$_POST['validateValue'];
			$service->searchArr=array(
				$validateId."Eq"=>$validateValue
			);
			$result=array(
				'jsonValidateReturn'=>array($_POST['validateId'],$_POST['validateError'])
			);
			unset($_POST['validateId']);
			unset($_POST['validateValue']);
			if(is_array($_REQUEST)){
				foreach($_REQUEST as $key=>$val)
					$service->searchArr[$key]=$val;
			}

			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );

			if($isRepeat){
				$result['jsonValidateReturn'][2]="false";
			}else{
				$result['jsonValidateReturn'][2]="true";
			}
			echo util_jsonUtil::encode ( $result );
		}
	}

 }
?>