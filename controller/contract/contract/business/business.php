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
       * �½���Ŀ
       */
      function c_toNewbusiness() {
      	  $this->show->assign('principalId', $_SESSION['USER_ID']);
		  $this->show->assign('principalName', $_SESSION['USERNAME']);
          $this->show->display($this->objPath . '_' .$this->objName . '-new');
      }
      /**
       * ��Ŀ�б�Tabҳ
       */
      function c_toListTab () {
      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-tab');
      }
      /**
       * δ�ύ��Ŀ
       */
      function c_toListwtj () {
      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-wtj');
      }
       /**
       * �����е���Ŀ
       */
      function c_toListspz () {
      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-spz');
      }
       /**
       * ִ�е���Ŀ
       */
      function c_toListzxz () {

      	   $this->show->display($this->objPath . '_' .$this->objName . '-list-zxz');
      }
      /**
       * ��Ŀ��ͬתΪ���ۺ�ͬ
       */
      function c_toseles () {
      	   $this->show->assign('principalId', $_SESSION['USER_ID']);
		   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display($this->objPath . '_' .$this->objName . '-seles');
      }
      /**
       * �鿴��Ŀ��ͬ
       */
      function c_toview () {

      	   $this->show->display($this->objPath . '_' .$this->objName . '-view');
      }
      /**
       * �����б�
       */
      function c_toClues() {
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-clues');
      }
      /**
       * �½���Ŀ����
       */
      function c_toCluesAdd() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-clues-add');
      }
      /**
       * ��������
       */
      function c_totrack() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-track');
      }
      /**
       * �������ټ�¼
       */
      function c_totrackadd() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-track-add');
      }
       /**
       * ���뼼��֧��
       */
      function c_toSupport() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-support');
      }
       /**
       * ���������
       */
      function c_toBorrow() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-borrow');
      }
       /**
       * ��������
       */
      function c_toOperation() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-operation');
      }
      /**
       * ��ע�������
       */
      function c_toMyclues() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-myclues');
      }
      /**
        * �Ҹ��ٵ�����
       */
      function c_toMytrack() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-mytrack');
      }
       /**
        * �ҵ���������
       */
      function c_toMybusiness() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-mybusiness-tab');
      }
       /**
        * �����鿴
       */
      function c_toCluesViewTab() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-cluesview-tab');
      }
       /**
        * ָ��������
       */
      function c_toTrackman() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-trackman');
      }
      /**
       * ������Ŀ����
       */
       function c_toConversionPro() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-conversion-project');
      }
       /**
       * �����ͬ����
       */
       function c_toConversionSeles() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-conversion-seles');
      }
      /**
       * ��Ŀ�б�
       */
       function c_toList() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-list');
      }
       /**
       * �����鿴����������Ϣ
       */
       function c_toCluesbase() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-cluesbase');
      }
       /**
       * �����鿴�������ټ�¼
       */
       function c_toTrackRecord() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-trackrecord');
      }
       /**
       * �����鿴����֧�ּ�¼
       */
       function c_toJszcRecord() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-jszcrecord');
      }
       /**
       * �����ü�¼
       */
       function c_toBorrowRecord() {
      	   $this->show->assign('deliveryDate' , date("Y-m-d"));
      	   $this->show->assign('principalName', $_SESSION['USERNAME']);
      	   $this->show->display( $this->objPath . '_' . $this->objName . '-borrowrecord');
      }
       /**
       * ָ��������
       */
       function c_toZdfzr() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-zdfzr');
      }
       /**
       * ָ��ִ����
       */
       function c_toZdzxr() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-zdzxr');
      }
       /**
       * �ҵ�����
       */
       function c_toMycluesTab() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-myclues-tab');
      }
        /**
       * �޸��ҵ�����
       */
       function c_toMycluesEdit() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-clues-edit');
      }
      /**
       * ����
       */
       function c_toreview() {

      	   $this->show->display( $this->objPath . '_' . $this->objName . '-review');
      }
 }
?>
