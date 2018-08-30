<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 19:40:09
 * @version 1.0
 * @description:��ʦ���˱���Ʋ�
 */
class controller_hr_tutor_scheme extends controller_base_action {

	function __construct() {
		$this->objName = "scheme";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * ��ת����ʦ���˱��б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
    * ��ʦ����
    */
   function c_toTutorAssess(){
       $tutorId = $_GET['id'];
		//�ж��Ƿ��е�ʦ���˱�
		$sql = "select id  from oa_hr_tutor_scheme where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			$tutorrecordDao = new model_hr_tutor_tutorrecords();
			$obj = $tutorrecordDao->get_d ( $_GET ['id'] );
			//�����˺Ż�ȡ���ʻ�����Ϣ�����ÿ�ʼ/�������ڣ�
			$dao = new model_hr_personnel_personnel();
			$perInfo = $dao->getPersonnelInfo_d($obj['studentAccount']);
			$obj['beginDate'] = $perInfo['entryDate'];
	        $obj['endDate'] = $perInfo['becomeDate'];
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
	     	$this->view ( 'add' );
		} else {
            $obj=$this->service->get_d($flagArr[0]['id']);
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
	        $this->view ( 'view' );
		}
   }
   /**
    * �鿴��ʦ����
    */
   function c_toTutorAssessView(){
       $tutorId = $_GET['id'];
		//�ж��Ƿ��е�ʦ���˱�
		$sql = "select id  from oa_hr_tutor_scheme where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			 echo "<br><div style='text-align:center'><b>���޵�ʦ���˱�</b></div>";
		} else {
            $obj=$this->service->get_d($flagArr[0]['id']);
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
	        $this->view ( 'view' );
		}
   }
   /**
	 * ��ת��������ʦ���˱�ҳ��
	 */
	function c_toAdd() {
		$this->permCheck (); //��ȫУ��
		$tutorrecord=new controller_hr_tutor_tutorrecords();
		$obj = $tutorrecord->service->get_d ( $_GET ['id'] );
		//�����˺Ż�ȡ���ʻ�����Ϣ�����ÿ�ʼ/�������ڣ�
		$dao = new model_hr_personnel_personnel();
		$perInfo = $dao->getPersonnelInfo_d($obj['studentAccount']);
		$obj['beginDate'] = $perInfo['entryDate'];
        $obj['endDate'] = $perInfo['becomeDate'];
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
     	$this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ʦ���˱�ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��ʦ���˱�ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj=$this->service->get_d($_GET[id]);
	if(empty($obj)){
		echo "<br><div style='text-align:center'><b>�õ�ʦ���޵�ʦ������Ϣ</b></div>";
		exit;
	}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }


   /**
	 * ��ת���鿴��ʦ���˱�ҳ��
	 */
	function c_toRewardView() {
      $this->permCheck (); //��ȫУ��
      $schemeId=$this->service->get_table_fields($this->service->tbl_name, "tutorId='".$_GET['id']."'", 'id');
		$obj=$this->service->get_d($schemeId);
	if(empty($obj)){
		echo "<br><div style='text-align:center'><b>�õ�ʦ���޵�ʦ������Ϣ</b></div>";
		exit;
	}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

	/*
	* ��ת���鿴��ʦ���˱�ҳ��(hr)
	 */
	function c_toHRView() {
      $this->permCheck (); //��ȫУ��
      $tutorrecordDao=new controller_hr_tutor_tutorrecords();
		$obj=$tutorrecordDao->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }


   	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object, true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '���𿼺˳ɹ���';
		if ($id) {
			msg ( $msg );
		}
	}
   	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d( $object, $isEditInfo )) {
			if($this->service->checkComplete_d($object)){
               //���㵼ʦ�������ֲ���д���ֺ�״̬
               if($this->service->gradeEdit_d($object)){
                  msg ( '������ɣ�' );
               }
			}else{
               msg ( '����ɹ���' );
			}
		}
	}
	/*
	 * ��ת����ʦ�����б�
	 */
	 function c_toExaminelist(){
	 	$this->assign( 'userId',$_SESSION['USER_ID'] );
	 	$this->view('examine-list');
	 }

	 /*
	  * ����ҳ��
	  */
	function c_toScore(){
		$this->permCheck (); //��ȫУ��
		$tutorId = isset ($_GET['tutorId']) ? $_GET['tutorId'] : null;
		$role = $_GET['role'];
	  if(!empty($tutorId)){
         $sql = "select id  from oa_hr_tutor_scheme where tutorId=" . $tutorId . "";
		 $flagArr = $this->service->_db->getArray($sql);
		 if(empty ($flagArr[0]['id'])){
	      	 echo "<br><div style='text-align:center'><b>���޵�ʦ���˱���Ϣ</b></div>";
	      	 break;
	     }else{
	     	$id = $flagArr[0]['id'];
	     }
	  }else{
	  	 $id = $_GET['id'];
	  }
		$obj=$this->service->get_d($id);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign( 'userId',$_SESSION['USER_ID'] );
		switch($role){
			case "stu" : $urlType = "addscore-stu";break;
			case "tut" : $urlType = "addscore-tut";break;
			case "sup" : $urlType = "addscore-sup";break;
			case "dept" : $urlType = "addscore-dept";break;
			case "HR" : $urlType = "addscore-hr";break;
		}
		$this->view($urlType);
//		$this->view("addscore");
	}


	//�������ּ���
	function c_toScoreInfo() {
		 $id = $_POST['schemeId'];
         $service = $this->service;
         $service->searchArr['id'] = $id;
		 $rows = $service->list_d("select_default");
         echo util_jsonUtil::encode ( $rows );
	}


	 function c_pageJson() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';

		$rows = $service->pageBySqlId("select_examinelist");
       $schemeinfoDao=new model_hr_tutor_schemeinfo();


		if(is_array($rows)){
	        foreach($rows as $k => $v){
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$v['id']));
				if($schemeinfo['selfgraded']>0){
					$rows[$k]['tutorScore']='��';//��ʦ������
				}else{
					$rows[$k]['tutorScore']='��';
				}
				if($schemeinfo['staffgraded']>0){
					$rows[$k]['staffScore']='��';//ѧԱ������
				}else{
					$rows[$k]['staffScore']='��';
				}
				if($schemeinfo['superiorgraded']>0){
					$rows[$k]['supScore']='��';//�ϼ�������
				}else{
					$rows[$k]['supScore']='��';
				}
				if($schemeinfo['assistantgraded']>0){
					$rows[$k]['assistantScore']='��';//����������
				}else{
					$rows[$k]['assistantScore']='��';
				}
				if($schemeinfo['hrgraded']>0){
					$rows[$k]['hrScore']='��';//����������
				}else{
					$rows[$k]['hrScore']='��';
				}
	        }

		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

 }
?>