<?php
/*
 * Created on 2012-10-31
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_hr_report_hrreport extends controller_base_action {

	function __construct() {
		$this->objName = "hrreport";
		$this->objPath = "hr_report";
		parent::__construct ();
	}

	/**
	 * �ڲ��Ƽ�����
	 */
	function c_toRecommendReport(){
		$recommendName=isset ( $_GET ['recommendName'] )?$_GET ['recommendName']:'';
		$isRecommendName=isset ( $_GET ['isRecommendName'] )?$_GET ['isRecommendName']:'';
		$positionName=isset ( $_GET ['positionName'] )?$_GET ['positionName']:'';
		$recruitManName=isset ( $_GET ['recruitManName'] )?$_GET ['recruitManName']:'';

		$this->assign('recommendName',$recommendName);
		$this->assign('isRecommendName',$isRecommendName);
		$this->assign('positionName',$positionName);
		$this->assign('recruitManName',$recruitManName);
		$this->view('recommend');
	}

	/**
	 * �ڲ��Ƽ����𱨱�
	 */
	function c_toRecombonusReport(){
		$recommendName=isset ( $_GET ['recommendName'] )?$_GET ['recommendName']:'';
		$isRecommendName=isset ( $_GET ['isRecommendName'] )?$_GET ['isRecommendName']:'';
		$positionName=isset ( $_GET ['positionName'] )?$_GET ['positionName']:'';
		$recruitManName=isset ( $_GET ['recruitManName'] )?$_GET ['recruitManName']:'';
		$postType=isset ( $_GET ['postType'] )?$_GET ['postType']:'';
		$developPositionName=isset ( $_GET ['developPositionName'] )?$_GET ['developPositionName']:'';
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//ְλ����
		$this->assign('recommendName',$recommendName);
		$this->assign('isRecommendName',$isRecommendName);
		$this->assign('positionName',$positionName);
		$this->assign('developPositionName',$developPositionName);

		$this->view('recombonus');
	}

	/**
	 * ��Ƹ�ƻ�����
	 */
	function c_toApplyReport() {
		extract($_GET);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//ְλ��������
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//ְλ����
		$this->assign('postTypeName',$postTypeName?$postTypeName:'');
		$this->assign('deptName',$deptName?$deptName:'');
		$this->assign('positionName',$positionName?$positionName:'');
		$this->assign('recruitManName',$recruitManName?$recruitManName:'');
		$this->assign('useAreaName',$useAreaName?$useAreaName:'');
		$this->assign('workPlace',$workPlace?$workPlace:'');
		$this->assign('projectGroup',$projectGroup?$projectGroup:'');
		$this->assign('resumeToName',$resumeToName?$resumeToName:'');
		$this->assign('positionLevel',$positionLevel?$positionLevel:'');
		$this->assign('state',$state?$state:'');
		$this->view('apply');
	}

	/**
	 * ��Ƹ���ݱ�
	 */
	function c_toRecruitmentReport(){
		extract($_GET);
		$userName=isset ( $_GET ['userName'] )?$_GET ['userName']:'';
		$deptName=isset ( $_GET ['deptName'] )?$_GET ['deptName']:'';
		$hrJobName=isset ( $_GET ['hrJobName'] )?$_GET ['hrJobName']:'';
		$recruitManName=isset ( $_GET ['createName'] )?$_GET ['createName']:'';
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//��λ����
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//ְλ��������
		$this->assign('formDate',$formDate);  //��ְ����
		$this->assign('userName',$userName); //����
		$this->assign('deptName',$deptName);  //����
		$this->assign('hrJobName',$hrJobName);//ְλ
		$this->assign('workPlace',$workPlace); //�����ص�
		$this->assign('createName',$createName); //��Ƹ��Ա
		$this->assign('positionLevel',$positionLevel); //����
		$this->assign('positionLevelId',$positionLevelId); //����id
		$this->assign('postTypeName',$postTypeName); //����id
		$this->assign('applyTypeName',$applyTypeName);
		$this->view('recruitment');
	}

	/**
	 * ������ϸ��
	 */
	function c_toCheckinReport(){
		extract($_GET);
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//��λ����
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//ְλ��������
		$this->assign('deptName',$deptName);
		$this->assign('hrJobName',$hrJobName);
		$this->assign('developPositionName',$developPositionName);
		$this->assign('userName',$userName);
		$this->assign('applyType',$applyType);
		$this->assign('projectGroup',$projectGroup);
		$this->assign('workPlace',$workPlace);
		$this->assign('hrSourceType1Name',$hrSourceType1Name);
		$this->assign('hrSourceType2Name',$hrSourceType2Name);
		$this->assign('sysCompanyName',$sysCompanyName);
		$this->assign('postTypeName',$postTypeName);
		$this->view('checkin');
	}

	/**
	 * ��λ���ͱ�
	 */
	function c_toJobtypeReport(){
		$startDate=isset ( $_GET ['startDate'] )?$_GET ['startDate']:date("Y-m").'-01';
		$endDate=isset ( $_GET ['endDate'] )?$_GET ['endDate']:date("Y-m-d");
		$postType=isset($_GET['postType'])?$_GET['postType']:null;
		$this->assign('startDate',$startDate);
		$this->assign('endDate',$endDate);
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//��������
		$this->view('jobtype');
	}

	/**
	 * ��ְ������
	 */
	function c_toEntrynumReport(){
		$searchYear=isset ( $_GET ['startDate'] )?$_GET ['startDate']:date("Y-m-01");
		$searchMonth=isset ( $_GET ['endDate'] )?$_GET ['endDate']:date("Y-m-d");
		$userName = isset($_GET ['createName'])?$_GET ['createName']:NULL;
		$this->assign('startDate',$searchYear);
		$this->assign('endDate',$searchMonth);
		$this->assign('createName',$userName);
		$this->view('entrynum');
	}

	/**
	 * ��Ƹ����������
	 */
	function c_toRecruitditchReport(){
		$startDate=isset ( $_GET ['startDate'] )?$_GET ['startDate']:date("Y-m-01");
		$endDate=isset ( $_GET ['endDate'] )?$_GET ['endDate']:date("Y-m-d");
		$hrSourceType1Name=isset($_GET['hrSourceType1Name'])?$_GET['hrSourceType1Name']:'';
		$createName=isset ( $_GET ['createName'] )?$_GET ['createName']:null;
		$this->showDatadictsByName ( array ('parentCode' => 'JLLY' ),$hrSourceType1Name);//��Ƹ����
		$this->assign('startDate',$startDate);
		$this->assign('endDate',$endDate);
		$this->assign('createName',$createName);
		$this->view('recruitditch');
	}

	/**
	 * ��Ƹ�ƻ�ͳ�Ʊ���
	 */
	function c_toApplyStatisticsReport(){
		$this->assign('formDateSta' ,$_GET ['formDateSta']);
		$this->assign('formDateEnd',$_GET ['formDateEnd']);
		$this->view('applystatistics');
	}

	/**
	 * ��Ա������Ƹ��ʱ�ʱ���
	 */
	function c_toTimelyRateReport(){
		extract($_GET);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//ְλ��������
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//ְλ����
		$startDate=isset ( $_GET ['startDate'] )?$_GET ['startDate']:date("Y-m").'-01';
		$endDate=isset ( $_GET ['endDate'] )?$_GET ['endDate']:date("Y-m-d");
		$this->assign('startDate',$startDate);
		$this->assign('endDate',$endDate);
		$this->assign('postTypeName',$postTypeName?$postTypeName:'');
		$this->assign('deptName',$deptName?$deptName:'');
		$this->assign('recruitManName',$recruitManName?$recruitManName:'');
		$this->view('timelyrate');
	}

	/**
	 * ��Ա������Ƹ��ʱ�ʱ���
	 */
	function c_toPersonTimelyRateReport(){
		extract($_GET);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//ְλ��������
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//ְλ����
		$startDate=isset ( $_GET ['startDate'] )?$_GET ['startDate']:date("Y-m").'-01';
		$endDate=isset ( $_GET ['endDate'] )?$_GET ['endDate']:date("Y-m-d");
		$this->assign('startDate',$startDate);
		$this->assign('endDate',$endDate);
		$this->assign('postTypeName',$postTypeName?$postTypeName:'');
		$this->assign('deptName',$deptName?$deptName:'');
		$this->assign('recruitManName',$recruitManName?$recruitManName:'');
		$this->view('timelyrate-person');
	}
}
?>
