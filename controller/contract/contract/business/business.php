<?php
/*
 * Created on 2011-2-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_contract_business_business extends controller_base_action {

	function __construct() {
		$this->objName = "business";
		$this->objPath = "contract_business";
		parent :: __construct();
	}
      /**
       * 新建项目
       */
      function c_toNewbusiness() {
      	  $this->show->assign('principalId', $_SESSION['USER_ID']);
		  $this->show->assign('principalName', $_SESSION['USERNAME']);
          $this->show->display($this->objPath . '_' .$this->objName . '-new');
      }
      /**
       * 项目列表Tab页
       */
      function c_toListTab () {
      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-tab');
      }
      /**
       * 未提交项目
       */
      function c_toListwtj () {
      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-wtj');
      }
       /**
       * 审批中的项目
       */
      function c_toListspz () {
      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-spz');
      }
       /**
       * 执行的项目
       */
      function c_toListzxz () {

      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-zxz');
      }
      /**
       * 项目合同转为销售合同
       */
      function c_toseles () {
      	   $this->show->assign('principalId', $_SESSION['USER_ID']);
		   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display($this->objPath . '_' .$this->objName . '-seles');
      }
      /**
       * 查看项目合同
       */
      function c_toview () {

      	   $this->show->display($this->objPath . '_' .$this->objName . '-view');
      }
      /**
       * 线索列表
       */
      function c_toClues() {
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-clues');
      }
      /**
       * 新建项目线索
       */
      function c_toCluesAdd() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-clues-add');
      }
      /**
       * 线索跟踪
       */
      function c_totrack() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-track');
      }
      /**
       * 新增跟踪记录
       */
      function c_totrackadd() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-track-add');
      }
       /**
       * 申请技术支持
       */
      function c_toSupport() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-support');
      }
       /**
       * 申请借试用
       */
      function c_toBorrow() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-borrow');
      }
       /**
       * 线索处理
       */
      function c_toOperation() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-operation');
      }
      /**
       * 我注册的线索
       */
      function c_toMyclues() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-myclues');
      }
      /**
        * 我跟踪的线索
       */
      function c_toMytrack() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-mytrack');
      }
       /**
        * 我的商务立项
       */
      function c_toMybusiness() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-mybusiness-tab');
      }
       /**
        * 线索查看
       */
      function c_toCluesViewTab() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-cluesview-tab');
      }
       /**
        * 指定跟踪人
       */
      function c_toTrackman() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-trackman');
      }
      /**
       * 申请项目立项
       */
       function c_toConversionPro() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-conversion-project');
      }
       /**
       * 申请合同审批
       */
       function c_toConversionSeles() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-conversion-seles');
      }
      /**
       * 项目列表
       */
       function c_toList() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-list');
      }
       /**
       * 线索查看——基本信息
       */
       function c_toCluesbase() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-cluesbase');
      }
       /**
       * 线索查看——跟踪记录
       */
       function c_toTrackRecord() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-trackrecord');
      }
       /**
       * 线索查看技术支持记录
       */
       function c_toJszcRecord() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-jszcrecord');
      }
       /**
       * 借试用记录
       */
       function c_toBorrowRecord() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-borrowrecord');
      }
       /**
       * 指定负责人
       */
       function c_toZdfzr() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-zdfzr');
      }
       /**
       * 指定执行人
       */
       function c_toZdzxr() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-zdzxr');
      }
       /**
       * 我的线索
       */
       function c_toMycluesTab() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-myclues-tab');
      }
        /**
       * 修改我的线索
       */
       function c_toMycluesEdit() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-clues-edit');
      }
      /**
       * 审批
       */
       function c_toreview() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-review');
      }
 }
?>
