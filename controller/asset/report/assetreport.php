<?php

class controller_asset_report_assetreport extends controller_base_action {

	function __construct() {
		$this->objName = "assetreport";
		$this->objPath = "asset_report";
		parent::__construct ();
	}

	/**
	 *
	 * 资产统计报表
	 */
	function c_toProAssetReport() {
		$assetTypeName=isset($_GET ['assetTypeName'])?$_GET ['assetTypeName']:null;
		$useStatusName=isset($_GET ['useStatusCode'])?$_GET ['useStatusCode']:null;
		$useStatusCode=isset($_GET ['useStatusName'])?$_GET ['useStatusName']:null;
		$assetSource=isset($_GET ['assetSource'])?$_GET ['assetSource']:null;
		$assetSourceName=isset($_GET ['assetSourceName'])?$_GET ['assetSourceName']:null;
		$orgName=isset($_GET ['orgName'])?$_GET ['orgName']:null;
		$orgId=isset($_GET ['orgId'])?$_GET ['orgId']:null;
		$useOrgName=isset($_GET ['useOrgName'])?$_GET ['useOrgName']:null;
		$useOrgId=isset($_GET ['useOrgId'])?$_GET ['useOrgId']:null;
		$userName=isset($_GET ['userName'])?$_GET ['userName']:null;
		$userId=isset($_GET ['userId'])?$_GET ['userId']:null;
		$this->assign ( 'assetSource', $assetSource );
		$this->assign ( 'useStatusCode', $useStatusCode );
		$this->assign ( 'assetTypeName', $assetTypeName );
		$this->assign ( 'useStatusName', $useStatusName );
		$this->assign ( 'assetSourceName', $assetSourceName );
		$this->assign ( 'orgName', $orgName );
		$this->assign ( 'orgId', $orgId );
		$this->assign ( 'useOrgName', $useOrgName );
		$this->assign ( 'useOrgId', $useOrgId );
		$this->assign ( 'userName', $userName );
		$this->assign ( 'userId', $userId );
		$this->display ( "assetsub" );
	}

	/**
	 *
	 * 资产统计报表查询页面
	 */
	function c_toProAssetSearch() {
		$assetTypeName=isset($_GET ['assetTypeName'])?$_GET ['assetTypeName']:null;
		$useStatusName=isset($_GET ['useStatusCode'])?$_GET ['useStatusCode']:null;
		$useStatusCode=isset($_GET ['useStatusName'])?$_GET ['useStatusName']:null;
		$assetSource=isset($_GET ['assetSource'])?$_GET ['assetSource']:null;
		$assetSourceName=isset($_GET ['assetSourceName'])?$_GET ['assetSourceName']:null;
		$orgName=isset($_GET ['orgName'])?$_GET ['orgName']:null;
		$orgId=isset($_GET ['orgId'])?$_GET ['orgId']:null;
		$useOrgName=isset($_GET ['useOrgName'])?$_GET ['useOrgName']:null;
		$useOrgId=isset($_GET ['useOrgId'])?$_GET ['useOrgId']:null;
		$userName=isset($_GET ['userName'])?$_GET ['userName']:null;
		$userId=isset($_GET ['userId'])?$_GET ['userId']:null;
		$this->showDatadicts ( array ('useOption' => 'SYZT' ),$useStatusCode);
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$assetSource );//资产来源 -- 数据字典
		$this->assign ( 'assetTypeName', $assetTypeName );
		$this->assign ( 'useStatusName', $useStatusName );
		$this->assign ( 'assetSourceName', $assetSourceName );
		$this->assign ( 'orgName', $orgName );
		$this->assign ( 'orgId', $orgId );
		$this->assign ( 'useOrgName', $useOrgName );
		$this->assign ( 'useOrgId', $useOrgId );
		$this->assign ( 'userName', $userName );
		$this->assign ( 'userId', $userId );
		$this->view ( "assetsub-search" );
	}

	/**
	 *
	 * 闲置资产统计表
	 */
	function c_toProAssetIdleReport() {
		$assetTypeName=isset($_GET ['assetTypeName'])?$_GET ['assetTypeName']:null;
		$this->assign ( 'assetTypeName', $assetTypeName );
		$this->display ( "assetIdlesub" );
	}

	/**
	 *
	 * 闲置资产统计表查询页面
	 */
	function c_toProAssetIdleSearch() {
		$assetTypeName=isset($_GET ['assetTypeName'])?$_GET ['assetTypeName']:null;
		$this->assign ( 'assetTypeName', $assetTypeName );
		$this->view ( "assetIdlesub-search" );
	}

	/**
	 *
	 * 调出资产统计表
	 */
	function c_toProAllocationReport() {
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:null;
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:null;
		$inDeptId=isset($_GET ['inDeptId'])?$_GET ['inDeptId']:null;
		$inDeptName=isset($_GET ['inDeptName'])?$_GET ['inDeptName']:null;
		$outDeptId=isset($_GET ['outDeptId'])?$_GET ['outDeptId']:null;
		$outDeptName=isset($_GET ['outDeptName'])?$_GET ['outDeptName']:null;
		$inProId=isset($_GET ['inProId'])?$_GET ['inProId']:null;
		$inProName=isset($_GET ['inProName'])?$_GET ['inProName']:null;
		$outProId=isset($_GET ['outProId'])?$_GET ['outProId']:null;
		$outProName=isset($_GET ['outProName'])?$_GET ['outProName']:null;

		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->assign ( 'inDeptId', $inDeptId );
		$this->assign ( 'inDeptName', $inDeptName );
		$this->assign ( 'outDeptId', $outDeptId );
		$this->assign ( 'outDeptName', $outDeptName );
		$this->assign ( 'inProId', $inProId );
		$this->assign ( 'inProName', $inProName );
		$this->assign ( 'outProId', $outProId );
		$this->assign ( 'outProName', $outProName );
		$this->display ( "allocationsub" );
	}

	/**
	 *
	 * 调出资产统计表查询页面
	 */
	function c_toProAllocationSearch() {
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:null;
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:null;
		$inDeptId=isset($_GET ['inDeptId'])?$_GET ['inDeptId']:null;
		$inDeptName=isset($_GET ['inDeptName'])?$_GET ['inDeptName']:null;
		$outDeptId=isset($_GET ['outDeptId'])?$_GET ['outDeptId']:null;
		$outDeptName=isset($_GET ['outDeptName'])?$_GET ['outDeptName']:null;
		$inProId=isset($_GET ['inProId'])?$_GET ['inProId']:null;
		$inProName=isset($_GET ['inProName'])?$_GET ['inProName']:null;
		$outProId=isset($_GET ['outProId'])?$_GET ['outProId']:null;
		$outProName=isset($_GET ['outProName'])?$_GET ['outProName']:null;

		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->assign ( 'inDeptId', $inDeptId );
		$this->assign ( 'inDeptName', $inDeptName );
		$this->assign ( 'outDeptId', $outDeptId );
		$this->assign ( 'outDeptName', $outDeptName );
		$this->assign ( 'inProId', $inProId );
		$this->assign ( 'inProName', $inProName );
		$this->assign ( 'outProId', $outProId );
		$this->assign ( 'outProName', $outProName );
		$this->view ( "allocation-search" );
	}

	/**
	 *
	 * 资产折旧统计表
	 */
	function c_toProDeprReport() {
		//年度
		//$years=isset($_GET ['years'])?$_GET ['years']:null;
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:"2011-09-01";
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:"2011-09-30";

		//$this->assign ( 'years', $years );
		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->display ( "deprsub" );
	}

	/**
	 *
	 * 资产折旧统计表查询页面
	 */
	function c_toProDeprSearch() {
		//年度
		//$years=isset($_GET ['years'])?$_GET ['years']:null;
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:"2011-09-01";
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:"2011-09-30";

		//$this->assign ( 'years', $years );
		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->view ( "depr-search" );
	}

	/**
	 *
	 * 报废资产统计表
	 */
	function c_toProScrapReport() {
		//年度
		//$years=isset($_GET ['years'])?$_GET ['years']:null;
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:"2011-09-01";
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:"2011-09-30";

		//$this->assign ( 'years', $years );
		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->display ( "scrapsub" );
	}

	/**
	 *
	 * 报废资产统计表查询页面
	 */
	function c_toProScrapSearch() {
		//年度
		//$years=isset($_GET ['years'])?$_GET ['years']:null;
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:"2011-09-01";
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:"2011-09-30";

		//$this->assign ( 'years', $years );
		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->view ( "scrap-search" );
	}


	/**
	 *
	 * 调出资产统计表
	 */
	function c_toProBorrowReport() {
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:null;
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:null;
		$assetId=isset($_GET ['assetId'])?$_GET ['assetId']:null;
		$assetCode=isset($_GET ['assetCode'])?$_GET ['assetCode']:null;
		$assetName=isset($_GET ['assetName'])?$_GET ['assetName']:null;
		$borrowDeptId=isset($_GET ['borrowDeptId'])?$_GET ['borrowDeptId']:null;
		$borrowDeptName=isset($_GET ['borrowDeptName'])?$_GET ['borrowDeptName']:null;
		$borrowId=isset($_GET ['borrowId'])?$_GET ['borrowId']:null;
		$borrowName=isset($_GET ['borrowName'])?$_GET ['borrowName']:null;

		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->assign ( 'assetId', $assetId );
		$this->assign ( 'assetCode', $assetCode );
		$this->assign ( 'assetName', $assetName );
		$this->assign ( 'borrowDeptId', $borrowDeptId );
		$this->assign ( 'borrowDeptName', $borrowDeptName );
		$this->assign ( 'borrowId', $borrowId );
		$this->assign ( 'borrowName', $borrowName );
		$this->display ( "borrowsub" );
	}

	/**
	 *
	 * 调出资产统计表查询页面
	 */
	function c_toProBorrowSearch() {
		$beginDate=isset($_GET ['beginDate'])?$_GET ['beginDate']:null;
		$endDate=isset($_GET ['endDate'])?$_GET ['endDate']:null;
		$assetId=isset($_GET ['assetId'])?$_GET ['assetId']:null;
		$assetCode=isset($_GET ['assetCode'])?$_GET ['assetCode']:null;
		$assetName=isset($_GET ['assetName'])?$_GET ['assetName']:null;
		$borrowDeptId=isset($_GET ['borrowDeptId'])?$_GET ['borrowDeptId']:null;
		$borrowDeptName=isset($_GET ['borrowDeptName'])?$_GET ['borrowDeptName']:null;
		$borrowId=isset($_GET ['borrowId'])?$_GET ['borrowId']:null;
		$borrowName=isset($_GET ['borrowName'])?$_GET ['borrowName']:null;

		$this->assign ( 'beginDate', $beginDate );
		$this->assign ( 'endDate', $endDate );
		$this->assign ( 'assetId', $assetId );
		$this->assign ( 'assetCode', $assetCode );
		$this->assign ( 'assetName', $assetName );
		$this->assign ( 'borrowDeptId', $borrowDeptId );
		$this->assign ( 'borrowDeptName', $borrowDeptName );
		$this->assign ( 'borrowId', $borrowId );
		$this->assign ( 'borrowName', $borrowName );
		$this->view ( "borrow-search" );
	}

	/***************************************S 固定资产相关报表***************************************/
	/**
	 * 全公司资产状态汇总报表
	 */
	function c_toCompanySummary() {
		$service = $this->service;
		//日期类型设置，默认为购置日期
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//开始日期设置，默认为2000年1月1号
		$this->assign('beginDate','2000-01-01');
		//结束日期设置，默认为当前日期
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//行政区域设置
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//用户设置
		$this->assign('userId',$_SESSION['USER_ID']);		
		//获取资产卡片相关权限
		$limits = $service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('companysummary');
	}
	
	/**
	 * 区域资产状态汇总报表
	 */
	function c_toAgencySummary() {
		$service = $this->service;
		//日期类型设置，默认为购置日期
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//开始日期设置，默认为2000年1月1号
		$this->assign('beginDate','2000-01-01');
		//结束日期设置，默认为当前日期
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//行政区域设置
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//用户设置
		$this->assign('userId',$_SESSION['USER_ID']);	
		//获取资产卡片相关权限
		$limits = $service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('agencysummary');
	}
	
	/**
	 * 公司年度新增资产分类统计报表
	 */
	function c_toAnnualSummary() {
		//日期类型，默认为购置日期
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//起止日期设置
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
	
		$this->view('annualsummary');
	}
	
	/**
	 * 公司年度报废资产分类统计报表
	 */
	function c_toScrapSummary() {
		//起止日期设置
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
	
		$this->view('scrapsummary');
	}
	
	/**
	 * 部门年度新增资产分类统计报表
	 */
	function c_toDeptAnnualSummary() {
		//日期类型，默认为购置日期
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//年份设置
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		if($_GET['deptDefault'] == 'none'){//无须设置默认部门
			$this->assign('deptId',$_GET['deptId']);
			$this->assign('deptName',$_GET['deptName']);
		}else{
			$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
			$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		}		
		//获取资产卡片相关权限
		$limits = $this->service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('deptannualsummary');
	}
	
	/**
	 * 部门资产汇总报表
	 */
	function c_toDeptSummary() {
		$service = $this->service;
		//日期类型设置，默认为购置日期
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//开始日期设置，默认为2000年1月1号
		$this->assign('beginDate','2000-01-01');
		//结束日期设置，默认为当前日期
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//日期格式
		$this->assign('dateFmt',$_GET['dateFmt'] ? $_GET['dateFmt'] : '');
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		if($_GET['deptDefault'] == 'none'){//无须设置默认部门
			$this->assign('deptId',$_GET['deptId']);
			$this->assign('deptName',$_GET['deptName']);
		}else{
			$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
			$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		}
		//获取资产卡片相关权限
		$limits = $service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
		
		$this->view('deptsummary');
	}
	
	/**
	 * 资产明细报表
	 */
	function c_toDetail() {
		$service = $this->service;
		//日期类型设置，默认为购置日期
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//开始日期设置，默认为2000年1月1号
		$this->assign('beginDate','2000-01-01');
		//结束日期设置，默认为当前日期
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//行政区域设置
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//资产名称
		$this->assign('assetName',$_GET['assetName'] ? $_GET['assetName'] : '');
		//资产状态
		$this->assign('useStatusName',$_GET['useStatusName'] ? $_GET['useStatusName'] : '');
		//用户设置
		$this->assign('userId',$_SESSION['USER_ID']);	
		//获取资产卡片相关权限
		$limits = $service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
		//部门权限，区域权限处理标识
		$this->assign('deptLimit',$_GET['deptLimit'] == 'none' ? $_GET['deptLimit'] : '');
		$this->assign('agencyLimit',$_GET['agencyLimit'] == 'none' ? $_GET['agencyLimit'] : '');
		
		$this->view('detail');
	}
	
	/**
	 * 二级部门资产明细报表
	 */
	function c_toDeptDetail() {
		//日期类型，默认为空
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : '');
		//起止日期，默认为空
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : '');
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : '');
		//日期格式
		$this->assign('dateFmt',$_GET['dateFmt'] ? $_GET['dateFmt'] : '');
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
	
		$this->view('deptdetail');
	}
	
	/**
	 * 三级部门资产明细报表
	 */
	function c_toSubDeptDetail() {
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//资产名称
		$this->assign('assetName',$_GET['assetName'] ? $_GET['assetName'] : '');
	
		$this->view('subdeptdetail');
	}
	
	/**
	 * 新增资产费用统计表
	 */
	function c_toDeptExpensesSummary() {
		//日期类型，默认为购置日期
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//起止日期设置
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : date('Y-m'));
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);	
		//获取资产卡片相关权限
		$limits = $this->service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('deptexpensessummary');
	}
	
	/**
	 * 区域新增资产费用统计表
	 */
	function c_toAgencyExpensesSummary() {
		//日期类型，默认为购置日期
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//起止日期设置
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : date('Y-m'));
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		//获取资产卡片相关权限
		$limits = $this->service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('agencyexpensessummary');
	}
	
	/**
	 * 年新增资产明细表
	 */
	function c_toYearDetail() {
		//日期类型，默认为购置日期
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//年份
		$this->assign('year',$_GET['year'] ? $_GET['year'] : date('Y'));
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		//行政区域设置
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//用户设置
		$this->assign('userId',$_SESSION['USER_ID']);
		//获取资产卡片相关权限
		$limits = $this->service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
		//部门权限，区域权限处理标识
		$this->assign('deptLimit',$_GET['deptLimit'] == 'none' ? $_GET['deptLimit'] : '');
		$this->assign('agencyLimit',$_GET['agencyLimit'] == 'none' ? $_GET['agencyLimit'] : '');
	
		$this->view('yeardetail');
	}
	
	/**
	 * 月新增资产明细表
	 */
	function c_toMonthDetail() {
		//日期类型，默认为购置日期
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//年份
		$this->assign('year',$_GET['year'] ? $_GET['year'] : date('Y'));
		//月份
		$this->assign('month',$_GET['month'] ? $_GET['month'] : date('m'));
		//公司设置
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//部门设置
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		//行政区域设置
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//用户设置
		$this->assign('userId',$_SESSION['USER_ID']);
		//获取资产卡片相关权限
		$limits = $this->service->getDeptLimit();
		//部门权限
		$deptLimit = $limits['部门权限'];
		$this->assign('deptIdStr',$deptLimit);
		//部门权限，区域权限处理标识
		$this->assign('deptLimit',$_GET['deptLimit'] == 'none' ? $_GET['deptLimit'] : '');
		$this->assign('agencyLimit',$_GET['agencyLimit'] == 'none' ? $_GET['agencyLimit'] : '');
	
		$this->view('monthdetail');
	}
	/***************************************E 固定资产相关报表***************************************/
}