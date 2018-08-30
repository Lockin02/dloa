<?php
/**
 * @author Administrator
 * @Date 2012��7��20�� ������ 11:33:19
 * @version 1.0
 * @description:�ڲ��Ƽ�������Ʋ�
 */
class controller_hr_recruitment_recomBonus extends controller_base_action {

	function __construct() {
		$this->objName = "recomBonus";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת���ڲ��Ƽ������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���ڲ��Ƽ������б�
	 */
	function c_myPage() {
		$this->assign('userid',$_SESSION['USER_ID']);
		$this->view('mylist');
	}

	/**
	 * ��ת�������ڲ��Ƽ�����ҳ��
	 */
	function c_toAdd() {
		$setit = new model_hr_recruitment_recommend();
		$resume = new model_hr_recruitment_resume();
		$uploadFile = new model_file_uploadfile_management ();
		$recominfo = $setit->get_d($_GET['recomid']);
		$resume->getParam ( array("myinnerId"=>$recominfo['id']) );
		$resumeinfo = $resume->page_d();
		foreach ( $recominfo as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//��ʾ������Ϣ
		$files = $uploadFile->getFilesByObjId ( $_GET ['recomid'], 'oa_hr_recruitment_recommend' );
		$fileStr='';
		if(is_array($files)){
			foreach($files as $fKey=>$fVal){
				$i=$fKey+1;
				//���븽����
				$fileArr['serviceType']="oa_hr_recommend_bonus";
				$fileArr['originalName']=$fVal['originalName'];
				$fileArr['newName']="oa_hr_recommend_bonus"."-".$fVal['newName'];
				$UPLOADPATH2=UPLOADPATH;
				$newPath=str_replace('\\','/',$UPLOADPATH2);
				$destDir=$newPath."oa_hr_recommend_bonus/";
				$fileArr['uploadPath']=$destDir;
				$fileArr['tFileSize']=$fVal['tFileSize'];
				$test = $uploadFile->add_d ( $fileArr, true );
				$fileStr.='<div class="upload" id="fileDiv'.$test.'"><a title="�������" href="?model=file_uploadfile_management&amp;action=toDownFileById&amp;fileId='.$test.'">'.$fVal['originalName'].'</a>&nbsp;<img src="images/closeDiv.gif" onclick="delfileById('.$test.')" title="���ɾ������"><div></div></div><input type="hidden" name="fileuploadIds['.$i.']" value="'.$test.'">';
			}
		}

		$this->show->assign("file",$fileStr);
		$this->assign ( "parentId", $recominfo['id'] );
		$this->assign ( "recommendCode", $recominfo['formCode'] );
		$this->assign ( "resumeId", $resumeinfo[0]['id'] );
		$this->assign ( "resumeCode", $resumeinfo[0]['resumeCode'] );
		$this->assign ( "formManName", $_SESSION["USERNAME"] );
		$this->assign ( "formManId", $_SESSION["USER_ID"] );
		$this->showDatadicts(array('job'=>'YPZW'));
		$this->view ('add' ,true);
	}

	/**
	 * ��ת���༭�ڲ��Ƽ�����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('job'=>'HRGWFL'));
		// $this->showDatadicts(array('job'=>'HRGWFL'),$obj['job']);
		$this->showDatadicts(array('job'=>'YPZW'),$obj['job']);
		$this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
		$this->view ('edit' ,true);
	}

	function c_postMail(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );

			if($folowInfo['examines']=="ok"){  //����ͨ����ָ����Ӧ��
				$obj = $this->service->get_d ( $folowInfo['objId'] );
				//�����ʼ�֪ͨ�ɹ�Ա
				$this->service->postMailto($obj);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$_POST[$this->objName]['jobName'] = $this->getDataNameByCode($_POST[$this->objName]['job']);
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
			echo "<script>history.back(-1);</script>";
		}
		//$this->listDataDict();
	}

	/**
	 * ��ת���鿴�ڲ��Ƽ�����ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴�ڲ��Ƽ�����ҳ��
	 */
	function c_toRead() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ( 'read' );
	}

	/**
	 * �޸Ķ���
	 */
	function c_handUp($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$object['state'] = 0;
		if ($this->service->add_d ( $object, $isAddInfo )) {
			msg ( '�ύ�ɹ���' );
		}
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
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

	/********************add chenrf 20150520***************/
	/**
	 * ��ת����Ӧ��������
	 */
	function c_redirectEwf(){
		if($_GET['id']==''){
			msg('�ύ���ݴ����޷�ָ����Ӧ������!');
			exit;
		}
		$url=$this->service->c_redirectEwf($_GET['id']);
		succ_show($url);
	}
	/**
	 * �ύ����
	 */
	function c_submit(){
		$obj['id']=$_GET['id'];
		$obj['state']=6;
		if($this->service->changeState($obj)){
			msg( '�ύ�ɹ���');
		}else{
			msg( '�ύʧ�ܣ�');
		}
	}
	/**
	 * ����ʱֱ���ύ
	 */
	function c_addSubmit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->addSubmit ( $object )) {
			msg ( '�ύ�ɹ���' );
		}else{
			msg( '�ύʧ�ܣ�');
		}
	}

}
?>