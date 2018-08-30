<?php
/**
 * �̵���������Ϣ���Ʋ���
 *  @author chenzb
 */
class controller_asset_checktask_check extends controller_base_action {
	
	public $provArr;

	function __construct() {
		$this->objName = "check";
		$this->objPath = "asset_checktask";
		parent::__construct ();
	}
	
	/**
	 * ��ת�������̵�������Ϣ�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * ��ת������ҳ��
	 */

	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 *
	 *����EXCEL���ϴ��̵�������Ϣ
	 */
    function c_add() {
  		$id = $this->service->importExcel( $_POST [$this->objName], true );

    	if ($id) {
			echo "<script>alert('�����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
		}
		else{
			echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		}
	}

	/**
	 *
	 *�̶��ʲ���ӯ�̿��ܱ�
	 */
	function c_toReport() {
		$dept=isset($_GET ['dept'])?$_GET ['dept']:"";
		$this->assign ( 'dept', $dept );
		$deptId=isset($_GET ['deptId'])?$_GET ['deptId']:"";
		$this->assign ( 'deptId', $deptId );
        $taskNo=isset($_GET ['taskNo'])?$_GET ['taskNo']:"";
        $this->assign ( 'taskNo', $taskNo );
		$this->display ( "check" );
	}

	/**
	 *
	 * �̶��ʲ���ӯ�̿�������ҳ��
	 */
	function c_toSearch() {
		$dept=isset($_GET ['dept'])?$_GET ['dept']:"";
		$this->assign ( 'dept', $dept );
		$deptId=isset($_GET ['deptId'])?$_GET ['deptId']:"";
		$this->assign ( 'deptId', $deptId );
        $taskNo=isset($_GET ['taskNo'])?$_GET ['taskNo']:"";
        $this->assign ( 'taskNo', $taskNo );
		$this->view ( "search" );
	}
	
	 /**
	  * ajaxɾ���̵������̵�ӱ���Ϣ
	  */
	  function c_deletes(){
		$message = "";
		try {
            $checkObj = $this->service->get_d ( $_GET ['id'] );
			$checkitemDao = new model_asset_checktask_checkitem();
	  		$condition = array(
	  			'checkId'=>$checkObj['id']
	  		);
	  		$checkitemDao->delete($condition);
			$this->service->deletes_d ( $_GET ['id'] );

			$message = '<div style="color:red" align="center">ɾ���ɹ�!</div>';

		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">ɾ��ʧ�ܣ��ö�������Ѿ�������!</div>';
		}
		if (isset ( $_GET ['url'] )) {
			$event = "document.location='" . iconv ( 'utf-8', 'gb2312', $_GET ['url'] ) . "'";
			showmsg ( $message, $event, 'button' );
		} else if (isset ( $_SERVER [HTTP_REFERER] )) {
			$event = "document.location='" . $_SERVER [HTTP_REFERER] . "'";
			showmsg ( $message, $event, 'button' );
		} else {
			$this->c_page ();
		}
		msg('ɾ���ɹ���');
	  }
}