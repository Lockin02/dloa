<?php

/**
 * @author Administrator
 * @Date 2011��8��9�� 19:37:07
 * @version 1.0
 * @description:�̵������Ϣ���Ʋ�
 */
class controller_stock_check_checkinfo extends controller_base_action {
	
	function __construct() {
		$this->objName = "checkinfo";
		$this->objPath = "stock_check";
		parent::__construct ();
	}
	
	/**
	 * ��ת���б�ҳ
	 */
	function c_toList() {
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->view ( "overage-list" );
				break; //��ӯ
			

			case 'SHORTAGE' :
				$this->view ( "shortage-list" );
				break; //�̿�����
			default :
				break;
		}
	}
	
	/**
	 * @desription ��ת���鿴ҳ��
	 */
	function c_init() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$checkinfo = $this->service->get_d ( $_GET ['id'] );
		foreach ( $checkinfo as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showViewDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		
		if ($checkinfo ['checkType'] == "OVERAGE")
			$this->show->assign ( "checkType", "��ӯ���" );
		else {
			$this->show->assign ( "checkType", "�̿�����" );
		}
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->display ( 'overage-view' );
				break;
			
			case 'SHORTAGE' :
				$this->display ( 'shortage-view' );
				break;
			default :
				break;
		}
	
	}
	
	/**
	 * @desription ��ת���޸�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$checkType = $_GET ['checkType'];
		$this->checkAuditLimit ( $checkType );
		$check = $this->service->get_d ( $_GET ['id'] );
		
		foreach ( $check as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showEditDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$this->show->assign ( "itemscount", count ( $check ['details'] ) );
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->view ( 'overage-edit' );
				break;
			case 'SHORTAGE' :
				$this->view ( 'shortage-edit' );
				break;
			default :
				break;
		}
	}
	
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		
		$checkType = $_GET ['checkType'];
		$this->checkAuditLimit ( $checkType );
		if ("OVERAGE" == $checkType) {
			$this->view ( 'overage-add' );
		} else {
			$this->view ( 'shortage-add' );
		}
	}
	
	/**
	 * ��ת����ӡҳ��
	 */
	function c_toPrint() {
		$this->permCheck ();
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$checkinfo = $this->service->get_d ( $_GET ['id'] );
		foreach ( $checkinfo as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showViewDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->view ( 'overage-print' );
				break;
			case 'SHORTAGE' :
				$this->view ( 'shortage-print' );
				break;
			
			default :
				break;
		}
	}
	
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	
	//========================================================ҵ����===================================//
	

	/**
	 * �����̵㵥
	 * @author chenzb
	 */
	function c_add() {
		$service = $this->service;
		$checkinfoObject = $_POST [$this->objName];
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$checkType = $checkinfoObject ['checkType'];
		/*s:--------------���Ȩ�޿���----------------*/
		if ("audit" == $actType) {
			if ($checkType == "OVERAGE") {
				if (! $service->this_limit ['��ӯ������']) {
					echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
					exit ();
				}
			}
			if ($checkType == "SHORTAGE") {
				if (! $service->this_limit ['�̿��������']) {
					echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
					exit ();
				}
			}
			$checkinfoObject ['auditUserName'] = $_SESSION ['USERNAME'];
			$checkinfoObject ['auditUserId'] = $_SESSION ['USER_ID'];
		}
		/*e:--------------���Ȩ�޿���----------------*/
		
		$id = $service->add_d ( $checkinfoObject );
		
		if ($id) {
			if ("audit" == $actType) {
				echo "<script>alert('��˵��ݳɹ�!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('�������ݳɹ�!'); window.opener.window.show_page();window.close();  </script>";
			}
		} else {
			if ("audit" == $actType) {
				
				echo "<script>alert('����̵�ʧ��,��ȷ�ϸ������Ƿ��㹻!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('�����̵㵥ʧ��,��ȷ�ϵ�����Ϣ�Ƿ�����!'); window.opener.window.show_page();window.close();  </script>";
			}
		}
	}
	
	/**
	 * �޸���ⵥ
	 * @author chenzb
	 */
	function c_edit() {
		$service = $this->service;
		$checkinfoObject = $_POST [$this->objName];
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$checkType = $checkinfoObject ['checkType'];
		/*s:--------------���Ȩ�޿���----------------*/
		if ("audit" == $actType) {
			if ($checkType == "OVERAGE") {
				if (! $service->this_limit ['��ӯ������']) {
					echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
					exit ();
				}
			}
			if ($checkType == "SHORTAGE") {
				if (! $service->this_limit ['�̿��������']) {
					echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
					exit ();
				}
			}
			$checkinfoObject ['auditUserName'] = $_SESSION ['USERNAME'];
			$checkinfoObject ['auditUserId'] = $_SESSION ['USER_ID'];
		}
		/*e:--------------���Ȩ�޿���----------------*/
		
		$id = $service->edit_d ( $checkinfoObject );
		
		if ($id) {
			if ("audit" == $actType) {
				echo "<script>alert('����̵㵥�ɹ�!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('�޸��̵㵥�ɹ�!'); window.opener.window.show_page();window.close();  </script>";
			}
		
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('����̵�ʧ��,��ȷ�ϸ������Ƿ��㹻!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('�޸��̵�ʧ��,��ȷ�ϵ�����Ϣ�Ƿ�����!'); window.opener.window.show_page();window.close();  </script>";
			}
		}
	}
	
	/*****************************���㲿�ֽ���**************************************/
	
	/**
	 *
	 * �̵����Ȩ���ж�
	 * @author chenzb
	 */
	function checkAuditLimit($checkType) {
		switch ($checkType) {
			case 'OVERAGE' :
				if ($this->service->this_limit ['��ӯ������']) {
					$this->assign ( "auditLimit", "1" );
				} else {
					$this->assign ( "auditLimit", "0" );
				}
				break;
			case 'SHORTAGE' :
				//���Ȩ���ж�
				if ($this->service->this_limit ['�̿��������']) {
					$this->assign ( "auditLimit", "1" );
				} else {
					$this->assign ( "auditLimit", "0" );
				}
				break;
			default :
				break;
		}
	}
	
	/**
	 * �����
	 * @author chenzb
	 */
	function c_cancelAudit() {
		$service = $this->service;
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$checkType = isset ( $_POST ['checkType'] ) ? $_POST ['checkType'] : null;
		if ($service->ctCancelAudit ( $id, $checkType )) {
			echo 1;
		} else
			echo 0;
	}
}
?>