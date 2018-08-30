<?php
/*
 * Created on 2010-8-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 保存合同启动和关闭的相关信息
 */
class model_contract_common_share extends model_base{

	function __construct() {
		$this->tbl_name = "oa_sale_share";
		$this->sql_map = "contract/common/shareSql.php";
		parent :: __construct();
	}

     function shareinfo_d ($rows){
        try{
			$this->start_d();

			    $toShare = array ();
		     	$toShareName = explode(',',$rows['toshareName']);
		     	$toShareNameId = explode(',',$rows['toshareNameId']);
		     	foreach ($toShareName as $key => $val){
                     $toShare[$key]['orderType'] = $rows['orderType'];
		     		 $toShare[$key]['orderName'] = $rows['orderName'];
		     		 $toShare[$key]['orderId'] = $rows['orderId'];
		     		 $toShare[$key]['shareName'] = $rows['shareName'];
		     		 $toShare[$key]['shareNameId'] = $rows['shareNameId'];
		     		 $toShare[$key]['shareDate'] = $rows['shareDate'];
		             $toShare[$key]['toshareName'] = $val ;
		             $toShare[$key]['toshareNameId'] = $toShareNameId[$key];
		     	}

             $this->createBatch($toShare);

			$this->commit_d();
			return $rows;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}

     }
  /**
   * 取消共享
   */
  function ajaxDeleteShare_d($id){
       $sql = "delete from oa_sale_share where id = '$id'";
       $this->_db->query($sql);
  }
  /**
   * 根据合同id 获取已经共享的人员
   */
  function getShareByConId($id){
       $sql="select toshareName,toshareNameId from oa_sale_share where orderId = ".$id."";
       $arr = $this->_db->getArray($sql);
       return $arr;
  }
}
?>
