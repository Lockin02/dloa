<?php
/*
 *合同共享类
 *by LiuB 2011-7-5 20:42:43
 */
class controller_contract_common_share extends controller_base_action{

	function __construct() {
		$this->objName = "share";
		$this->objPath = "contract_common";
		parent::__construct ();
	}

   	 /**
      * 合同共享方法
      */
	function c_shareInfo() {
		$rows = $_POST [$this->objName] ;
		$id = $this->service->shareinfo_d ($rows );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
     /**
      * 跳转-我的合同--共享的合同Tab
      */
      function c_toMyShare(){
      	$this->assign('userId' , $_SESSION['USER_ID']);
      	$this->display("tomyshare");
      }
      /**
       * 我共享的合同
       */
      function c_myShareOrder(){
      	 $this->assign('userId' , $_GET['userId']);
      	 $this->display("myshareorder");
      }
      /**
       * 被共享的合同
       */
      function c_toShareOrder(){
      	 $this->assign('userId' , $_GET['userId']);
      	 $this->display("toshareorder");
      }


    /**
     * ajax 取消共享
     */
    function c_ajaxDeleteShare(){
       try{
			$this->service->ajaxDeleteShare_d($_GET['id']);
			echo 1;
		} catch(Exception $e){
			echo 0;
		}
    }
}
?>
