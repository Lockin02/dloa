<?php
/**
 * @author chengl
 * @Date 2012��8��20�� 10:57:17
 * @version 1.0
 * @description:�̵���Ϣ ���Ʋ�
 */
class controller_hr_inventory_inventory extends controller_base_action {

	function __construct() {
		$this->objName = "inventory";
		$this->objPath = "hr_inventory";
		parent :: __construct();
	}
	/*
	 * �鿴�����̵�
	 */
	function c_page() {
		$stageId = $_GET['stageId'];
		$this->assign ( "stageId", $stageId );
		$this->view('list');
	}

	/*
	 * ��ת����д�ܽ���Ϣҳ��
	 */
	function c_toeditsummary(){
		$stageId=$_GET['stageId'];
		$this->assign ( "stageId", $stageId );
		$obj = $this->service->getByStageAndDept ($stageId,$_SESSION['DEPT_ID']);
		$stageDao=new model_hr_inventory_stageinventory();
		$stage=$stageDao->get_d($stageId);
		$this->assign ( "templateId", $stage['templateId']);
		if(is_array($obj)){
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
		}else{
			$this->assign ( "deptName", $_SESSION['DEPT_NAME'] );
			$this->assign ( "deptId", $_SESSION['DEPT_ID'] );
			$this->assign ( "inventoryName", $stage['inventoryName']);
			$this->assign ( "remark", "");
			$this->assign ( "id", "");
		}
		$question=$this->service->trtd($stage['templateId'],$obj['id']);
		$this->assign ( "question", $question );
		$this->view('editsummary');
	}

	/*
	 * �ܽ�༭����
	 */
	function c_editsummary(){
		$obj=$_POST[$this->objName];
		if($_POST['save']){
			$isSave=true;
		}else{
			$isSave=false;
		}
		$id=$this->service->editsummary_d($obj,$isSave);
		if($id){
			msg("����ɹ�");
		}
	}

	/*
	 * ���ݽ׶��̵�ID����ܽ����Ե�stateֵ
	 */
	function c_getState(){
		$id=$_POST['id'];
		$state=$this->service->getState_d($id);
		echo $state;
	}

	/*
    * �����̵�ID��ò����ܽ���Ϣ
    */
	function c_viewSummaryInfo(){
		$inventoryId=$_GET['id'];
		$summaryDao=new model_hr_inventory_inventorysummaryvalue();
		$summaryArr=$summaryDao->findAll(array("inventoryId"=>$inventoryId));
		$count=1;
		$summary="";
		if($summaryArr){
			foreach($summaryArr as $key =>$val){
				$summary.="<tr><td class=form_text_left_three></td><td class=form_text_right_three colspan='3' align='left'>" .$count.".   "."<span style=color:blue>$val[question]</span><br/><textarea readonly='readonly' class=txt_txtarea_biglong>$val[answer]</textarea></td></tr>";
				$count++;
			}
		}else{
			$summary="<tr><td align=center style=font-size:20px>�����ܽ���Ϣ</td><tr>";
		}
		$this->assign("summary",$summary);
		$this->view('summaryview');
	}



	/**
	 * ��ʼ��
	 */
	function c_init() {
		$stageId=$_GET['stageId'];
		if(empty($_GET['id'])){
			$this->assign ( "stageId", $stageId );
			$obj = $this->service->getByStageAndDept ($stageId,$_SESSION['DEPT_ID']);
		}else{
			$obj=$this->service->get_d($_GET['id']);
			$stageId=$obj['stageId'];
		}
		$stageDao=new model_hr_inventory_stageinventory();
		$stage=$stageDao->get_d($stageId);
		$this->assign ( "templateId", $stage['templateId']);
		if(is_array($obj)){
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
		}else{
			$this->assign ( "deptName", $_SESSION['DEPT_NAME'] );
			$this->assign ( "deptId", $_SESSION['DEPT_ID'] );
			$this->assign ( "inventoryName", $stage['inventoryName']);
			$this->assign ( "remark", "");
			$this->assign ( "id", "");

		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

}
?>
