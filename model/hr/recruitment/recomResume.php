<?php
/**
 * 1 �˲ų�ѡ
 2 ������
 3 ��̭
 4 ����ְ
 5 ����ְ
 6 ������ְ
 7 ������
 * @author Administrator
 * @Date 2012��7��17�� ���ڶ� 14:29:10
 * @version 1.0
 * @description:�ڲ��Ƽ������� Model��
 */
class model_hr_recruitment_recomResume  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recommend_resume";
		$this->sql_map = "hr/recruitment/recomResumeSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
		1 => array (
				'statusEName' => 'begin',
				'statusCName' => '�˲ų�ѡ',
				'key' => '1'
				),
				2 => array (
				'statusEName' => 'interviewing',
				'statusCName' => '������',
				'key' => '2'
				),
				3 => array (
				'statusEName' => 'getout',
				'statusCName' => '��̭',
				'key' => '3'
				),
				4 => array (
				'statusEName' => 'wait',
				'statusCName' => '����ְ',
				'key' => '4'
				),
				5 => array (
				'statusEName' => 'onwork',
				'statusCName' => '����ְ',
				'key' => '5'
				),
				6 => array (
				'statusEName' => 'giveup',
				'statusCName' => '������ְ',
				'key' => '6'
				),
				7 => array (
				'statusEName' => 'black',
				'statusCName' => '������',
				'key' => '7'
				)
				);
				parent::__construct ();
	}



	/**
	 * ��Ӷ���
	 */
	function add_recomd($object, $isAddInfo = false) {
		try{
			$this->start_d();
			$object['formCode'] = date ( "YmdHis" );
			$resume = new model_hr_recruitment_resume();
			$resume->add_d($object,true);
			$resumeinfo = $resume->find(array('applicantName'=>$object['applicantName']));
			//var_dump($resumeinfo);
			$recomobj = array();
			$recomobj = $resumeinfo;
			$recomobj['formCode'] = date ( "YmdHis" );
			$recomobj['parentId'] = $_POST['parentId'];
			$recomobj['resumeId'] = $resumeinfo['id'];
			$recomobj['state'] = $this->statusDao->statusEtoK ( 'begin' );
			$recomobj['id'] = NULL;
			$newId = parent::add_d($recomobj,true) ;
			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return $newId;
		}
	}
	/*
	 * �������
	 */
	function ajaxadd_d(){
		try{
			$info = explode( ",",$_POST ['id'] );
			$applyid = $_POST['applyid'];
			//var_dump($info);
			$resume = new model_hr_recruitment_resume();
				
			$this->start_d();
			for ($i=0; $i < count($info); $i++) {
				if($this->find(array("resumeId"=>$info[$i],'parentId'=>$applyid))){
					return 2;
				}
				if($this->find(array('parentId'=>$applyid))){
					return 3;
				}
				$temp = $resume->get_d($info[$i]);
				//var_dump($temp);
				$temp['formCode'] = date ( "YmdHis" );
				$temp['parentId'] = $applyid;
				$temp['resumeId'] = $temp['id'];
				$temp['state'] = $this->statusDao->statusEtoK ( 'begin' );
				$temp['id'] = NULL;
				$this->add_d($temp);

			}
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			return 0;
		}
		return 1;
	}

	/*
	 * ��ѯ����
	 */
	function checkit_d(){
		try{
			$info = explode( ",",$_POST ['id'] );
			$resume = new model_hr_recruitment_resume();
				
			$this->start_d();
			for ($i=0; $i < count($info); $i++) {
				if($resume->find(array("id"=>$info[$i],'resumeType'=>3))){
					return 1;
				}

			}
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			return 2;
		}
		return 0;
	}
}
?>