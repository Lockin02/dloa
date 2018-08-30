<?php
/*
 * Created on 2010-9-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class controller_rdproject_task_stophistory extends controller_base_action {

	/*
	 * @desription ���캯��
	 * @date 2010-9-27 ����02:31:46
	 */
	function __construct() {
		$this->objName = "stophistory";
		$this->objPath = "rdproject_task";
		parent::__construct ();
	}


	/*
	 * @desription ��ת���ҽ��ܵ�����������ͣҳ��
	 * @date 2011��8��8�� 13:45:17
	 * zengzx
	 */
	function c_toApplyToStop() {
			$this->show->assign("lastStatus",$_GET['status']);
			$this->show->assign("taskId",$_GET['id']);
			$taskDao = new model_rdproject_task_rdtask();
			$beginTime = $taskDao->get_table_fields( $taskDao->tbl_name,'id='.$_GET['id'],"planBeginDate" );
			$this->show->assign("beginTime",$beginTime);
			$this->display ( 'apply' );
	}

	/*
	 * @desription ��ת���ҷ����������ͣҳ��
	 * @date 2010-9-27 ����02:31:46
	 */
	function c_toStopTask() {
			$this->show->assign("lastStatus",$_GET['status']);
			$this->show->assign("taskId",$_GET['id']);
			$taskDao = new model_rdproject_task_rdtask();
			$beginTime = $taskDao->get_table_fields( $taskDao->tbl_name,'id='.$_GET['id'],"planBeginDate" );
			$this->show->assign("beginTime",$beginTime);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/*
	 * ��ת���ָ�����ҳ��
	 */
	 function c_toReBackTask(){
			$this->show->assign("taskId",$_GET['id']);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );

	 }


	/*
	 * ��ͣ����,����������ͣ��¼
	 */
	function c_add(){
		$stophistory=$_POST [$this->objName];
		if ($this->service->add_d($stophistory)) {
			msg ( '��������ͣ��' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task = $rdtaskDao->get_d ( $stophistory ['taskId'] );
			$task ['operType_'] = "������ͣ����";
			$task ['operateLog_'] = "����ԭ��".$stophistory['applyReason'];
			$this->service->tbl_name = $rdtaskDao->tbl_name;//����Ϊ����������루ʹ��������������룩
			$this->behindMethod ( $task );
		}
	}

	/*
	 * ��ͣ����,����������ͣ��¼
	 */
	function c_stop(){
		$stophistory=$_POST [$this->objName];
		if($stophistory['lastStatus'] == 'SQZT'){
			unset($stophistory['lastStatus']);
			if( $this->service->stopByApply_d($stophistory) ){
				msg('��ͣ�ɹ�');
			};
		}else{
			if ($this->service->stop_d($stophistory)) {
				msg ( '��ͣ�ɹ���' );
			}
		}
		$rdtaskDao=new model_rdproject_task_rdtask();
		$task = $rdtaskDao->get_d ( $stophistory ['taskId'] );
		$task ['operType_'] = "��ͣ����";
		$task ['operateLog_'] = "��ͣԭ��".$stophistory['stopReason'];
		$this->service->tbl_name = $rdtaskDao->tbl_name;//����Ϊ����������루ʹ��������������룩
		$this->behindMethod ( $task );
	}

	/*
	 * �ָ������޸���ͣ��¼
	 */
	 function c_edit(){
	 	$stophistory=$_POST [$this->objName];
	 	if ($this->service->edit_d($stophistory)) {
			msg ( '�ָ���ɣ�' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task = $rdtaskDao->get_d ( $stophistory ['taskId'] );
			$task ['operType_'] = "�ָ�����";
			$task ['operateLog_'] = "�ָ�����:".$stophistory['remark'];
			$this->service->tbl_name = $rdtaskDao->tbl_name;//����Ϊ����������루ʹ��������������룩
			$this->behindMethod ( $task );
		}
	 }

	/*
	 * �����ͣ����
	 */
	 function c_fightback(){
	 	$taskId = $_GET['id'];
	 	if ($this->service->fightback_d($taskId)) {
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task = $rdtaskDao->get_d ( $taskId );
			$task ['operType_'] = "�����ͣ����";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//����Ϊ����������루ʹ��������������룩
			$this->behindMethod ( $task );
			echo 1;
		}else{
			echo 0;
		}
	 }
 }
?>
