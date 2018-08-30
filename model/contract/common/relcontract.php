<?php
/*
 * Created on 2011-6-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 所有合同公共类
 */

//include_once "module/phpExcel/classes/PHPExcel.php";
//include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
//include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
//include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
//include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
class model_contract_common_relcontract extends model_base{

	function __construct() {
		$this->contDao = new model_contract_contract_contract();
		parent::__construct ();
	}


   /**
    * 根据合同号判断合同是否存在
    */
    function orderBe($orderTempCode,$orderCode){
         if((empty($orderTempCode) && !empty($orderCode)) || (!empty($orderTempCode) && !empty($orderCode))){
             $sql = "select id from view_oa_order where orderCode = '$orderCode'";
         }else if(!empty($orderTempCode) && empty($orderCode)){
             $sql = "select id from view_oa_order where orderTempCode = '$orderTempCode'";
         }
         $orderCode = $this->_db->getArray($sql);
         return $orderCode;
    }

    //是否
    function rtYesOrNo_d($value){
		if($value == 1){
			return '是';
		}else{
			return '否';
		}
    }


    /**
     * 服务成本确认打回
     */
    function ajaxBack_d($id,$remark = ''){
		try {
			$this->start_d();
			
        	$conDao = new model_contract_contract_contract();
            $row = $conDao->get_d($id);
			$sql = "update oa_contract_contract set RdproConfirm = '0',EngConfirm='0',isSubApp = '0',isSubAppChange='0' where id = $id ";

			$this->_db->query($sql);
			$findSql = "select dealStatus from oa_contract_contract where id = $id ";
			$arr = $this->_db->getArray($findSql);
		    if($arr[0]['dealStatus'] == '2'){
		   		$sqlA = "update oa_contract_contract set dealStatus = '1' where id = $id ";
			    $this->_db->query($sqlA);
		   	}
            if($row['isSubAppChange'] == '0'){
            	$isChange = 0;
            }else{
                $isChange = 2;
                //更新变更历史审批状态为物料确认打回
                $sql = "select max(id) as id from oa_contract_changlog where objType = 'contract' and objId = ". $id;
                $rs = $this->_db->getArray($sql);
                if(!empty($rs[0]['id'])){
                	$sql = "update oa_contract_changlog set ExaStatus = '物料确认打回',ExaDT = '" . day_date . "' where id = ". $rs[0]['id'];
                	$this->_db->query($sql);
                }
            }
            $handleDao = new model_contract_contract_handle();
            $handleDao->handleAdd_d(array(
                "cid"=> $id,
                "stepName"=> "打回至销售",
                "isChange"=> $isChange,
                "stepInfo"=> "",
                "remark" => $remark
            ));
//			$Code = $this->find(array (
//				"id" => $id
//			), null, "Code");
//			//获取默认发送人
//			include (WEB_TOR . "model/common/mailConfig.php");
//			$emailDao = new model_common_mail();
//			$emailInfo = $emailDao->subBorrowEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "subProBorrowMail", $Code['Code'], "通过", $mailUser['subProBorrow']['subNameId']);
            $this->commit_d();
            return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}
