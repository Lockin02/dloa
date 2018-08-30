<?php

class controller_asset_report_assetreport extends controller_base_action {

	function __construct() {
		$this->objName = "assetreport";
		$this->objPath = "asset_report";
		parent::__construct ();
	}

	/**
	 *
	 * �ʲ�ͳ�Ʊ���
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
	 * �ʲ�ͳ�Ʊ����ѯҳ��
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
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$assetSource );//�ʲ���Դ -- �����ֵ�
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
	 * �����ʲ�ͳ�Ʊ�
	 */
	function c_toProAssetIdleReport() {
		$assetTypeName=isset($_GET ['assetTypeName'])?$_GET ['assetTypeName']:null;
		$this->assign ( 'assetTypeName', $assetTypeName );
		$this->display ( "assetIdlesub" );
	}

	/**
	 *
	 * �����ʲ�ͳ�Ʊ��ѯҳ��
	 */
	function c_toProAssetIdleSearch() {
		$assetTypeName=isset($_GET ['assetTypeName'])?$_GET ['assetTypeName']:null;
		$this->assign ( 'assetTypeName', $assetTypeName );
		$this->view ( "assetIdlesub-search" );
	}

	/**
	 *
	 * �����ʲ�ͳ�Ʊ�
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
	 * �����ʲ�ͳ�Ʊ��ѯҳ��
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
	 * �ʲ��۾�ͳ�Ʊ�
	 */
	function c_toProDeprReport() {
		//���
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
	 * �ʲ��۾�ͳ�Ʊ��ѯҳ��
	 */
	function c_toProDeprSearch() {
		//���
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
	 * �����ʲ�ͳ�Ʊ�
	 */
	function c_toProScrapReport() {
		//���
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
	 * �����ʲ�ͳ�Ʊ��ѯҳ��
	 */
	function c_toProScrapSearch() {
		//���
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
	 * �����ʲ�ͳ�Ʊ�
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
	 * �����ʲ�ͳ�Ʊ��ѯҳ��
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

	/***************************************S �̶��ʲ���ر���***************************************/
	/**
	 * ȫ��˾�ʲ�״̬���ܱ���
	 */
	function c_toCompanySummary() {
		$service = $this->service;
		//�����������ã�Ĭ��Ϊ��������
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//��ʼ�������ã�Ĭ��Ϊ2000��1��1��
		$this->assign('beginDate','2000-01-01');
		//�����������ã�Ĭ��Ϊ��ǰ����
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//������������
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//�û�����
		$this->assign('userId',$_SESSION['USER_ID']);		
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('companysummary');
	}
	
	/**
	 * �����ʲ�״̬���ܱ���
	 */
	function c_toAgencySummary() {
		$service = $this->service;
		//�����������ã�Ĭ��Ϊ��������
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//��ʼ�������ã�Ĭ��Ϊ2000��1��1��
		$this->assign('beginDate','2000-01-01');
		//�����������ã�Ĭ��Ϊ��ǰ����
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//������������
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//�û�����
		$this->assign('userId',$_SESSION['USER_ID']);	
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('agencysummary');
	}
	
	/**
	 * ��˾��������ʲ�����ͳ�Ʊ���
	 */
	function c_toAnnualSummary() {
		//�������ͣ�Ĭ��Ϊ��������
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//��ֹ��������
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
	
		$this->view('annualsummary');
	}
	
	/**
	 * ��˾��ȱ����ʲ�����ͳ�Ʊ���
	 */
	function c_toScrapSummary() {
		//��ֹ��������
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
	
		$this->view('scrapsummary');
	}
	
	/**
	 * ������������ʲ�����ͳ�Ʊ���
	 */
	function c_toDeptAnnualSummary() {
		//�������ͣ�Ĭ��Ϊ��������
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//�������
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		if($_GET['deptDefault'] == 'none'){//��������Ĭ�ϲ���
			$this->assign('deptId',$_GET['deptId']);
			$this->assign('deptName',$_GET['deptName']);
		}else{
			$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
			$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		}		
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $this->service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('deptannualsummary');
	}
	
	/**
	 * �����ʲ����ܱ���
	 */
	function c_toDeptSummary() {
		$service = $this->service;
		//�����������ã�Ĭ��Ϊ��������
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//��ʼ�������ã�Ĭ��Ϊ2000��1��1��
		$this->assign('beginDate','2000-01-01');
		//�����������ã�Ĭ��Ϊ��ǰ����
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//���ڸ�ʽ
		$this->assign('dateFmt',$_GET['dateFmt'] ? $_GET['dateFmt'] : '');
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		if($_GET['deptDefault'] == 'none'){//��������Ĭ�ϲ���
			$this->assign('deptId',$_GET['deptId']);
			$this->assign('deptName',$_GET['deptName']);
		}else{
			$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
			$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		}
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
		
		$this->view('deptsummary');
	}
	
	/**
	 * �ʲ���ϸ����
	 */
	function c_toDetail() {
		$service = $this->service;
		//�����������ã�Ĭ��Ϊ��������
		$dateType = $_GET['dateType'] ? $_GET['dateType'] : 'buyDate';
		$this->assign('dateType',$dateType);
		//��ʼ�������ã�Ĭ��Ϊ2000��1��1��
		$this->assign('beginDate','2000-01-01');
		//�����������ã�Ĭ��Ϊ��ǰ����
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : day_date);
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//������������
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//�ʲ�����
		$this->assign('assetName',$_GET['assetName'] ? $_GET['assetName'] : '');
		//�ʲ�״̬
		$this->assign('useStatusName',$_GET['useStatusName'] ? $_GET['useStatusName'] : '');
		//�û�����
		$this->assign('userId',$_SESSION['USER_ID']);	
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
		//����Ȩ�ޣ�����Ȩ�޴����ʶ
		$this->assign('deptLimit',$_GET['deptLimit'] == 'none' ? $_GET['deptLimit'] : '');
		$this->assign('agencyLimit',$_GET['agencyLimit'] == 'none' ? $_GET['agencyLimit'] : '');
		
		$this->view('detail');
	}
	
	/**
	 * ���������ʲ���ϸ����
	 */
	function c_toDeptDetail() {
		//�������ͣ�Ĭ��Ϊ��
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : '');
		//��ֹ���ڣ�Ĭ��Ϊ��
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : '');
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : '');
		//���ڸ�ʽ
		$this->assign('dateFmt',$_GET['dateFmt'] ? $_GET['dateFmt'] : '');
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
	
		$this->view('deptdetail');
	}
	
	/**
	 * ���������ʲ���ϸ����
	 */
	function c_toSubDeptDetail() {
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : '');
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : '');
		//�ʲ�����
		$this->assign('assetName',$_GET['assetName'] ? $_GET['assetName'] : '');
	
		$this->view('subdeptdetail');
	}
	
	/**
	 * �����ʲ�����ͳ�Ʊ�
	 */
	function c_toDeptExpensesSummary() {
		//�������ͣ�Ĭ��Ϊ��������
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//��ֹ��������
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : date('Y-m'));
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);	
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $this->service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('deptexpensessummary');
	}
	
	/**
	 * ���������ʲ�����ͳ�Ʊ�
	 */
	function c_toAgencyExpensesSummary() {
		//�������ͣ�Ĭ��Ϊ��������
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//��ֹ��������
		$this->assign('beginDate',$_GET['beginDate'] ? $_GET['beginDate'] : date('Y-01'));
		$this->assign('endDate',$_GET['endDate'] ? $_GET['endDate'] : date('Y-m'));
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $this->service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
	
		$this->view('agencyexpensessummary');
	}
	
	/**
	 * �������ʲ���ϸ��
	 */
	function c_toYearDetail() {
		//�������ͣ�Ĭ��Ϊ��������
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//���
		$this->assign('year',$_GET['year'] ? $_GET['year'] : date('Y'));
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		//������������
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//�û�����
		$this->assign('userId',$_SESSION['USER_ID']);
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $this->service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
		//����Ȩ�ޣ�����Ȩ�޴����ʶ
		$this->assign('deptLimit',$_GET['deptLimit'] == 'none' ? $_GET['deptLimit'] : '');
		$this->assign('agencyLimit',$_GET['agencyLimit'] == 'none' ? $_GET['agencyLimit'] : '');
	
		$this->view('yeardetail');
	}
	
	/**
	 * �������ʲ���ϸ��
	 */
	function c_toMonthDetail() {
		//�������ͣ�Ĭ��Ϊ��������
		$this->assign('dateType',$_GET['dateType'] ? $_GET['dateType'] : 'buyDate');
		//���
		$this->assign('year',$_GET['year'] ? $_GET['year'] : date('Y'));
		//�·�
		$this->assign('month',$_GET['month'] ? $_GET['month'] : date('m'));
		//��˾����
		$this->assign('company',$_GET['company'] ? $_GET['company'] : 'all');
		//��������
		$this->assign('deptId',$_GET['deptId'] ? $_GET['deptId'] : $_SESSION['DEPT_ID']);
		$this->assign('deptName',$_GET['deptName'] ? $_GET['deptName'] : $_SESSION['DEPT_NAME']);
		//������������
		$this->assign('agencyCode',$_GET['agencyCode'] ? $_GET['agencyCode'] : 'all');
		//�û�����
		$this->assign('userId',$_SESSION['USER_ID']);
		//��ȡ�ʲ���Ƭ���Ȩ��
		$limits = $this->service->getDeptLimit();
		//����Ȩ��
		$deptLimit = $limits['����Ȩ��'];
		$this->assign('deptIdStr',$deptLimit);
		//����Ȩ�ޣ�����Ȩ�޴����ʶ
		$this->assign('deptLimit',$_GET['deptLimit'] == 'none' ? $_GET['deptLimit'] : '');
		$this->assign('agencyLimit',$_GET['agencyLimit'] == 'none' ? $_GET['agencyLimit'] : '');
	
		$this->view('monthdetail');
	}
	/***************************************E �̶��ʲ���ر���***************************************/
}