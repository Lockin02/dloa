<?php

/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description: ��Ŀ����߼���Ϣaction
 *
 */
class controller_rdproject_task_tkover extends controller_base_action {

	function __construct() {
		$this->objName = "tkover";
		$this->objPath = "rdproject_task";
		parent::__construct ();
	}

	/*
	 *��ת������ǿ����ֹҳ��
	 */
	function c_toAdd() {
		$this->show->assign ( "taskId", $_GET ['id'] );
		$this->showDatadicts ( array ('finishGrade' => 'XMRWZPDJ' ),'common' );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/*
	 * ����������ֹ��Ϣ
	 */
	function c_add() {
		$tkover = $_POST [$this->objName];
		$id=$this->service->add_d ( $tkover );
		/*�������������Ϊ100%*/
		$tkinfoDao=new model_rdproject_task_rdtask();
		$tkinfo['id']=$tkover['taskId'];
		$tkinfo['effortRate']=100;
		$tkinfoDao->updateById($tkinfo);
		if ($id) {
			msg ( '��ֹ�ɹ���' );
			$rdtaskDao = new model_rdproject_task_rdtask ();
			$task = $rdtaskDao->get_d ( $tkover ['taskId'] );
			$task ['operType_'] = "��ֹ����" . $task ['name'] . "��";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//����Ϊ����������루ʹ��������������룩
			$this->behindMethod ( $task );
		}
	}
	/**
	 * @desription ��ת���ҽ��ܵ������Ҽ��ύ����
	 * @param tags
	 * @date 2010-9-29 ����07:19:40
	 */
	function c_toSumbit() {
		$taskinfo = $this->service->getSubmitTkInfo_d ( $_GET ['id'] );
		$this->showDatadicts ( array ('selfGrade' => 'XMRWZPDJ' ) );
		foreach ( $taskinfo as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->assign( 'taskType',$this->getDataNameByCode( $taskinfo['taskType'] ) );
		$this->assign( 'priority',$this->getDataNameByCode( $taskinfo['priority'] ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-sumbit' );
	}
	/**
	 * @desription ��ת�����ҳ��
	 * @param tags
	 * @date 2010-10-2 ����04:33:25
	 */
	function c_toAudit() {
		//$tkover = $_POST [$this->objName];
		//$this->show->assign ( "taskId", $_GET ['taskId'] );
		$overinfo = $this->service->getOverLastByTkId ( $_GET ['taskId'] );
		$overinfo['selfGrade'] = $this->getDataNameByCode($overinfo['selfGrade']);
		$taskInfoDao = new model_rdproject_task_rdtask();
		foreach ( $overinfo as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('finishGrade' => 'XMRWZPDJ' ) );
		$tkinfo=$taskInfoDao->get_d( $_GET ['taskId']);
		$this->show->assign ( "informTime", $tkinfo['putWorkload'] );
		$this->show->assign ( "appraiseWorkload", $tkinfo['appraiseWorkload'] );//���ƹ�����
		$this->show->assign ( "actWorkload", $tkinfo['actWorkload'] );//ʵ�ʹ�����
		$this->show->display ( $this->objPath . '_' . $this->objName . '-audit' );
	}

	/**
	 * @desription ������Ϣ����
	 * @param tags
	 * @date 2010-10-2 ����04:50:04
	 */
	function c_addAuditTkinfo() {
		$tkauditinfo=$_POST [$this->objName];
		$id = $this->service->addAuditInfo_d ($tkauditinfo, true );
		if ($id) {
			msg ( '�����ɹ�' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task ['id']=$tkauditinfo['taskId'];
			$task ['operType_'] = "��������";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//����Ϊ����������루ʹ��������������룩
			$this->behindMethod ( $task );
		}
	}
	/**
	 * @desription �ύ���񱣴�����
	 * @param tags
	 * @date 2010-10-2 ����10:45:23
	 */
	function c_addSubmitTkinfo() {
		$tksubmitinfo=$_POST [$this->objName];
		$id = $this->service->addSubmitInfo_d ($tksubmitinfo , true );

		if ($id) {
			msg ( '�ύ����ɹ�' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task ['id']=$tksubmitinfo['taskId'];
			$task ['operType_'] = "�ύ����";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//����Ϊ����������루ʹ��������������룩
			$this->behindMethod ( $task );
		}
	}

}
?>
