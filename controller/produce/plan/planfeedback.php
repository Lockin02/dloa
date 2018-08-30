<?php
/**
 * @author Michael
 * @Date 2014��9��1�� 15:15:56
 * @version 1.0
 * @description:�����ƻ����ȷ������Ʋ�
 */
class controller_produce_plan_planfeedback extends controller_base_action {

	function __construct() {
		$this->objName = "planfeedback";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * ��ת�������ƻ����ȷ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������ƻ����ȷ����б�(���ƻ���ʾ)
	 */
	function c_pagePlan() {
		$this->assign('planId' ,$_GET['id']);
		$this->view('list-plan');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_planPageJson() {
		$service = $this->service;
		$service->getParam( $_REQUEST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->groupBy = 'c.feedbackNum';
		$rows = $service->page_d('select_plan');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת�����������ƻ����ȷ���ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭�����ƻ����ȷ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴�����ƻ����ȷ���ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴���ȷ���ҳ��
	 */
	function c_toViewPlan() {
		$this->permCheck (); //��ȫУ��
		$planDao = new model_produce_plan_produceplan();
		$planObj = $planDao->get_d($_GET['planId']);
		foreach ($planObj as $key => $val) {
			$this->assign($key ,$val);
		}
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('feedbackNum' ,$obj['feedbackNum']);
		$this->assign('feedbackDate' ,$obj['feedbackDate']);
		$this->assign('feedbackName' ,$obj['feedbackName']);
		$this->view ('view-plan');
	}
 }
?>