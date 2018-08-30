<?php

/**
 * �ʲ����޿��Ʋ���
 *  @author chenzb
 */
class controller_asset_daily_rent extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "rent";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}
	/**
	 * ��ת������Ϣ�б�
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
	 * ��ʼ������
	 */
	function c_init() {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		if(isset($_GET['btn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}

		foreach ( $obj as $key => $val ) {
				$this->show->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			//echo"1111";
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

/**
	  * ajaxɾ���������������嵥�ӱ���Ϣ
	  */
	  function c_deletes(){
		$message = "";
		try {
            $rentObj = $this->service->get_d ( $_GET ['id'] );
			$rentitemDao = new model_asset_daily_rentitem();
	  		$condition = array(
	  			'rentId'=>$rentObj['id']
	  		);
	  		$rentitemDao->delete($condition);
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
?>