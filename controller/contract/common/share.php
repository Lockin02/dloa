<?php
/*
 *��ͬ������
 *by LiuB 2011-7-5 20:42:43
 */
class controller_contract_common_share extends controller_base_action{

	function __construct() {
		$this->objName = "share";
		$this->objPath = "contract_common";
		parent::__construct ();
	}

   	 /**
      * ��ͬ������
      */
	function c_shareInfo() {
		$rows = $_POST [$this->objName] ;
		$id = $this->service->shareinfo_d ($rows );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
     /**
      * ��ת-�ҵĺ�ͬ--����ĺ�ͬTab
      */
      function c_toMyShare(){
      	$this->assign('userId' , $_SESSION['USER_ID']);
      	$this->display("tomyshare");
      }
      /**
       * �ҹ���ĺ�ͬ
       */
      function c_myShareOrder(){
      	 $this->assign('userId' , $_GET['userId']);
      	 $this->display("myshareorder");
      }
      /**
       * ������ĺ�ͬ
       */
      function c_toShareOrder(){
      	 $this->assign('userId' , $_GET['userId']);
      	 $this->display("toshareorder");
      }


    /**
     * ajax ȡ������
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
