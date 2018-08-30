<?php
/**
 * @author Administrator
 * @Date 2012年7月17日 星期二 10:43:19
 * @version 1.0
 * @description:增员申请简历库 Model层 
 */
 class model_hr_recruitment_applyResume  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_apply_resume";
		$this->sql_map = "hr/recruitment/applyResumeSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			1 => array (
				'statusEName' => 'begin',
				'statusCName' => '人才初选',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'interviewing',
				'statusCName' => '面试中',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'getout',
				'statusCName' => '淘汰',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'wait',
				'statusCName' => '待入职',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'onwork',
				'statusCName' => '已入职',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'giveup',
				'statusCName' => '放弃入职',
				'key' => '6'
			),
			7 => array (
				'statusEName' => 'black',
				'statusCName' => '黑名单',
				'key' => '7'
			)
		);
		parent::__construct ();
	}
	/**
	 * 添加对象
	 */
	function add_applyd($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$object['formCode'] = date ( "YmdHis" );
		$resume = new model_hr_recruitment_resume();
		$resume->add_d($object,true);
		$resumeinfo = $resume->find(array('applicantName'=>$object['applicantName']));
		//var_dump($resumeinfo);
		$applyobj = array();
		$applyobj = $resumeinfo;
		$applyobj['formCode'] = date ( "YmdHis" );
		$applyobj['parentId'] = $_POST['parentId'];
		$applyobj['resumeId'] = $resumeinfo['id'];
		$applyobj['state'] = $this->statusDao->statusEtoK ( 'begin' );
		$applyobj['id'] = NULL;
		
		//加入数据字典处理 add by chengl 2011-05-15
		$newId = $this->create ( $applyobj );
		return $newId;
	}
 }
?>