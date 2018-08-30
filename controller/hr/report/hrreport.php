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
	 * 内部推荐报表
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
	 * 内部推荐奖金报表
	 */
	function c_toRecombonusReport(){
		$recommendName=isset ( $_GET ['recommendName'] )?$_GET ['recommendName']:'';
		$isRecommendName=isset ( $_GET ['isRecommendName'] )?$_GET ['isRecommendName']:'';
		$positionName=isset ( $_GET ['positionName'] )?$_GET ['positionName']:'';
		$recruitManName=isset ( $_GET ['recruitManName'] )?$_GET ['recruitManName']:'';
		$postType=isset ( $_GET ['postType'] )?$_GET ['postType']:'';
		$developPositionName=isset ( $_GET ['developPositionName'] )?$_GET ['developPositionName']:'';
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//职位类型
		$this->assign('recommendName',$recommendName);
		$this->assign('isRecommendName',$isRecommendName);
		$this->assign('positionName',$positionName);
		$this->assign('developPositionName',$developPositionName);

		$this->view('recombonus');
	}

	/**
	 * 招聘计划报表
	 */
	function c_toApplyReport() {
		extract($_GET);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//职位需求类型
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//职位类型
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
	 * 招聘数据表
	 */
	function c_toRecruitmentReport(){
		extract($_GET);
		$userName=isset ( $_GET ['userName'] )?$_GET ['userName']:'';
		$deptName=isset ( $_GET ['deptName'] )?$_GET ['deptName']:'';
		$hrJobName=isset ( $_GET ['hrJobName'] )?$_GET ['hrJobName']:'';
		$recruitManName=isset ( $_GET ['createName'] )?$_GET ['createName']:'';
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//岗位类型
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//职位需求类型
		$this->assign('formDate',$formDate);  //入职年月
		$this->assign('userName',$userName); //姓名
		$this->assign('deptName',$deptName);  //部门
		$this->assign('hrJobName',$hrJobName);//职位
		$this->assign('workPlace',$workPlace); //工作地点
		$this->assign('createName',$createName); //招聘人员
		$this->assign('positionLevel',$positionLevel); //级别
		$this->assign('positionLevelId',$positionLevelId); //级别id
		$this->assign('postTypeName',$postTypeName); //级别id
		$this->assign('applyTypeName',$applyTypeName);
		$this->view('recruitment');
	}

	/**
	 * 报到明细表
	 */
	function c_toCheckinReport(){
		extract($_GET);
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//岗位类型
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//职位需求类型
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
	 * 岗位类型表
	 */
	function c_toJobtypeReport(){
		$startDate=isset ( $_GET ['startDate'] )?$_GET ['startDate']:date("Y-m").'-01';
		$endDate=isset ( $_GET ['endDate'] )?$_GET ['endDate']:date("Y-m-d");
		$postType=isset($_GET['postType'])?$_GET['postType']:null;
		$this->assign('startDate',$startDate);
		$this->assign('endDate',$endDate);
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//到岗类型
		$this->view('jobtype');
	}

	/**
	 * 入职分析表
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
	 * 招聘渠道分析表
	 */
	function c_toRecruitditchReport(){
		$startDate=isset ( $_GET ['startDate'] )?$_GET ['startDate']:date("Y-m-01");
		$endDate=isset ( $_GET ['endDate'] )?$_GET ['endDate']:date("Y-m-d");
		$hrSourceType1Name=isset($_GET['hrSourceType1Name'])?$_GET['hrSourceType1Name']:'';
		$createName=isset ( $_GET ['createName'] )?$_GET ['createName']:null;
		$this->showDatadictsByName ( array ('parentCode' => 'JLLY' ),$hrSourceType1Name);//招聘渠道
		$this->assign('startDate',$startDate);
		$this->assign('endDate',$endDate);
		$this->assign('createName',$createName);
		$this->view('recruitditch');
	}

	/**
	 * 招聘计划统计报表
	 */
	function c_toApplyStatisticsReport(){
		$this->assign('formDateSta' ,$_GET ['formDateSta']);
		$this->assign('formDateEnd',$_GET ['formDateEnd']);
		$this->view('applystatistics');
	}

	/**
	 * 增员申请招聘及时率报表
	 */
	function c_toTimelyRateReport(){
		extract($_GET);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//职位需求类型
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//职位类型
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
	 * 增员申请招聘及时率报表
	 */
	function c_toPersonTimelyRateReport(){
		extract($_GET);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$applyType);//职位需求类型
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$postType);//职位类型
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
