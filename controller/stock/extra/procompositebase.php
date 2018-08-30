<?php
/**
 * @author huangzf
 * @Date 2012��6��1�� ������ 11:27:45
 * @version 1.0
 * @description:��Ʒ���Ͽ��ɹ������ۺϱ������Ϣ���Ʋ� 
 */
class controller_stock_extra_procompositebase extends controller_base_action {
	
	function __construct() {
		$this->objName = "procompositebase";
		$this->objPath = "stock_extra";
		parent::__construct ();
	}
	
	/**
	 * ��ת����Ʒ���Ͽ��ɹ������ۺϱ������Ϣ�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת��������Ʒ���Ͽ��ɹ������ۺϱ������Ϣҳ��
	 */
	function c_toAdd() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year - 3; $i < $year + 3; $i ++) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( "yearStr", $yearStr );
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭��Ʒ���Ͽ��ɹ������ۺϱ������Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$year = $obj ['activeYear'];
		$yearStr = "";
		for($i = $year - 3; $i < $year + 3; $i ++) {
			if ($i == $year) {
				$yearStr .= "<option value=$i selected>" . $i . "��</option>";
			} else {
				$yearStr .= "<option value=$i>" . $i . "��</option>";
			}
		
		}
		$this->assign ( "yearStr", $yearStr );
		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴��Ʒ���Ͽ��ɹ������ۺϱ������Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ) );
		$this->view ( 'view' );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see controller_base_action::c_add()
	 */
	function c_add() {
		$service = $this->service;
		if ($service->add_d ( $_POST [$this->objName] )) {
			echo "<script>alert('�����ɹ�!'); window.opener.window.show_page();window.close();  </script>";
		} else {
			echo "<script>alert('����ʧ��!'); window.opener.window.show_page();window.close();  </script>";
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see controller_base_action::c_edit()
	 */
	function c_edit() {
		$service = $this->service;
		if ($service->edit_d ( $_POST [$this->objName] )) {
			echo "<script>alert('�޸ĳɹ�!'); window.opener.window.show_page();window.close();  </script>";
		} else {
			echo "<script>alert('�޸�ʧ��!'); window.opener.window.show_page();window.close();  </script>";
		}
	}
	
	/**
	 * 
	 * �����
	 */
	function c_activeReport() {
		$service = $this->service;
		if ($service->activeReport ( $_POST ['id'] )) {
			echo "0";
		} else {
			echo "1";
		}
	}
	
	/**
	 * 
	 * ��ȡ���ۺ�ͬ�豸���и������ϵ�ִ��������
	 */
	function c_getConEquNum() {
		$service = $this->service;
		echo $service->getConEquNum ( $_POST ['id'] );
	}
	
	/**
	 * 
	 * ��ȡ�豸�������ϵĿ����Ʒ������
	 */
	function c_getProActNum() {
		$service = $this->service;
		echo $service->getProActNum ( $_POST ['id'] );
	}
	
	/**
	 * 
	 * �鿴�����豸�����
	 */
	function c_toViewActReport() {
		$this->permCheck (); //��ȫУ��
		$object = $this->service->find ( array ("isActive" => "1" ) );
		
		if ($object) {
			$obj = $this->service->get_d ( $object ['id'] );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ) );
			$this->view ( 'view' );
		} else {
			echo "û����Ч��Ϣ";
		}
	
	}
}
?>