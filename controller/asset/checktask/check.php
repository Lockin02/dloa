<?php
/**
 * 盘点任务导入信息控制层类
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
	 * 跳转到导入盘点任务信息列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增页面
	 */

	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 *
	 *导入EXCEL中上传盘点任务信息
	 */
    function c_add() {
  		$id = $this->service->importExcel( $_POST [$this->objName], true );

    	if ($id) {
			echo "<script>alert('新增成功!');self.parent.show_page();self.parent.tb_remove();</script>";
		}
		else{
			echo "<script>alert('新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		}
	}

	/**
	 *
	 *固定资产盘盈盘亏总表
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
	 * 固定资产盘盈盘亏表搜索页面
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
	  * ajax删除盘点主表及盘点从表信息
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

			$message = '<div style="color:red" align="center">删除成功!</div>';

		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
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
		msg('删除成功！');
	  }
}