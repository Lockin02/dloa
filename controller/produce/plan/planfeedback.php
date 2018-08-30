<?php
/**
 * @author Michael
 * @Date 2014年9月1日 15:15:56
 * @version 1.0
 * @description:生产计划进度反馈控制层
 */
class controller_produce_plan_planfeedback extends controller_base_action {

	function __construct() {
		$this->objName = "planfeedback";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产计划进度反馈列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到生产计划进度反馈列表(按计划显示)
	 */
	function c_pagePlan() {
		$this->assign('planId' ,$_GET['id']);
		$this->view('list-plan');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_planPageJson() {
		$service = $this->service;
		$service->getParam( $_REQUEST ); //设置前台获取的参数信息
		$service->groupBy = 'c.feedbackNum';
		$rows = $service->page_d('select_plan');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增生产计划进度反馈页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑生产计划进度反馈页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看生产计划进度反馈页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看进度反馈页面
	 */
	function c_toViewPlan() {
		$this->permCheck (); //安全校验
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