<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 16:08:18
 * @version 1.0
 * @description:��Ӧ����Ա��Ϣ���Ʋ�
 */
class controller_outsourcing_supplier_personnel extends controller_base_action {

	function __construct() {
		$this->objName = "personnel";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ӧ����Ա��Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    /**
	 * ��ת����Ӧ�������Ա��Ϣ�б�
	 */
    function c_toPageList() {
		$suppCode = $_SESSION['USER_ID'];
		$suppId = $this->service->get_table_fields('oa_outsourcesupp_supplib', "suppCode='".$suppCode."'", 'id');
		if($suppId){
		$this->assign('suppCode',$suppCode);
		$this->assign('suppId',$suppId);
		$this->view('page-list');
		}
		else
			msg("�������Ӧ����Ա��");
    }

	/**
	 * ��ת����Ӧ����Ա��Ϣ�б�
	 */
    function c_toEditList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('edit-list');
    }

    	/**
	 * ��ת����Ӧ����Ա��Ϣ�б�
	 */
    function c_toViewList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('view-list');
    }

   /**
	 * ��ת��������Ӧ����Ա��Ϣҳ��
	 */
	function c_toAdd() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 //��ȡ��Ӧ����Ϣ
		 $basicinfoDao=new model_outsourcing_supplier_basicinfo();
		 $suppInfo=$basicinfoDao->get_d($suppId);
		 $this->assign('suppId',$suppId);
		 $this->assign('suppCode',$suppInfo['suppCode']);
		 $this->assign('suppName',$suppInfo['suppName']);
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
//		$this->showDatadicts ( array ('levelCode' => 'WBRYJB' ));
//		$this->showDatadicts ( array ('skillTypeCode' => 'WBJNLX' ));

    	 $this->view ( 'add' );
   }

      /**
	 * ��ת��������Ӧ����Ա��Ϣҳ��
	 */
	function c_toListAdd() {
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
		$this->showDatadicts ( array ('levelCode' => 'WBRYJB' ));
		$this->showDatadicts ( array ('skillTypeCode' => 'WBJNLX' ));

    	 $this->view ( 'list-add' );
   }

   /**
	 * ��ת���༭��Ӧ����Ա��Ϣҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ),$obj['highEducation']);
		$this->showDatadicts ( array ('levelCode' => 'WBRYJB' ),$obj['levelCode']);
		$this->showDatadicts ( array ('skillTypeCode' => 'WBJNLX' ),$obj['skillTypeCode']);
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��Ӧ����Ա��Ϣҳ��
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
    * ��ת��������Ӧ����Աҳ��
    */
    function c_toExcellOut() {
    	$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
		$this->view( 'excellout' );
    }

   /**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

      	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '����ɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

		 /**
	 *��Ϣ�޸�
	 *
	 */
	 function c_edit(){
		$flag=$this->service->edit_d( $_POST [$this->objName]);
		if($flag){
			msgGo('����ɹ�');
		}else{
			msgGo('����ʧ��');

		}
	 }

	 	/**
	 * ����excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();
		
		$title = '��Ա��Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ����excell
	 */
	 function c_excellOut() {
	 	set_time_limit(0);
	 	$formData = $_POST[$this->objName];
	 	if(!empty($formData['suppName'])) //�����Ӧ��
			$this->service->searchArr['suppName'] = $formData['suppName'];

		if(!empty($formData['userName'])) //����
			$this->service->searchArr['userName'] = $formData['userName'];

		if(!empty($formData['age'])) //����
			$this->service->searchArr['age'] = $formData['age'];

		if(!empty($formData['mobile']))   //��ϵ�绰
			$this->service->searchArr['mobile'] = $formData['mobile'];

		if(!empty($formData['email']))  //����
			$this->service->searchArr['email'] = $formData['email'];

		if(!empty($formData['highEducation'])) {   //ѧ��
			$tmp_highEducation = $formData['highEducation'];
			for ($i = 0; $i < count($tmp_highEducation); $i++) {
				$tmp_highEducation[$i] = "'".$tmp_highEducation[$i]."'";
			}
			$this->service->searchArr['xueli'] = substr(implode(',' ,$tmp_highEducation),1,-1);
	 	}

		if(!empty($formData['highSchool'])) //��ҵѧУ
			$this->service->searchArr['highSchool'] = $formData['highSchool'];

		if(!empty($formData['professionalName'])) //רҵ
			$this->service->searchArr['professionalName'] = $formData['professionalName'];

		if(!empty($formData['identityCard'])) //���֤��
			$this->service->searchArr['identityCard'] = $formData['identityCard'];

		if(!empty($formData['workBeginDate'])) //��ʼ����ʱ��
			$this->service->searchArr['workBeginDate'] = $formData['workBeginDate'];

	 	if(!empty($formData['workYears'])) //�������Ź�������
			$this->service->searchArr['workYears'] = $formData['workYears'];

		if(!empty($formData['tradeList'])) //���̾����о�
			$this->service->searchArr['csjylj'] = $formData['tradeList'];

		if(!empty($formData['certifyList'])) { //��������
//			$tmp = explode(',', $formData['certifyList']);
//			for($i = 0; $i < count($tmp); $i++) {
//				$sql_tmp = " and certifyList like '%".$tmp[$i]."%') ";
//				$this->service->searchArr['certifyList'] = $tmp[$i];
//			}
//			$sql_tmp = "and  c.certifyList like '%".$tmp[0]."%'";
			$this->service->searchArr['certifyList'] = $formData['certifyList'];
			//var_dump($sql_tmp);exit();
		}

		if(!empty($formData['skillList']))  //��������
			$this->service->searchArr['jnlx'] = $formData['skillList'];

		$rows = $this->service->listBySqlId('select_excell');
		
		if (!$rows){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>" .
					"alert('û�м�¼!');self.parent.tb_remove();".
				 "</script>";
		}

		//���ͷ���������飬Ϊ����ʹ��ģ��
		$colArr  = array(
//			"suppName"=>"�����Ӧ��",
//			"userName"=>"����",
//			"age"=>"����",
//			"mobile"=>"��ϵ�绰",
//			"email"=>"����",
//			"highEducationName"=>"ѧ��",
//			"highSchool"=>"��ҵѧУ",
//			"professionalName"=>"רҵ",
//			"identityCard"=>"���֤��",
//			"workBeginDate"=>"��ʼ����ʱ��",
//			"workYears"=>"�������Ź�������",
//			"tradeList"=>"���̾����о�",
//			"certifyList"=>"��������",
//			"skillList"=>"��������"
		);
		$modelName = '��Ӧ����Ա��Ϣ';
	 	return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr, $rows, $modelName);
	 }
	 /*
	  * ��ת���������Ա������ҳ��
	  */
	  function c_toExcelImport(){
	  	$this->display('excelImport');
	  }
	  /*
	   * �������Ա������
	   */
	  function c_excelImport(){
		set_time_limit(0);
		$resultArr = $this->service->excelImport_d ();
		$title = '��Ա��Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	  }
	  /*
	  * ��ת���������Ա������ҳ��
	  */
	  function c_toExcelExport(){
		$suppName = $this->service->get_table_fields('oa_outsourcesupp_supplib', "suppCode='".$_SESSION['USER_ID']."'", 'suppName');
	  	$this->assign ('suppName',$suppName);
	  	$this->view('excelExport');
	  }
 }
?>