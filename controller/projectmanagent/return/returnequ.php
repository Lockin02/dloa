<?php
/**
 * @author Administrator
 * @Date 2011��5��31�� 14:51:11
 * @version 1.0
 * @description:�����˻��豸����Ʋ�
 */
class controller_projectmanagent_return_returnequ extends controller_base_action {

	function __construct() {
		$this->objName = "returnequ";
		$this->objPath = "projectmanagent_return";
		parent::__construct ();
	 }

	/*
	 * ��ת�������˻��豸��
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ("select_edit");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

 }
?>