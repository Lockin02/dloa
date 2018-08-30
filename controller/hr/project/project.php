<?php
/**
 * @author Administrator
 * @Date 2012-05-30 19:25:55
 * @version 1.0
 * @description:��Ŀ�������Ʋ�
 */
class controller_hr_project_project extends controller_base_action {

	function __construct() {
		$this->objName = "project";
		$this->objPath = "hr_project";
		parent::__construct ();
	 }

	/*
	 * ��ת����Ŀ�����б�
	 */
    function c_page() {
      $this->view('list');
    }
	/**
	 * ������ϢTabҳ�б�
	 */
	function c_tabList(){
		$this->assign("userId",$_GET['userAccount']);
		$this->assign("userNo",$_GET['userNo']);
		$this->view('tablist');
	}
   /**
	 * ��ת��������Ŀ����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

	/**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
   /**
	 * ��ת���༭��Ŀ����ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��Ŀ����ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   	/**
	 * �б�߼���ѯ
	 */
	function c_toSearch(){
        $this->view('search');
	}


	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$title = '��Ŀ���鵼�����б�';
		$thead = array( '������Ϣ','������' );
		$objNameArr = array (
			0 => 'userNo', //Ա�����
			1 => 'userName', //����
			2 => 'projectName', //��Ŀ����
			3 => 'projectPlace', //��Ŀ�ص�
		    4 => 'projectManager', //��Ŀ����
			5 => 'beginDate', //�μ���Ŀ��ʼʱ��
			6 => 'closeDate', //�μ���Ŀ����ʱ��
			7 => 'projectRole', //����Ŀ�еĽ�ɫ
			8 => 'projectContent', //����Ŀ�еĹ�������
		       );
        $resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/**
	 * ��������
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['project']['listSql']))));
		$this->view('excelout-select');

	}

	/**
	 * ��������
	 */
	function c_selectExcelOut(){
		$rows=array();//���ݼ�
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		$colNameArr=array();//��������
		include_once ("model/hr/project/projectFieldArr.php");
		if(is_array($_POST['project'])){
			foreach($_POST['project'] as $key=>$val){
					foreach($projectFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
		$newColArr=array_combine($_POST['project'],$colNameArr);//�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['project']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutProject($newColArr,$dataArr);
	}
	/******************* E ���뵼��ϵ�� ************************/
 }
?>